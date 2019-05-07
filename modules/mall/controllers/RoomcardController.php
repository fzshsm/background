<?php
namespace app\modules\mall\controllers;

use app\components\QCloudCos;
use app\controllers\Controller;
use app\modules\league\api\GloryComplaint;
use app\modules\league\api\GloryMatch;
use app\modules\league\api\PubgLeague;
use app\modules\mall\api\RoomCard;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;
use yii\web\UploadedFile;


class RoomcardController extends Controller
{
    private $_processFileError;
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $data =  [];
        $request = \Yii::$app->request;
        $page = $request->get('page'  , 1);
        $pageSize = $request->get('pageSize' , 15);
        $type = $request->get('searchType',0);
        $content = $request->get('content');

        $roomCard = new RoomCard();
        $roomCardData = $roomCard->listdata($page , $pageSize,$type,$content);
        if(empty($roomCardData)){
            \Yii::$app->session->setFlash( 'error' , $roomCard->getError());
        }else{
            foreach ($roomCardData['results'] as $value){
                $data[$value['roomCardId']] = $value;
            }
        }
        $totalCount = isset( $roomCardData['totalSize'] ) ? $roomCardData['totalSize'] : 0;
        $dataProvider = new ArrayDataProvider([
            'sort' => [
                'attributes' => ['roomCardId'],
            ],
            'pagination' => [
                'pageSize' => $pageSize,
                'totalCount' => $totalCount
            ]
        ]);
        $dataProvider->setModels($data);
        $dataProvider->setTotalCount($totalCount);
        return $this->render('index' , [ 'dataProvider' => $dataProvider]);
    }

    public function actionCreate(){
        $request = \Yii::$app->request;
        $leagueList = $this->getLeagueList(1);
        if($request->isPost){
            $postData = $request->post();
            $roomCard = new RoomCard();
            if(!$this->getCover('roomCardIcon')){
                \Yii::$app->session->setFlash('error' , '需上传图片');
                return $this->render( 'create',['leagueList' => $leagueList]);
            }
            $response = $roomCard->create($postData);

            if($response){
                \Yii::$app->session->setFlash('success' , "创建房卡成功！");

                $leagueId = $response;

                $image = $this->uploadCover($leagueId,'roomCardIcon');
                if(!empty($image)){
                    $postData['roomCardIcon'] = $image;
                }

                $roomCard = new RoomCard($leagueId);
                $postData['roomCardId'] = $leagueId;
                if(!($roomCard->update($postData))){
                    \Yii::$app->session->setFlash('error' , $roomCard->getError());
                }
            }else{
                \Yii::$app->session->setFlash('error' , $roomCard->getError());
            }
        }

        return $this->render( 'create',['leagueList' => $leagueList]);
    }

    public function actionUpdate($id){
        $request = \Yii::$app->request;
        $roomCard = new RoomCard($id);

        if($request->isPost){
            $postData = $request->post();
            $data = $roomCard->detail();
            $image = $this->getCover('roomCardIcon');
            if($image != false){
                $newImage = $this->uploadCover($id,'roomCardIcon');
                if(!empty($newImage)){
                    $postData['roomCardIcon'] = $newImage;
                }
            }else{
                $postData['roomCardIcon'] = $data['roomCardIcon'];
            }
            $postData['roomCardId'] = $id;
            if($roomCard->update($postData)){
                \Yii::$app->session->setFlash('success' , "修改房卡信息成功！");
            }else{
                \Yii::$app->session->setFlash('error' , $roomCard->getError());
            }

        }
        $data = $roomCard->detail();//var_dump($data);exit;
        if(empty($data)){
            \Yii::$app->session->setFlash('dataError' , $roomCard->getError());
        }

        $data['roomCardIcon'] = isset($data['roomCardIcon']) ?  $data['roomCardIcon'].'?'.time() : '' ;

        $leagueList = [];
        if($data['roomCardType'] != 3){
            $leagueList = $this->getLeagueList($data['gameType']);
        }

        return $this->render( 'update' , ['data' => $data, 'leagueList' => $leagueList]);
    }

    protected function getCover($name){
        $cover = UploadedFile::getInstanceByName($name);
        return !empty($cover) ? $cover : false;
    }

    protected function uploadCover($roomCardId,$name){
        try{
            $cover = $this->getCover($name);
            if(empty($cover)){
                return false;
            }
            $qCloudCos = new QCloudCos();
            $srcPath = $cover->tempName;


            if (YII_ENV_DEV) {
                $dstPath = '/mall/roomcard/test/'.$roomCardId.'.png';
            }else{
                $dstPath = '/mall/roomcard/'.$roomCardId.'.png';
            }

            $result = $qCloudCos->upload(QCloudCos::BUCKET_NAME , $srcPath , $dstPath,null,null,0);
            if(!empty($result) && isset($result['code'])){
                \Yii::trace($result , 'League.Upload.IMG');
                if($result['code'] != 0){
                    throw new \Exception($result['message']);
                }
                return $result['data']['access_url'];
            }
        }catch( \Exception $e){
            $this->_processFileError = $e->getMessage();
            \Yii::warning($this->_processFileError , 'League.Upload.IMG');
        }
        return false;
    }
    
    public function actionLeague(){
        $gameType = \Yii::$app->request->get('gameType',1);

        $pubgLeague = new PubgLeague();

        $response = $pubgLeague->getLeagueList(['gameType' => $gameType]);

        if(!is_array($response)){
            $response = [];
        }

        $data = [];
        foreach ($response as $value){
            $data[] = [
                'id' => $value['id'],
                'name' => $value['name']
            ];
        }

        $status = ['status' => 'success','message' => '','result' => $data];

        return Json::encode($status);
    }

    protected function getLeagueList($gameType = 1){
        $pubgLeague = new PubgLeague();
        $leagueList = $pubgLeague->getLeagueList(['gameType' => $gameType]);
        foreach ($leagueList as $value){
            $leagueListData[$value['id']] = $value['name'];
        }
        return $leagueListData;
    }

    public function actionUser()
    {
        $searchType = \Yii::$app->request->get('searchType');
        $content = \Yii::$app->request->get('content');

        $roomCard = new RoomCard();

        $data = $roomCard->searchUserByUserNo($searchType,$content);

        if(!is_array($data)){
            $data = [];
        }else{
            $nickname = urldecode($data['nickName']);
            $data['nickName'] = $nickname;
        }

        $status = ['status' => 'success','message' => '','result' => $data];

        return Json::encode($status);
    }

    public function actionSend(){
        $request = \Yii::$app->request;

        if($request->isPost){
            $userNo =  $request->post('userNo');
            $roomCardId = $request->post('roomCardId');
            $roomCardNum = $request->post('roomCardNum');

            $data = [];

            for ($i=0;$i<count($userNo);$i++){
                $data[] = [
                    'roomCardId' => $roomCardId[$i],
                    'userId' => $userNo[$i],
                    'roomCardNum' => $roomCardNum[$i]
                ];
            }

            $roomCard = new RoomCard();

            if($roomCard->sendRoomCard(Json::encode($data))){
                \Yii::$app->session->setFlash('success' , "发送房卡成功");
            }else{
                \Yii::$app->session->setFlash('error' , $roomCard->getError());
            }

        }

        $roomCardList = $this->getRoomCardList();
        return $this->render('send',['roomCardList' => Json::encode($roomCardList)]);
    }

    protected function getRoomCardList(){
        $roomCard = new RoomCard();

        $list = $roomCard->getRoomCardAllList();

        $data = [];

        if(!is_array($list)){
            $list = [];
        }

        foreach ($list as $value){
            $data[] = [
                'id' => $value['roomCardId'],
                'name' => $value['roomCardName']
            ];
        }
        return $data;
    }

    public function actionDetail(){

        $data =  [];
        $request = \Yii::$app->request;
        $page = $request->get('page'  , 1);
        $pageSize = $request->get('pageSize' , 15);
        $searchType = $request->get('searchType');
        $status = $request->get('status');
        $content = $request->get('content');
        $date = $request->get('date');
        $id = $request->get('id');

        $time = 0;
        if(!empty($date)){
            list($begin , $end) = explode('至' , $date);
            $time = $begin.','.$end;
        }

        $roomCard = new RoomCard();
        $roomCardData = $roomCard->detailList($id,$page , $pageSize,$searchType,$status,$content,$time);
        if(empty($roomCardData)){
            \Yii::$app->session->setFlash( 'error' , $roomCard->getError());
        }else{
            foreach ($roomCardData['results'] as $value){
                $data[] = $value;
            }
        }
        $totalCount = isset( $roomCardData['totalSize'] ) ? $roomCardData['totalSize'] : 0;
        $dataProvider = new ArrayDataProvider([
            'sort' => [
                'attributes' => [''],
            ],
            'pagination' => [
                'pageSize' => $pageSize,
                'totalCount' => $totalCount
            ]
        ]);
        $dataProvider->setModels($data);
        $dataProvider->setTotalCount($totalCount);

        return $this->render('detail' , [ 'dataProvider' => $dataProvider]);
    }
}

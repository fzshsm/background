<?php

namespace app\modules\league\controllers;

use app\components\QCloudCos;
use app\controllers\Controller;
use app\helper\PubgParam;
use app\modules\league\api\GloryMatch;
use app\modules\league\api\PubgLeague;
use app\modules\league\api\PubgMatch;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;
use yii\web\UploadedFile;

/**
 * Default controller for the `league` module
 */
class PubgController extends Controller
{
    
    private $_processFileError;

    public function actionIndex(){
        $request = \Yii::$app->request;
        $page = $request->get('page',1);
        $pageSize = $request->get('pageSize' , 15);
        $type = $request->get('searchType' , 0);
        $content = $request->get('content');
        $sort = $request->get('sort','-id');

        if($sort == 'id'){
            $sort = 'id,asc';
        }else{
            $sort = 'id,desc';
        }

        $data = [];
        $match = new PubgLeague();
        $matchDatas = $match->listdata($page , $pageSize, $type, $content,2, $sort);
        if(!empty($matchDatas)){
            $responseData['recordsTotal'] = $matchDatas['totalSize'];
            $responseData['recordsFiltered'] = $matchDatas['totalSize'];
            foreach ($matchDatas['results'] as $value){
                $value['signinCount'] = $value['signCount'];
                $value['id'] = $value['leagueId'];
                $value['name'] = $value['leagueName'];
                $data[$value['leagueId']] = $value;
            }
        }else{
            $responseData['error'] = $match->getError();
        }

        $totalSize = isset($matchDatas['totalSize']) ? $matchDatas['totalSize'] : 0;
        $responseData = new ArrayDataProvider([
            'sort' => [
                'attributes' => ['id'],
            ],
            'pagination' => [
                'pageSize' => $pageSize,
                'totalCount' => $totalSize
            ]
        ]);

        $responseData->setModels($data);
        $responseData->setTotalCount($totalSize);

        $leagueSorts = $this->getLeagueSorts();
        $leagueSortList[] = ['id' => 0, 'name' => '全部'];
        foreach ($leagueSorts as $key => $value){
            $leagueSortList[$key] = [
                'id' => $key,
                'name' => $value
            ];
        }

        $robotList = $this->getRobotList();
        return $this->render('../default/index',['leagueSortList' => $leagueSortList,'gameType' => 'pubg','responseData' => $responseData, 'robotList' => $robotList]);
    }

    public function actionCreate(){
        $request = \Yii::$app->request;
        if($request->isPost){
            $postData = $request->post();
            $match = new PubgLeague();
            $postData['createUserId'] = \Yii::$app->user->identity->id;
            $postData['gameType'] = 2;
            $teamAllowCount = $postData['teamAllowCount'];
            if(!empty($teamAllowCount)){
                $teamAllowCount = implode($teamAllowCount,',');
            }
            $postData['teamAllowCount'] = $teamAllowCount;
            $response = $match->create($postData);

            if($response){
                \Yii::$app->session->setFlash('success' , "创建联赛成功！");

                $leagueId = $response;
                $imageName = ['cover','shareIcon','shareCover'];
                foreach ($imageName as $value){
                    if($this->getCover($value)){
                        $image = $this->uploadCover($leagueId,$value);
                        if(!empty($image)){
                            $postData[$value] = $image;
                        }
                    }
                }

                $match = new PubgLeague($leagueId);

                $postData['leagueId'] = $leagueId;
                $postData['updateUserId'] = \Yii::$app->user->identity->id;

                if(!($match->update($postData))){
                    \Yii::$app->session->setFlash('error' , $match->getError());
                }
            }else{
                \Yii::$app->session->setFlash('error' , $match->getError());
            }
        }

        $leagueSorts = $this->getLeagueSorts();
        $leagueTypes = $this->getLeagueTypes();

        return $this->render( 'create',[ 'leagueSorts' => $leagueSorts, 'teamAllowCount' => [], 'leagueTypes' => $leagueTypes ]);
    }
    
    public function actionUpdate($id){
        $request = \Yii::$app->request;
        $match = new PubgLeague($id);
        $data = $match->detail();
        if($request->isPost){
            $postData = $request->post();

            $imageName = ['cover','shareIcon','shareCover'];
            foreach ($imageName as $value){
                $image = $this->getCover($value);
                if($image != false){
                    $newImage = $this->uploadCover($id,$value);
                    if(!empty($newImage)){
                        $postData[$value] = $newImage;
                    }
                }else{
                    $postData[$value] = $data[$value];
                }
            }
            $teamAllowCount = $postData['teamAllowCount'];
            if(!empty($teamAllowCount)){
                $teamAllowCount = implode($teamAllowCount,',');
            }
            $postData['teamAllowCount'] = $teamAllowCount;

            $postData['updateUserId'] = \Yii::$app->user->identity->id;
            $postData['leagueId'] = $id;
            $postData['gameType'] = 2;
            if($match->update($postData)){
                \Yii::$app->session->setFlash('success' , "修改联赛信息成功！");
            }else{
                \Yii::$app->session->setFlash('error' , $match->getError());
            }
            $data = $match->detail();
        }
        
        if(empty($data)){
            \Yii::$app->session->setFlash('dataError' , $match->getError());
        }

        $data['cover'] = isset($data['cover']) ?  $data['cover'].'?'.time() :$data['cover'] ;

        $teamAllowCount = $data['teamAllowCount'];
        $teamAllowCount = explode(',',$teamAllowCount);


        $leagueSorts = $this->getLeagueSorts();
        $leagueTypes = $this->getLeagueTypes();

        return $this->render( 'update' , ['data' => $data,'leagueSorts' => $leagueSorts, 'teamAllowCount' => $teamAllowCount, 'leagueTypes' => $leagueTypes ]);
    }

    public function actionBind()
    {
        $response = ['status' => 'success' , 'message' => '' ];
        $name = \Yii::$app->request->get('name');
        $leagueId = \Yii::$app->request->get('leagueId');

        $pubgLeague = new PubgLeague();

        $data = [
            'name' => $name,
            'leagueId' => $leagueId
        ];

        if($pubgLeague->bindRobot($data) == false){
            $response['status'] = 'error';
            $response['message'] = $pubgLeague->getError();
        }
        return Json::encode($response);
    }

    public function actionUnbind()
    {
        $response = ['status' => 'success' , 'message' => '' ];
        $name = \Yii::$app->request->get('name');

        $pubgLeague = new PubgLeague();

        $data = [
            'name' => $name,
        ];

        if($pubgLeague->unbindRobot($data) == false){
            $response['status'] = 'error';
            $response['message'] = $pubgLeague->getError();
        }

        return Json::encode($response);
    }

    protected function getCover($name){
        $cover = UploadedFile::getInstanceByName($name);
        return !empty($cover) ? $cover : false;
    }

    protected function uploadCover($leagueId,$name){
        try{
            $cover = $this->getCover($name);
            if(empty($cover)){
                return false;
            }
            $qCloudCos = new QCloudCos();
            $srcPath = $cover->tempName;

            if (YII_ENV_DEV) {
                $namePath = [
                    'shareIcon' => "/league/pubg/share/test/".$leagueId.".png?t=".time(),
                    'shareCover' => "/league/pubg/sharecover/test/".$leagueId.".png?t=".time(),
                    'cover' => "/league/pubg/test/".$leagueId.".png?t=".time(),
                ];
            }else{
                $namePath = [
                    'shareIcon' => "/league/pubg/share/".$leagueId.".png?t=".time(),
                    'shareCover' => "/league/pubg/sharecover/".$leagueId.".png?t=".time(),
                    'cover' => "/league/pubg/".$leagueId.".png?t=".time(),
                ];
            }

            $dstPath = $namePath[$name];

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
    
    protected function deleteCover($cover){
        try{
            $coverInfo = parse_url($cover);
            $qCloudCos = new QCloudCos();
            $result = $qCloudCos->delFile(QCloudCos::BUCKET_NAME , $coverInfo['path']);

            if(!empty($result) && isset($result['code']) && $result['code'] != 0){
                throw new \Exception($result['message']);
            }
            return true;
        }catch( \Exception $e){
            $this->_processFileError = $e->getMessage();
            \Yii::warning($this->_processFileError , 'League.Delete.IMG');
        }
        return false;
    }

    protected function getLeagueSorts(){
        $match = new PubgLeague();
        $leagueSorts = $match->leagueSorts(['gameType' => 2]);
        $leagueSortsData = [];

        if(!is_array($leagueSorts)){
            $leagueSorts = [];
        }

        foreach ($leagueSorts as $leagueSort){
            $leagueSortsData[$leagueSort['id']] = $leagueSort['val'];
        }
        return $leagueSortsData;
    }

    protected function getRobotList(){
        $pubgLeague = new PubgLeague();

        $response = $pubgLeague->getRobotList();

        if(!is_array($response)){
            $response = [];
        }

        $robotList = [];

        foreach ($response as $value){
            $robotList[$value['name']] = $value['name'];
        }

        return $robotList;
    }

    protected function getLeagueTypes(){
        $match = new PubgLeague();
        $leagueTypes = $match->leagueTypes(['gameType' => 2]);
        if(!is_array($leagueTypes)){
            $leagueTypes = [];
        }
        $leagueTypesData = [];
        foreach ($leagueTypes as $leagueType){
            $leagueTypesData[$leagueType['id']] = $leagueType['val'];
        }
        return $leagueTypesData;
    }

}

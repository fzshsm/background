<?php

namespace app\modules\club\controllers;

use app\components\QCloudCos;
use app\controllers\Controller;
use app\modules\club\api\Club;
use app\modules\club\api\PubgClub;
use app\modules\club\api\PubgMember;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;
use yii\web\UploadedFile;

class PubgController extends Controller
{
    private $_processFileError;
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex(){
        $data =  [];
        $request = \Yii::$app->request;
        $page = $request->get('page'  , 1);
        $pageSize = $request->get('pageSize' , 15);
        $gameType = $request->get('status', 2);
        $approvalStatus = $request->get('approvalStatus',1);
        $teamName = $request->get('teamName');

        if($approvalStatus == 3){
            $approvalStatus = '0,1,2';
        }

        $club = new PubgClub();
        $clubListData = $club->listData($page,$pageSize,$gameType,$approvalStatus,$teamName);

        if(empty($clubListData)){
            \Yii::$app->session->setFlash( 'error' , $club->getError());
        }else{
            $clubListData['results'] = empty($clubListData['results']) ? [] : $clubListData['results'];
            foreach ($clubListData['results'] as $value){
                $data[$value['id']] = $value;
            }
        }
        $totalCount = isset( $clubListData['totalSize'] ) ? $clubListData['totalSize'] : 0;
        $dataProvider = new ArrayDataProvider([
            'sort' => [
                'attributes' => ['id'],
            ],
            'pagination' => [
                'pageSize' => $pageSize,
                'totalCount' => $totalCount
            ]
        ]);
        $dataProvider->setModels($data);
        $dataProvider->setTotalCount($totalCount);
        $approvalStatusData = $this->getTeamApprovalStatus();

        return $this->render('../default/index' , ['dataProvider' => $dataProvider,'gameType' => 'pubg','approvalStatusData' => $approvalStatusData]);
    }

    public function actionCreate(){
        $request = \Yii::$app->request;

        $club = new PubgClub();

        if($request->isPost){
            $postData = $request->post();

            $icon = $this->uploadCover();

            if(!empty($icon)){
                $postData['teamLogo'] = $icon;
            }
            $postData['id'] = 0;
            $postData['createUserId'] = \Yii::$app->user->identity->id;

            \Yii::trace($postData , 'club.create');

            if( $club->create($postData) ){
                \Yii::$app->session->setFlash('success' , "创建战队成功！");
            }else{
                \Yii::$app->session->setFlash('error' , $club->getError());
            }
        }

        return $this->render('create');
    }

    public function actionUpdate($id){
        $request = \Yii::$app->request;

        $club = new PubgClub($id);

        if($request->isPost){
            $postData = $request->post();
            $icon = UploadedFile::getInstanceByName('teamLogo');
            if(empty($icon)){
                $icon = $postData['image'];
            }else{
                $icon = $this->uploadCover();
            }
            $postData['teamLogo'] = $icon;

            \Yii::trace($postData,'club.update');
            if($club->update($postData)){
                \Yii::$app->session->setFlash('success','更新战队成功');
            }else{
                \Yii::$app->session->setFlash('error',$club->getError());
            }
        }

        $clubDetail = $club->detail();

        return $this->render('update',['data' => $clubDetail]);
    }

    protected function getCover(){
        $cover = UploadedFile::getInstanceByName('teamLogo');
        return !empty($cover) ? $cover : false;
    }

    protected function uploadCover(){
        try{
            $cover = $this->getCover();
            if(empty($cover)){
                return false;
            }
            $qCloudCos = new QCloudCos();
            $srcPath = $cover->tempName;
            $dstPath = "/clubimg/" . md5($cover->name . time()) . "." . $cover->extension;
            $result = $qCloudCos->upload(QCloudCos::BUCKET_NAME , $srcPath , $dstPath);
            if(!empty($result) && isset($result['code'])){
                \Yii::trace($result , 'clubimg.Upload.IMG');
                if($result['code'] != 0){
                    throw new \Exception($result['message']);
                }
                return $result['data']['access_url'];
            }
        }catch( \Exception $e){
            $this->_processFileError = $e->getMessage();
            \Yii::warning($this->_processFileError , 'clubimg.Upload.IMG');
        }
        return false;
    }

    public function actionStatus()
    {
        $response = ['status' => 'success' , 'message' => '' ];
        $request = \Yii::$app->request;
        $userId = $request->get('userId');
        $userTeamId = $request->get('userTeamId');
        $isPass = $request->get('isPass');
        $remark = $request->get('remark');

        $club = new PubgClub();
        if($club->changeStatus($userId,$userTeamId,$isPass,$remark) == false){
            $response['status'] = 'error';
            $response['message'] = $club->getError();
        }

        return Json::encode($response);
    }

    public function actionDelete(){
        $response = ['status' => 'success' , 'message' => '' ];
        $request = \Yii::$app->request;
        $teamId = $request->get('id');

        $club = new PubgClub();
        if($club->deleteTeam($teamId) == false){
            $response['status'] = 'error';
            $response['message'] = $club->getError();
        }

        return Json::encode($response);
    }

    protected function getTeamApprovalStatus(){
        $pubgClub = new PubgClub();

        $data = [];
        $response = $pubgClub->getTeamApprovalStatus();

        if(!is_array($response)){
            $response = [];
        }

        foreach ($response as $value){
            $data[$value['id']] = $value['val'];
        }
        if(!empty($data)){
            $data[3] = '全部';
        }

        return $data;
    }

}

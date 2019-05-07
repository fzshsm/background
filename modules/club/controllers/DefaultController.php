<?php

namespace app\modules\club\controllers;

use app\components\QCloudCos;
use app\controllers\Controller;
use app\modules\club\api\Club;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;
use yii\web\UploadedFile;

class DefaultController extends Controller
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

        $club = new Club();
        $clubListData = $club->listData($page,$pageSize);

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
        $clubType = $club->clubType();

        return $this->render('index' , ['dataProvider' => $dataProvider,'clubType' => $clubType,'gameType' => 'glory']);
    }

    public function actionCreate(){
        $request = \Yii::$app->request;

        $club = new Club();

        if($request->isPost){
            $postData = $request->post();

            $icon = $this->uploadCover();

            if(!empty($icon)){
                $postData['icon'] = $icon;
            }
            $postData['id'] = 0;
            \Yii::trace($postData , 'club.create');

            if( $club->create($postData) ){
                \Yii::$app->session->setFlash('success' , "创建战队成功！");
            }else{
                \Yii::$app->session->setFlash('error' , $club->getError());
            }
        }

        $clubType = $this->getClubType();

        return $this->render('create',['clubType' => $clubType]);
    }

    public function actionUpdate($id){
        $request = \Yii::$app->request;

        $club = new Club($id);

        if($request->isPost){
            $postData = $request->post();
            $icon = UploadedFile::getInstanceByName('icon');
            if(empty($icon)){
                $icon = $postData['image'];
            }else{
                $icon = $this->uploadCover();
            }
            $postData['icon'] = $icon;
            \Yii::trace($postData,'club.update');
            if($club->update($postData)){
                \Yii::$app->session->setFlash('success','更新战队成功');
            }else{
                \Yii::$app->session->setFlash('error',$club->getError());
            }
        }

        $clubDetail = $club->detail();
        $clubType = $this->getClubType();

        return $this->render('update',['data' => $clubDetail,'clubType' => $clubType]);
    }

    protected function getCover(){
        $cover = UploadedFile::getInstanceByName('icon');
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

    protected function getClubType(){
        $club = new Club();
        $clubTypeList = $club->clubType();

        $clubType = [];
        foreach ($clubTypeList as $value){
            $clubType[$value['id']] = $value['val'];
        }

        return $clubType;
    }

    public function actionDelete($id)
    {
        $response = ['status' => 'success' , 'message' => '' ];
        $club = new Club($id);

        if($club->deleteById() == false){
            $response['status'] = 'error';
            $response['message'] = $club->getError();
        }

        return Json::encode($response);
    }
}

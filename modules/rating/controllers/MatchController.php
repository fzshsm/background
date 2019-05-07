<?php
namespace app\modules\rating\controllers;

use app\controllers\Controller;
use app\modules\rating\api\Match;
use app\components\QCloudCos;
use yii\helpers\Json;
use yii\web\UploadedFile;
use yii\data\ArrayDataProvider;

class MatchController extends Controller {

    private $_processFileError;

    public function actionIndex(){
        $data =  [];
        $request = \Yii::$app->request;
        $page = $request->get('page', 1);
        $pageSize = $request->get('pageSize' , 15);

        $match = new Match();
        $matchListData = $match->listData($page,$pageSize);
        if(empty($matchListData)){
            \Yii::$app->session->setFlash( 'error' , $match->getError());
        }else{
            $matchListData['results'] = empty($matchListData['results']) ? $matchListData : $matchListData['results'];
            foreach ($matchListData['results'] as $value){
                $data[$value['id']] = $value;
            }
        }
        $totalCount = isset( $matchListData['totalSize'] ) ? $matchListData['totalSize'] : 0;
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

        return $this->render('index' , ['dataProvider' => $dataProvider]);
    }

    public function actionCreate(){
        $request = \Yii::$app->request;
        $match = new Match();

        if($request->isPost){
            $postData = $request->post();
            $icon = $this->uploadCover();
            $params = [
                'name' => $postData['name'],
            ];

            if(!empty($icon)){
                $params['icon'] = $icon;
            }

            \Yii::trace($postData , 'game.create');
            if( $match->create($params) ){
                \Yii::$app->session->setFlash('success' , '创建成功');
            }else{
                \Yii::$app->session->setFlash('error' , $match->getError());
            }
        }

        return $this->render('create');
    }

    public function actionUpdate($id){
        $request = \Yii::$app->request;
        $match = new Match($id);

        if($request->isPost){
            $postData = $request->post();
            $icon = $this->uploadCover();
            $params = [
                'name' => $postData['name'],
                'icon' => $postData['image'],
                'id' => $id
            ];

            if(!empty($icon)){
                $params['icon'] = $icon;
            }

            \Yii::trace($postData , 'game.update');
            if( $match->update($params) ){
                \Yii::$app->session->setFlash('success' , '更新成功');
            }else{
                \Yii::$app->session->setFlash('error' , $match->getError());
            }
        }
        $matchDetail = $match->detail();
        return $this->render('update',['data' => $matchDetail]);
    }

    public function actionDelete($id){
        $response = ['status' => 'success' , 'message' => '' ];
        $match = new Match($id);

        if($match->deleteById() == false){
            $response['status'] = 'error';
            $response['message'] = $match->getError();
        }
        return Json::encode($response);
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
            $dstPath = "/gametypesimg/" . md5($cover->name . time()) . "." . $cover->extension;
            $result = $qCloudCos->upload(QCloudCos::BUCKET_NAME , $srcPath , $dstPath);
            if(!empty($result) && isset($result['code'])){
                \Yii::trace($result , 'gametypesimg.Upload.IMG');
                if($result['code'] != 0){
                    throw new \Exception($result['message']);
                }
                return $result['data']['access_url'];
            }
        }catch( \Exception $e){
            $this->_processFileError = $e->getMessage();
            \Yii::warning($this->_processFileError , 'gametypesimg.Upload.IMG');
        }
        return false;
    }
}
<?php

namespace app\modules\mall\controllers;

use app\components\QCloudCos;
use app\controllers\Controller;
use app\modules\mall\api\Goods;
use app\modules\mall\api\RoomCard;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\VarDumper;
use yii\web\UploadedFile;

class DefaultController extends Controller
{
    private $_processFileError;

    public function beforeAction($action) {

        if(in_array($action->id,['upload','create','update'])) {

            $action->controller->enableCsrfValidation = false;
        }
        parent::beforeAction($action);

        return true;
    }
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex(){
        $data =  [];
        $request = \Yii::$app->request;
        $page = $request->get('page'  , 1);
        $pageSize = $request->get('pageSize' , 15);
        $goodsStatus = $request->get('goodsStatus',3);
        $goodsName = $request->get('goodsName');

        $goods = new Goods();
        $goodsListData = $goods->listData($page,$pageSize,$goodsStatus,$goodsName);

        if(empty($goodsListData)){
            \Yii::$app->session->setFlash( 'error' , $goods->getError());
        }else{
            $goodsListData['results'] = empty($goodsListData['results']) ? [] : $goodsListData['results'];
            foreach ($goodsListData['results'] as $value){
                $value['id'] = $value['goodsId'];
                $data[$value['id']] = $value;
            }
        }
        $totalCount = isset( $goodsListData['totalSize'] ) ? $goodsListData['totalSize'] : 0;
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
        $roomCard = new RoomCard();
        if($request->isPost){
            $postData = $request->post();

            $cover = $this->uploadCover();
            if(!empty($cover)){
                $postData['goodsImg'] = $cover;
            }

            $goods = new Goods();

            if( $goods->create($postData)){
                \Yii::$app->session->setFlash('success' , "创建商品成功！");
            }else{
                \Yii::$app->session->setFlash('error' , $goods->getError());
            }
        }

        $roomCardResponse = $roomCard->queryRoomCardList();

        $roomCardList = [];
        $roomCardDesc = [];
        if($roomCardResponse == false){
            \Yii::$app->session->setFlash('error' , $roomCard->getError());
        }else{
            foreach ($roomCardResponse as $value){
                $roomCardList[$value['roomCardId']] = $value['roomCardName'];
                $roomCardDesc[$value['roomCardId']] = $value['roomCardDesc'];
            }
        }

        return $this->render('create',['roomCardList' => $roomCardList, 'roomCardDesc' => json_encode($roomCardDesc)]);
    }

    public function actionUpdate($id){
        $request = \Yii::$app->request;
        $goods = new Goods($id);
        $roomCard = new RoomCard();

        if($request->isPost){
            $postData = $request->post();
            $cover = $this->getCover();
            if(!empty($cover)){
                $postData['goodsImg'] = $this->uploadCover();
            }else{
                $postData['goodsImg'] = $postData['image'];
            }

            $postData['goodsId'] = $id;

            if( $goods->update($postData)){
                \Yii::$app->session->setFlash('success' , "更新商品成功！");
            }else{
                \Yii::$app->session->setFlash('error' , $goods->getError());
            }
        }

        $data = $goods->detail();
        if($data == false){
            \Yii::$app->session->setFlash('dataError' , $goods->getError());
        }

        $roomCardResponse = $roomCard->queryRoomCardList();

        $roomCardList = [];
        $roomCardDesc = [];
        if($roomCardResponse == false){
            \Yii::$app->session->setFlash('error' , $roomCard->getError());
        }else{
            foreach ($roomCardResponse as $value){
                $roomCardList[$value['roomCardId']] = $value['roomCardName'];
                $roomCardDesc[$value['roomCardId']] = $value['roomCardDesc'];
            }
        }

        return $this->render('update',['data' => $data, 'roomCardList' => $roomCardList, 'roomCardDesc' => json_encode($roomCardDesc)]);
    }

    public function actionShelve($id)
    {
        $response = ['status' => 'success' , 'message' => '' ];
        $goods = new Goods($id);

        $data = [
            'goodsId' => $id,
            'status' => 1
        ];

        if($goods->updateGoodsStatus($data) == false){
            $response['status'] = 'error';
            $response['message'] = $goods->getError();
        }
        return Json::encode($response);
    }

    public function actionUnshelve($id)
    {
        $response = ['status' => 'success' , 'message' => '' ];
        $goods = new Goods($id);

        $data = [
            'goodsId' => $id,
            'status' => 2
        ];

        if($goods->updateGoodsStatus($data) == false){
            $response['status'] = 'error';
            $response['message'] = $goods->getError();
        }
        return Json::encode($response);
    }

    public function actionDelete($id)
    {
        $response = ['status' => 'success' , 'message' => '' ];
        $goods = new Goods($id);

        if($goods->delGoods() == false){
            $response['status'] = 'error';
            $response['message'] = $goods->getError();
        }
        return Json::encode($response);
    }

    protected function getCover(){
        $cover = UploadedFile::getInstanceByName('goodsImg');
        return !empty($cover) ? $cover : false;
    }

    //编辑器中上传图片
    public function actionUpload()
    {
        $imageUrl = $this->uploadCover('goodsImg');

        if(empty($imageUrl)){
            echo Json::encode( ['error' =>1 ,'message' => '上传失败']);exit;
        }

        echo Json::encode(['error' => 0, 'url' => $imageUrl]);exit;
    }

    protected function uploadCover(){
        try{
            $cover = $this->getCover();
            if(empty($cover)){
                return false;
            }
            $qCloudCos = new QCloudCos();
            $srcPath = $cover->tempName;
            $dstPath = "/goodsimg/" . md5($cover->name . time()) . "." . $cover->extension;
            $result = $qCloudCos->upload(QCloudCos::BUCKET_NAME , $srcPath , $dstPath);
            if(!empty($result) && isset($result['code'])){
                \Yii::trace($result , 'goodsimg.Upload.IMG');
                if($result['code'] != 0){
                    throw new \Exception($result['message']);
                }
                return $result['data']['access_url'];
            }
        }catch( \Exception $e){
            $this->_processFileError = $e->getMessage();
            \Yii::warning($this->_processFileError , 'goodsimg.Upload.IMG');
        }
        return false;
    }

}

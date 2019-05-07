<?php

namespace app\modules\mall\controllers;

use app\components\QCloudCos;
use app\controllers\Controller;
use app\modules\mall\api\Goods;
use app\modules\mall\api\Order;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\UploadedFile;

class OrderController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex(){
        $data =  [];
        $request = \Yii::$app->request;
        $page = $request->get('page'  , 1);
        $pageSize = $request->get('pageSize' , 15);
        $searchType = $request->get('searchType');
        $content = $request->get('content');
        $date = $request->get('date');

        $time = 0;
        if(!empty($date)){
            list($begin , $end) = explode('至' , $date);
            $time = $begin.','.$end;
        }

        $order = new Order();
        $orderListData = $order->listData($page,$pageSize,$searchType,$content,$time);

        if(empty($orderListData)){
            \Yii::$app->session->setFlash( 'error' , $order->getError());
        }else{
            $orderListData['results'] = empty($orderListData['results']) ? [] : $orderListData['results'];
            foreach ($orderListData['results'] as $value){
                $value['id'] = $value['shopOrderId'];
                $data[$value['id']] = $value;
            }
        }
        $totalCount = isset( $orderListData['totalSize'] ) ? $orderListData['totalSize'] : 0;
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

    public function actionExpress()
    {
        $response = ['status' => 'success' , 'message' => '' ];
        $order = new Order();
        $request = \Yii::$app->request;
        $shopOrderId = $request->get('shopOrderId');
        $expressName = $request->get('expressName');
        $expressNo = $request->get('expressNo');


        $data = [
            'shopOrderId' => $shopOrderId,
            'expressName' => $expressName,
            'expressNo' => $expressNo
        ];

        if($order->sendGoods($data) == false){
            $response['status'] = 'error';
            $response['message'] = $order->getError();
        }
        return Json::encode($response);
    }
}

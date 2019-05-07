<?php
namespace app\modules\mall\api;

use app\components\RequestRemoteApi;

class Order extends RequestRemoteApi{

    private $_id;
    private $_error;
    
    public function __construct($orderId = null){
        $this->_id = $orderId;
    }
    
    protected function checkId(){
        if(empty($this->_id) || !is_numeric($this->_id)){
            throw new \Exception('无效的订单编号！');
        }
    }

    public function getRequestUrl($action){
        return \Yii::$app->params['pubgApiDomain'] . $action;
    }
    
    public function listdata($page,$pagesize,$searchType,$content,$time){
        $logCategory = "Api.Order.listdata";
        $data = [];
        $params = [
            'pageNo' => $page,
            'pageSize' => $pagesize,
        ];

        if(!empty($content)){
            $params[$searchType] = $content;
        }

        if(!empty($time)){
            $params['buyTime'] = $time;
        }

        \Yii::trace( $params , $logCategory . '.SendParams');
        
        $responseRequireParams = [
            'shopOrderId' => '订单ID',
            'goodsName ' => '商品名',
            'goodsIcon' => '商品图',
            'buyTime' => '购买时间',
            'expressName' => '快递公司',
            'status' => '状态',
            'byCount' => '购买数量',
            'totalOrderFee' => '订单总金额',
            'expressNo' => '快递单号'
        ];
        $response = $this->request('/shop/queryOrderList' , $params);

        if(!empty($response) && is_array($response)){
            $responseParams = [];
            if(isset($response['results']) && !empty(($response['results']))){
                $responseParams = array_keys($response['results'][0]);
            }
            $this->checkResponseMissParam($responseRequireParams, $responseParams);
            $missParams = $this->getMissParams();
            if(!empty($missParams)){
                $this->warning = $this->getMissParamsMessage($responseRequireParams);
                foreach ($response['results'] as $key => $value){
                    foreach ($missParams as $param){
                        $value[$param] = null;
                        $response['results'][$key] = $value;
                    }
                }
            }
            $data = $response;
            \Yii::trace($data , $logCategory);
        }
        return $data;
    }

    public function sendGoods($data)
    {
        try {
            \Yii::trace($data , 'Api.Order.create');
            return $this->request('/shop/sendGoods' , $data);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

}
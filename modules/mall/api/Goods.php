<?php
namespace app\modules\mall\api;

use app\components\RequestRemoteApi;

class Goods extends RequestRemoteApi{

    private $_id;
    private $_error;
    
    public function __construct($goodsId = null){
        $this->_id = $goodsId;
    }
    
    protected function checkId(){
        if(empty($this->_id)){
            throw new \Exception('无效的商品编号！');
        }
    }

    public function getRequestUrl($action){
        return \Yii::$app->params['pubgApiDomain'] . $action;
    }
    
    public function listdata($page,$pagesize,$goodsStatus,$goodsName){
        $logCategory = "Api.Goods.listdata";
        $data = [];
        $params = [
            'pageNo' => $page,
            'pageSize' => $pagesize,
        ];

        if($goodsStatus != 3){
            $params['status'] = $goodsStatus;
        }

        if(!empty($goodsName)){
            $params['goodsName'] = $goodsName;
        }

        \Yii::trace( $params , $logCategory . '.SendParams');
        
        $responseRequireParams = [
            'goodsId' => '商品ID',
            'goodsName' => '商品名',
            'goodsImg' => '商品图',
            'price' => '价格',
            'stockCount' => '库存',
            'status' => '状态',
            'sortWeight' => '权重'
        ];
        $response = $this->request('/shop/queryGoodsList' , $params);

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

    public function create($data)
    {
        try {
            \Yii::trace($data , 'Api.Goods.create');
            return $this->request('/shop/addGoods' , $data);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function update($data){
        try {
            $this->checkId();
            \Yii::trace($data , 'Api.Goods.update');
            return $this->request('/shop/addGoods' , $data, 'post');
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function detail(){
        try {
            $this->checkId();
            $params = [
                'goodsId' => $this->_id,
            ];
            \Yii::trace($params , 'Api.Goods.detail');
            return $this->request('/shop/getGoodsDetail' , $params);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function updateGoodsStatus($data){
        try {
            $this->checkId();

            \Yii::trace($data , 'Api.Goods.delete');
            return $this->request('/shop/updateGoodsStatus' , $data);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }


    public function delGoods(){
        try {
            $this->checkId();
            $params = [
                'goodsId' => $this->_id,
            ];
            \Yii::trace($params , 'Api.Goods.delete');
            return $this->request('/shop/deleteGoods' , $params);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }
    
    
    public function owner($page,$pagesize,$goodsStatus = 0, $goodsCode = null , $goodsName = null){
        $logCategory = "Api.Goods.Owner";
        $data = [];
        $params = [
            'pageNo' => $page,
            'pageSize' => $pagesize,
        ];
    
        $params['status'] = $goodsStatus;
        if(!empty($goodsCode)){
            $params['goodsNo'] = $goodsCode;
        }
        
        if(!empty($goodsName)){
            $params['goodsName'] = $goodsName;
        }
    
        \Yii::trace( $params , $logCategory . '.SendParams');
    
        $responseRequireParams = [
            'goodsId' => '商品ID',
            'goodsNo' => '商品编号',
            'goodsName' => '商品名称',
            'goodsImg' => '商品图片',
            'price' => '购买价格',
            'status' => '状态',
            'nickName' => '用户昵称',
            'userNo' => '用户编号',
            'getTime' => '获得时间',
            'useNickName' => '使用人',
            'useTime' => '使用时间'
        ];
        $response = $this->request('/game/bag/queryBagGoodsList' , $params);
    
        if(!empty($response) && is_array($response)){
            $responseParams = [];
            if(isset($response['result']) && !empty(($response['result']))){
                $responseParams = array_keys($response['result'][0]);
            }
            $this->checkResponseMissParam($responseRequireParams, $responseParams);
            $missParams = $this->getMissParams();
            if(!empty($missParams)){
                $this->warning = $this->getMissParamsMessage($responseRequireParams);
                foreach ($response['result'] as $key => $value){
                    foreach ($missParams as $param){
                        $value[$param] = null;
                        $response['result'][$key] = $value;
                    }
                }
            }
            $data = $response;
            \Yii::trace($data , $logCategory);
        }
        return $data;
    }

}
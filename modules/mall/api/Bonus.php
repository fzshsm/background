<?php
namespace app\modules\mall\api;

use app\components\RequestRemoteApi;
use yii\helpers\Json;
use yii\helpers\VarDumper;

class Bonus extends RequestRemoteApi{

    private $_id;
    private $_error;
    
    public function __construct($goodsId = null){
        $this->_id = $goodsId;
    }
    
    protected function checkId(){
        if(empty($this->_id) || !is_numeric($this->_id)){
            throw new \Exception('无效的奖金编号！');
        }
    }

    public function getRequestUrl($action){
        return \Yii::$app->params['pubgApiDomain'] . $action;
    }
    
    public function listdata($page,$pagesize,$type,$content){
        $logCategory = "Api.Bouns.listdata";
        $data = [];
        $params = [
            'pageNo' => $page,
            'pageSize' => $pagesize,
        ];

        if(!empty($type)){
            $params['currencyType'] = $type;
        }

        if(!empty($content)){
            $params['name'] = $content;
        }

        \Yii::trace( $params , $logCategory . '.SendParams');

        $responseRequireParams = [
            'name' => '配置名',
            'currencyType' => '奖励类型',
            'status' => '状态',
        ];
        $response = $this->request('/game/bonus/querySeasonBonusPage' , $params);

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

    public function update($data){
        try {
            \Yii::trace($data , 'Api.Bonus.update');
            return $this->request('/game/bonus/addSeasonBonus' , [],'get',['content-type' => 'application/json'],$data);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function detail(){
        try {
            $params = [
                'id' => $this->_id,
            ];
            \Yii::trace($params , 'Api.Bonus.detail');
            return $this->request('/game/bonus/getSeasonBonusDetail' , $params);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function updateStatus($data){
        try {
            \Yii::trace($data , 'Api.Bonus.delete');
            return $this->request('/game/bonus/updateSeasonBonusStatus' , $data);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function getBonusList(){
        try {
            \Yii::trace([] , 'Api.Bonus.list');
            return $this->request('/game/bonus/queryAllSeasonBonusList');
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function sendBonus($data){
        try {
            \Yii::trace($data , 'Api.Bonus.sendBonus');
            return $this->request('/game/bonus/bonusSettlement' , $data);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function bonusDetail($seasonId){
        try {

            $param = [
                'seasonId' => $seasonId
            ];

            \Yii::trace($param , 'Api.Bonus.bonusDetail');
            return $this->request('/game/bonus/querySeasonBonusRecord' , $param);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }
}
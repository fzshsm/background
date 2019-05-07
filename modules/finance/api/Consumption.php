<?php
namespace app\modules\finance\api;

use app\components\RequestRemoteApi;

class Consumption extends RequestRemoteApi{

    private $_id;
    private $_error;
    
    public function __construct($consumptionId = null){
        $this->_id = $consumptionId;
    }
    
    protected function checkId(){
        if(empty($this->_id) || !is_numeric($this->_id)){
            throw new \Exception('无效的消费流水编号！');
        }
    }

    public function getRequestUrl($action){
        return \Yii::$app->params['pubgApiDomain'] . $action;
    }
    
    public function listdata($page,$pagesize,$time,$searchType,$content,$type){
        $logCategory = "Api.Consumption.listdata";
        $data = [];
        $params = [
            'pageNo' => $page,
            'pageSize' => $pagesize,
        ];

        if(!empty($content)){
            $params[$searchType] = $content;
        }

        if(!empty($time)){
            $params['upDateTime'] = $time;
        }

        if(!empty($type)){
            $params['type'] = $type;
        }

        \Yii::trace( $params , $logCategory . '.SendParams');
        
        $responseRequireParams = [
            'id' => '编号',
        ];
        $response = $this->request('/finance/querySystemCoinLogList' , $params);

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

    public function sendList($page,$pagesize,$userId,$type){
        $logCategory = "Api.Consumption.listdata";
        $data = [];
        $params = [
            'pageNo' => $page,
            'pageSize' => $pagesize,
        ];

        if(!empty($userId)){
            $params['userNo'] = $userId;
        }

        if($type != 3){
            $params['status'] = $type;
        }

        \Yii::trace( $params , $logCategory . '.SendParams');

        $responseRequireParams = [
            'id' => '编号',
        ];//var_dump($params);exit;
        $response = $this->request('/user/rewark/queryRewardList' , $params);

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

    public function audit($data){
        try{
            \Yii::trace($data,'Api.comsumption.audit');
            return $this->request('/user/rewark/rewardAudit',$data);
        }catch(\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }
}
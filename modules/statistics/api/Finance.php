<?php
namespace app\modules\statistics\api;


use app\components\RequestRemoteApi;

class Finance extends RequestRemoteApi {
    
    public function getError(){
        return $this->_error;
    }

    public function getRequestUrl($action){
        return \Yii::$app->params['dataApiDomain'] . $action;
    }
    
    public function rechargeStatistics($begin , $end){
        
        $logCategory = "Api.finance.rechargeStatistics";
        $data = [];
        $params = [
            'startTime' => $begin,
            'endTime' => $end,
        ];
        \Yii::trace( $params , $logCategory . '.SendParams');
        $response = $this->request('/analyze/finance/recharge' , $params);
        if(!empty($response) && is_array($response)){
            $data = $response;
            \Yii::trace($data , $logCategory);
        }
        return $data;
    }

    public function currencyStatistics($begin , $end){

        $logCategory = "Api.finance.currencyStatistics";
        $data = [];
        $params = [
            'startTime' => $begin,
            'endTime' => $end,
        ];
        \Yii::trace( $params , $logCategory . '.SendParams');
        $response = $this->request('/analyze/finance/currency' , $params);
        if(!empty($response) && is_array($response)){
            $data = $response;
            \Yii::trace($data , $logCategory);
        }
        return $data;
    }

    public function monitorStatistics($begin , $end){

        $logCategory = "Api.finance.currencyStatistics";
        $data = [];
        $params = [
            'startTime' => $begin,
            'endTime' => $end,
        ];
        \Yii::trace( $params , $logCategory . '.SendParams');
        $response = $this->request('/analyze/finance/monitor' , $params);
        if(!empty($response) && is_array($response)){
            $data = $response;
            \Yii::trace($data , $logCategory);
        }
        return $data;
    }

    public function taskStatistics($begin){

        $logCategory = "Api.finance.taskStatistics";
        $data = [];
        $params = [
            'startTime' => $begin,
        ];
        \Yii::trace( $params , $logCategory . '.SendParams');
        $response = $this->request('/analyze/finance/task' , $params);
        if(!empty($response) && is_array($response)){
            $data = $response;
            \Yii::trace($data , $logCategory);
        }
        return $data;
    }

}
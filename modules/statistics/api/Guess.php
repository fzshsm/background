<?php
namespace app\modules\statistics\api;


use app\components\RequestRemoteApi;

class Guess extends RequestRemoteApi {
    
    public function getError(){
        return $this->_error;
    }

    public function getRequestUrl($action){
        return \Yii::$app->params['dataApiDomain'] . $action;
    }
    
    public function guessStatistics($begin , $end, $rankTime, $pageNo, $pageSize,$orderBy = 'asc'){
        
        $logCategory = "Api.finance.rechargeStatistics";
        $data = [];
        $params = [
            'startTime' => $begin,
            'endTime' => $end,
            'rankTime' => $rankTime,
            'pageNo' => $pageNo,
            'pageSize' => $pageSize,
            'orderBy' => $orderBy
        ];
        \Yii::trace( $params , $logCategory . '.SendParams');
        $response = $this->request('/analyze/guess/statistics' , $params);
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

}
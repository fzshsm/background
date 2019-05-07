<?php
namespace app\modules\statistics\api;


use app\components\RequestRemoteApi;

class Awaken extends RequestRemoteApi {
    
    public function getError(){
        return $this->_error;
    }

    public function getRequestUrl($action){
        return \Yii::$app->params['dataApiDomain'] . $action;
    }

    public function awakenStatistic($type,$startTime,$endTime){

        $logCategory = "Api.awaken.awakenStatistic";
        $data = [];
        $params = [
            'type' => $type,
            'startTime' => $startTime,
            'endTime' => $endTime
        ];
        \Yii::trace( $params , $logCategory . '.SendParams');
        $response = $this->request('/analyze/awaken' , $params);
        if(!empty($response) && is_array($response)){
            $data = $response;
            \Yii::trace($data , $logCategory);
        }
        return $data;
    }

}
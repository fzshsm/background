<?php
/**
 * Created by IntelliJ IDEA.
 * User: Zhangxinlong
 * Date: 2017/11/1
 * Time: 16:03
 */
namespace app\modules\statistics\api;


use app\components\RequestRemoteApi;

class User extends RequestRemoteApi {
    
    public function getError(){
        return $this->_error;
    }

    public function getRequestUrl($action){
        return \Yii::$app->params['dataApiDomain'] . $action;
    }
    
    public function statistics($begin , $end){
        
        $logCategory = "Api.User.statistics";
        $data = [];
        $params = [
            'startDate' => $begin,
            'endDate' => $end,
        ];
        \Yii::trace( $params , $logCategory . '.SendParams');
        $response = $this->request('/analyze/statistics/userStatistics' , $params);
        if(!empty($response) && is_array($response)){
            $data = $response;
            \Yii::trace($data , $logCategory);
        }
        return $data;
    }
}
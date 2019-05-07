<?php
/**
 * Created by IntelliJ IDEA.
 * User: Zhangxinlong
 * Date: 2017/11/1
 * Time: 16:03
 */
namespace app\modules\statistics\api;


use app\components\RequestRemoteApi;

class Game extends RequestRemoteApi {
    
    public function getError(){
        return $this->_error;
    }

    public function getRequestUrl($action){
        return \Yii::$app->params['dataApiDomain'] . $action;
    }
    
    public function statistics($begin , $end, $gameType){
        
        $logCategory = "Api.Game.statistics";
        $data = [];
        $params = [
            'startTime' => $begin,
            'endTime' => $end,
            'gameType' => $gameType
        ];
        \Yii::trace( $params , $logCategory . '.SendParams');
        $response = $this->request('/analyze/statistics/gameStatistics' , $params);
        if(!empty($response) && is_array($response)){
            $data = $response;
            \Yii::trace($data , $logCategory);
        }
        return $data;
    }

//    public function leagueStatistics($begin , $end){
//
//        $logCategory = "Api.Game.leagueStatistics";
//        $data = [];
//        $params = [
//            'startTime' => $begin,
//            'endTime' => $end
//        ];
//        \Yii::trace( $params , $logCategory . '.SendParams');
//        $response = $this->request('/getLeagueStatistics' , $params);
//        if(!empty($response) && is_array($response)){
//            $data = $response;
//            \Yii::trace($data , $logCategory);
//        }
//        return $data;
//    }
    
}
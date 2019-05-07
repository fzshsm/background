<?php
namespace app\modules\statistics\api;


use app\components\RequestRemoteApi;

class Behavior extends RequestRemoteApi {
    
    public function getError(){
        return $this->_error;
    }

    public function getRequestUrl($action){
        return \Yii::$app->params['dataApiDomain'] . $action;
    }

    public function behaviorStatistic($begin , $end){

        $logCategory = "Api.behavior.behaviorStatistic";
        $data = [];
        $params = [
            'startTime' => $begin,
            'endTime' => $end,
        ];
        \Yii::trace( $params , $logCategory . '.SendParams');
        $response = $this->request('/analyze/behavior' , $params);
        if(!empty($response) && is_array($response)){
            $data = $response;
            \Yii::trace($data , $logCategory);
        }
        return $data;
    }

    public function behaviorLeague($begin , $gameType){

        $logCategory = "Api.behavior.behaviorLeague";
        $data = [];
        $params = [
            'startTime' => $begin,
            'gameType' => $gameType,
        ];
        \Yii::trace( $params , $logCategory . '.SendParams');
        $response = $this->request('/analyze/behavior/league' , $params);
        if(!empty($response) && is_array($response)){
            $data = $response;
            \Yii::trace($data , $logCategory);
        }
        return $data;
    }

    public function behaviorGame($begin , $gameType,$flag){

        $logCategory = "Api.behavior.behaviorGame";
        $data = [];
        $params = [
            'startTime' => $begin,
            'gameType' => $gameType,
            'flag' => $flag
        ];
        \Yii::trace( $params , $logCategory . '.SendParams');
        $response = $this->request('/analyze/behavior/game' , $params);
        if(!empty($response) && is_array($response)){
            $data = $response;
            \Yii::trace($data , $logCategory);
        }
        return $data;
    }

    public function behaviorTask($begin){

        $logCategory = "Api.behavior.behaviorTask";
        $data = [];
        $params = [
            'startTime' => $begin,
        ];
        \Yii::trace( $params , $logCategory . '.SendParams');
        $response = $this->request('/analyze/behavior/task' , $params);
        if(!empty($response) && is_array($response)){
            $data = $response;
            \Yii::trace($data , $logCategory);
        }
        return $data;
    }

    public function behaviorOrder($begin){

        $logCategory = "Api.behavior.behaviorOrder";
        $data = [];
        $params = [
            'startTime' => $begin,
        ];
        \Yii::trace( $params , $logCategory . '.SendParams');
        $response = $this->request('/analyze/behavior/order' , $params);
        if(!empty($response) && is_array($response)){
            $data = $response;
            \Yii::trace($data , $logCategory);
        }
        return $data;
    }

    public function behaviorGoods($begin,$goodsType){

        $logCategory = "Api.behavior.behaviorGoods";
        $data = [];
        $params = [
            'startTime' => $begin,
            'goodsType' => $goodsType
        ];
        \Yii::trace( $params , $logCategory . '.SendParams');
        $response = $this->request('/analyze/behavior/goods' , $params);
        if(!empty($response) && is_array($response)){
            $data = $response;
            \Yii::trace($data , $logCategory);
        }
        return $data;
    }

    public function behaviorGuess($begin){

        $logCategory = "Api.behavior.behaviorOrder";
        $data = [];
        $params = [
            'startTime' => $begin,
        ];
        \Yii::trace( $params , $logCategory . '.SendParams');
        $response = $this->request('/analyze/behavior/guess' , $params);
        if(!empty($response) && is_array($response)){
            $data = $response;
            \Yii::trace($data , $logCategory);
        }
        return $data;
    }

    public function behaviorCurrency($begin, $flag){

        $logCategory = "Api.behavior.behaviorCurrency";
        $data = [];
        $params = [
            'startTime' => $begin,
            'flag' => $flag
        ];
        \Yii::trace( $params , $logCategory . '.SendParams');
        $response = $this->request('/analyze/behavior/currency' , $params);
        if(!empty($response) && is_array($response)){
            $data = $response;
            \Yii::trace($data , $logCategory);
        }
        return $data;
    }

    public function behaviorLottery($begin){

        $logCategory = "Api.behavior.behaviorLottery";
        $data = [];
        $params = [
            'startTime' => $begin,
        ];
        \Yii::trace( $params , $logCategory . '.SendParams');
        $response = $this->request('/analyze/behavior/lottery' , $params);
        if(!empty($response) && is_array($response)){
            $data = $response;
            \Yii::trace($data , $logCategory);
        }
        return $data;
    }

    public function behaviorTimesGame($begin,$leagueName){

        $logCategory = "Api.behavior.behaviorLottery";
        $data = [];
        $params = [
            'startTime' => $begin,
            'leagueName' => $leagueName
        ];
        \Yii::trace( $params , $logCategory . '.SendParams');
        $response = $this->request('/analyze/behavior/timesGame' , $params);
        if(!empty($response) && is_array($response)){
            $data = $response;
            \Yii::trace($data , $logCategory);
        }
        return $data;
    }

}
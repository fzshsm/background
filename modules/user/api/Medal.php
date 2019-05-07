<?php
/**
 * Created by IntelliJ IDEA.
 * User: Zhangxinlong
 * Date: 2017/9/18
 * Time: 17:26
 */

namespace app\modules\user\api;


use app\components\RequestRemoteApi;

class Medal extends RequestRemoteApi {
    
    private $_id;
    private $_error;
    
    public function __construct($medalId = null){
        $this->_id = $medalId;
    }
    
    protected function checkId(){
        if(empty($this->_id)){
            throw new \Exception('无效编号！');
        }
    }
    
    public function getRequestUrl($action){
        return   str_replace('manager' , '' , \Yii::$app->params['remoteApiDomain']) . "/userMedal/" . $action;
    }
    
    public function listDataByUser($userId){
        try {
            $params = ['userId' => $userId];
            $response = $this->request('toAdd' , $params);
            \Yii::trace($response , 'APi.Medal.listDataByUser');
            return $response;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }
    
    public function findByUser($userId){
        try {
            $params = ['userId' => $userId];
            $response = $this->request('queryMyMedals' , $params);
            \Yii::trace($response , 'APi.Medal.findByUser');
            return $response;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }
    
    public function add($userId , $seasonId ,  $medalIds){
        try {
            $params = ['userId' => $userId , 'medalIds' => $medalIds , 'seasonId' => $seasonId];
            $response = $this->request('/addMedals' , $params);
            \Yii::trace($response , 'Api.Medal.add');
            return $response;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
        return true;
    }
    
    public function deleteById(){
        try {
            $this->checkId();
            $params = ['id' =>  $this->_id];
            $response = $this->request('/delMedals' , $params);
            \Yii::trace($response , 'Api.Medal.delete');
            return $response;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
        return true;
    }

    public function getMedalSwitch($leagueId){
        try {
            $params = ['leagueId' => $leagueId];
            $response = $this->request('medalSwitch' , $params);
            \Yii::trace($response , 'APi.Medal.medalSwitch');
            return $response;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }
}
<?php
namespace app\modules\league\api;

use app\components\RequestRemoteApi;
use yii\httpclient\Client;

class PubgData extends RequestRemoteApi{

    private $_id;
    private $_error;
    
    public function __construct($matchId = null){
        $this->_id = $matchId;
    }
    
    public function checkId(){
        if(empty($this->_id) || !is_numeric($this->_id)){
            throw new \Exception('无效的比赛编号！');
        }
    }

    public function getRequestUrl($action){
        return \Yii::$app->params['dataApiDomain'] . $action;
    }

    public function save($data){
        try {
            $logCategory = "Api.Match.save";
            $response = $this->request('/v1/pb/game/save' , $data,'post');
            \Yii::trace($response , $logCategory);
            return $response;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function teamInfo(){
        try {
            $logCategory = "Api.Match.teaminfo";
            $data = ['gid' => $this->_id];
            $response = $this->request('/v1/pb/game/detail', $data);
            \Yii::trace($response , $logCategory);

            return $response;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function saveGameRecord($data){
        try {
            $logCategory = "Api.Match.saveRecord";
            $response = $this->request('/v1/pb/game/addgame' , $data,'post');
            \Yii::trace($response , $logCategory);
            return $response;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

}
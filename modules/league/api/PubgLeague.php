<?php
namespace app\modules\league\api;

use app\components\RequestRemoteApi;

class PubgLeague extends RequestRemoteApi{

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
        return \Yii::$app->params['pubgApiDomain']  . $action;
    }


    public function listdata($page = 1 , $pageSize = 15,$flag = 0,$name = '',$gameType = 1, $sort = 'id,asc'){
        $logCategory = "Api.Match.listdata";
        $data = [];
        $params = [
            'pageNo' => $page,
            'pageSize' => $pageSize,
            'gameType' => $gameType,
            'sort' => $sort
        ];
        if(!empty($flag)){
            $params['leagueCategory'] = $flag;
        }
        if(!empty($name)){
            $params['leagueName'] = $name;
        }
        $responseRequireParams = [
            'id' => '编号',
            'name' => '名称',
            'cover' => 'Logo',
            'reward' => '奖金',
            'signCount' => '报名人数',
            'shareCover' => '分享封面',
            'sponsor' => '举办单位',
            'leagueDescribe' => '简介',
            'status' => '状态',
        ];
        $response = $this->request('/pubg/game/league/queryLeaguePage' , $params);
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
    
    public function create($data){
        \Yii::trace($data , 'Api.Match.create');
        return $this->request('/pubg/game/league/addLeague' , $data,'post');
    }
    
    public function detail(){
        try {
            $this->checkId();
            $logCategory = "Api.Match.detail";
            $data = [];
            $params = ['leagueId' => $this->_id];
            $response = $this->request('/pubg/game/league/getLeague' , $params);
            \Yii::trace($response , $logCategory);
            if(!empty($response) && is_array($response)){
                $data = $response;
            }
            return $data;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }
    
    
    public function update($data){
        try {
            $this->checkId();
            \Yii::trace($data , 'Api.Match.update');
            return $this->request('/pubg/game/league/updateLeague' , $data,'post');
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
        return true;
    }
    
    public function disabled(){
        try {
            $this->checkId();
            
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
        return true;
    }

    public function leagueSorts($data){
        try {
            \Yii::trace([] , 'Api.Match.leagueSorts');
            return $this->request('/pubg/game/league/getLeagueCategory',$data);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function leagueTypes($data){
        try {
            \Yii::trace([] , 'Api.Match.leagueTypes');
            return $this->request('/pubg/game/league/getLeagueModel',$data);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function getRobotList(){
        try {
            \Yii::trace([] , 'Api.Match.robotList');
            return $this->request('/pubg/game/match/queryFreeRobot');
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function bindRobot($data){
        try {
            \Yii::trace([] , 'Api.Match.bindRobot');
            return $this->request('/pubg/game/match/bindRobot',$data);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function unbindRobot($data){
        try {
            \Yii::trace([] , 'Api.Match.leagueSorts');
            return $this->request('/pubg/game/match/unbindRobot',$data);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function getLeagueList($data){
        try {
            \Yii::trace([] , 'Api.Match.leagueTypes');
            return $this->request('/pubg/game/league/queryLeagueList',$data);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }
    
}
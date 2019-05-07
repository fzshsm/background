<?php
namespace app\modules\league\api;

use app\components\RequestRemoteApi;

class PubgSeason extends RequestRemoteApi{

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

    public function datalist($leagueId,$page,$pageSize,$gameType = 1){
        try {
            \Yii::trace([] , 'Api.Season.leagueTypes');
            $params = [
                'pageNo' => $page,
                'pageSize' => $pageSize,
                'leagueId' => $leagueId,
                'gameType' => $gameType
            ];
            return $this->request('/pubg/game/league/querySeasonPage',$params);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function detail(){
        try {
            $this->checkId();
            $logCategory = "Api.Season.detail";
            $data = [];
            $params = ['seasonId' => $this->_id];
            $response = $this->request('/pubg/game/league/getSeason' , $params);
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

    public function create($data){
        \Yii::trace($data , 'Api.Season.create');
        return $this->request('/pubg/game/league/addSeason' , $data,'post');
    }

    public function update($data){
        try {
            $this->checkId();
            \Yii::trace($data , 'Api.Season.update');
            return $this->request('/pubg/game/league/updateSeason' , $data,'post');
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

}
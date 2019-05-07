<?php
namespace app\modules\league\api;

use app\components\RequestRemoteApi;
use yii\httpclient\Client;

class PubgMatch extends RequestRemoteApi{

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

    public function datalist($seasonId,$page,$pageSize,$status,$time=null, $searchType = null, $content = null){
        try {
            \Yii::trace([] , 'Api.Match.leagueTypes');
            $params = [
                'pageNo' => $page,
                'pageSize' => $pageSize,
            ];

            if(!empty($seasonId)){
                $params['seasonId'] = $seasonId;
            }
            if(!empty($status)){
                $params['status'] = $status;
            }

            if(!empty($time)){
                $params['time'] = $time;
            }

            if(!empty($content)){
                $params[$searchType] = $content;
            }

            return $this->request('/pubg/game/match/queryMatchPage',$params);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function detail(){
        try {
            $this->checkId();
            $logCategory = "Api.Match.detail";
            $data = [];
            $params = ['matchId' => $this->_id];
            $response = $this->request('/pubg/game/match/getMatch' , $params);
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
        \Yii::trace($data , 'Api.Match.create');
        return $this->request('/pubg/game/match/addMatch' , $data,'post');
    }

    public function update($data){
        try {
            $this->checkId();
            \Yii::trace($data , 'Api.Match.update');
            return $this->request('/pubg/game/match/updateMatch' , $data,'post');
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function save($data){
        $client = new Client();
        try {
            $logCategory = "Api.Index.save";
            $requestUrl = \Yii::$app->params['dataApiDomain'].'/pb/game/save';

            $response = $client->post($requestUrl)->setData($data)->send();

            $responseData = [];
            if(!empty($response)){
                $responseData = json_decode($response->getContent(),true);
            }
            \Yii::trace($responseData , $logCategory);
            if(!empty($responseData) && is_array($responseData)){
                $data = $responseData['result'];
            }
            return $data;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function mapList(){
        $data = [];
        \Yii::trace($data , 'Api.Match.mapList');
        return $this->request('/pubg/game/match/getPubgMap' , [],'post');
    }

    public function getRequestUrl($action){
        return \Yii::$app->params['pubgApiDomain'] . $action;
    }

    //预约详情
    public function reserDetail(){
        try {
            $this->checkId();
            $logCategory = "Api.Match.reserdetail";
            $params = ['matchId' => $this->_id];
            $response = $this->request('/pubg/game/match/getMatchTeam' , $params);
            \Yii::trace($response , $logCategory);
            $data = [];
            if(!empty($response) && is_array($response)){
                $data = $response;
            }
            return $data;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    //赛事队伍及队员
    public function teamInfo(){
        $client = new Client();
        try {
            $logCategory = "Api.Index.teaminfo";
            $requestUrl = \Yii::$app->params['dataApiDomain'].'/pb/game/detail';
            $data = ['gid' => $this->_id];

            $response = $client->get($requestUrl)->setData($data)->send();

            $responseData = [];
            if(!empty($response)){
                $responseData = json_decode($response->getContent(),true);

            }
            \Yii::trace($responseData , $logCategory);
            if(!empty($responseData) && is_array($responseData)){
                $data = $responseData['result'];
            }
            return $data;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function customer(){
        try {
            $logCategory = "Api.Match.customer";
            $response = $this->request('/pubg/game/match/queryObList');
            \Yii::trace($response , $logCategory);

            return $response;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function matchSettlement($data){
        $client = new Client();
        try {
            $requestUrl = \Yii::$app->params['pubgSettlementApi'].'/pubg/game/settlement/gameMatchSettlement';
            $cacheKey = 'login-'.\Yii::$app->user->id;
            $token = \Yii::$app->cache->get($cacheKey);
            $header['content-type'] = 'application/json';
            $header['X-Authorization'] = 'Bearer '.$token;
            $response = $client->createRequest()
                ->setUrl($requestUrl)
                ->addHeaders($header)
                ->setContent($data)
                ->send();

            $responseData = [];
            if(!empty($response)){
                $responseData = json_decode($response->getContent(),true);

            }

            return $responseData;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function matchUserDetail(){
        try {
            $this->checkId();
            $logCategory = "Api.Match.reserdetail";
            $params = ['matchId' => $this->_id];
            $response = $this->request('/pubg/game/match/getMatchUsers' , $params);
            \Yii::trace($response , $logCategory);
            $data = [];
            if(!empty($response) && is_array($response)){
                $data = $response;
            }
            return $data;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function singleMatchSettlement($data){
        $client = new Client();
        try {
            $requestUrl = \Yii::$app->params['pubgApiDomain'].'/pubg/game/settlement/singleMatch';
            $cacheKey = 'login-'.\Yii::$app->user->id;
            $token = \Yii::$app->cache->get($cacheKey);
            $header['content-type'] = 'application/json';
            $header['X-Authorization'] = 'Bearer '.$token;
            $response = $client->createRequest()
                ->setUrl($requestUrl)
                ->addHeaders($header)
                ->setContent($data)
                ->send();

            $responseData = [];
            if(!empty($response)){
                $responseData = json_decode($response->getContent(),true);

            }
            if(!empty($responseData) && is_array($responseData)){
                $data = $responseData['result'];
            }
            return $data;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }
}
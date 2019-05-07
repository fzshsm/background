<?php
namespace app\modules\league\api;

use app\components\RequestRemoteApi;
use yii\helpers\VarDumper;

class PubgMember extends RequestRemoteApi{
    
    private $_id;
    private $_error;
    
    
    public function __construct($matchId = null){
        $this->_id = $matchId;
    }

    public function getRequestUrl($action){
        return \Yii::$app->params['pubgApiDomain']  . $action;
    }
    
    public function checkId(){
        if(empty($this->_id)){
            throw new \Exception('无效的编号！');
        }
    }
    
    public function listdata($leagueId = 0 , $status = 0 , $page = 1 , $pageSize = 15 , $condition = ['0' => ''],$gameType=2){
        $logCategory = "Api.Member.listdata";
        $data = [];

        $params = [
            'pageNo' => $page,
            'pageSize' => $pageSize,
            'gameType' => $gameType
        ];
        if(!empty($status)){
            $params['status'] = $status;
        }
        if(!empty($leagueId)){
            $params['leagueId'] = $leagueId;
        }
        $typeList = ['0' => 'all' , '1' => 'nickname' , '2' => 'mobile' , '3' => 'steamId', '4' => 'qq'];
        $type = $typeList['0'];

        $content = "";
        if(!empty($condition) && is_array($condition)){
            $conditionKeys = array_keys($condition);
            $typeKeys = array_keys($typeList);
            $conditionKey = $conditionKeys[0];
            if(in_array($conditionKey, $typeKeys)){
                $type = $typeList[$conditionKey];
            }
            if(!empty($condition[$conditionKey])){
                $content = $condition[$conditionKey];
            }
        }
        if($type != 'all'){
            unset($params['pageNo']);
            unset($params['pageSize']);
            $params[$type] = $content;
        }

        \Yii::trace($params , $logCategory . '.params');
        $responseRequireParams = [
            'leagueSignId' => '编号',
            'leagueName' => '联赛名',
            'steamId' => 'steamId',
            'nickname' => '昵称',
            'headImage' => '头像',
            'qq' => 'QQ',
            'mobile' => '手机',
            'createTime' => '加入时间',
            'status' => '状态',
        ];
        $response = $this->request('/pubg/game/league/queryLeagueSignPage' , $params);
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
    
    public function detail(){
        try {
            $this->checkId();
            $logCategory = "Api.Member.detail";
            $params = [
                'type' =>  4,
                'content' => $this->_id,
            ];
            \Yii::trace($params , $logCategory . '.params');
            $responseRequireParams = [
                'id' => '编号',
                'userId' => '用户',
                'gameLeagueId' => '联赛',
                'seasonId' => '赛季',
                'screenshot' => '截图',
                'rolerId' => '角色名',
                'qq' => 'QQ',
                'mobile' => '手机',
                'time' => '加入时间',
                'status' => '状态',
                'score' => '积分',
                'winCount' => '胜',
                'loseCount' => '败',
            ];
            $response = $this->request('/queryPlayers' , $params);
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
                $data = $response['results'][0];
                \Yii::trace($data , $logCategory);
                return $data;
            }
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }
    
    
    public function update($data){
        try {
            $this->checkId();
            \Yii::trace($data , 'Api.Member.update');
            return $this->request('/editPlayerInfo' , $data);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function audit($data){
        try{
            \Yii::trace($data,'Api.Member.audit');
            return $this->request('/pubg/game/league/auditLeagueSign',$data);
        }catch(\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function ban($data){
        try{
            \Yii::trace($data,'Api.Member.ban');
            return $this->request('/pubg/game/sign/gameBanYes',$data);
        }catch(\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function unban($data){
        try{
            \Yii::trace($data,'Api.Member.unban');
            return $this->request('/pubg/game/sign/gameBanNo',$data);
        }catch(\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function leagueMemberInfo($leagueId,$seasonId,$userId,$gameType){
        try{
            $data = [
                'leagueId' => $leagueId,
                'seasonId' => $seasonId,
                'uid' => $userId,
                'gameType' => $gameType
            ];
            \Yii::trace($data,'Api.Member.queryLeagueMemberInfo');
            return $this->request('/pubg/game/league/queryLeagueMemberInfo',$data);
        }catch(\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function updateLeagueMemberInfo($data){
        try{
            \Yii::trace($data,'Api.Member.updateLeagueMemberInfo');//VarDumper::dump($data,10,true);exit;
            return $this->request('/pubg/game/league/updateLeagueMemberInfo',$data);
        }catch(\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function delWhiteList($data){
        try{
            $this->checkId();
            \Yii::trace($data,'Api.Member.delWhiteList');
            return $this->request('/game/league/delWhiteList',$data);
        }catch(\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }
    
}
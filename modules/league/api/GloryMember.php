<?php
namespace app\modules\league\api;

use app\components\RequestRemoteApi;

class GloryMember extends RequestRemoteApi{
    
    private $_id;
    private $_error;
    
    
    public function __construct($matchId = null){
        $this->_id = $matchId;
    }
    
    public function checkId(){
        if(empty($this->_id)){
            throw new \Exception('无效的编号！');
        }
    }
    
    public function listdata($leagueId = 0 , $seasonId = 0 , $condition = ['all' => ''] , $status = 0 , $page = 1 , $pageSize = 15 , $order = 'id'){
        $logCategory = "Api.Member.listdata";
        $data = [];
        
        $orderList = ['id' => 0 , 'score' =>1];
        if(!isset($orderList[$order])){
            $orderType = 0;
        }else{
            $orderType = $orderList[$order];
        }

        $typeList = ['all' => 0 , 'qq' => 1 , 'mobile' => 2 , 'role' => 3];
        $type = $typeList['all'];
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

        if($content == ''){
            $type = 0;
        }

        $params = [
            'pageNo' => $page,
            'pageSize' => $pageSize,
            'leagueId' => $leagueId,
            'seasonId' => $seasonId,
            'type' =>  $type,
            'content' => $content,
            'status' => $status,
            'orderType' => $orderType
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
        $response = $this->request('/queryPlayers' , $params,'post');
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
        return true;
    }
    
    public function changeStatus($status,$remark=null){
        try {
            $this->checkId();
            if(!in_array($status , [2,3,4,5])){
                throw new \Exception('无效的状态值！');
            }
            $params = [
                'id' => $this->_id,
                'status' => $status,
            ];
            if(!empty($remark)){
                $params['remark'] = $remark;
            }
            $response = $this->request( '/checkPlayer' , $params);
            return $response;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }
    
}
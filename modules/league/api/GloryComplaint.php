<?php
namespace app\modules\league\api;

use app\components\RequestRemoteApi;

class GloryComplaint extends RequestRemoteApi{
    
    private $_id;
    private $_error;
    
    public function __construct($complaintId = null){
        $this->_id = $complaintId;
    }
    
    protected function checkId(){
        if(empty($this->_id) || !is_numeric($this->_id)){
            throw new \Exception('无效的投诉编号！');
        }
    }
    
    public function getError(){
        return $this->_error;
    }
    
    public function listdata($page = 1 , $pageSize = 15 , $condition = ['roleId' => ''] , $date = ['begin' => '' , 'end' => '']){
        $logCategory = "Api.Complaint.listdata";
        $data = [];
        $typeList = ['roleId' , 'gameRecordId' , 'nickName'];
        
        $params = [
            'pageNo' => $page,
            'pageSize' => $pageSize,
        ];
    
        if(isset($date['begin']) && !empty($date['begin'])){
            $params['startTime'] = $date['begin'];
        }
        if(isset($date['end']) && !empty($date['end'])){
            $params['endTime'] = $date['end'];
        }
        
        if(!empty($condition) && is_array($condition)){
            $conditionKeys = array_keys($condition);
            $conditionKey = $conditionKeys[0];
            if(in_array($conditionKey, $typeList) && !empty($condition[$conditionKey])){
                $params[$conditionKey] = $condition[$conditionKey];
            }
        }
        
        \Yii::trace( $params , $logCategory . '.SendParams');
        
        $responseRequireParams = [
            'id' => '编号',
            'reportRoleId' => '投诉人',
            'reportedRoleId' => '被投诉人',
            'userNo' => '用户编号',
            'nickName' => '昵称',
            'gameScreenshot' => '游戏截图',
            'time' => '时间',
            'leagueId' => '联赛',
            'gameRecordId' => '游戏编号',
            'reportContent' => '投诉理由',
            'reportNum' => '被投诉次数',
        ];
        $response = $this->request('/queryComplains' , $params);
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
            
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
        //return $data;
        return true;
    }
    
    public function clear($userId){
        try {
            $params = ['userId' => $userId];
            \Yii::trace($params , 'Complaint.clear');
            $response = $this->request('/clearComplains' , $params);
            \Yii::trace($response , 'Complaint.clear');
            return $response;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }
    
    
}
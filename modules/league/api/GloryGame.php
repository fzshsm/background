<?php
namespace app\modules\league\api;

use app\components\RequestRemoteApi;

class GloryGame extends RequestRemoteApi{
    
    
    private $_id;
    private $_error;
    
    public function __construct($complaintId = null){
        $this->_id = $complaintId;
    }
    
    protected function checkId(){
        if(empty($this->_id) || !is_numeric($this->_id)){
            throw new \Exception('无效的比赛编号！');
        }
    }

    public function listdata($page = 1 , $pageSize = 15 , $condition = ['roleId' => ''] , $date = ['begin' => '' , 'end' => ''],$status,$dataType){
        $logCategory = "Api.Game.listdata";
        $data = [];
        $typeList = ['roleId' , 'gameRecordId' , 'nickName' , 'leagueId' , 'seasonId'];
        
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
            foreach($conditionKeys as $key){
                if(!empty($key) && in_array($key, $typeList) && !empty($condition[$key])){
                    $params[$key] = $condition[$key];
                }
            }
        }

        if(!empty($status)){
            $params['status'] = $status;
        }

        if($dataType == 0 || $dataType == 1){
            $params['dataType'] = $dataType;
        }
        
        \Yii::trace( $params , $logCategory . '.SendParams');
        
        $responseRequireParams = [
            'id' => '编号',
            'startTime' => '开始时间',
            'endTime' => '结束时间',
            'winner' => '胜利者',
            'status' => '状态',
            'leagueId' =>'联赛',
            'seasonId' => '赛季',
            'group1Members' => 'A队',
            'group2Members' => 'B队',
        ];
        $response = $this->request('/queryGameRecord' , $params);
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
    
    public function cancel(){
        try {
            $this->checkId();
            $params = [
                'gameRecordId' => $this->_id,
            ];
            $response = $this->request( '/obsoleteGameResult' , $params);
            return $response;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }
    
    public function changeResult($result){
        try {
            $this->checkId();
            if(!in_array($result , [2,3,4])){
                throw new \Exception('无效的状态值！');
            }
            $params = [
                'gameRecordId' => $this->_id,
                'winner' => $result
            ];
            $response = $this->request( '/modifyGameResult' , $params);
            return $response;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function refreshQueue(){
        try {
            $response = $this->request( '/reshGameQueue');
            return $response;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }
}
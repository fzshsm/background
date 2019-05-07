<?php
namespace app\modules\user\api;

use app\components\RequestRemoteApi;

class Authenticate extends RequestRemoteApi{
    
    private $_id;
    private $_error;
    
    public function __construct($complaintId = null){
        $this->_id = $complaintId;
    }
    
    protected function checkId(){
        if(empty($this->_id)){
            throw new \Exception('无效编号！');
        }
    }
    
    public function getError(){
        return $this->_error;
    }
    
    public function listdata($condition = ['all' => 0] , $status = -1 , $page = 1 , $pageSize = 15 ,   $order = 'id.desc'){
        $logCategory = "Api.Authenticate.listdata";
        $data = [];
        $typeList = ['all' => 0 , 'qq' => 1 , 'nickname' => 2];
        $type = $typeList['all'];
        $content = "";
        \Yii::trace($condition , $logCategory);
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
        $params = [
            'pageNo' => $page,
            'pageSize' => $pageSize,
            'type' => $type,
            'status' => $status,
            'content' => $content,
        ];
        $responseRequireParams = [
            'id' => '编号',
            'userId' => '用户编号',
            'nickName' => '昵称',
            'qq' => 'QQ',
            'personName' => '真实姓名',
            'cardId' => '身份证',
            'facadePhotoUrl' => '正面照',
            'backFacesPhotoUrl' => '反面照',
            'bodyHalfPhotoUrl' => '半身照',
            'clubName' => '战队',
            'status' => '状态',
        ];
        $response = $this->request('/queryCertificationList' , $params);
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
    
    public function changeStatus($status){
        try {
            $this->checkId();
            if(!in_array($status , [0,1,2])){
                throw new \Exception('无效的状态值！');
            }
            $params = [
                'id' => $this->_id,
                'status' => $status
            ];
            $response = $this->request( '/checkValidPlayer' , $params);
            return $response;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }
}
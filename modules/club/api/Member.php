<?php
namespace app\modules\club\api;

use app\components\RequestRemoteApi;

class Member extends RequestRemoteApi{

    private $_id;
    private $_error;
    
    public function __construct($memberId = null){
        $this->_id = $memberId;
    }
    
    protected function checkId(){
        if(empty($this->_id) || !is_numeric($this->_id)){
            throw new \Exception('无效的成员编号！');
        }
    }
    
    public function listdata($id){
        $logCategory = "Api.Member.listdata";
        $data = [];
        $params = ['id' => $id];

        \Yii::trace( $params , $logCategory . '.SendParams');
        
        $responseRequireParams = [
            'userId' => '成员编号',
            'userNo' => '用户No',
            'realName' => '认证名',
            'roleId' => '游戏角色ID',
            'qq' => 'QQ',
            'leagueName' => '当前联赛名',
            'userTypeName' => '成员类型',
        ];
        $response = $this->request('/queryClubMembers' , $params);

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

}
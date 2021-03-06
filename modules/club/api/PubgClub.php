<?php
namespace app\modules\club\api;

use app\components\RequestRemoteApi;

class PubgClub extends RequestRemoteApi{

    private $_id;
    private $_error;
    
    public function __construct($clubId = null){
        $this->_id = $clubId;
    }

    public function getRequestUrl($action){
        return \Yii::$app->params['pubgApiDomain'] . $action;
    }
    
    protected function checkId(){
        if(empty($this->_id) || !is_numeric($this->_id)){
            throw new \Exception('无效的战队编号！');
        }
    }
    
    public function listdata($page,$pagesize,$gameType,$approvalStatus,$teamName = ''){
        $logCategory = "Api.Club.listdata";
        $data = [];
        $params = [
            'pageNo' => $page,
            'pageSize' => $pagesize,
            'gameType' => $gameType,
            'approvalStatus' => $approvalStatus
        ];
        if(!empty($teamName)){
            $params['teamName'] = $teamName;
        }

        \Yii::trace( $params , $logCategory . '.SendParams');
        
        $responseRequireParams = [
            'id' => '战队编号',
            'name' => '战队名称',
            'icon' => '战队图标',
            'type' => '类型',
            'desc' => '描述'
        ];
        $response = $this->request('/usercenter/team/teamList' , $params);

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

    public function Create($data)
    {
        try {
            \Yii::trace($data , 'Api.Club.update');
            return $this->request('/usercenter/team/createTeam' , $data);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function update($data)
    {
        try {
            \Yii::trace($data , 'Api.Club.update');
            $data['teamId'] = $this->_id;
            return $this->request('/usercenter/team/updateTeam' , $data);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function detail(){
        try {
            $logCategory = "Api.Club.detail";
            $data = [];
            $params = [
                'id' => $this->_id,
            ];
            $responseRequireParams = [
                'id' => '战队编号',
                'name' => '战队名称',
                'icon' => 'icon',
                'type' => '类型',
                'desc' => '描述'
            ];

            $response = $this->request('/usercenter/team/teamDetail' , $params);
            if(!empty($response) && is_array($response)){
                $responseParams = [];
                if(!empty(($response))){
                    $responseParams = array_keys($response);
                }
                $this->checkResponseMissParam($responseRequireParams, $responseParams);
                $missParams = $this->getMissParams();
                if(!empty($missParams)){
                    $this->warning = $this->getMissParamsMessage($responseRequireParams);
                    foreach ($missParams as $param){
                        $response[$param] = null;
                    }
                }
                $data = $response;
                \Yii::trace($data , $logCategory);
            }
            return $data;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function changeStatus($userId,$userTeamId,$isPass,$remark = null)
    {
        try {
            $params = [
                'userId' => $userId,
                'userTeamId' => $userTeamId,
                'isPass' => $isPass,
                'remark' => $remark
            ];

            \Yii::trace($params , 'Api.Club.approval');
            return $this->request('/usercenter/team/teamApproval' , $params);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function getTeamApprovalStatus(){
        try {
            \Yii::trace([] , 'Api.Club.status');
            return $this->request('/usercenter/team/getTeamApprovalStatus');
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function deleteTeam($teamId){
        try {
            $params = ['teamId' => $teamId];

            \Yii::trace($params , 'Api.Club.delete');
            return $this->request('/usercenter/team/deleteTeam', $params );
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function getPubgAllTeam(){
        try {
            $params = [
                'gameType' => 2
            ];
            \Yii::trace($params , 'Api.Club.allTeam');
            return $this->request('/usercenter/team/getAllTeam', $params);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

}
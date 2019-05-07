<?php
namespace app\modules\club\api;

use app\components\RequestRemoteApi;

class PubgMember extends RequestRemoteApi{

    private $_id;
    private $_error;
    
    public function __construct($memberId = null){
        $this->_id = $memberId;
    }

    public function getRequestUrl($action){
        return \Yii::$app->params['pubgApiDomain'] . $action;
    }
    
    protected function checkId(){
        if(empty($this->_id) || !is_numeric($this->_id)){
            throw new \Exception('无效的成员编号！');
        }
    }
    
    public function listdata($id,$pageNo){
        $logCategory = "Api.Member.listdata";
        $data = [];
        $params = [
            'userTeamId' => $id,
            'pageNo' => $pageNo
        ];

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
        $response = $this->request('/usercenter/team/getUserTeamMember' , $params);

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

    public function remove($teamMemberId,$userId)
    {
        try {
            $params = [
                'teamMemberId' => $teamMemberId,
                'userId' => $userId
                ];

            \Yii::trace($params , 'Api.Member.remove');
            return $this->request('/usercenter/team/removeTeamMember' , $params);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function getUserTeamIdentity(){
        try {
            \Yii::trace([] , 'Api.Member.identity');
            return $this->request('/usercenter/team/getUserTeamIdentity');
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function updateTeamMemberIdentity($teamMemberId,$teamIdentity,$userId){
        try {
            $params = [
                'teamMemberId' => $teamMemberId,
                'teamIdentity' => $teamIdentity,
                'userId' => $userId
            ];

            \Yii::trace($params , 'Api.Member.UpdateIdentity');
            return $this->request('/usercenter/team/updateTeamMemberIdentity' , $params);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

}
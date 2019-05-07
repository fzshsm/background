<?php

namespace app\modules\user\api;

use app\components\RequestRemoteApi;

class Invite extends RequestRemoteApi {
    
    private $_id;
    private $_error;
    
    public function __construct($invitId = null){
        $this->_id = $invitId;
    }
    
    protected function checkId(){
        if(empty($this->_id)){
            throw new \Exception('无效邀请编号！');
        }
    }

    public function listData($page,$pageSize){
        $logCategory = "Api.Invite.listData";
        $params = [
            'pageNo' => $page,
            'pageSize' => $pageSize,
        ];
        $data = [];
        \Yii::trace($params , $logCategory);
        $response = $this->request('/inviteStatistics' , $params);
        if(!empty($response) && is_array($response)){
            $data = $response;
        }
        \Yii::trace($data , $logCategory);
        return $data;
    }
    
    public function listDataByUser($userId,$page,$pageSize){
        try {
            $params = [
                'userId' => $userId,
                'pageNo' => $page,
                'pageSize' => $pageSize
            ];
            \Yii::trace($params , 'APi.Invite.queryInvitedUsers');
            return $this->request('/queryInvitedUsers' , $params);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

}
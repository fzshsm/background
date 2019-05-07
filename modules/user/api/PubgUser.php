<?php
namespace app\modules\user\api;

use app\components\RequestRemoteApi;
use yii\helpers\VarDumper;
use yii\httpclient\Client;

class PubgUser extends RequestRemoteApi{
    
    private $_id;
    private $_error;

    public function __construct($id = null ){
        $this->_id = $id;
    }
    
    
    protected function checkId(){
        if(empty($this->_id)){
            throw new \Exception('未找到用户编号！');
        }
    }

    public function getRequestUrl($action){
        return \Yii::$app->params['pubgApiDomain']  . $action;
    }

    public function updateAuthInfo($data){
        try {
            $this->checkId();
            \Yii::trace($data , 'APi.User.updateAuthInfo');
            $response = $this->request('/modifyUserAuthInfo' , $data);
            \Yii::trace($response , 'Api.User.updateAuthInfo');
            return $response;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
        return true;
    }
    

    public function pubgUserInfo(){
        try {
            $params = [
                'userId' => $this->_id,
                'gameType' => 2
            ];

            $response = $this->request('/usercenter/team/getUserTeamMemberDetail' , $params);

            \Yii::trace($response , 'Api.User.pubgAuthorize');

            return $response;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function pubgAuthorize($data){
        try {
            \Yii::trace($data , 'APi.User.pubgAuthorize');

            $response = $this->request('/usercenter/team/addTeamMember' , $data);

            return $response;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function getGameRole($userId,$gameType){
        try {
            $params = [
                'userId' => $userId,
                'gameType' => $gameType
            ];

            \Yii::trace($params , 'APi.User.gameRole');

            $response = $this->request('/usercenter/game/getGameRole' , $params);
            return $response;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function getSteamRole($userId){
        try {
            $params = [
                'userId' => $userId,
            ];

            \Yii::trace($params , 'APi.User.getSteamRole');

            $response = $this->request('/pubg/game/role/getGameRole' , $params);
            return $response;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function bindSteamRole($userId,$nickName){
        try {
            $params = [
                'userId' => $userId,
                'nickName' => $nickName
            ];

            \Yii::trace($params , 'APi.User.steamRole');

            $response = $this->request('/pubg/game/role/bindRole' , $params);
            return $response;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function userBagList($userId,$page = 1 , $pageSize = 15, $searchType,$status,$content,$time){
        try {
            $params = [
                'userId' => $userId,
                'pageNo' => $page,
                'pageSize' => $pageSize,
            ];

            if(!empty($status)){
                $params['status'] = $status;
            }

            if(!empty($content)){
                $params[$searchType] = $content;
            }

            if(!empty($time)){
                $params['useTime'] = $time;
            }

            \Yii::trace($params , 'APi.User.userBagList');

            $response = $this->request('/game/bag/queryBagList' , $params);
            return $response;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function bagUseMark($bagDetailId){
        try {
            $params = [
                'bagDetailId' => $bagDetailId
            ];

            \Yii::trace($params , 'APi.User.bagUseMark');

            $response = $this->request('/game/bag/useMarker' , $params);
            return $response;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function bagOverview($userId){
        try {
            $params = [
                'userId' => $userId
            ];

            \Yii::trace($params , 'APi.User.bagOverview');

            $response = $this->request('/game/bag/queryBag' , $params);
            return $response;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function getUserDetailInfo($gameType){
        try {
            $params = [
                'uid' => $this->_id,
                'gameType' => $gameType
            ];

            \Yii::trace($params , 'APi.User.getUserDetailInfo');

            $response = $this->request('/usercenter/game/getUserDetailInfo' , $params);
            return $response;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function updateUserInfo($data){
        try {
            $this->checkId();
            \Yii::trace($data , 'APi.User.updateUserInfo');
            $response = $this->request('/usercenter/game/updateUserInfo' , $data);
            \Yii::trace($response , 'Api.User.updateUserInfo');
            return $response;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
        return true;
    }

    public function betLogin($username,$password){
        $client = new Client();

        $data = [
            'username' => $username,
            'password' => $password
        ];
        try {
            $requestUrl = \Yii::$app->params['pubgApiDomain'].'/auth/login';
            $response = $client->createRequest()
                ->setUrl($requestUrl)
                ->setMethod('post')
                ->setData($data)
                ->send();
            $responseData = [];
            $token = '';
            if(!empty($response)){
                $responseData = json_decode($response->getContent(),true);

            }
            if(!empty($responseData) && is_array($responseData)){
                $token = $responseData['token'];
            }
            return $token;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function getBetToken($username,$password){
        try {
            $data = [
                'username' => $username,
                'password' => $password
            ];
            \Yii::trace($data , 'APi.User.getToken');
            $response = $this->request('/auth/getToken' , $data);
            \Yii::trace($response , 'Api.User.getToken');
            return $response;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function sendCurrency($userId,$coinB,$remark){
        try {
            $params = [
                'userId' => $userId,
                'coinB' => $coinB,
                'remark' => $remark
            ];

            \Yii::trace($params , 'APi.User.sendCurrency');

            $response = $this->request('/user/rewark/addReward' , $params);
            return $response;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function clearGloryRole(){
        try {
            $this->checkId();
            $params = [
                'userId' => $this->_id,
            ];
            $response = $this->request('/game/league/clearWzryGameRole' , $params);
            \Yii::trace($response , 'Api.User.clearGloryRole');
            return $response;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }
}
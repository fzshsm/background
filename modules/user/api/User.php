<?php
namespace app\modules\user\api;

use app\components\RequestRemoteApi;
use yii\httpclient\Client;

class User extends RequestRemoteApi{
    
    private $_id;
    
    public function __construct($id = null ){
        $this->_id = $id;
    }
    
    
    protected function checkId(){
        if(empty($this->_id)){
            throw new \Exception('未找到记录编号！');
        }
    }
    
    
    public function getIdentityValue($data){
        $value = "";
        if(!empty($data) && is_array($data)){
            if(isset($data['player']) && $data['player'] == 1){
                $value = "player";
            }else if(isset($data['famous']) && $data['famous'] == 1){
                $value = "famous";
            }
        }
            
        return $value;
    }
    
    public function login($username , $password){
        $data = [];
        $params = ['username' => $username , 'password' =>$password,'status' => 'normal'];
//        $response = $this->request('/login' , $params, 'post');

        $response = (new \yii\db\Query())
            ->select(['id'])
            ->from('sr_user')
            ->where($params)
            ->one();
        if($response !== false){
            $data = [
                'id' => isset($response['id']) ? $response['id'] : 1,
                'username' => $username,
                'password' => $password,
                'authKey' =>  $username . '-auth-' . md5(time()),
                'accessToken' =>  $username . '-' . md5(\Yii::$app->request->getUserIP() . \Yii::$app->formatter->asDate(time()))
            ];
        }
        return $data;
    }


    public function listdata($page = 1 , $pageSize = 15 , $condition = ['all' => ''] ,  $order = 'id.desc'){
        $logCategory = "Api.User.listData";
        $data = [];
        $typeList = ['all' => 0 , 'nickname' => 1 , 'mobile' => 2 , 'id' => 3 , 'qq' => 4];
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
                'content' => $content,
        ];
        $responseRequireParams = [ 
                'id' => '编号',
                'userNo' => '用户编号',
                'nickName' => '昵称',
                'gender' => '性别',
                'gameLeagueId' => '所属联赛',
//                 'mobile' => '手机',
                'qq' => 'QQ',
//                 'medalNum' => '勋章',
                'isRealInfo' => '实名',
                'isPlayer' => '职业玩家',
                'isFamous' => '名人',
                'isForbind' => '状态',
                'clubName' => '战队',
//                 'winCount' => '胜场',
//                 'gameCount' => '总场次'
        ];
        $response = $this->request('/queryUsers' , $params);
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
           $logCategory = "Api.User.detail";
           $data = [];
           $params = [
               'id' => $this->_id,
           ];
           $responseRequireParams = [
                   'id' => '编号',
                   'userNo' => '用户编号',
                   'nickName' => '昵称',
                   'gender' => '性别',
                   'mobile' => '手机',
                   'qq' => 'QQ',
                   'medalNum' => '勋章',
                   'isRealInfo' => '实名',
                   'isPlayer' => '职业玩家',
                   'isFamous' => '名人',
                   'isForbind' => '状态',
                   'clubName' => '战队',
                   'winCount' => '胜场',
                   'loseCount' => '败场',
                   'gameCount' => '总场次',
                   'winRatio' =>'胜率',
                   'gameLeagueId' => '所属联赛',
           ];
           $response = $this->request('/getDetail' , $params);
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
    
    public function authInfo(){
        try {
            $this->checkId();
            $logCategory = "Api.User.authInfo";
            $data = [];
            $params = [
                'userId' => $this->_id,
            ];
            $responseRequireParams = [
                'userId' => '用户编号',
                'personName' => '用户名称',
                'clubName' => '战队',
                'headImg' => '头像',
                'clubId' => '战队编号',
            ];
            $response = $this->request('/getUserAuthInfo' , $params);

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
    
    
    
    public function update($data){
        try {
            $this->checkId();
            \Yii::trace($data , 'APi.User.update');
            $response = $this->request('/refreshUserInfo' , $data);
            \Yii::trace($response , 'Api.User.update');
            return $response;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
        return true;
    }
    
    
    public function changeState($state = 'lock'){
        try {
            $this->checkId();
            $params = [
                'id' => $this->_id,
                'forbidType' => $state == 'lock' ? 1 : 0,
            ];
            $response = $this->request('/forbidAccount' , $params);
            \Yii::trace($response , 'Api.User.changeState');
            return $response;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
        return true;
    }
    
    public function unbindQQ(){
        try {
            $this->checkId();
            $params = [
                'uId' => $this->_id,
            ];
            $response = $this->request('/unbindQQ' , $params);
            \Yii::trace($response , 'Api.User.unbindQQ');
            return $response;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
        return true;
    }

    public function controlChat($time,$controlType = '0'){
        try {
            $this->checkId();
            $params = [
                'uid' => $this->_id,
                'time' => intval($time),
                'controlType' => intval($controlType)
            ];

            $response = $this->request('/controlChat', $params);
            \Yii::trace($response, 'Api.User.controlChat');
            return $response;
        } catch(\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }
    public function authlist(){
        try {
            //personName , qq
            $params = [
            
            ];
            $response = $this->request('/queryAuthedUsers' , $params);
            \Yii::trace($response , 'Api.User.authlist');

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
            $this->checkId();
            \Yii::trace($data , 'APi.User.pubgAuthorize');

            $response = $this->request('/usercenter/team/getUserTeamMemberDetail' , $data);
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
}
<?php
namespace app\modules\mall\api;

use app\components\RequestRemoteApi;
use yii\helpers\Json;
use yii\helpers\VarDumper;

class RoomCard extends RequestRemoteApi{
    
    private $_id;
    private $_error;

    public function __construct($roomCardId = null){
        $this->_id = $roomCardId;
    }
    
    protected function checkId(){
        if(empty($this->_id) || !is_numeric($this->_id)){
            throw new \Exception('无效的投诉编号！');
        }
    }

    public function getRequestUrl($action){
        return \Yii::$app->params['pubgApiDomain'] . $action;
    }
    
    public function listdata($page = 1 , $pageSize = 15, $type, $content){
        try {
            $logCategory = "Api.Complaint.listdata";

            $params = [
                'pageNo' => $page,
                'pageSize' => $pageSize,
            ];

            if(!empty($type)){
                $params['roomCardType'] = $type;
            }

            if(!empty($content)){
                $params['roomCardName'] = $content;
            }

            \Yii::trace( $params , $logCategory . '.SendParams');
            return $this->request('/game/roomCard/queryRoomCardPage' , $params);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function create($data){
        try {
            \Yii::trace($data , 'Api.RoomCard.create');
            return $this->request('/game/roomCard/addRoomCard' , $data,'post');
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function update($data){
        try {
            \Yii::trace($data , 'Api.RoomCard.update');
            return $this->request('/game/roomCard/updateRoomCard' , $data,'post');
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function detail(){
        try {
            $params = [
                'roomCardId' => $this->_id,
            ];
            \Yii::trace($params , 'Api.RoomCard.detail');
            return $this->request('/game/roomCard/getRoomCardDetail' , $params);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function searchUserByUserNo($searchType,$content){
        try {
            $params = [
                $searchType => $content,
            ];
            \Yii::trace($params , 'Api.RoomCard.user');
            return $this->request('/usercenter/user/getUserByUserNo' , $params);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function sendRoomCard($data){
        try {
            $requestUrl = $this->getRequestUrl('/game/roomCard/sendRoomCard');
            $cacheKey = 'login-'.\Yii::$app->user->id;
            $token = \Yii::$app->cache->get($cacheKey);
            $header['content-type'] = 'application/json';
            $header['X-Authorization'] = 'Bearer '.$token;
            $response = $this->createRequest()->setUrl($requestUrl)
                ->addHeaders($header)
                ->setContent($data)
                ->send();
            \Yii::trace($data , 'Api.RoomCard.sendRoomCard');
            $content = Json::decode($response->getContent());
            if($content == false){
                \Yii::error($response->getContent() , __METHOD__);
                throw new \Exception("接口返回数据格式不正确！" , 500);
            }
            if(isset($content['code'])){
                if($content['code'] == 200){
                    return isset($content['result']) ? $content['result'] : true;
                }else{
                    throw new \Exception($content['msg'] , $content['code']);
                }
            }elseif(isset($content['status'])){
                if($content['status'] == 404){
                    \Yii::error("Request Url :" . $requestUrl , __METHOD__);
                    throw new \Exception("请求的数据接口不存在！" ,  $content['status']);
                }elseif($content['status'] != 200){
                    throw new \Exception($content['message'] ,  $content['status']);
                }
            }
        }catch (\Exception $e){
            if($e->getCode() == 2){
                $this->_error = "无法连接接口服务！";
            }elseif ( $e->getCode() >= 100){
                $this->_error = $e->getMessage();
            }else{
                \Yii::error($e , __METHOD__);
                $this->_error = "请求接口发生未知错误！";
            }
            return false;
        }
    }

    public function getRoomCardAllList(){
        try {

            \Yii::trace('' , 'Api.RoomCard.sendRoomCard');
            return $this->request('/game/roomCard/queryRoomCardList');
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function detailList($id,$page = 1 , $pageSize = 15,$searchType,$status,$content,$time){
        try {
            $logCategory = "Api.Complaint.listdata";

            $params = [
                'roomCardId' => $id,
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

            \Yii::trace( $params , $logCategory . '.SendParams');
            return $this->request('/game/roomCard/queryUserRoomCardList' , $params);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function queryRoomCardList(){
        try {
            \Yii::trace([] , 'Api.RoomCard.queryRoomCardList');
            return $this->request('/game/roomCard/queryRoomCardList' );
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }
    
}
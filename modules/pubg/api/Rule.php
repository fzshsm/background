<?php
namespace app\modules\pubg\api;

use app\components\RequestRemoteApi;
use yii\helpers\Json;

class Rule extends RequestRemoteApi{

    private $_id;
    private $_error;
    
    public function __construct($matchId = null){
        $this->_id = $matchId;
    }
    
    public function checkId(){
        if(empty($this->_id) || !is_numeric($this->_id)){
            throw new \Exception('无效的配置编号！');
        }
    }

    public function getRequestUrl($action){
        return \Yii::$app->params['pubgApiDomain'] . $action;
    }

    public function datalist($page,$pageSize){
        try {
            \Yii::trace([] , 'Api.Rule.list');
            $params = [
                'pageNo' => $page,
                'pageSize' => $pageSize,
            ];

            return $this->request('/pubg/config/queryPubgRankConfigPage',$params);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function detail($id){
        try {
            $logCategory = "Api.Rule.detail";
            $data = [];
            $params = ['id' => $id];
            $response = $this->request('/pubg/config/getPubgRankConfigDetail' , $params);
            \Yii::trace($response , $logCategory);
            if(!empty($response) && is_array($response)){
                $data = $response;
            }
            return $data;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function update($data){
        try {
            $requestUrl = $this->getRequestUrl('/pubg/config/addPubgRankConfig');
            $cacheKey = 'login-'.\Yii::$app->user->id;
            $token = \Yii::$app->cache->get($cacheKey);
            $header['content-type'] = 'application/json';
            $header['X-Authorization'] = 'Bearer '.$token;
            $response = $this->createRequest()->setUrl($requestUrl)
                ->addHeaders($header)
                ->setContent($data)
                ->send();
            \Yii::trace($data , 'Api.Rule.update');
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

    public function status($data){
        try {
            \Yii::trace($data , 'Api.Rule.delete');
            return $this->request('/pubg/config/updatePubgRankConfigStatus' , $data,'get');
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function configList(){
        try {
            \Yii::trace([] , 'Api.Rule.alllist');
            return $this->request('/pubg/config/queryAllRankConfigList');
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }



}
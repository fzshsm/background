<?php
namespace app\components;

use yii\helpers\VarDumper;
use yii\httpclient\Client;
use yii\helpers\Json;

class RequestRemoteApi extends Client{
    
    
    private $_error;
    private $_missParams = [];
    protected $warning;
    
    public function getRequestUrl($action){
        return \Yii::$app->params['remoteApiDomain'] . $action;
    }
    
    public function request($action , $data = [] , $method = 'get',$header = [],$content = []){
        try {
            $url = $this->getRequestUrl($action);
            $cacheKey = 'login-'.\Yii::$app->user->id;
            $token = \Yii::$app->cache->get($cacheKey);
            $header['X-Authorization'] = 'Bearer '.$token;
            if(!empty($content)){
                $response = $this->createRequest()->setUrl($url)->addHeaders($header)->setMethod($method)->setContent($content)->setOptions([CURLOPT_CONNECTTIMEOUT => 3, CURLOPT_TIMEOUT => 3])->send();
            }else{
                $response = $this->createRequest()->setUrl($url)->addHeaders($header)->setMethod($method)->setData($data)->setOptions([CURLOPT_CONNECTTIMEOUT => 3, CURLOPT_TIMEOUT => 3])->send();
            }

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
                    \Yii::error("Request Url :" . $url , __METHOD__);
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
        }
        return false;
    }
    
    public function checkResponseMissParam($requireParams , $responseParams){
        if (is_array($requireParams) && !empty($requireParams) && is_array($responseParams) && !empty($responseParams)){
            foreach ($requireParams as $param => $name){
                if(!in_array($param, $responseParams)){
                    $this->_missParams[] = $param;
                }
            }
        }
    }
    
    public function getMissParams(){
        return $this->_missParams;
    }
    
    public function getMissParamsMessage($paramLabels){
        $message = '';
        if(!empty($paramLabels) && is_array($paramLabels)){
            foreach ($this->_missParams as $param){
                if(isset($paramLabels[$param]) && !empty($paramLabels[$param])){
                    $message = $paramLabels[$param] . ' , ';
                }
            }
            $message = "缺少以下数据：" . rtrim($message , ' , ');
        }
        return $message;
    }
    
    public function getWarning(){
        return $this->warning;
    }
    
    public function getError(){
        return $this->_error;
    }
    
}
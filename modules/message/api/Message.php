<?php
namespace app\modules\message\api;

use app\components\RequestRemoteApi;

class Message extends RequestRemoteApi{

    private $_id;
    private $_error;
    
    public function __construct($messageId = null){
        $this->_id = $messageId;
    }
    
    protected function checkId(){
        if(empty($this->_id) || !is_numeric($this->_id)){
            throw new \Exception('无效的消息编号！');
        }
    }
    
    public function listdata($page,$pagesize){
        $logCategory = "Api.Message.listdata";
        $data = [];
        $params = [
            'pageNo' => $page,
            'pageSize' => $pagesize,
        ];

        \Yii::trace( $params , $logCategory . '.SendParams');
        
        $responseRequireParams = [
            'title' => '标题',
            'time' => '时间',
            'status' => '状态',
            'userName' => '发布人',
            'context' => '内容'
        ];
        $response = $this->request('/getPushRecord' , $params);

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

    public function create($data)
    {
        try {
            \Yii::trace($data , 'Api.Message.create');
            return $this->request('/pushMsg' , $data);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

}
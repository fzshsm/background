<?php
namespace app\modules\club\api;

use app\components\RequestRemoteApi;

class Club extends RequestRemoteApi{

    private $_id;
    private $_error;
    
    public function __construct($clubId = null){
        $this->_id = $clubId;
    }
    
    protected function checkId(){
        if(empty($this->_id) || !is_numeric($this->_id)){
            throw new \Exception('无效的战队编号！');
        }
    }
    
    public function listdata($page,$pagesize){
        $logCategory = "Api.Club.listdata";
        $data = [];
        $params = [
            'pageNo' => $page,
            'pageSize' => $pagesize,
            'queryAll' => 0
        ];

        \Yii::trace( $params , $logCategory . '.SendParams');
        
        $responseRequireParams = [
            'id' => '战队编号',
            'name' => '战队名称',
            'icon' => '战队图标',
            'type' => '类型',
            'desc' => '描述'
        ];
        $response = $this->request('/queryClub' , $params);

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
            return $this->request('/addOrUpateClub' , $data);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function update($data)
    {
        try {
            $this->checkId();
            \Yii::trace($data , 'Api.Club.update');
            $data['id'] = $this->_id;
            return $this->request('/addOrUpateClub' , $data);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function detail(){
        try {
            $this->checkId();
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

            $response = $this->request('/findClub' , $params);
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

    public function clubType(){
        try{
            \Yii::trace( [],'Api.Club.update');
            return $response = $this->request('/getClubType');
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function allClubList(){
        try{
            $params = [
                'queryAll' => 1
            ];
            \Yii::trace( $params,'Api.Club.allClubList');
            return $response = $this->request('/queryClub',$params);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function deleteById()
    {
        try {
            $this->checkId();

            $params  = [
                'id' => $this->_id
            ];

            \Yii::trace($params , 'Api.Club.delete');
            return $this->request('/delClub' , $params);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

}
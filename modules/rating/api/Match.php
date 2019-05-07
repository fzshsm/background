<?php
namespace app\modules\rating\api;

use app\components\RequestRemoteApi;

class Match extends RequestRemoteApi{

    private $_id;
    private $_error;
    
    public function __construct($gameId = null){
        $this->_id = $gameId;
    }
    
    protected function checkId(){
        if(empty($this->_id) || !is_numeric($this->_id)){
            throw new \Exception('无效的赛事编号！');
        }
    }
    
    public function listdata($page,$pageSize){
        $logCategory = "Api.Game.listdata";
        $data = [];
        $params = [
            'pageNo' => $page,
            'pageSize' => $pageSize
        ];

        \Yii::trace( $params , $logCategory . '.SendParams');
        
        $responseRequireParams = [
            'id' => '编号',
            'name' => '赛事名称',
            'icon' => '赛事图标',
            'time' => '更新时间',
        ];
        $response = $this->request('/getGameTypes' , $params);

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
        \Yii::trace($data , 'Api.Game.create');
        return $this->request('/addOrUpdateGameType' , $data);
    }

    public function update($data)
    {
        try {
            $this->checkId();
            \Yii::trace($data , 'Api.Game.update');
            return $this->request('/addOrUpdateGameType' , $data);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function detail(){
        try {
            $this->checkId();
            $logCategory = "Api.Game.detail";
            $data = [];
            $params = [
                'id' => $this->_id,
            ];
            $responseRequireParams = [
                'id' => '编号',
                'name' => '赛事名',
                'icon' => '赛事图标',
                'time' => '更新时间',
            ];
            $response = $this->request('/queryGameType' , $params);
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
    
    public function deleteById(){
        try {
            $this->checkId();
            $params = [
                'id' => $this->_id,
            ];
            $response = $this->request( '/delGameType' , $params);
            return $response;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }
}
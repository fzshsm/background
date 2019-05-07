<?php
namespace app\modules\rating\api;

use app\components\RequestRemoteApi;

class Rule extends RequestRemoteApi{

    private $_id;
    private $_error;
    
    public function __construct($ruleId = null){
        $this->_id = $ruleId;
    }
    
    protected function checkId(){
        if(empty($this->_id) || !is_numeric($this->_id)){
            throw new \Exception('无效的规则编号！');
        }
    }
    
    public function listdata($id){
        $logCategory = "Api.Rule.listdata";
        $data = [];
        $params = [
            'id' => $id
        ];

        \Yii::trace( $params , $logCategory . '.SendParams');
        
        $responseRequireParams = [
            'id' => '编号',
            'type' => '规则类型',
            'scoreOne' => '得分一',
            'scoreTwo' => '得分二',
            'remark' => '备注'
        ];
        $response = $this->request('/getGameRules' , $params);

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
            \Yii::trace($data , 'Api.Rule.create');
            return $this->request('/addOrUpdateGameRule' , $data);
        }catch (\Exception $e) {
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function update($data)
    {
        try {
            $this->checkId();
            \Yii::trace($data , 'Api.Rule.update');

            return $this->request('/addOrUpdateGameRule' , $data);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function detail(){
        try {
            $this->checkId();
            $logCategory = "Api.rule.detail";
            $data = [];
            $params = [
                'id' => $this->_id,
            ];
            $responseRequireParams = [
                'id' => '编号',
                'type' => '规则类型',
                'scoreOne' => '得分一',
                'scoreTwo' => '得分二',
                'remark' => '备注',
                'pid' => '赛事类型ID',
                'time' => '更新时间'
            ];
            $response = $this->request('/queryGameRule' , $params);
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
            $response = $this->request( '/delGameTypeRule' , $params);
            return $response;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }
}
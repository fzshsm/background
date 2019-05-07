<?php
namespace app\modules\admin\models;

use app\components\RequestRemoteApi;

class Version extends RequestRemoteApi{

    private $_id;
    private $_error;

    public function __construct($versionId = null){
        $this->_id = $versionId;
    }

    protected function checkId(){
        if(empty($this->_id) || !is_numeric($this->_id)){
            throw new \Exception('无效的版本编号！');
        }
    }

    public function getRequestUrl($action){
        return \Yii::$app->params['pubgApiDomain'] . $action;
    }

    public function listdata($type){
        $logCategory = "Api.Version.listdata";
        $data = [];
        $params = [];
        if($type){
            $params = [
                'type' => (int)$type,
            ];
        }

        \Yii::trace( $params , $logCategory . '.SendParams');

        $responseRequireParams = [
            'id' => '编号',
            'code' => '版本号',
            'type' => '设备类型',
            'remark' => '版本描述',
            'downLoadUrl' => '下载链接',
            'time' => '更新时间',
        ];
        $response = $this->request('/manager/queryAppVersion' , $params);

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
            \Yii::trace($data , 'Api.Version.create');
            return $this->request('/manager/saveOrUpdateAppVersion' , $data);
        }catch (\Exception $e) {
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function update($data)
    {
        try {
            $this->checkId();
            \Yii::trace($data , 'Api.Version.update');

            return $this->request('/manager/saveOrUpdateAppVersion' , $data);
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
            $response = $this->request( '/manager/delAppVersion' , $params);
            return $response;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }
}
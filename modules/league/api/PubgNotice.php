<?php
namespace app\modules\league\api;

use app\components\RequestRemoteApi;

class PubgNotice extends RequestRemoteApi{

    private $_id;
    private $_error;
    
    public function __construct($noticeId = null){
        $this->_id = $noticeId;
    }
    
    protected function checkId(){
        if(empty($this->_id) || !is_numeric($this->_id)){
            throw new \Exception('无效的公告编号！');
        }
    }
    
    public function getError(){
        return $this->_error;
    }
    
    public function listdata($leagueId){
        $logCategory = "Api.Notice.listdata";
        $data = [];
        $params = [
            'leagueId' => (int)$leagueId,
        ];

        \Yii::trace( $params , $logCategory . '.SendParams');
        
        $responseRequireParams = [
            'id' => '编号',
            'leagueId' => '赛季ID',
            'message' => '公告内容',
            'sortWeight' => '排序权重',
            'time' => '更新时间',
        ];
        $response = $this->request('/queryNotice' , $params);

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
        \Yii::trace($data , 'Api.Notice.create');
        return $this->request('/saveOrUpdateNotice' , $data);
    }

    public function update($data)
    {
        try {
            $this->checkId();
            \Yii::trace($data , 'Api.Notice.update');

            return $this->request('/saveOrUpdateNotice' , $data);
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
            $response = $this->request( '/delNotice' , $params);
            return $response;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }
}
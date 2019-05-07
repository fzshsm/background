<?php
namespace app\modules\league\api;

use app\components\RequestRemoteApi;

class PubgCustom extends RequestRemoteApi{

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

    public function datalist($name,$page,$pageSize){
        try {
            \Yii::trace([] , 'Api.custom.list');
            $params = [
                'pageNo' => $page,
                'pageSize' => $pageSize,
            ];

            if(!empty($name)){
                $params['name'] = $name;
            }

            return $this->request('/pubg/game/config/queryWithPage',$params);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function detail($id){
        try {
            $logCategory = "Api.Custom.detail";
            $data = [];
            $params = ['id' => $id];
            $response = $this->request('/pubg/game/config/query' , $params);
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
            \Yii::trace($data , 'Api.Csutom.update');
            return $this->request('/pubg/game/config/addOrUpdate' , $data,'post');
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function del($data){
        try {
            \Yii::trace($data , 'Api.Csutom.delete');
            return $this->request('/pubg/game/config/delete' , $data,'get');
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function configList(){
        try {
            \Yii::trace([] , 'Api.Match.leagueSorts');
            return $this->request('/pubg/game/config/queryConfigs');
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

}
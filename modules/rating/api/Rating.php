<?php
/**
 * Created by IntelliJ IDEA.
 * User: Zhangxinlong
 * Date: 2017/11/3
 * Time: 10:39
 */

namespace app\modules\rating\api;


use app\components\RequestRemoteApi;

class Rating extends RequestRemoteApi {
    private $_id;
    
    public function __construct($recordId = null){
        $this->_id = $recordId;
    }
    
    public function checkId(){
        if(empty($this->_id) || !is_numeric($this->_id)){
            throw new \Exception('无效的记录编号！');
        }
    }

    public function listData($searchType = 'personName' , $content = '' , $page = 1 , $pageSize = 20){
        $logCategory = "Api.Rating.listData";
        $params = [
            'pageNo' => $page,
            'pageSize' => $pageSize,
            $searchType => $content
        ];
        $data = [];
        \Yii::trace($params , $logCategory);
        $response = $this->request('/queryScoreRecords' , $params);
        if(!empty($response) && is_array($response)){
            $data = $response;
        }
        \Yii::trace($data , $logCategory);
        return $data;
    }
    
    public function types(){
        $logCategory = "Api.Rating.types";
        $data = [];
        $params = [];
        \Yii::trace($params , $logCategory);
        $response = $this->request('/getGameTypes' , $params);
        \Yii::trace($response , $logCategory);
        if(!empty($response) && is_array($response)){
            foreach($response as $value){
                $data[$value['id']] = $value['name'];
            }
        }
        return $data;
    }
    
    public function detail(){
        try {
            $this->checkId();
            $logCategory = "Api.Rating.detail";
            $data = [];
            $params = ['id' => $this->_id];
            $response = $this->request('/queryScoreRecordById' , $params);
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
    
    public function create($data){
        try {
            \Yii::trace($data , 'Api.Rating.create');
            return $this->request('/addScoreRecord' , $data);
        }catch (\Exception $e) {
            $this->_error = $e->getMessage();
            return false;
        }

    }
    
    public function update($data){
        try {
            $this->checkId();
            \Yii::trace($data , 'Api.Rating.update');
            return $this->request('/editScoreRecord' , $data);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }
    
    public function deleteById(){
        try {
            $this->checkId();
            $data = ['recordId' => $this->_id];
            \Yii::trace($data , 'Api.Rating.delete');
            return $this->request('/delScoreRecord' , $data);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function gameTypeAndRule(){
        $logCategory = "Api.Rating.gameTypeAndRule";
        $response = $this->request('/getGameTypeAndRules' );
        \Yii::trace($response , $logCategory);
        $data = [];
        if(!empty($response) && is_array($response)){
            $data = $response;
        }
        return $data;
    }

    public function getUsersScoreList(){
        try {
            $logCategory = "Api.Rating.getUsersScoreList";
            $data = [];
            $response = $this->request('/queryAuthedUsers');
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
}
<?php
namespace app\modules\league\api;

use app\components\RequestRemoteApi;
use yii\httpclient\Client;

class PubgRobot extends RequestRemoteApi{

    private $_id;
    private $_error;
    
    public function __construct($recruitId = null){
        $this->_id = $recruitId;
    }
    
    protected function checkId(){
        if(empty($this->_id) || !is_numeric($this->_id)){
            throw new \Exception('无效的机器人编号！');
        }
    }

   public function getRequestUrl($action)
   {
       return \Yii::$app->params['pubgApiDomain']  . $action;
   }

    public function listdata(){
        try {

            $logCategory = "Api.robot.listdata";
            $params = [];

            \Yii::trace($params, $logCategory . '.SendParams');

            return $this->request('/pubg/game/match/queryRobot');
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function update($data)
    {
        try {
            \Yii::trace($data , 'Api.Robot.update');
            return $this->request('/pubg/game/match/updateRobot' , $data);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function updateVersion($data){
        $client = new Client();
        $requestUrl = \Yii::$app->params['pubgVersionApi'].'/game/version';

        $response = $client->get($requestUrl)->setData($data)->send();

        $responseData = [];
        if(!empty($response)){
            $responseData = json_decode($response->getContent(),true);

        }
        return $responseData;
    }

    public function getVersion(){
        $client = new Client();
        $requestUrl = \Yii::$app->params['pubgVersionApi'].'/game/version';

        $response = $client->get($requestUrl)->send();

        $responseData = [];
        if(!empty($response)){
            $responseData = json_decode($response->getContent(),true);

        }
        return $responseData;
    }

}
<?php
namespace app\modules\league\api;

use app\components\RequestRemoteApi;
use yii\httpclient\Client;

class PubgVersion extends RequestRemoteApi{

    private $_id;
    private $_error;
    
    public function __construct($versionId = null){
        $this->_id = $versionId;
    }


   public function getRequestUrl($action)
   {
       return \Yii::$app->params['pubgVersionApi']  . $action;
   }

    public function setVersion($data){

        try {
            \Yii::trace($data , 'Api.Notice.update');

            return $this->request('/game/set/version',$data);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function getVersion(){
        $logCategory = "Api.version.detail";
        $params = [];

        \Yii::trace( $params , $logCategory);

        return $this->request('/game/version');
    }

    public function checkRobot($data){
        try {
            \Yii::trace($data , 'Api.Notice.update');

            return $this->request('/game/set/version',$data);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

}
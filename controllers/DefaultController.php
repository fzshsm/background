<?php
namespace app\controllers;



use app\components\RequestRemoteApi;
use yii\httpclient\Client;

class DefaultController extends \app\controllers\Controller{
    
    private $_error;
    
    public function actionIndex(){
        $data = $this->getData();
        return $this->render('index' , ['data' => $data]);
    }
    
    protected function getData(){
        $client = new Client();
        try {
            $data = [];
            $logCategory = "Api.Index.Count";
            $requestUrl = \Yii::$app->params['dataApiDomain'].'/analyze/statistics/overview';

            $response = $client->get($requestUrl)->send();
            $responseData = [];
            if(!empty($response)){
                $responseData = json_decode($response->getContent(),true);

            }
            \Yii::trace($responseData , $logCategory);
            if(!empty($responseData) && is_array($responseData)){
                $data = $responseData['result'];
            }
            return $data;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }
}
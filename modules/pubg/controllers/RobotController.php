<?php

namespace app\modules\pubg\controllers;

use app\controllers\Controller;
use app\modules\league\api\PubgRobot;
use app\modules\league\api\PubgVersion;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;

class RobotController extends Controller {

    public function actionIndex(){
        $data =  [];
        $request = \Yii::$app->request;
        $page = $request->get('page'  , 1);
        $pageSize = $request->get('pageSize' , 15);

        $pubgRobot = new PubgRobot();
        $pubgVersion = new PubgVersion();

        $robotListData = $pubgRobot->listData();
        $status = 1;
        $robotUrl = [];
        if(empty($robotListData)){
            \Yii::$app->session->setFlash( 'error' , $pubgRobot->getError());
        }else{
            foreach ($robotListData as $value){
                $value['status'] = $status;
                array_push($robotUrl,$value['url']);
                $data[] = $value;
            }
        }

        $cacheKey = 'robot-url-'.\Yii::$app->user->id;
        \Yii::$app->cache->set($cacheKey , $robotUrl , 86400);

        $totalCount =  0;
        $dataProvider = new ArrayDataProvider([
            'sort' => [
                'attributes' => ['id'],
            ],
            'pagination' => [
                'pageSize' => $pageSize,
                'totalCount' => $totalCount
            ]
        ]);
        $dataProvider->setModels($data);
        $dataProvider->setTotalCount($totalCount);

        $version = $pubgVersion->getVersion();
        if($version == false) {
            \Yii::$app->session->setFlash('dataError', $pubgVersion->getError());
            $version = '';
        }

        return $this->render('index' , ['dataProvider' => $dataProvider,'version' => $version]);
    }

    public function actionClose()
    {
        $response = ['status' => 'success' , 'message' => '' ];
        $name = \Yii::$app->request->get('name');

        $pubgRobot = new PubgRobot();

        $data = [
            'name' => $name,
            'isLive' => 3
        ];

        if($pubgRobot->update($data) == false){
            $response['status'] = 'error';
            $response['message'] = $pubgRobot->getError();
        }
        return Json::encode($response);
    }

    public function actionStart()
    {
        $response = ['status' => 'success' , 'message' => '' ];
        $name = \Yii::$app->request->get('name');

        $pubgRobot = new PubgRobot();

        $data = [
            'name' => $name,
            'isLive' => 4
        ];

        if($pubgRobot->update($data) == false){
            $response['status'] = 'error';
            $response['message'] = $pubgRobot->getError();
        }
        return Json::encode($response);
    }

    public function actionVersion(){
        $request = \Yii::$app->request;
        $pubgVersion = new PubgVersion();

        $postData = $request->post();
        $params = [
            'v' => $postData['version'],
        ];

        \Yii::trace($postData , 'version.update');
        $response = $pubgVersion->setVersion($params);
        if($response){
            $status = ['status' => 'success','message' => '更新成功'];
        }else{
            $status = ['status' => 'error','message' => '更新失败'];
        }

        return Json::encode($status);
    }

    public function actionStatus(){
        $cacheKey = 'robot-url-'.\Yii::$app->user->id;
        $robotUrl = \Yii::$app->cache->get($cacheKey);

        $data = [];
        foreach ($robotUrl as $value){
            $result = $this->getPubgRobotStatus($value);
            $data[] = ['result' => $result];
        }

        $status = ['status' => 'success','message' => '更新成功','result' => $data];

        return Json::encode($status);
    }

    protected function getPubgRobotStatus($url){
        $ch = curl_init();

        $url = $url.'/game/test';
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_TIMEOUT,60);

        $content = curl_exec($ch);

        if ($content  === false) {
            return 3;
        }
        $content = Json::decode($content);
        $result = isset($content['result']) ? $content['result'] : false;

        if($result === true){
            return 2;
        }else{
            return 3;
        }
    }
}
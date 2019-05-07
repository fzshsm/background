<?php
/**
 * Created by IntelliJ IDEA.
 * User: Zhangxinlong
 * Date: 2017/9/18
 * Time: 16:28
 */

namespace app\modules\user\controllers;


use app\controllers\Controller;
use app\modules\user\api\Medal;
use yii\helpers\Json;

class MedalController extends Controller {
    
    public function actionIndex($id){
        $medal = new Medal();
        $haveMedal = $medal->findByUser($id);
        $medalList = $medal->listDataByUser($id);
        return $this->render('index' , ['haveMedal' => $haveMedal , 'medalList' => $medalList]);
    }
    
    public function actionAdd(){
        $request = \Yii::$app->request;
        $response = ['status' => 'success' , 'message' => '' ];
        $userId = $request->get('userId');
        $seasonId = $request->get('seasonId');
        $medalIds = rtrim($request->get('medalIds') , ',');
        $medal = new Medal();
        if($medal->add($userId , $seasonId , $medalIds) == false){
            $response['status'] = 'error';
            $response['message'] = $medal->getError();
        }
        return Json::encode($response);
    }
    
    public function actionDelete($id){
        $response = ['status' => 'success' , 'message' => '' ];
        $medal = new Medal($id);
        if($medal->deleteById() == false){
            $response['status'] = 'error';
            $response['message'] = $medal->getError();
        }
        return Json::encode($response);
    }
}
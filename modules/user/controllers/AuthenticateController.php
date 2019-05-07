<?php
namespace app\modules\user\controllers;


use app\controllers\Controller;
use app\modules\user\api\Authenticate;
use yii\helpers\Json;

class AuthenticateController extends Controller {
    
    public function actionIndex(){
        $request = \Yii::$app->request;
        if($request->isAjax){
            $search = $request->get('search' , ['type' => 'all' , 'value' => '']);
            $status = $request->get('status' , 1);
            if(empty($search['value'])){
                $search['type'] = 'all';
            }
            $start = $request->get('start');
            $pageSize = $request->get('length');
            $page = $start / $pageSize + 1;
            $responseData = [
                'draw' => $request->get('draw'),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [ ],
                'error' => ''
            ];
            $authenticate = new Authenticate();
            $authenticateData = $authenticate->listdata([$search['type'] => trim($search['value'])] , $status , $page , $pageSize);
            if(!empty($authenticateData)){
                $responseData['recordsTotal'] = $authenticateData['totalSize'];
                $responseData['recordsFiltered'] = $authenticateData['totalSize'];
                $responseData['data'] = $authenticateData['results'];
            }else{
                $responseData['error'] = $authenticate->getError();
            }
        
            return Json::encode($responseData);
        }
        return $this->render('index');
    }
    
    public function actionAgree($id){
        $response = ['status' => 'success' , 'message' => '' ];
        $authenticate = new Authenticate($id);
        if($authenticate->changeStatus(2) == false){
            $response['status'] = 'error';
            $response['message'] = $authenticate->getError();
        }
        return Json::encode($response);
    }
    
    public function actionReject($id){
        $response = ['status' => 'success' , 'message' => '' ];
        $authenticate = new Authenticate($id);
        if($authenticate->changeStatus(0) == false){
            $response['status'] = 'error';
            $response['message'] = $authenticate->getError();
        }
        return Json::encode($response);
    }
    
}
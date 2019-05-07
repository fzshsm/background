<?php
namespace app\controllers;

use app\controllers\Controller;
use app\modules\user\api\PubgUser;
use app\modules\user\api\User;
use yii\helpers\Json;
use app\models\LoginForm as LoginForm;
use yii\filters\AccessControl;
use yii\helpers\Url;

class LoginController extends Controller{
    
    public $layout = false;

    public function behaviors(){
        return [
            'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        ['allow' => true , 'actions' => ['index'] , 'roles' => ['?' , '@']],
                        ['allow' => true , 'actions' => ['logout'] , 'roles' => ['@']],
                    ],
            ],
        ];
    }
    
    public function actionIndex(){
        header("Access-control-Allow-Origin:*");
        $request = \Yii::$app->request;
        $betLogin = $request->get('login');
        if($request->isPost){
            $data = ['loginStatus' => 'invalid' , 'redirectUrl' => \Yii::$app->urlManager->createUrl('/') , 'message' => ''];
            
            $model = new LoginForm();

            $postData['LoginForm'] = $request->post();
            $this->loginBetSystem($postData['LoginForm']['username'],$postData['LoginForm']['password'],'login');
            $postData['LoginForm']['password'] = md5($postData['LoginForm']['password']);
            if ($model->load($postData) && $model->login()) {
                $data['loginStatus'] = 'success';
            }else{
                $error = @array_shift($model->errors);
                $data['message'] = $error[0];
            }

            return Json::encode($data);
        }

        if($betLogin == 'betSystem'){
            $username = $request->get('username');
            $password = $request->get('password');
            $model = new LoginForm();
            $postData['LoginForm'] = [
                'username' => $username,
                'password' => $password
            ];
            if ($model->load($postData) && $model->loginByUrl()){
                $this->loginBetSystem($username,$password,'token');
                return $this->redirect('/league');
            }else{
                return $this->render('index');
            }
        }

        return $this->render('index');
    }
    
    public function actionLogout(){
        $userId = \Yii::$app->user->id;
        \Yii::$app->user->logout();
        $menuCacheKey = 'menus-'.$userId;
        $loginCacheKey = 'login-'.$userId;
        \Yii::$app->cache->delete($menuCacheKey);
        \Yii::$app->cache->delete($loginCacheKey);
        return $this->redirect(Url::to('/login'));
    }

    protected function loginBetSystem($username,$password,$type){
        $pubgUser = New PubgUser();
        if($type == 'login'){
            $token = $pubgUser->betLogin($username,$password);
        }else{
            $token = $pubgUser->getBetToken($username,$password);
        }

        if($token){
            $cacheKey = 'login-'.\Yii::$app->user->id;
            \Yii::$app->cache->set($cacheKey , $token , 3600*24*10);
        }else{
            return $this->redirect(Url::to('/login'));
        }
    }
}
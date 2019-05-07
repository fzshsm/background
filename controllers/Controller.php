<?php
namespace app\controllers;

use app\modules\admin\models\Menu;
use app\modules\admin\models\OperateLog;
use app\modules\admin\models\Permission;
use app\modules\admin\models\RolePermission;
use app\modules\admin\models\User;
use app\modules\user\api\Medal;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\HttpException;

class Controller extends \yii\web\Controller{

    public function behaviors(){

        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['allow' => true , 'roles' => ['@']]
                ],
            ],
        ];
    }
    
    public function beforeAction($action){
        if(in_array($action->controller->id , ['login' , 'error'])){
           return parent::beforeAction($action);
        }

        if(\Yii::$app->user->id > 0){
            $user = User::findOne(['id' => \Yii::$app->user->id,'status' => 'normal']);
            if(empty($user)){
                throw new HttpException(404 , '未找到管理员信息！');
            }

            if($user->role_id == 1){
                $permission = Permission::findOne(['module' => $action->controller->module->id , 'controller' => $action->controller->id , 'action' => $action->id]);
            }else{
                if($action->controller->module->id != 'Manage'){
                    $permission = Permission::findOne(['module' => $action->controller->module->id , 'controller' => $action->controller->id , 'action' => $action->id]);
                    if(empty($permission)){
                        throw new HttpException(404 , '未配置的功能！');
                    }
                    $rolePermission = RolePermission::findOne(['role_id' => $user->role_id , 'permission_id' => $permission->id]);
                    if(empty($rolePermission)){
                        throw new HttpException(403 , '你无权使用此功能！');
                    }
                }
            }
            $this->getMenu($user->role_id);
            $this->log($action , isset($permission) ? $permission->name : '首页');
            $this->checkToken();
        }
        return parent::beforeAction($action);
    }
    
    protected function getMenu($roleId){
        $cacheKey = 'menus-'.\Yii::$app->user->id;
        $cacheData = \Yii::$app->cache->get($cacheKey);
        if($cacheData !== false){
            \Yii::$app->view->params['menus'] = $cacheData;
        }else{
            $permission = Permission::tableName();
            $rolePermission = RolePermission::tableName();

            $params = "{$permission}.menu_id > 0 ";
            if($roleId > 1){
                $params .= "  AND {$rolePermission}.role_id =  {$roleId}";
            }

            $menuPermission = Permission::find()
                ->select("{$permission}.id , {$permission}.name, {$permission}.menu_id")
                ->joinWith('rolePermissionRelevance')
                ->where($params)
                ->indexBy("id")->all();

            $menuIds = [];
            foreach ($menuPermission as $value)
            {
                array_push($menuIds,$value['menu_id']);
            }
            //$menuList = Menu::findAll(['id' => $menuIds , 'parent_id' => 0]);
            $menuList = Menu::find()
            ->where(['id' => $menuIds , 'parent_id' => 0])
            ->orderBy('rating ASC')
            ->all();
            foreach($menuList as $key =>  $menuData){
                $parseUrl = explode('/' , $menuData->url);
                \Yii::$app->view->params['menus'][$key] = [
                    'id' => $menuData->id,
                    'class' =>  $menuData->class,
                    'name' => $menuData->name,
                    'url' => $menuData->url,
                    'module' => isset($parseUrl[1]) ? $parseUrl[1] : '',
                    'controller' => isset($parseUrl[2]) ? $parseUrl[2] : '',
                    'action' => isset($parseUrl[3]) ? $parseUrl[3] : '',
                    'childNode' => $this->getMenuChild($menuData->id,$roleId)
                ];
            }
            \Yii::$app->cache->set($cacheKey , \Yii::$app->view->params['menus'] , 86400);
        }
    }
    
    public function getMenuChild($parentId,$roleId){
        $menuChild = [];
        $menuList = Menu::find()
        ->where(['parent_id' => $parentId])
        ->orderBy('rating asc')
        ->all();

        $menuIds = [];
        foreach ($menuList as $value){
            array_push($menuIds,$value['id']);
        }

        $permissionMenuIds = [];
        if($roleId > 1){
            $menuIds = '('.implode(",", $menuIds).')';
            $sql = "SELECT a.menu_id FROM sr_permission a
                    LEFT JOIN sr_role_permission b ON a.id = b.`permission_id`  
                    WHERE b.`role_id` = {$roleId} AND a.`menu_id` IN {$menuIds}";

            $response = \Yii::$app->db->createCommand($sql)->queryAll();

            if(!is_array($response)){
                $response = [];
            }
            foreach ($response as $value){
                array_push($permissionMenuIds,$value['menu_id']);
            }

        }else{
            $permissionMenuIds = $menuIds;
        }

        foreach($menuList as $key =>  $menuData){
            if(in_array($menuData->id,$permissionMenuIds)){
                $parseUrl = explode('/' , $menuData->url);
                $menuChild[] = [
                    'class' =>  $menuData->class,
                    'name' => $menuData->name,
                    'url' => $menuData->url,
                    'module' => isset($parseUrl[1]) ? $parseUrl[1] : '',
                    'controller' => isset($parseUrl[2]) ? $parseUrl[2] : 'default',
                    'action' => isset($parseUrl[3]) ? $parseUrl[3] : '',
                ];
            }
        }
        return $menuChild;
    }
    
    protected function log($action , $permissionName){
        $request = \Yii::$app->request;
        $data = $request->isPost ? $request->post() : $request->get();
        $note = Json::encode($data);
        $targetUrl = ( !empty($action->controller->module->id) ? "/" . $action->controller->module->id : "")
                    . (!empty($action->controller->id) ? "/" . $action->controller->id : "")
                    . (!empty($action->id) ? "/" . $action->id : "");
        
        $operateLog = new OperateLog();
        $operateLog->createLog(\Yii::$app->user->id , $targetUrl , $permissionName , $note , $request->getUserIP());
    }

    protected function checkToken(){
        $cacheKey = 'login-'.\Yii::$app->user->id;
        $token = \Yii::$app->cache->get($cacheKey);
        if($token == null){
            return $this->redirect(Url::to('/login'));
        }

        return true;
    }
}
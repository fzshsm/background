<?php
namespace app\modules\admin\controllers;

use app\controllers\Controller;
use app\modules\admin\models\Menu;
use app\modules\admin\models\Permission;
use yii\data\ArrayDataProvider;

/**
 * Default controller for the `User` module
 */
class PermissionController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $request = \Yii::$app->request;
        $pageSize = $request->get('pageSize', 30);

        $permissiondb = new Permission();

        $response = $permissiondb->getPermissionList($pageSize);

        $permissionData = $response['data'];

        $data = [];

        if(!empty($permissionData) && is_array($permissionData)){
            foreach ($permissionData as $row){
                $data[$row->id] = [
                    'id' => $row->id,
                    'name' => $row->name,
                    'module' => $row->module,
                    'controller' => $row->controller,
                    'action' => $row->action,
                    'menu_name' => $row->menu['name'],
                ];
            }
        }

        $dataProvider = new ArrayDataProvider([
            'sort' => [
                'attributes' => ['id'],
            ],
            'pagination' => [
                'pageSize' => $pageSize,
                'totalCount' => $response['count']
            ]
        ]);
        $dataProvider->setModels($data);
        $dataProvider->setTotalCount($response['count']);

        return $this->render('index',['dataProvider' => $dataProvider]);
    }

    public function actionCreate()
    {
        $request = \Yii::$app->request;
        $menuList = $this->getAllMenu();
        if($request->isPost){
            $postData = $request->post();

            $permissiondb = new Permission();

            $checkPer= $permissiondb->getOnePermission($postData['module'],$postData['controller'],$postData['action'],$postData['menu_id']);

            if($checkPer != null){
                \Yii::$app->session->setFlash('error' , '该权限已存在！');
                return $this->render('create',['menuList' => $menuList]);
            }

            $response = $permissiondb->createPermissions($postData['name'],$postData['module'],$postData['action'],$postData['controller'],$postData['menu_id']);

            if($response){
                \Yii::$app->session->setFlash('success' , "创建权限成功！");
            }else{
                \Yii::$app->session->setFlash('error' , '创建权限失败！');
            }
        }

        return $this->render('create',['menuList' => $menuList]);
    }

    public function actionUpdate($id)
    {
        $request = \Yii::$app->request;

        $permissiondb = new Permission();

        if($request->isPost){
            $postData = $request->post();

            $response = $permissiondb->updatePermission($id,$postData['name'],$postData['module'],$postData['controller'],$postData['action'],$postData['menu_id']);

            if($response){
                \Yii::$app->session->setFlash('success' , "编辑权限成功！");
            }else{
                \Yii::$app->session->setFlash('error' , '编辑权限失败！');
            }

        }
        $data = $permissiondb->getOnePermissionById($id);
        $menuList = $this->getAllMenu();
        return $this->render('update',['data' =>$data,'menuList' => $menuList]);
    }

    protected function getAllMenu()
    {
        $menudb = new Menu();

        $response = $menudb->getAllMenu();

        $data[0] =  '-';

        foreach ($response as $row)
        {
            $parentName = empty($row->subMenu)?'父级菜单':$row->subMenu['name'];
            $data[$row->id] =  $row->name.'('.$parentName.')';
        }

        return $data;
    }

    //自动更新权限信息,/module/controller/action
    public function actionAutomatic(){
        $response = ['status' => 'success' , 'message' => '' ];
        $sqlPerData = $this->getAllPermission();

        $modulesNames = $this->getAllModules();
        $filePerData = [];
        foreach ($modulesNames as $modulesName){
            $controllersNames = $this->getControllers($modulesName);
            foreach ($controllersNames as $key => $value){
                $actions = $this->getActions($modulesName,$value);
                if(!empty($actions)){
                    foreach ($actions as $action){
                        $filePerData[$modulesName][] = [
                            'controller' => $key,
                            'action' => $action
                        ];
                    }
                }
            }
        }

        $deletePerData = $this->diffPermisssion($filePerData,$sqlPerData);
        if(!empty($deletePerData)){
            $this->deletePermission($deletePerData);
        }

        return $this->asJson($response);
    }

    //获取modules,根据modules路径获取modules
    protected function getAllModules(){
        $modulesNames = [];
        $modulePath = \Yii::$app->basePath.'/modules';
        $handler = opendir($modulePath);

        while( ($fileName = readdir($handler)) !== false )
        {
            if($fileName != "." && $fileName != "..")
            {
                array_push($modulesNames,$fileName);
            }
        }
        closedir($handler);

        return $modulesNames;
    }

    //获取controllers,根据module读取controller名
    protected function getControllers($module){
        $controllersPath = \Yii::$app->basePath.'/modules/'.$module.'/controllers';
        $handler = opendir($controllersPath);
        $controllersData = [];

        while (($fileName = readdir($handler)) !== false)
        {
            if($fileName != "." && $fileName != ".."){
                $fileNameArr = explode('Controller',$fileName);
                $key = $fileNameArr[0];
                $key = preg_replace('/([A-Z])/',"-$1",$key);
                $key = substr(strtolower($key),1);
                $controllersData[$key] = $fileName;
            }
        }
        closedir($handler);

        return $controllersData;
    }

    //获取actions,根据module和controller读取action名
    protected function getActions($module,$controllerFilename){
        $controllerPath = \Yii::$app->basePath.'/modules/'.$module.'/controllers/'.$controllerFilename;
        $content = file_get_contents($controllerPath);
        preg_match_all("/.*?public.*?function(.*?)\(.*?\)/i", $content, $matches);

        $actions = $matches[1];

        $actionData = [];
        foreach ($actions as $action){
            if(empty($action)){
                continue;
            }

            if(strpos($action,'action') === false){
                continue;
            }

            $action = explode('action',$action);

            $actionName = $action[1];
            $actionName = preg_replace('/([A-Z])/',"-$1",$actionName);
            $actionName = substr(strtolower($actionName),1);
            array_push($actionData,$actionName);
        }

        return $actionData;
    }

    //获取sql的所有权限
    protected function getAllPermission(){
        $permission = new Permission();

        $permissionData = $permission->getAllPermission();

        $sqlPerData = [];

        foreach ($permissionData as $value){
            $sqlPerData[$value['module']][] = [
                'controller' => $value['controller'],
                'action' => $value['action']
            ];
        }

        return $sqlPerData;
    }

    //文件权限和sql权限进行比较
    protected function diffPermisssion($filePerData,$sqlPerData){
        //循环文件得到的权限
        foreach ($filePerData as $moduleKey => $value){
            $permissions = isset($sqlPerData[$moduleKey]) ? $sqlPerData[$moduleKey] : [];
            //若sql中无该模块的权限则直接添加，否则进行权限比对
            if(!empty($permissions)){
                foreach ($value as $item){
                    $controller = $item['controller'];
                    $action = $item['action'];
                    $i = 0;
                    //循环sql中的权限进行比对
                    foreach ($permissions as $perKey => $perVal){
                        if($controller == $perVal['controller'] && $action == $perVal['action']){
                            unset($sqlPerData[$moduleKey][$perKey]);
                            $i = 1;
                            break;
                        }
                    }
                    if($i == 0){
                        //新增
                        $this->updatePermission($moduleKey,$controller,$action);
                    }else{
                        $permissions = $sqlPerData[$moduleKey];
                    }
                }
            }else{
                //新增
                foreach ($value as $val){
                    $this->updatePermission($moduleKey,$val['controller'],$val['action']);
                }
            }
        }
        $deletePerData = [];
        //删除的权限中过滤menu的权限
        foreach ($sqlPerData as $sqlKey => $sqlVal){
            if(!empty($sqlVal)){
                foreach ($sqlVal as $val){
                    $del = true;
                    if($val['action'] == 'index'){
                        if($val['controller'] == 'default'){
                            $menuUrl = '/'.$sqlKey;
                        }else{
                            $menuUrl = '/'.$sqlKey.'/'.$val['controller'];
                        }
                        $checkMenu = Menu::find()->where(['url' => $menuUrl])->one();
                        if($checkMenu){
                            $del = false;
                        }
                    }
                    if($del){
                        $deletePerData[$sqlKey][] = $val;
                    }
                }
            }
        }
        return $deletePerData;
    }

    //添加权限,判断default&&index，生成两个菜单，两者父子级关系，再生成两个权限，分别跟菜单绑定
    protected function updatePermission($module,$controller,$action){
        $permission = new Permission();
        $menu = new Menu();

        $checkPer = $permission->getOnePermission($module,$controller,$action);

        if($checkPer == null){
            $url = '/'.$module.'/'.$controller;
            if($action !== 'index'){
                $url = $url.'/'.$action;
            }

            //判断default和index进行菜单的创建,创建父级和子级并进行绑定
            if ($controller == 'default' && $action == 'index') {
                $menuUrl = '/'.$module;

                $i = 0;
                while ($i == 0) {
                    $checkMenu = $menu->checkMenuByUrl($menuUrl);
                    $count = count($checkMenu);
                    if($count == 0){
                        $firstMenuId = $menu->createMenus($menuUrl.'(父级菜单)', 0, $menuUrl, '', 0);
                        $permission->createPermissions($url, $module, $action, $controller, $firstMenuId);
                    }elseif($count == 1){
                        $menu  = new Menu();
                        $permission = new Permission();
                        $secondMenuId = $menu->createMenus($menuUrl.'(子级菜单)', $checkMenu[0]->id, $menuUrl, '', 0);
                        $permission->createPermissions($url, $module, $action, $controller, $secondMenuId);
                        $i = 1;
                    }else{
                        $i = 1;
                    }
                }
            }else{
                $permission->createPermissions($url,$module,$action,$controller,0);
            }
        }

        return true;
    }

    //删除权限
    protected function deletePermission($deletePerData){
        $permission = new Permission();

        foreach ($deletePerData as $delKey => $delVal){
            foreach ($delVal as $value){
                $response = $permission->getOnePermission($delKey,$value['controller'],$value['action']);
                if($response){
                    $permission->deletePermission($response->id);
                }
            }
        }
        return true;
    }
}

<?php
namespace app\modules\admin\controllers;

use app\controllers\Controller;
use app\modules\admin\models\Permission;
use app\modules\admin\models\Menu;
use app\modules\admin\models\RolePermission;
use app\modules\admin\models\User;
use yii\data\ArrayDataProvider;

/**
 * Default controller for the `User` module
 */
class MenuController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $request = \Yii::$app->request;
        $pageSize = $request->get('pageSize', 30);

        $menudb = new Menu();

        $response = $menudb->getMenuList($pageSize);

        $menuData = $response['data'];

        $data = [];

        if(!empty($menuData) && is_array($menuData)){
            foreach ($menuData as $row){
                $data[$row->id] = [
                    'id' => $row->id,
                    'name' => $row->name,
                    'parent_name' => isset($row->subMenu)?$row->subMenu['name']:'',
                    'url' => $row->url,
                    'class' => $row->class,
                    'rating' => $row->rating
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
        $menudb = new Menu();
        $permission = new Permission();
        if($request->isPost){
            $postData = $request->post();
            $url = $postData['url'];
            $url = explode('/',$url);
            $module = $url[1];
            $controller = isset($url[2])?$url[2]:'default';
            $action = isset($url[3])?$url[3]:'index';

            $response = $menudb->createMenus($postData['name'],$postData['parent_id'],$postData['url'],$postData['class'],$postData['rating']);

            if( $response ){
                $check = $permission->getOnePermission($module,$controller,$action,$response);
                if(!$check){
                    $permission->createPermissions($postData['url'],$module,$action,$controller,$response);
                }
                \Yii::$app->session->setFlash('success' , "创建菜单成功！");
            }else{
                \Yii::$app->session->setFlash('error' , '创建菜单失败！');
            }
        }

        $parentMenusData = $this->getParentMenu();

        return $this->render('create',['menuList' => $parentMenusData]);
    }

    public function actionUpdate($id)
    {
        $request = \Yii::$app->request;

        $menudb = new Menu();

        if($request->isPost){
            $postData = $request->post();

            $response = $menudb->updateMenu($id,$postData['name'],$postData['parent_id'],$postData['url'],$postData['class'],$postData['rating']);

            if($response){
                \Yii::$app->session->setFlash('success' , '编辑菜单成功！');
            }else{
                \Yii::$app->session->setFlash('error' , '编辑菜单失败！');
            }
        }

        $menuData = $menudb->getOneMenu($id);

        $data = [
            'id' => $menuData->id,
            'name' => $menuData->name,
            'parent_id' => $menuData->parent_id,
            'parent_name' => '',
            'url' => $menuData->url,
            'class' => $menuData->class,
            'rating' => $menuData->rating
        ];
        $parentMenusData = $this->getParentMenu();

        return $this->render('update',['data' => $data,'menuList' => $parentMenusData]);
    }

    public function actionDelete($id)
    {
        $menudb = new Menu();

        $response = $menudb->deleteMenu($id);

        if (!$response) {
            \Yii::$app->session->setFlash('error', '删除菜单失败！');
        }

        return $this->redirect('/admin/menu');
    }

    //获取所有的父级菜单
    protected function getParentMenu()
    {
        $menudb = new Menu();

        $parentMenus = $menudb->getParentMenu();
        $parentMenusData[0] =  '-';
        if($parentMenus){
            foreach ($parentMenus as $parentMenu)
            {
                $parentMenusData[$parentMenu->id] = $parentMenu->name;
            }
        }

        return $parentMenusData;
    }
}

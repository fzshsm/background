<?php
namespace app\modules\admin\controllers;

use app\controllers\Controller;
use app\modules\admin\models\Menu;
use app\modules\admin\models\Permission;
use app\modules\admin\models\Role;
use app\modules\admin\models\RolePermission;
use app\modules\admin\models\User;
use yii\data\ArrayDataProvider;

/**
 * Default controller for the `User` module
 */
class RoleController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $request = \Yii::$app->request;
        $pageSize = $request->get('pageSize', 30);

        $roledb = new Role();

        $response = $roledb->getRoleList($pageSize);

        $roleData = $response['data'];

        $data = [];

        if(!empty($roleData) && is_array($roleData)){
            foreach ($roleData as $row){
                $data[$row->id] = [
                    'id' => $row->id,
                    'name' => $row->name,
                    'description' => $row->description,
                    'create_time' => date('Y-m-d H:i:s',$row->create_time),
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

        if($request->isPost){
            $postData = $request->post();
            $roledb = new Role();

            $response = $roledb->createRole($postData['name'],$postData['description']);

            if( $response ){
                \Yii::$app->session->setFlash('success' , "创建角色成功！");
            }else{
                \Yii::$app->session->setFlash('error' , '创建角色失败！');
            }
        }
        return $this->render('create');
    }

    public function actionUpdate($id)
    {
        $request = \Yii::$app->request;

        $roledb = new Role();

        if($request->isPost){
            $postData = $request->post();

            $response = $roledb->updateRole($id,$postData['name'],$postData['description']);

            if($response){
                \Yii::$app->session->setFlash('success' , '编辑角色成功！');
            }else{
                \Yii::$app->session->setFlash('error' , '编辑角色失败！');
            }
        }

        $roleData = $roledb->getOneRole($id);

        $data = [
            'id' => $roleData->id,
            'name' => $roleData->name,
            'description' => $roleData->description
        ];

        return $this->render('update',['data' => $data]);
    }

    //分配权限
    public function actionAssigment($id)
    {
        $request = \Yii::$app->request;

        $rolePerdb = new RolePermission();
        $user = User::findOne(['id' => \Yii::$app->user->id]);

        if($request->isPost){
            $data = $this->getPermissionByRoleId($id);
            $permission_ids  = $data['per_ids'];
            if($id == 1){
                if( $user->role_id != 1){
                    \Yii::$app->session->setFlash('error' , '您没有权限为此角色分配权限！');
                    $perData = $this->getMenuPerList();
                    return $this->render('permission',['data' => $data,'per_data' => $perData,'permission_ids' => $permission_ids]);
                }
            }
            $postData = $request->post();
            $newPerIds = $postData['permission_ids'];
            $updateData = $this->screenData($permission_ids,$newPerIds);
            $rolePerdb->assigmentRolePermission($id,$updateData);
            \Yii::$app->session->setFlash('success' , '权限分配成功！');
        }

        $perData = $this->getMenuPerList();

        $data = $this->getPermissionByRoleId($id);
        $permission_ids  =$data['per_ids'];

        return $this->render('permission',['data' => $data,'per_data' => $perData,'permission_ids' => $permission_ids]);
    }

    protected function getPermissionByRoleId($id)
    {
        $roledb = new Role();
        $rolePerdb = new RolePermission();
        $permissiondb = new Permission();

        $role = $roledb->getOneRole($id);

        $result = [
            'per_ids' => [],
            'role_name' => $role->name,
            'role_id' => $id
        ];
        if($role == false){
            return $result;
        }

        $rolePermissionData = $rolePerdb->getPerByRoleId($id);

        if($rolePermissionData == false){
            return $result;
        }

        $permissionIds = [];

        foreach($rolePermissionData as $row)
        {
            $permissionIds[] = $row->permission_id;
        }

        $permissionData = $permissiondb->getPermissions($permissionIds);

        $data = [];

        foreach ($permissionData as $row)
        {
            array_push($data,$row->id);
        }

        $result = [
            'per_ids' => $data,
            'role_name' => $role->name,
            'role_id' => $role->id
        ];

        return $result;
    }

    protected function getMenuPerList()
    {
        $menudb = new Menu();
        $permissiondb = new Permission();

        $menuResponse = $menudb->getParentMenu();
        $permissionResponse = $permissiondb->getAllPermission();

        $data = [];
        foreach ($menuResponse as $value)
        {
            $urls = explode('/',$value->url);
            $module = $urls[1];
            $menu_name = $value->name;

            $data[$menu_name] = [];
            foreach ($permissionResponse as $row)
            {
                if($row['module'] == $module){
                    $data[$menu_name] += [
                        $row['id'] => $row['name']
                    ];
                }
            }
        }

        return $data;
    }

    protected function screenData($oldPerIds,$newPerIds){
        $delData = [];
        $addData = [];

        foreach ($newPerIds as $newPerId) {
            if(!in_array($newPerId,$oldPerIds)){
                array_push($addData,$newPerId);
            }
        }

        foreach ($oldPerIds as $oldPerId){
            if(!in_array($oldPerId,$newPerIds)){
                array_push($delData,$oldPerId);
            }
        }

        $data = [
            'delData' => $delData,
            'addData' => $addData
        ];

        return $data;
    }
}

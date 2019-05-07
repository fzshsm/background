<?php
namespace app\modules\admin\controllers;

use app\controllers\Controller;
use app\modules\admin\models\Role;
use yii\data\ArrayDataProvider;
use app\modules\admin\models\User;
use yii\helpers\Json;

/**
 * Default controller for the `User` module
 */
class UserController extends Controller
{
    const ADMIN_ID = 1;
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $request = \Yii::$app->request;
        $pageSize = $request->get('pageSize', 30);

        $userdb = new User();

        $response = $userdb->getUserList($pageSize);

        $userData = $response['data'];
        $results = [];
        if(!empty($userData) && is_array($userData)){
            foreach ($userData as $row){
                $results[$row->id] = [
                    'id' => $row->id,
                    'username' => $row->username,
                    'role_name' => $row->role['name'],
                    'create_time' => date('Y-m-d H:i:s',$row['create_time']),
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
        $dataProvider->setModels($results);
        $dataProvider->setTotalCount($response['count']);

        return $this->render('index',['dataProvider' => $dataProvider]);
    }

    public function actionCreate()
    {
        $request = \Yii::$app->request;
        if($request->isPost){
            $postData = $request->post();
            $user = new User();

            $response = $user->createUser($postData['name'],md5($postData['password']),$postData['role_id']);

            if( $response !== false){
                \Yii::$app->session->setFlash('success' , "创建用户成功！");
            }else{
                \Yii::$app->session->setFlash('error' , '创建用户失败！');
            }
        }

        $roleList = $this->getRoleList();
        return $this->render('create',['roleList' => $roleList]);
    }

    public function actionUpdate($id)
    {
        $request = \Yii::$app->request;
        $userdb = new User();

        if($request->isPost){
            $postData = $request->post();
            $response = $userdb->updateUser($id,$postData['name'],$postData['role_id']);
            if($response){
                \Yii::$app->session->setFlash('success' , "编辑用户成功！");
            }else{
                \Yii::$app->session->setFlash('error' , '编辑用户失败！');
            }
        }
        $userDetail = $userdb->getOneUser($id);
        $data = [
            'name' => $userDetail->username,
            'role_id' => $userDetail->role_id
        ];
        $roleList = $this->getRoleList();

        return $this->render('update',['data' =>$data,'type' => 'update','roleList' => $roleList]);
    }

    public function actionResetPassword($id)
    {
        $request = \Yii::$app->request;
        if($request->isPost){
            $postData = $request->post();
            $usedb = new User();
            $response = $usedb->resetPassword($id,md5($postData['password']));
            if( $response ){
                $response = ['status' => 'success' , 'message' => '重置成功' ];
            }else{
                $response = ['status' => 'error' , 'message' => '重置失败' ];
            }
            return Json::encode($response);
        }
        return false;
    }

    public function actionDelete($id)
    {
        $usedb = new User();
        if($id == self::ADMIN_ID){
            \Yii::$app->session->setFlash('error','admin不许删除');
            return $this->redirect('/admin/user');
        }

        $response = $usedb->deleteUser($id,'delete');
        if( !$response ){
            \Yii::$app->session->setFlash('error','删除失败');
        }

        return $this->redirect('/admin/user');
    }

    protected function getRoleList()
    {
        $roledb = new Role();

        $response = $roledb->getAllRole();

        $data[0] = '-';

        foreach ($response as $row)
        {
            $data[$row->id] = $row->name;
        }

        return $data;
    }

}

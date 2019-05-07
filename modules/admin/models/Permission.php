<?php

namespace app\modules\admin\models;

use Yii;
use yii\data\Pagination;
use yii\data\SqlDataProvider;

/**
 * This is the model class for table "sr_permission".
 *
 * @property integer $id
 * @property string $name
 * @property string $module
 * @property string $action
 * @property string $controller
 * @property string $menu_id
 * @property string $create_time
 * @property string $update_time
 */
class Permission extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sr_permission';
    }
    
    public function getRolePermissionRelevance(){
        return $this->hasOne(RolePermission::className() , ['permission_id' => 'id']);
    }

    public function getMenu(){
        return $this->hasOne(Menu::className(),['id' => 'menu_id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'menu_id'], 'required'],
            [['menu_id'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['name'], 'string', 'max' => 120],
            [['module'], 'string', 'max' => 20],
            [['action', 'controller'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '权限ID',
            'name' => '权限名称',
            'module' => '模块名',
            'action' => '动作名',
            'controller' => '控制器',
            'menu_id' => '菜单ID',
            'create_time' => '创建时间',
            'update_time' => '修改时间',
        ];
    }

    public function createPermissions($name,$module,$action,$controller,$menu_id)
    {
        $this->name = $name;
        $this->module = $module;
        $this->action = $action;
        $this->controller = $controller;
        $this->menu_id = $menu_id;
        $this->create_time = time();
        $this->update_time = time();
        return $this->save();
    }

    public function getPermissionList($pageSize)
    {
        $query = $this::find()
            ->JoinWith('menu');

        $data = ['data' => [],'count' => 0];

        if($query){
            $count = $query->count();

            $pagination = new Pagination(['totalCount' => $count,'pageSize' => $pageSize]);

            $response = $query->offset($pagination->offset)
                ->limit($pagination->limit)
                ->orderBy('id DESC')
                ->all();

            $data = ['data' => $response,'count' => $count];
        }

        return $data;
    }

    public function updatePermission($id,$name,$module,$controller,$action,$menu_id)
    {
        $query = $this::findOne($id);

        if($query){
            $query->name = $name;
            $query->module = $module;
            $query->action = $action;
            $query->menu_id = $menu_id;
            $query->controller = $controller;
            return $query->save();
        }

        return false;
    }

    public function deletePermission($id)
    {
        $query = $this::findOne($id);
        if($query){
            return $query->delete();
        }
        return false;
    }

    public function getOnePermissionById($id)
    {
        $permission = $this::tableName();
        $query = $this::find()
            ->JoinWith('menu')
            ->where(["{$permission}.id" =>$id])
            ->one();

        return $query;
    }

    public function getOnePermission($module,$controller,$action,$menu_id = null)
    {
        $where = ['module' => $module,'controller' => $controller,'action' => $action];

        if(!empty($menu_id)){
            $where = array_merge($where,['menu_id' => $menu_id]);
        }

        return $query = $this::find()
            ->where($where)
            ->one();
    }

    public function getPermissions($ids)
    {
        $query = $this::find()
            ->where(['id' => $ids])
            ->all();

        return $query;
    }

    public function getAllPermission()
    {
        $query = $this::find()->asArray()->all();
        return $query;
    }

    public function getMenuPermission()
    {
        $query = $this::find()
            ->where(['!=','menu_id',0])
            ->all();

        return $query;
    }

}

<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "sr_role_permission".
 *
 * @property integer $role_id
 * @property integer $permission_id
 */
class RolePermission extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sr_role_permission';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_id', 'permission_id'], 'required'],
            [['role_id', 'permission_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'role_id' => '角色ID',
            'permission_id' => '权限ID',
        ];
    }

    public function assigmentRolePermission($roleId,$data)
    {
        $delData = $data['delData'];
        $addData = $data['addData'];

        foreach ($delData as $delId){
            $this->deleteRolePer($roleId,$delId);
        }
        foreach ($addData as $addId){
            $this->createRolePer($roleId,$addId);
        }
        return true;
    }

    public function createRolePer($roleId,$permissionId)
    {
        $sql = "insert into sr_role_permission (role_id,permission_id) values ($roleId,$permissionId)";
        return \Yii::$app->db->createCommand($sql)->execute();
    }

    public function getPerByRoleId($roleId)
    {
        $query = $this::find()
            ->where(['role_id' => $roleId])
            ->all();

        if($query){
            return $query;
        }

        return [];
    }

    public function deleteRolePer($roleId,$permissionId)
    {
        return $this::deleteAll(['role_id' => $roleId,'permission_id' => $permissionId]);
    }
}

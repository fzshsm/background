<?php

namespace app\modules\admin\models;

use Yii;
use yii\data\Pagination;
use yii\data\SqlDataProvider;

/**
 * This is the model class for table "sr_user".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property integer $role_id
 * @property string $create_time
 * @property string $update_time
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sr_user';
    }

    public function getRole(){
        return $this->hasOne(Role::className() , ['id' => 'role_id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'role_id'], 'required'],
            [['role_id'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['username'], 'string', 'max' => 32],
            [['password'], 'string', 'max' => 256],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '用户ID',
            'username' => '用户名',
            'password' => '登录密码',
            'role_id' => '角色ID',
            'create_time' => '创建时间',
            'update_time' => '修改时间',
        ];
    }

    public function createUser($name,$password,$role_id)
    {
        $this->username = $name;
        $this->password = $password;
        $this->role_id = $role_id;
        $this->create_time = time();
        $this->update_time = time();
        $status = $this->save();
        $id = $this->id;

        return ['status' => $status,'user_id' => $id];
    }

    public function getUserList($pageSize)
    {
        $query = $this::find()
            ->where([$this::tableName().'.status' => 'normal'])
            ->JoinWith('role');

        $data = ['data' =>[],'count' => 0];
        if($query){
            $count = $query->count();

            $pagination = new Pagination(['totalCount' => $count,'pageSize' => $pageSize]);

            $response = $query->offset($pagination->offset)
                ->limit($pagination->limit)
                ->all();

            $data = ['data' => $response,'count' => $count];
        }

        return $data;
    }

    public function resetPassword($id,$password)
    {
        $query = $this::findOne($id);
        if($query) {
            $query->password = $password;
            return $query->save();
        }
        return false;
    }

    public function deleteUser($id,$status)
    {
        $query = $this::findOne($id);
        $query->status = $status;
        return $query->save();
    }

    public function getOneUser($id)
    {
        $query = $this::findOne($id);
        if($query){
            return $query;
        }
        return false;
    }

    public function updateUser($id,$username,$role_id)
    {
        $query = $this::findOne($id);
        if($query){
            $query->username = $username;
            $query->role_id = $role_id;
            $query->update_time = time();
            return $query->save();
        }
        return false;
    }
}

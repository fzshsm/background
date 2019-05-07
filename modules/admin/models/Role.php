<?php

namespace app\modules\admin\models;

use Yii;
use yii\data\Pagination;

/**
 * This is the model class for table "sr_role".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $create_time
 * @property string $update_time
 */
class Role extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sr_role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['create_time', 'update_time'], 'safe'],
            [['name'], 'string', 'max' => 32],
            [['description'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '角色ID',
            'name' => '角色名称',
            'description' => '描述',
            'create_time' => '创建时间',
            'update_time' => '修改时间',
        ];
    }

    public function createRole($name,$description)
    {
        $this->name = $name;
        $this->description = $description;
        $this->create_time = time();
        $this->update_time = time();
        return $this->save();
    }

    public function getRoleList($pageSize)
    {
        $query = $this::find();

        if($query){
            $count = $query->count();

            $pagination = new Pagination(['totalCount' => $count,'pageSize' => $pageSize]);

            $response = $query->offset($pagination->offset)
                ->limit($pagination->limit)
                ->all();

            return ['data' => $response,'count' => $count];
        }
        return false;
    }

    public function updateRole($id,$name,$description)
    {
        $query = $this::findOne($id);

        if($query){
            $query->name = $name;
            $query->description = $description;
            $query->update_time = time();
            return $query->save();
        }

        return false;
    }

    public function getOneRole($id)
    {
        $query = $this::findOne($id);

        if($query){
            return $query;
        }

        return false;
    }

    public function getAllRole()
    {
        $query = $this::find()->all();

        return $query;
    }
}

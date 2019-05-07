<?php

namespace app\modules\admin\models;

use Yii;
use yii\data\Pagination;
use yii\data\SqlDataProvider;

/**
 * This is the model class for table "sr_menu".
 *
 * @property integer $id
 * @property string $name
 * @property integer $parent_id
 * @property string $url
 * @property string $class
 * @property integer $rating
 * @property string $create_time
 * @property string $update_time
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sr_menu';
    }

    public function getSubMenu(){
        return $this->hasOne(Menu::className(),['id' => 'parent_id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id'], 'required'],
            [['parent_id', 'rating'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['name'], 'string', 'max' => 128],
            [['url', 'class'], 'string', 'max' => 256],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '菜单名',
            'parent_id' => '父节点',
            'url' => 'url',
            'class' => '样式属性',
            'rating' => '顺序',
            'create_time' => '创建时间',
            'update_time' => '修改时间',
        ];
    }

    public function createMenus($name,$parent_id,$url,$class,$rating)
    {
        $this->name = $name;
        $this->parent_id = $parent_id;
        $this->url = $url;
        $this->class = $class;
        $this->rating = $rating;
        $this->create_time = time();
        $this->update_time = time();
        if($this->save() == false){
            return false;
        }else{
            return $this->attributes['id'];
        }
    }

    public function getMenuList($pageSize)
    {
        $query = $this::find()
            ->joinWith('subMenu sub');

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

    public function updateMenu($id,$name,$parent_id,$url,$class,$rating)
    {
        $query = $this::findOne($id);

        if($query){
            $query->name = $name;
            $query->parent_id = $parent_id;
            $query->url = $url;
            $query->class = $class;
            $query->rating = $rating;
            $query->update_time = time();
            return $query->save();
        }

        return false;
    }

    public function deleteMenu($id)
    {
        $query = $this::findOne($id);
        if($query){
            return $query->delete();
        }
        return false;
    }

    public function getOneMenu($id)
    {
        return $query = $this::findOne($id);
    }

    public function getParentMenu()
    {
        return $query = $this::findAll(['parent_id' => 0]);
    }

    public function getAllMenu()
    {
        return $query = $this::find()->joinWith('subMenu sub')->all();
    }

    public function getPerMenu($ids)
    {
        return $query = $this::find()
            ->where(['id' => $ids])
            ->orderBy('rating ASC')
            ->all();
    }

    public function getMenuByAction($action)
    {
        return $query = $this::find()
            ->where(['url' => $action])
            ->one();
    }

    public function checkMenuByUrl($url){
        return $query = $this::find()
            ->where(['url' => $url])
            ->all();
    }
}

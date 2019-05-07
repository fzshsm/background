<?php

namespace app\modules\news\models;

use Yii;
use yii\data\Pagination;

/**
 * This is the model class for table "sr_news".
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $title
 * @property string $subhead
 * @property string $brief
 * @property string $cover
 * @property string $author
 * @property integer $view
 * @property integer $praise
 * @property integer $comment
 * @property string $postion
 * @property string $status
 * @property integer $sequence
 * @property string $content
 * @property integer $release_time
 * @property integer $create_time
 * @property integer $create_user_id
 * @property string $create_user_ip
 * @property integer $update_time
 * @property integer $update_user_id
 * @property string $update_user_ip
 */
class News extends \yii\db\ActiveRecord
{
    const NEWS_CATEGORY_ID = 0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sr_news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'view', 'praise', 'comment', 'release_time', 'create_time', 'create_user_id', 'update_time', 'update_user_id','sequence'], 'integer'],
            [['title', 'subhead', 'brief','cover', 'author', 'status', 'content', 'release_time', 'create_time', 'create_user_id', 'create_user_ip','postion'], 'required'],
            [['status', 'content'], 'string'],
            [['title'], 'string', 'max' => 200],
            [['subhead'], 'string', 'max' => 100],
            [['cover'], 'string', 'max' => 500],
            [['author'], 'string', 'max' => 50],
            [['create_user_ip', 'update_user_ip'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => '分类编号',
            'title' => '标题',
            'subhead' => '副标题',
            'brief' => '简介',
            'cover' => '封面',
            'author' => '作者',
            'view' => '浏览量',
            'praise' => '点赞数',
            'comment' => '评论数',
            'postion' => '位置',
            'sequence' => '排序',
            'status' => '新闻状态',
            'content' => '内容',
            'release_time' => '发布时间',
            'create_time' => '创建时间',
            'create_user_id' => '创建人',
            'create_user_ip' => '创建IP',
            'update_time' => '更新时间',
            'update_user_id' => '更新人',
            'update_user_ip' => '更新IP',
        ];
    }

    public function getNewsList($pageSize,$begin,$end,$title,$sort)
    {
        if(!empty($begin)){
            $begin = strtotime($begin);
            $end = strtotime("$end +1 day");
        }

        if(strpos($sort,'-') !== false){
            $sort = explode('-',$sort);

            $sort = trim($sort[1]);
            $orderBy = " $sort  DESC";
        }else{
            $orderBy = " $sort  ASC";
        }

        $params = [];

        if(!empty($title)){
            $params = ['like','title' ,$title];
        }

        if(!empty($begin)){
            if(empty($params)){
                $params = ["between",'release_time',$begin,$end];
            }else{
                $params = ['and',["between",'game_begin_time',$begin,$end],['like','title' ,$title]];
            }
        }

        $query = $this::find()
            ->where($params)
            ->orderBy($orderBy);

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

    public function createNews($title,$subhead,$cover,$author,$content,$release_time,$create_user_id,$create_user_ip,$postion,$sequence,$status,$brief)
    {
        $this->category_id = self::NEWS_CATEGORY_ID;
        $this->title = $title;
        $this->subhead = $subhead;
        $this->cover = $cover;
        $this->author = $author;
        $this->content = $content;
        $this->release_time = $release_time;
        $this->create_user_id = $create_user_id;
        $this->create_user_ip = $create_user_ip;
        $this->create_time = time();
        $this->postion = $postion;
        $this->sequence = $sequence;
        $this->status = $status;
        $this->brief = $brief;
        return $this->save();
    }

    public function updateNews($id,$title,$subhead,$cover,$author,$content,$release_time,$update_user_id,$update_user_ip,$postion,$sequence,$status,$brief)
    {
        $query = $this::findOne($id);

        if($query){
            $query->title = $title;
            $query->subhead = $subhead;
            $query->cover = $cover;
            $query->author = $author;
            $query->content = $content;
            $query->release_time = $release_time;
            $query->update_user_id = $update_user_id;
            $query->update_user_ip = $update_user_ip;
            $query->update_time = time();
            $query->postion = $postion;
            $query->sequence = $sequence;
            $query->status = $status;
            $query->brief = $brief;
            return $query->save();
        }
        return false;
    }

    public function updateNewsStatus($id,$status)
    {
        $query = $this::findOne($id);
        if($query){
            $query->status = $status;
            return $query->save();
        }
        return false;
    }

    public function updateNewsView($id,$view)
    {
        $query = $this::findOne($id);

        if($query){
            $query->view = $view;
            return $query->save();
        }
        return false;
    }

    public function updateNewsPraise($id,$praise)
    {
        $query = $this::findOne($id);

        if($query){
            $query->praise = $praise;
            return $query->save();
        }
        return false;
    }

    public function updateNewsComment($id,$comment)
    {
        $query = $this::findOne($id);

        if($query){
            $query->updateCounters(['comment' => $comment]) ;
            return $query->save();
        }
        return false;
    }

    public function getOneNews($id)
    {
        return $this::findOne($id);
    }
}

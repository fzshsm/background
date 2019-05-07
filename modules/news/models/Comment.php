<?php

namespace app\modules\news\models;

use Yii;
use yii\data\Pagination;

/**
 * This is the model class for table "sr_comment".
 *
 * @property integer $id
 * @property integer $news_id
 * @property string $title
 * @property string $content
 * @property string $nickname
 * @property string $avatar
 * @property integer $reply_id
 * @property integer $reply
 * @property integer $praise
 * @property string $status
 * @property integer $create_time
 * @property string $create_user_id
 * @property string $create_user_ip
 * @property integer $update_time
 * @property string $update_user_id
 * @property string $update_user_ip
 */
class Comment extends \yii\db\ActiveRecord
{
    const REPLY_STATUS_NORMAL = 'normal';
    const NEWS_AUTHOR_AVATAR = 'http://starrank-1254164914.file.myqcloud.com/icon/logo300x300.png';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sr_comment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['news_id', 'content', 'nickname', 'avatar', 'create_time', 'create_user_id', 'create_user_ip'], 'required'],
            [['news_id', 'reply_id', 'reply', 'praise', 'create_time', 'update_time'], 'integer'],
            [['status','source'], 'string'],
            [['title', 'avatar'], 'string', 'max' => 300],
            [['content'], 'string', 'max' => 500],
            [['nickname', 'create_user_id', 'update_user_id'], 'string', 'max' => 50],
            [['create_user_ip', 'update_user_ip'], 'string', 'max' => 15],
            [['floor'] , 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '编号',
            'news_id' => '新闻编号',
            'title' => '评论标题',
            'content' => '评论内容',
            'nickname' => '用户昵称',
            'avatar' => '用户头像',
            'reply_id' => '回复评论ID',
            'reply' => '回复数量',
            'praise' => '点赞数',
            'status' => '状态',
            'create_time' => '创建时间',
            'create_user_id' => '创建人',
            'create_user_ip' => '创建IP',
            'update_time' => '更新时间',
            'update_user_id' => '更新人',
            'update_user_ip' => '更新IP',
        ];
    }

    public function getCommentListByNewsId($newsId, $pageSize,$sort,$nickname)
    {
        if(strpos($sort,'-') !== false){
            $sort = explode('-',$sort);

            $sort = trim($sort[1]);
            $orderBy = " $sort  DESC";
        }else{
            $orderBy = " $sort  ASC";
        }

        $where = ['news_id' => $newsId,'reply_id' => 0];

        if(!empty($nickname)){
            $where = array_merge($where,['nickname' => $nickname]);
        }

        $data = ['data' => [], 'count' => 0];

        $query = $this::find()
            ->where($where)
            ->orderBy($orderBy);

        if ($query) {
            $count = $query->count();

            $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);

            $response = $query->offset($pagination->offset)
                ->limit($pagination->limit)
                ->all();

            $data = ['data' => $response, 'count' => $count];
        }
        return $data;
    }

    public function changeCommentStatus($commentsId, $status)
    {
        $query = $this::findOne($commentsId);
        if($query){
            $query->status = $status;
            return $query->save();
        }
        return false;
    }

    public function getCommentListByReplyId($replyId,$pageSize,$sort)
    {
        if(strpos($sort,'-') !== false){
            $sort = explode('-',$sort);

            $sort = trim($sort[1]);
            $orderBy = " $sort  DESC";
        }else{
            $orderBy = " $sort  ASC";
        }

        $query = $this::find()
            ->where(['reply_id' => $replyId])
            ->orderBy($orderBy);

        $data = ['data' => [], 'count' => 0];

        if ($query) {
            $count = $query->count();

            $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);

            $response = $query->offset($pagination->offset)
                ->limit($pagination->limit)
                ->all();

            $data = ['data' => $response, 'count' => $count];
        }
        return $data;
    }

    public function authorReply($newsId,$replyId,$author,$content)
    {
        $userId =  \Yii::$app->user->id;
        $userIP = \Yii::$app->request->getUserIP();

        $this->avatar = self::NEWS_AUTHOR_AVATAR;
        $this->news_id = $newsId;
        $this->reply_id = $replyId;
        $this->nickname =  $author;
        $this->content =  $content;
        $this->status = self::REPLY_STATUS_NORMAL;
        $this->create_time = time();
        $this->create_user_ip = $userIP;
        $this->create_user_id  = (string)$userId;
        $this->update_time = time();
        $this->update_user_ip = $userIP;
        $this->update_user_id = (string)$userId;
        return $this->save();
    }

    public function getOneCommentDetail($id)
    {
        return $this::findOne($id);
    }

    public function updateCommentReply($id,$reply)
    {
        $query = $this::findOne($id);

        if($query){
            $query->updateCounters(['reply' => $reply]) ;
            return $query->save();
        }
        return false;
    }
}

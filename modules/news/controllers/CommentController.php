<?php
namespace app\modules\news\controllers;

use app\controllers\Controller;
use app\modules\news\api\NewsList;
use app\modules\news\models\Comment;
use app\modules\news\models\News;
use app\components\QCloudCos;
use function GuzzleHttp\Psr7\str;
use yii\helpers\Json;
use yii\web\UploadedFile;
use yii\data\ArrayDataProvider;

/**
 * Default controller for the `User` module
 */
class CommentController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex($id)
    {
        $request = \Yii::$app->request;
        $pageSize = $request->get('pageSize', 30);
        $sort = $request->get('sort','id');
        $nickname = $request->get('nickname');

        $newsList = new NewsList();
        $commentdb = new Comment();

        $newsDetail = $newsList->getOneNews($id);

        $response = $commentdb->getCommentListByNewsId($id,$pageSize,$sort,$nickname);

        $data = [];

        $commentData = $response['data'];

        if(!empty($commentData) && is_array($commentData)){
            foreach ($commentData as $row){
                $data[] = [
                    'id' => $row->id,
                    'news_id' => $row->news_id,
                    'content' => $row->content,
                    'nickname' => $row->nickname,
                    'avatar' => $row->avatar,
                    'praise' => $row->praise,
                    'reply' => $row->reply,
                    'status' => $row->status,
                    'create_time' => $row->create_time,
                ];
            }
        }

        $dataProvider = new ArrayDataProvider([
            'sort' => [
                'attributes' => ['id','reply','praise','create_time'],
            ],
            'pagination' => [
                'pageSize' => $pageSize,
                'totalCount' => $response['count']
            ]
        ]);
        $dataProvider->setModels($data);
        $dataProvider->setTotalCount($response['count']);
var_dump($newsDetail);exit;
        return $this->render('index',['dataProvider' => $dataProvider,'newsDetail' => $newsDetail]);
    }

    //更改新闻评论状态
    public function actionNormal($id)
    {
        $commentdb = new Comment();
        $newsdb = new News();

        $response = $commentdb->changeCommentStatus($id,'normal');
        $status = ['status' => 'success','message' =>'恢复成功'];
        if($response){
            $comment = $commentdb::findOne($id);
            $newsdb->updateNewsComment($comment->news_id,1);

            if($comment->reply_id != 0){
                $commentdb->updateCommentReply($comment->reply_id,1);
            }
        }else{
            $status = ['status' => 'error','message' =>'恢复失败'];
        }

        return Json::encode($status);
    }

    public function actionDelete($id)
    {
        $commentdb = new Comment();
        $newsdb = new News();

        $response = $commentdb->changeCommentStatus($id,'delete');
        $status = ['status' => 'success','message' =>'删除成功'];
        if($response){
            $comment = $commentdb::findOne($id);
            $newsdb->updateNewsComment($comment->news_id,-1);

            if($comment->reply_id != 0){
                $commentdb->updateCommentReply($comment->reply_id,-1);
            }
        }else{
            $status = ['status' => 'error','message' =>'删除失败'];
        }

        return Json::encode($status);
    }

    //评论的回复列表
    public function actionReply($id)
    {
        $request = \Yii::$app->request;
        $pageSize = $request->get('pageSize', 30);
        $sort = $request->get('sort','id');

        $commentdb = new Comment();

        $response = $commentdb->getCommentListByReplyId($id,$pageSize,$sort);
        $commentDetail = $commentdb->getOneCommentDetail($id);

        $data = [];

        $commentData = $response['data'];

        if(!empty($commentData) && is_array($commentData)){
            foreach ($commentData as $row){
                $data[] = [
                    'id' => $row->id,
                    'news_id' => $row->news_id,
                    'content' => $row->content,
                    'nickname' => $row->nickname,
                    'avatar' => $row->avatar,
                    'praise' => $row->praise,
                    'reply' => $row->reply,
                    'status' => $row->status,
                    'reply_id' => $row->reply_id,
                    'create_time' => $row->create_time,
                ];
            }
        }

        $dataProvider = new ArrayDataProvider([
            'sort' => [
                'attributes' => ['id','reply','praise','create_time'],
            ],
            'pagination' => [
                'pageSize' => $pageSize,
                'totalCount' => $response['count']
            ]
        ]);
        $dataProvider->setModels($data);
        $dataProvider->setTotalCount($response['count']);

        return $this->render('reply',['dataProvider' => $dataProvider,'commentDetail' => $commentDetail]);
    }

    //作者回复
    public function actionAuthor($id)
    {
        $request = \Yii::$app->request;

        $postData = $request->post();

        $newsId = $postData['news_id'];

        $newsdb = new News();
        $commentdb = new Comment();

        $newDetail = $newsdb->getOneNews($newsId);

        $response = $commentdb->authorReply($newsId,$id,$newDetail->author,$postData['reply_content']);

        $status = ['status' => 'success','message' =>'回复成功'];

        if($response == null){
            $status = ['status' => 'error','message' =>'回复失败'];
        }else{
            $commentdb->updateCommentReply($id,1);
            $newsdb->updateNewsComment($newsId,1);
        }

        return Json::encode($status);
    }
}

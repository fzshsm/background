<?php
/**
 * Created by IntelliJ IDEA.
 * User: Zhangxinlong
 * Date: 2017/12/12
 * Time: 17:20
 */

namespace app\commands;


use app\helper\NgaComment;
use app\modules\news\models\Comment;
use app\modules\news\models\News;
use yii\console\Controller;
use yii\helpers\Html;

class NgaController extends Controller {
    
    public function actionIndex($newsId , $tid , $totalPage = 1){
        $ngaComment = new NgaComment();
        $newsComment = Comment::find()->where(['news_id' => $newsId , 'source' => 'nga'])->orderBy('id DESC')->asArray()->one();
        if(!empty($newsComment)){
            $beginPage = ceil($newsComment['floor'] / 20);
        }else{
            $beginPage = 1;
        }
        for($i = $beginPage; $i <= $totalPage ; $i++){
            $requestUrl = "http://rsync3.ngacn.cc/read.php?tid={$tid}&page={$i}";
            $commentList = $ngaComment->getCommentList($requestUrl , $i);
            foreach($commentList as $comment){
                $this->save($newsId , $comment);
            }
            echo "collect page : {$i}; \r\n";
            sleep(1);
        }
    }
    
    
    protected function save($newsId , $comment){
        $newsComment = Comment::findOne(['news_id' => $newsId , 'source' => $comment['source'] , 'floor' => $comment['floor']]);
        if(empty($newsComment)){
            $commentModel = new Comment();
            $commentModel->attributes = $comment;
            $commentModel->news_id = $newsId;
            if($commentModel->save() == false){
                var_dump($commentModel->attributes);
                echo iconv('utf-8' , 'gbk' , Html::errorSummary($commentModel));
                echo "\r\n";
                exit;
            }
            News::updateAllCounters(['comment' => 1] , ['id' => $newsId]);
        }
    }
    
}
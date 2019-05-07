<?php
namespace app\modules\news\controllers;

use app\controllers\Controller;
use app\modules\message\api\Message;
use app\modules\news\models\News;
use app\modules\news\api\NewsList;
use app\components\QCloudCos;
use yii\helpers\Json;
use yii\web\UploadedFile;
use yii\data\ArrayDataProvider;

/**
 * Default controller for the `User` module
 */
class DefaultController extends Controller
{
    private $_processFileError;

    public function beforeAction($action) {

        if(in_array($action->id,['upload','create','update'])) {

            $action->controller->enableCsrfValidation = false;
        }
        parent::beforeAction($action);

        return true;
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $request = \Yii::$app->request;
        $page = $request->get('page', 1);
        $pageSize = $request->get('pageSize', 15);
        $date = $request->get('date');
        $title = $request->get('title');
        $sort = $request->get('sort', '-id');
        $status = $request->get('status',1);

        $begin = $end = '';
        if (!empty($date)) {
            if (!empty($date)) {
                list($begin, $end) = explode('至', $date);
            }
        }

        $responseData = [
            'draw' => $request->get('draw'),
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => [],
            'error' => ''
        ];

        $newsdb = new NewsList();


        $response = $newsdb->listdata($page, $pageSize, trim($begin), trim($end), $title, $sort,$status);
        $data = [];
        if (!empty($response)) {
            $newsData = $response['results'];
            if (!empty($newsData) && is_array($newsData)) {
                foreach ($newsData as $value) {
                    $value['releaseTime'] = $value['releaseTime']/1000;
                    $data[$value['id']] = $value;
                }
            }

        } else {
            $responseData['error'] = $newsdb->getError();
        }

        $totalSize = isset($response['totalSize']) ? $response['totalSize'] : 0;
        $responseData = new ArrayDataProvider([
            'sort' => [
                'attributes' => ['id', 'sequence', 'releaseTime'],
            ],
            'pagination' => [
                'pageSize' => $pageSize,
                'totalCount' => $totalSize
            ]
        ]);
        $responseData->setModels($data);
        $responseData->setTotalCount($totalSize);

        return $this->render('index',['dataProvider' => $responseData,'gameType' => $status]);

    }

    public function actionCreate()
    {
        $request = \Yii::$app->request;

        if($request->isPost){
            $postData = $request->post();

            $newdb = new NewsList();

            $cover = $this->uploadCover('cover');

            $postData['releaseTime'] = strtotime($postData['release_time']) * 1000;
            $postData['updateUserId'] = $postData['createUserId']= \Yii::$app->user->identity->id;
            $postData['cover'] = $cover;

            $response = $newdb->updateNews($postData);

            if( $response ){
                \Yii::$app->session->setFlash('success' , "创建新闻成功！");
            }else{
                \Yii::$app->session->setFlash('error' , $newdb->getError());
            }
        }
        return $this->render('create');
    }

    public function actionUpdate($id)
    {
        $request = \Yii::$app->request;

        $newsdb = new NewsList();

        if($request->isPost){
            $postData = $request->post();

            $cover = UploadedFile::getInstanceByName('cover');
            if(empty($cover)){
                $cover = $postData['image'];
            }else{
                $cover = $this->uploadCover('cover');
            }

            $postData['releaseTime'] = strtotime($postData['release_time']) * 1000;
            $postData['updateUserId'] = \Yii::$app->user->identity->id;
            $postData['cover'] = $cover;
            $postData['id'] = $id;

            $response = $newsdb->updateNews($postData);

            if($response){
                \Yii::$app->session->setFlash('success' , '编辑新闻成功！');
            }else{
                \Yii::$app->session->setFlash('error' , $newsdb->getError());
            }
        }

        $data = $newsdb->getOneNews($id);

        return $this->render('update',['data' => $data]);
    }

    //发布新闻
    public function actionRelease($id)
    {
        $newsdb = new NewsList();
        $userId = \Yii::$app->user->identity->id;
        $response = $newsdb->updateNewsStatus($id,'release',$userId);

        $status = ['status' => 'success','message' =>'发布成功'];
        if(!$response){
            $status = ['status' => 'error','message' =>'发布失败'];
        }else{
            $this->pushMsg($id);
        }

        return Json::encode($status);
    }

    //关闭新闻
    public function actionClose($id)
    {
        $newsdb = new NewsList();
        $userId = \Yii::$app->user->identity->id;
        $response = $newsdb->updateNewsStatus($id,'close',$userId);

        $status = ['status' => 'success','message' =>'关闭成功'];
        if(!$response){
            $status = ['status' => 'error','message' =>'关闭失败'];
        }

        return Json::encode($status);
    }

    //编辑器中上传图片
    public function actionUpload()
    {
        $imageUrl = $this->uploadCover('img');

        if(empty($imageUrl)){
            echo Json::encode( ['error' =>1 ,'message' => '上传失败']);exit;
        }

        echo Json::encode(['error' => 0, 'url' => $imageUrl]);exit;
    }

    protected function getCover($imgName){
        $cover = UploadedFile::getInstanceByName($imgName);
        return !empty($cover) ? $cover : false;
    }

    protected function uploadCover($imgName){
        try{
            $cover = $this->getCover($imgName);
            if(empty($cover)){
                return false;
            }

            $date = date("Ymd");

            if($imgName == 'cover'){
                $categoryName = 'cover';
            }else{
                $categoryName = 'content';
            }
            //根据新闻的封面和内容来区分图片存储路径
            $pathName = $categoryName.'/'.$date.'/';

            $qCloudCos = new QCloudCos();
            $srcPath = $cover->tempName;
            $dstPath = "/newsimg/".$pathName . md5($cover->name . time()) . "." . $cover->extension;
            $result = $qCloudCos->upload(QCloudCos::BUCKET_NAME , $srcPath , $dstPath);
            if(!empty($result) && isset($result['code'])){
                \Yii::trace($result , 'newsimg.Upload.IMG');
                if($result['code'] != 0){
                    throw new \Exception($result['message']);
                }
                return $result['data']['access_url'];
            }
        }catch( \Exception $e){
            $this->_processFileError = $e->getMessage();
            \Yii::warning($this->_processFileError , 'newsimg.Upload.IMG');
        }
        return false;
    }

    protected function pushMsg($newsId){
        $news = new News();
        $message = new Message();
        $response = $news->getOneNews($newsId);

        if($response){
            $data = [
                'title' => $response->title,
                'content' => $response->brief,
                'msgType' => 1,
                'bizId' => $newsId,
                'userName' => \Yii::$app->user->getIdentity()->username
            ];
            $message->create($data);
        }
        return true;
    }
}

<?php

namespace app\modules\message\controllers;

use app\components\QCloudCos;
use app\controllers\Controller;
use app\modules\message\api\Message;
use yii\data\ArrayDataProvider;
use yii\web\UploadedFile;

class DefaultController extends Controller
{
    private $_processFileError;
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex(){
        $data =  [];
        $request = \Yii::$app->request;
        $page = $request->get('page'  , 1);
        $pageSize = $request->get('pageSize' , 15);

        $message = new Message();
        $messageListData = $message->listData($page,$pageSize);

        if(empty($messageListData)){
            \Yii::$app->session->setFlash( 'error' , $message->getError());
        }else{
            $messageListData['results'] = empty($messageListData['results']) ? [] : $messageListData['results'];
            foreach ($messageListData['results'] as $value){
                $data[] = $value;
            }
        }
        $totalCount = isset( $messageListData['totalSize'] ) ? $messageListData['totalSize'] : 0;
        $dataProvider = new ArrayDataProvider([
            'sort' => [
                'attributes' => ['id'],
            ],
            'pagination' => [
                'pageSize' => $pageSize,
                'totalCount' => $totalCount
            ]
        ]);
        $dataProvider->setModels($data);
        $dataProvider->setTotalCount($totalCount);

        return $this->render('index' , ['dataProvider' => $dataProvider]);
    }

    public function actionCreate(){
        $request = \Yii::$app->request;

        $message = new Message();

        if($request->isPost){
            $postData = $request->post();
            $postData['userName'] = \Yii::$app->user->getIdentity()->username;

            \Yii::trace($postData , 'message.create');

            if( $message->create($postData) ){
                \Yii::$app->session->setFlash('success' , "创建消息成功！");
            }else{
                \Yii::$app->session->setFlash('error' , $message->getError());
            }
        }

        return $this->render('create');
    }

}

<?php

namespace app\modules\user\controllers;


use app\controllers\Controller;
use app\modules\user\api\Invite;
use yii\data\ArrayDataProvider;

class InviteController extends Controller {
    
    public function actionIndex(){
        $request = \Yii::$app->request;
        $invite = new Invite();
        $page = $request->get('page'  , 1);
        $pageSize = $request->get('pageSize' , 20);

        $inviteListData = $invite->listData($page,$pageSize);

        $data = [];
        if(empty($inviteListData)){
            \Yii::$app->session->setFlash( 'error' , $invite->getError());
        }else{
            $inviteData['results'] = empty($inviteListData['results']) ? [] : $inviteListData['results'];
            foreach ($inviteData['results'] as $value){
                $data[$value['userId']] = $value;
            }
        }
        $totalCount = isset( $inviteListData['totalSize'] ) ? $inviteListData['totalSize'] : 0;
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

    public function actionDetail($id){
        $request = \Yii::$app->request;
        $invite = new Invite();
        $page = $request->get('page'  , 1);
        $pageSize = $request->get('pageSize' , 30);

        $inviteListData = $invite->listDataByUser($id,$page,$pageSize);

        $data = [];
        if(empty($inviteListData)){
            \Yii::$app->session->setFlash( 'error' , $invite->getError());
        }else{
            $inviteData['results'] = empty($inviteListData['results']) ? [] : $inviteListData['results'];
            foreach ($inviteData['results'] as $value){
                $data[$value['userNo']] = $value;
            }
        }
        $totalCount = isset( $inviteListData['totalSize'] ) ? $inviteListData['totalSize'] : 0;
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

        return $this->render('detail' , ['dataProvider' => $dataProvider]);
    }

}
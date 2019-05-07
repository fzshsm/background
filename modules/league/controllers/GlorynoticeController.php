<?php
/**
 * Created by IntelliJ IDEA.
 * User: Zhangxinlong
 * Date: 2017/11/3
 * Time: 10:31
 */

namespace app\modules\league\controllers;


use app\controllers\Controller;
use app\modules\league\api\GloryNotice;
use app\modules\user\api\Rating;
use app\modules\user\api\User;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;

class GlorynoticeController extends Controller {

    public function actionIndex(){
        $data =  [];
        $request = \Yii::$app->request;
        $pageSize = $request->get('pageSize' , 15);
        $leagueId = $request->get('leagueId');

        $notice = new GloryNotice();
        $noticeListData = $notice->listData($leagueId);
        if(empty($noticeListData)){
            \Yii::$app->session->setFlash( 'error' , $notice->getError());
        }else{
            $noticeListData['results'] = empty($noticeListData['results']) ? $noticeListData : $noticeListData['results'];
            foreach ($noticeListData['results'] as $value){
                $data[$value['id']] = $value;
            }
        }
        $totalCount = isset( $noticeListData['totalSize'] ) ? $noticeListData['totalSize'] : 0;
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
        $notice = new GloryNotice();

        $postData = $request->post();
        $params = [
            'leagueId' => (int)$postData['leagueId'],
            'id' => 0,
            'message' => $postData['message'],
            'sortWeight' => (int)$postData['sortWeight']
        ];

        \Yii::trace($postData , 'notice.create');
        if( $notice->create($params) ){
            $status = ['status' => 'success','message' => '创建成功'];
        }else{
            $status = ['status' => 'error','message' => $notice->getError()];
        }

        return Json::encode($status);
    }
    
    public function actionUpdate($id){
        $request = \Yii::$app->request;
        $notice = new GloryNotice($id);

        $postData = $request->post();
        $params = [
            'leagueId' => (int)$postData['leagueId'],
            'id' => (int)$id,
            'message' => $postData['message'],
            'sortWeight' => (int)$postData['sortWeight']
        ];
        \Yii::trace($postData , 'notice.update');
        if( $notice->update($params) ){
            $status = ['status' => 'success','message' => '更新成功'];
        }else{
            $status = ['status' => 'error','message' => '更新失败'];
        }

        return Json::encode($status);
    }
    
    public function actionDelete($id){
        $response = ['status' => 'success' , 'message' => '' ];
        $notice = new GloryNotice($id);

        if($notice->deleteById() == false){
            $response['status'] = 'error';
            $response['message'] = $notice->getError();
        }
        return Json::encode($response);
    }
}
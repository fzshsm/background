<?php
/**
 * Created by IntelliJ IDEA.
 * User: Zhangxinlong
 * Date: 2017/11/3
 * Time: 10:31
 */

namespace app\modules\admin\controllers;


use app\controllers\Controller;
use app\modules\admin\models\Version;
use app\modules\league\api\Notice;
use app\modules\user\api\Rating;
use app\modules\user\api\User;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;

class VersionController extends Controller {

    public function actionIndex(){
        $data =  [];
        $request = \Yii::$app->request;
        $pageSize = $request->get('pageSize' , 15);
        $type = $request->get('type',0);

        $version = new Version();
        $versionListData = $version->listData($type);

        if(empty($versionListData)){
            \Yii::$app->session->setFlash( 'error' , $version->getError());
        }else{
            $versionListData['results'] = empty($versionListData['results']) ? $versionListData : $versionListData['results'];
            foreach ($versionListData['results'] as $value){
                $data[$value['id']] = $value;
            }
        }
        $totalCount = isset( $versionListData['totalSize'] ) ? $versionListData['totalSize'] : 0;
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
        $version = new Version();

        $postData = $request->post();

        $params = [
            'remark' => $postData['remark'],
            'downLoadUrl' => $postData['downLoadUrl'],
            'type' => (int)$postData['type'],
            'code' => (int)$postData['code'],
            'forceUpdate' => $postData['forceUpdate'],
            'codeDesc' => $postData['codeDesc']
        ];

        \Yii::trace($postData , 'version.create');
        if( $version->create($params) ){
            $status = ['status' => 'success','message' => '创建成功'];
        }else{
            $status = ['status' => 'error','message' => $version->getError()];
        }

        return Json::encode($status);
    }

    public function actionUpdate($id){
        $request = \Yii::$app->request;
        $version = new Version($id);

        $postData = $request->post();

        $params = [
            'remark' => $postData['remark'],
            'downLoadUrl' => $postData['downLoadUrl'],
            'type' => (int)$postData['type'],
            'code' => $postData['code'],
            'forceUpdate' => $postData['forceUpdate'],
            'id' => (int)$id,
            'codeDesc' => $postData['codeDesc']
        ];

        \Yii::trace($postData , 'version.update');
        if( $version->update($params) ){
            $status = ['status' => 'success','message' => '更新成功'];
        }else{
            $status = ['status' => 'error','message' => '更新失败'];
        }

        return Json::encode($status);
    }

    public function actionDelete($id){
        $response = ['status' => 'success' , 'message' => '' ];
        $version = new Version($id);

        if($version->deleteById() == false){
            $response['status'] = 'error';
            $response['message'] = $version->getError();
        }
        return Json::encode($response);
    }
}
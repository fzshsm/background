<?php
namespace app\modules\rating\controllers;


use app\controllers\Controller;
use app\modules\rating\api\Rule;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;

class RuleController extends Controller {

    public function actionIndex($id){
        $data =  [];
        $request = \Yii::$app->request;
        $pageSize = $request->get('pageSize' , 15);
        $gameName = $request->get('gameName');

        $rule = new Rule();
        $ruleListData = $rule->listData($id);
        if(empty($ruleListData)){
            \Yii::$app->session->setFlash( 'error' , $rule->getError());
        }else{
            $ruleListData['results'] = empty($ruleListData['results']) ? $ruleListData : $ruleListData['results'];
            foreach ($ruleListData['results'] as $value){
                $data[$value['id']] = $value;
            }
        }
        $totalCount = isset( $ruleListData['totalSize'] ) ? $ruleListData['totalSize'] : 0;
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

        return $this->render('index' , ['dataProvider' => $dataProvider,'gameName' => $gameName]);
    }

    public function actionCreate($id){
        $request = \Yii::$app->request;
        $rule = new Rule();
        $gameName = $request->get('gameName');

        if($request->isPost){
            $postData = $request->post();
            $params = [
                'type' => (int)$postData['type'],
                'scoreOne' => (int)$postData['scoreOne'],
                'scoreTwo' => (int)$postData['scoreTwo'],
                'remark' => $postData['remark'],
                'pid' => (int)$id,
                'id' => 0
            ];

            \Yii::trace($postData , 'rule.create');
            if( $rule->create($params) ){
                \Yii::$app->session->setFlash('success' , '创建成功');
            }else{
                \Yii::$app->session->setFlash('error' , $rule->getError());
            }
        }

        return $this->render('create',['pid' => $id,'gameName' => $gameName]);
    }

    public function actionUpdate($id){
        $request = \Yii::$app->request;
        $rule = new Rule($id);

        if($request->isPost){
            $postData = $request->post();
            $params = [
                'type' => (int)$postData['type'],
                'scoreOne' => (int)$postData['scoreOne'],
                'scoreTwo' => (int)$postData['scoreTwo'],
                'remark' => $postData['remark'],
                'pid' => (int)$postData['pid'],
                'id' => (int)$id
            ];

            \Yii::trace($postData , 'rule.update');
            if( $rule->update($params) ){
                \Yii::$app->session->setFlash('success' , '更新成功');
            }else{
                \Yii::$app->session->setFlash('error' , $rule->getError());
            }
        }

        $data = $rule->detail();

        return $this->render('update',['data' => $data]);
    }

    public function actionDelete($id){
        $response = ['status' => 'success' , 'message' => '' ];
        $rule = new Rule($id);

        if($rule->deleteById() == false){
            $response['status'] = 'error';
            $response['message'] = $rule->getError();
        }
        return Json::encode($response);
    }
}
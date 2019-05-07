<?php

namespace app\modules\pubg\controllers;

use app\controllers\Controller;
use app\modules\league\api\PubgRobot;
use app\modules\league\api\PubgVersion;
use app\modules\pubg\api\Rule;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;
use yii\helpers\VarDumper;

class RuleController extends Controller {

    public function actionIndex(){
        $data =  [];
        $request = \Yii::$app->request;
        $page = $request->get('page'  , 1);
        $pageSize = $request->get('pageSize' , 15);

        $rule = new Rule();

        $ruleListData = $rule->datalist($page,$pageSize);

        if(empty($ruleListData)){
            \Yii::$app->session->setFlash( 'error' , $rule->getError());
        }else{
            foreach ($ruleListData['results'] as $value){
                $data[$value['id']] = $value;
            }
        }

        $totalCount =  0;
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
        $rule = new Rule();

        if($request->isPost){
            $postData = $request->post();
            $ranks = $postData['ranks'];
            $score = $postData['rankScore'];

            $rankScore = $this->organizeRule($ranks,$score);
            $postData['rankScore'] = $rankScore;

            \Yii::trace($postData , 'rule.create');
            if( $rule->update(Json::encode($postData)) ){
                \Yii::$app->session->setFlash('success' , '创建成功');
            }else{
                \Yii::$app->session->setFlash('error' , $rule->getError());
            }
        }

        return $this->render('create');
    }

    public function actionUpdate($id){
        $request = \Yii::$app->request;
        $rule = new Rule($id);

        if($request->isPost){
            $postData = $request->post();
            $ranks = $postData['ranks'];
            $score = $postData['rankScore'];

            $rankScore = $this->organizeRule($ranks,$score);
            $postData['rankScore'] = $rankScore;
            $postData['id'] = $id;

            \Yii::trace($postData , 'rule.update');
            if( $rule->update(Json::encode($postData)) ){
                \Yii::$app->session->setFlash('success' , '更新成功');
            }else{
                \Yii::$app->session->setFlash('error' , $rule->getError());
            }
        }

        $data = $rule->detail($id);

        return $this->render('update',['data' => $data]);
    }

    public function actionStatus(){
        $request = \Yii::$app->request;

        $id = $request->get('id');
        $status = $request->get('status');

        $rule = new Rule();

        $data = [
            'id' => $id,
            'status' => $status
        ];

        if($rule->status($data)){
            $status = ['status' => 'success','message' => ''];
        }else{
            $status = ['status' => 'error','message' =>$rule->getError()];
        }

        return Json::encode($status);
    }

    protected function organizeRule($ranks,$rewards){
        $data = [];

        for($i=0;$i<count($ranks);$i++){
            $data[] = [
                'rank' => $ranks[$i],
                'score' => $rewards[$i]
            ];
        }
        return $data;
    }
}
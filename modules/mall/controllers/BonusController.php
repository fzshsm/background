<?php

namespace app\modules\mall\controllers;

use app\controllers\Controller;
use app\modules\mall\api\Bonus;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;
use yii\helpers\VarDumper;

class BonusController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex(){
        $data =  [];
        $request = \Yii::$app->request;
        $page = $request->get('page'  , 1);
        $pageSize = $request->get('pageSize' , 15);
        $type = $request->get('searchType');
        $content = $request->get('content');

        $bonus = new Bonus();
        $bonusListData = $bonus->listData($page,$pageSize,$type,$content);

        if(empty($bonusListData)){
            \Yii::$app->session->setFlash( 'error' , $bonus->getError());
        }else{
            $bonusListData['results'] = empty($bonusListData['results']) ? [] : $bonusListData['results'];
            foreach ($bonusListData['results'] as $value){
                $value['bonus'] = $this->organizeShowBonus($value['bonus']);
                $data[$value['id']] = $value;
            }
        }
        $totalCount = isset( $bonusListData['totalSize'] ) ? $bonusListData['totalSize'] : 0;
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

        if($request->isPost){
            $postData = $request->post();

            $ranks = $postData['ranks'];
            $rewards = $postData['rewards'];

            $bonusData = $this->organizeBonus($ranks,$rewards);
            $postData['bonus'] = $bonusData;
            $bonus = new Bonus();

            if($bonus->update(Json::encode($postData))){
                \Yii::$app->session->setFlash('success' , "创建奖金配置成功！");
            }else{
                \Yii::$app->session->setFlash('error' , $bonus->getError());
            }
        }

        return $this->render('create');
    }

    public function actionUpdate($id){
        $request = \Yii::$app->request;
        $bonus = new Bonus($id);

        if($request->isPost){
            $postData = $request->post();
            $ranks = $postData['ranks'];
            $rewards = $postData['rewards'];

            $bonusData = $this->organizeBonus($ranks,$rewards);
            $postData['bonus'] = $bonusData;
            $postData['bonusId'] = $id;

            if( $bonus->update(Json::encode($postData))){
                \Yii::$app->session->setFlash('success' , "更新奖金成功！");
            }else{
                \Yii::$app->session->setFlash('error' , $bonus->getError());
            }
        }
        $data = $bonus->detail();

        return $this->render('update',['data' => $data]);
    }

    public function actionStatus($id)
    {
        $response = ['status' => 'success' , 'message' => '' ];
        $bonus = new Bonus($id);
        $status = \Yii::$app->request->get('status');

        $data = [
            'id' => $id,
            'status' => $status
        ];

        if($bonus->updateStatus($data) == false){
            $response['status'] = 'error';
            $response['message'] = $bonus->getError();
        }
        return Json::encode($response);
    }

    protected function organizeBonus($ranks,$rewards){
        $data = [];

        for($i=0;$i<count($ranks);$i++){
            $data[] = [
                'rank' => $ranks[$i],
                'bonus' => $rewards[$i]
            ];
        }
        return $data;
    }

    protected function organizeShowBonus($bonus){
        $bonus = explode(',',$bonus);
        $data = '';

        foreach ($bonus as $value){
            if(empty($value)){
                continue;
            }
            $v = explode('#',$value);
            if(strpos($v[0],'-')){
                $ranks = explode('-',$v[0]);
                $rank = '第'.$ranks[0].'至'.$ranks[1].'名奖金：'.$v[1].'；';
            }else{
                $rank = '第'.$v[0].'名奖金:'.$v[1].'；';
            }
            $data .= $rank;
        }
        return $data;
    }

}

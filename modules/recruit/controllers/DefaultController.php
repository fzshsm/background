<?php

namespace app\modules\recruit\controllers;

use app\controllers\Controller;
use app\modules\league\api\GloryMatch;
use app\modules\league\api\Match;
use app\modules\message\api\Message;
use app\modules\recruit\api\Recruit;
use app\modules\user\api\Medal;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;

class DefaultController extends Controller {

    public function actionIndex(){
        $data =  [];
        $request = \Yii::$app->request;
        $page = $request->get('page'  , 1);
        $pageSize = $request->get('pageSize' , 15);
        
        $recruit = new Recruit();
        $recruitListData = $recruit->listData($page,$pageSize);

        if(empty($recruitListData)){
            \Yii::$app->session->setFlash( 'error' , $recruit->getError());
        }else{
            $recruitListData['results'] = empty($recruitListData['results']) ? [] : $recruitListData['results'];
            foreach ($recruitListData['results'] as $value){
                $data[$value['id']] = $value;
            }
        }
        $totalCount = isset( $recruitListData['totalSize'] ) ? $recruitListData['totalSize'] : 0;
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
    
    public function actionUpdate($id){
        $request = \Yii::$app->request;
        $recruit = new Recruit($id);
        $medal = new Medal();
        $match = new GloryMatch();

        if($request->isPost){
            $position = [1 => '射手',2 => '打野',3 => '中路',4 => '边路',5 => '辅助'];
            $postData = $request->post();
            $medals = $postData['medals'];
            if(!empty($medals)){
                $medals = implode($medals,',');
            }
            $postData['medals'] = $medals;
            $postData['id'] = $id;
            $msg = '地点：'.$postData['clubLocation'].'，薪资：'.$postData['minPay'].'-'.$postData['maxPay'].'，位置：'.$position[$postData['rolerPositionType']].'，点击查看详情...';
            \Yii::trace($postData , 'Recruit.update');
            if( $recruit->update($postData) ){
                //审核成功进行消息推送
                if($postData['status'] == 1){
                    $this->pushMsg($id,$postData['hidden_clubName'],$msg);
                }
                \Yii::$app->session->setFlash('success' , "编辑招聘成功！");
            }else{
                \Yii::$app->session->setFlash('error' , $recruit->getError());
            }
        }
        $data = $recruit->detail();
        $medalsIds = $data['medals'];
        $medalsIds = explode(',',$medalsIds);

        $medalList = $medal->getMedalSwitch($data['leagueId']);

        $medalsData = [];
        foreach ($medalList as $value){
            $medalsData[$value['id']] = $value['name'];
        }

        $medalsDataKeys = array_keys($medalsData);

        array_multisort($medalsDataKeys);

        $medalsListData = [];
        foreach ($medalsDataKeys as $medalsDataKey){
            $medalsListData[$medalsDataKey] = $medalsData[$medalsDataKey];
        }

        $types = $match->types();
        $matchTypes = [];
        foreach($types as $type){
            $matchTypes[$type['id']] = $type['name'];
        }

        return $this->render('update' , [ 'data' => $data,'medals' => $medalsListData,'medalsIds' => $medalsIds,'matchTypes' => $matchTypes,'medalsIdsJs' => $data['medals']]);
    }

    public function actionDelete($id)
    {
        $response = ['status' => 'success' , 'message' => '' ];
        $recruit = new Recruit($id);

        if($recruit->deleteById() == false){
            $response['status'] = 'error';
            $response['message'] = $recruit->getError();
        }
        return Json::encode($response);

    }

    public function actionApply($id){
        $request = \Yii::$app->request;
        $page = $request->get('page'  , 1);
        $pageSize = $request->get('pageSize' , 15);
        $recruit = new Recruit($id);
        $match = new GloryMatch();

        $recruitType = [0=>'KPL',1=>'战队',2=>'业余战队',3=>'次级联赛'];
        $positionType = [0=>'主力',1=>'替补',2=>'试训'];
        $rolerPositionType = [1=>'射手',2=>'打野',3=>'中路',4=>'边路',5=>'辅助'];
        $status = [0=>'等待审核',1=>'审核成功',2=>'审核失败',3=>'失效'];
        //招聘详情
        $recruitDetail = $recruit->detail();
        $recruitDetail['recruitType'] = $recruitType[$recruitDetail['recruitType']];
        $recruitDetail['positionType'] = $positionType[$recruitDetail['positionType']];
        $recruitDetail['rolerPositionType'] = $rolerPositionType[$recruitDetail['rolerPositionType']];
        $recruitDetail['hasClubExperience'] = $recruitDetail['hasClubExperience'] == 0 ? '没有' : '有';
        $types = $match->types();
        foreach($types as $type){
            if($recruitDetail['leagueId'] == $type['id']){
                $recruitDetail['leagueId'] = $type['name'];
                break;
            }
        }
        $recruitDetail['status'] = $status[$recruitDetail['status']];
        $recruitDetail['age'] = $recruitDetail['minYear'].'岁-'.$recruitDetail['maxYear'].'岁';
        $recruitDetail['pay'] = $recruitDetail['minPay'].'-'.$recruitDetail['maxPay'].'';

        $medalsUrls = $recruitDetail['medalUrls'];

        if(!empty($medalsUrls)){
            $recruitDetail['medalUrls'] = explode(',',$medalsUrls);
        }else{
            $recruitDetail['medalUrls'] = [];
        }

        //应聘数据
        $applysListData = $recruit->getApply($id,$page,$pageSize);

        $data = [];
        if(empty($applysListData)){
            \Yii::$app->session->setFlash( 'error' , $recruit->getError());
        }else{
            $applyData['results'] = empty($applysListData['results']) ? [] : $applysListData['results'];
            if(is_array($applyData)){
                foreach ($applyData['results'] as $value){
                    $value['medalUrls'] = explode(',',$value['medalUrls']);
                    $data[] = $value;
                }
            }
        }
        $totalCount = isset( $applysListData['totalSize'] ) ? $applysListData['totalSize'] : 0;
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

        return $this->render('apply',['dataProvider' => $dataProvider,'recruitDetail' => $recruitDetail]);
    }

    public function actionMedal(){
        $request = \Yii::$app->request;
        $leagueId = $request->get('leagueId');
        $medal = new Medal();

        $medalList = $medal->getMedalSwitch($leagueId);
        $medalsData = [];
        foreach ($medalList as $value){
            $medalsData[$value['id']] = $value['name'];
        }

        $data = ['status' => 'success','data' => $medalsData];

        return Json::encode($data);
    }

    protected function pushMsg($recruitId,$clubName,$msg){
        $message = new Message();

        $data = [
            'title' => $clubName.'招人啦',
            'content' => $msg,
            'msgType' => 2,
            'bizId' => $recruitId,
            'userName' => \Yii::$app->user->getIdentity()->username
        ];
        $message->create($data);
        return true;
    }
}
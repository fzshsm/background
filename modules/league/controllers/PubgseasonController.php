<?php
namespace app\modules\league\controllers;


use app\controllers\Controller;
use app\modules\league\api\PubgLeague;
use app\modules\league\api\PubgSeason;
use app\modules\pubg\api\Rule;
use yii\data\ArrayDataProvider;
use yii\helpers\VarDumper;

class PubgseasonController extends Controller {

    public function actionIndex($leagueId){
        $data =  [];
        $request = \Yii::$app->request;
        $page = $request->get('page'  , 1);
        $pageSize = $request->get('pageSize' , 15);
        
        $season = new PubgSeason();
        $league = new PubgLeague($leagueId);
        $leagueDetail = $league->detail();
        $matchName = isset($leagueDetail['leagueName']) ? $leagueDetail['leagueName'] : '';
        $matchDatas = $season->datalist($leagueId , $page , $pageSize, 2);
        if(empty($matchDatas)){
            \Yii::$app->session->setFlash( 'error' , $season->getError());
        }else{
            foreach ($matchDatas['results'] as $value){
                $value['id'] = $value['seasonId'];
                $data[$value['id']] = $value;
            }
        }
        $totalCount = isset( $matchDatas['totalSize'] ) ? $matchDatas['totalSize'] : 0;
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
        return $this->render('../gloryseason/index' , ['leagueId' => $leagueId , 'matchName' => $matchName , 'dataProvider' => $dataProvider,'gameType' => 'pubg']);
    }
    
    public function actionCreate($leagueId){
        $request = \Yii::$app->request;
        $season = new PubgSeason();
        $league = new PubgLeague($leagueId);
        $leagueDetail = $league->detail();
        $matchName = isset($leagueDetail['leagueName']) ? $leagueDetail['leagueName'] : '';

        if($request->isPost){
            $postData = $this->processSendTime($request->post());
            $postData['leagueId'] = $leagueId;
            $postData['createUserId'] = \Yii::$app->user->identity->id;
            $postData['gameType'] = 2;
            \Yii::trace($postData , 'season.create');
            if( $season->create($postData) ){
                \Yii::$app->session->setFlash('success' , "创建赛季成功！");
            }else{
                \Yii::$app->session->setFlash('error' , $season->getError());
            }
        }

        $ruleConfigList = $this->getPubgRuleConfigList();

        return $this->render( 'create' , ['leagueId' =>$leagueId ,  'matchName' => $matchName, 'ruleConfigList' => $ruleConfigList]);
    }
    
    public function actionUpdate($id){
        $request = \Yii::$app->request;
        $season = new PubgSeason($id);
        $leagueId = $request->get('leagueId');

        if($request->isPost){
            $postData = $this->processSendTime($request->post());
            $postData['seasonId'] = $id;
            $postData['updateUserId'] = \Yii::$app->user->identity->id;
            $postData['gameType'] = 2;
            \Yii::trace($postData , 'season.update');
            if($season->update($postData)){
                \Yii::$app->session->setFlash('success' , "修改赛季信息成功！");
            }else{
                \Yii::$app->session->setFlash('error' , $season->getError());
            }
        }

        $matchName = '赛事';
        $data = $season->detail();
        if(empty($data)){
            \Yii::$app->session->setFlash('dataError' , $season->getError());
        }else{
            $data['matchTime'] = $data['startTime'].' 至 '.$data['endTime'];
            $data['name'] = $data['leagueName'];
            $matchName = isset($data['leagueName']) ? $data['leagueName'] : '';
        }

        $ruleConfigList = $this->getPubgRuleConfigList();

        return $this->render( 'update' , ['matchName' => $matchName ,  'data' => $data,'leagueId' => $leagueId, 'ruleConfigList' => $ruleConfigList]);
    }

    protected function processSendTime($data){
        list($data['startTime'] , $data['endTime']) = explode(' 至 ' , $data['matchTime']);
        return $data;
    }

    protected function getPubgRuleConfigList(){
        $pubgRule= new Rule();
        $configList = $pubgRule->configList();
        if(!is_array($configList)){
            $configList = [];
        }
        $data = [];
        foreach ($configList as $value){
            $data[$value['id']] = $value['configName'];
        }
        return $data;
    }
}
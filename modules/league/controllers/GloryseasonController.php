<?php
namespace app\modules\league\controllers;


use app\controllers\Controller;
use app\modules\admin\models\User;
use app\modules\league\api\GloryMatch;
use app\modules\league\api\PubgSeason;
use app\modules\mall\api\Bonus;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;

class GloryseasonController extends Controller {

    public function actionIndex($leagueId){
        $data =  [];
        $request = \Yii::$app->request;
        $page = $request->get('page'  , 1);
        $pageSize = $request->get('pageSize' , 15);

        $match = new GloryMatch();
        $pubgSeason = new PubgSeason();

        $matchType = $match->types();
        $matchName = isset($matchType[$leagueId]) ? $matchType[$leagueId]['name'] : '';

        $matchDatas = $pubgSeason->datalist($leagueId , $page , $pageSize, 1);

        if(empty($matchDatas)){
            \Yii::$app->session->setFlash( 'error' , $match->getError());
        }else{
            foreach ($matchDatas['results'] as $value){
                $value['reward'] = empty($value['reward']) ? 0 : $value['reward'];
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
        return $this->render('index' , ['leagueId' => $leagueId , 'matchName' => $matchName , 'dataProvider' => $dataProvider,'gameType' => 'glory']);
    }
    
    public function actionCreate($leagueId){
        $request = \Yii::$app->request;
        $match = new GloryMatch();
        $matchType = $match->types();
        $matchName = isset($matchType[$leagueId]) ? $matchType[$leagueId]['name'] : '';

        $pubgSession = new PubgSeason();

        if($request->isPost){
            $postData = $this->processSendTime($request->post());
            $postData['leagueId'] = $leagueId;
            $postData['createUserId'] = \Yii::$app->user->identity->id;
            $postData['gameType'] = 1;
            \Yii::trace($postData , 'season.create');
            if( $pubgSession->create($postData) ){
                \Yii::$app->session->setFlash('success' , "创建赛季成功！");
                GloryMatch::clearTypesCache();
            }else{
                \Yii::$app->session->setFlash('error' , $pubgSession->getError());
            }
        }
        $bonusConfigList = $this->getBonusConfig();
        return $this->render( 'create' , ['leagueId' =>$leagueId ,  'matchName' => $matchName,'bonusConfigList' => $bonusConfigList]);
    }
    
    public function actionUpdate($id){
        $request = \Yii::$app->request;
        $pubgSession = new PubgSeason($id);
        $leagueId = $request->get('leagueId');

        if($request->isPost){
            $postData = $this->processSendTime($request->post());
            $postData['seasonId'] = $id;
            $postData['updateUserId'] = \Yii::$app->user->identity->id;
            $postData['gameType'] = 1;
            \Yii::trace($postData , 'season.update');
            if($pubgSession->update($postData)){
                \Yii::$app->session->setFlash('success' , "修改赛季信息成功！");
                GloryMatch::clearTypesCache();
            }else{
                \Yii::$app->session->setFlash('error' , $pubgSession->getError());
            }
        }
        $matchName = '赛事';
        $data = $pubgSession->detail();
        if(empty($data)){
            \Yii::$app->session->setFlash('dataError' , $pubgSession->getError());
        }else{
            $data['matchTime'] = $data['startTime'].' 至 '.$data['endTime'];
            $data['name'] = $data['leagueName'];
            $matchName = isset($data['leagueName']) ? $data['leagueName'] : '';
        }
        $bonusConfigList = $this->getBonusConfig();
        return $this->render( 'update' , ['matchName' => $matchName ,  'data' => $data, 'bonusConfigList' => $bonusConfigList,'leagueId' => $leagueId]);
    }
    
    protected function processShowTime($data){
        $data['createTime'] = $data['createTime'] / 1000 ;
        $data['startTime'] =  $data['startTime'] / 1000;
        $data['endTime'] =  $data['endTime'] / 1000;
        $data['matchTime'] = date('Y-m-d H:i' , $data['startTime']) . ' 至 '  . date('Y-m-d H:i' , $data['endTime']);
        return $data;
    }
    
    protected function processSendTime($data){
        list($data['startTime'] , $data['endTime']) = explode(' 至 ' , $data['matchTime']);
//        $data['startTime'] = strtotime($data['startTime']) * 1000;
//        $data['endTime'] = strtotime($data['endTime']) * 1000;

        return $data;
    }

    protected function getBonusConfig(){
        $bonus = new Bonus();

        $list = $bonus->getBonusList();

        if(!is_array($list)){
            $list = [];
        }

        $data = [];

        foreach ($list as $value){
            $data[$value['id']] = $value['name'];
        }

        return $data;
    }

    public function actionBonus($id){
        $response = ['status' => 'success' , 'message' => '' ];
        $bonus = new Bonus();
        $data  = [
            'seasonId' => $id,
            'adminId' => \Yii::$app->user->identity->id
        ];

        if($bonus->sendBonus($data) == false){
            $response['status'] = 'error';
            $response['message'] = $bonus->getError();
        }
        return Json::encode($response);
    }

    public function actionDetail($id){
        $data =  [];
        $request = \Yii::$app->request;
        $page = $request->get('page'  , 1);
        $pageSize = $request->get('pageSize' , 15);
        $seasonName = $request->get('seasonName');
        $leagueId = $request->get('leagueId');

        $bonus = new Bonus();

        $bonusData = $bonus->bonusDetail($id);
        $operateUser = '';
        $operateDate = '';

        if(empty($bonusData)){
            \Yii::$app->session->setFlash( 'error' , $bonus->getError());
        }else{
            foreach ($bonusData['users'] as $value){
                $value['nickName'] = urldecode($value['nickName']);
                $value['totalCount'] = $value['winCount'] + $value['loseCount'] + $value['tieCount'];
                $value['winRatio'] = ( $value['totalCount'] > 0 ? round($value['winCount'] / $value['totalCount'] , 4) * 100 : 0 ) . '%';
                $data[] = $value;
            }
            if(isset($bonusData['adminId']) && !empty($bonusData['adminId'])){
                $user = new User();
                $response = $user->findOne($bonusData['adminId']);
                $operateUser = $response->username;
            }
            $operateDate = $bonusData['date'];


        }
        $totalCount = isset( $bonusData['totalSize'] ) ? $bonusData['totalSize'] : 0;
        $dataProvider = new ArrayDataProvider([
            'sort' => [
                'attributes' => [],
            ],
            'pagination' => [
                'pageSize' => $pageSize,
                'totalCount' => $totalCount
            ]
        ]);
        $dataProvider->setModels($data);
        $dataProvider->setTotalCount($totalCount);
        return $this->render('detail' , ['dataProvider' => $dataProvider,'matchName' => $seasonName, 'leagueId' => $leagueId, 'operateDate' => $operateDate, 'operateUser' => $operateUser]);
    }
}
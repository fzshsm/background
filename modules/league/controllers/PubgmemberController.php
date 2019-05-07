<?php
namespace app\modules\league\controllers;


use app\controllers\Controller;
use app\modules\league\api\PubgLeague;
use app\modules\league\api\PubgMatch;
use app\modules\league\api\PubgMember;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;

class PubgmemberController extends Controller {

    public function actionIndex(){
        $data = [];
        $currentSeason = [];
        $seasonList = [];
        $leagueName = '';
        $request = \Yii::$app->request;
        $leagueId = $request->get('leagueId' , 0);
        $status = $request->get('status' ,$leagueId <= 0 ?  2 : 0);
        $searchType = $request->get('searchType','0');
        $content = $request->get('content');

        $pubgLeague = new PubgLeague($leagueId);
        $leagueData = $pubgLeague->detail();

        if(!empty($leagueId) && isset($leagueData)){
            $leagueName = isset($leagueData['leagueName']) ? $leagueData['leagueName'] : '';
        }

        $search['type'] = $searchType;
        $page = $request->get('page'  , 1);
        $pageSize = $request->get('pageSize' , 15);

        \Yii::trace($request->get() , 'member');

        $member = new PubgMember();
        $memberData = $member->listdata($leagueId  , $status , $page , $pageSize ,[$searchType => trim($content)],2);
        if(!empty($memberData)){
            foreach($memberData['results'] as $value){
                $value['league'] = $value['leagueName'];
                $data[$value['leagueSignId']] = $value;
            }
        }else{
            \Yii::$app->session->setFlash( 'error' , $member->getError());
        }
        $totalCount = isset( $memberData['totalSize'] ) ? $memberData['totalSize'] : 0;
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

        return $this->render('../glorymember/index' , ['leagueName' => $leagueName , 'currentSeason' => $currentSeason , 'seasonList' => $seasonList,'dataProvider' => $dataProvider,'gameType' => 'pubg']);
    }
    
//    public function actionUpdate($id){
//        $request = \Yii::$app->request;
//        $leagueName = '';
//
//        $member = new PubgMember($id);
//        if($request->isPost){
//            $postData = $request->post();
//            if($member->update($postData) == false){
//                \Yii::$app->session->setFlash('error' , $member->getError());
//            }else{
//                \Yii::$app->session->setFlash('success' , '修改信息成功！');
//            }
//        }
//        $data = $member->detail();
//        $match = new PubgMatch();
//        $matchType = $match->types();
//        if(!empty($data['gameLeagueId']) && isset($matchType[$data['gameLeagueId']])){
//            $leagueName = $matchType[$data['gameLeagueId']]['name'];
//        }
//        $data['league'] = isset($matchType[$data['gameLeagueId']]) ? $matchType[$data['gameLeagueId']]['name'] : null;
//        if(isset($matchType[$data['gameLeagueId']]) && isset($matchType[$data['gameLeagueId']]['childRaceInfos'][$data['seasonId']])){
//            $data['season'] = $matchType[$data['gameLeagueId']]['childRaceInfos'][$data['seasonId']]['name'];
//        }else{
//            $data['season'] = null;
//        }
//        return $this->render('update' , ['data' => $data , 'leagueName' => $leagueName]);
//    }
    
    public function actionAgree($id){
        $response = ['status' => 'success' , 'message' => '' ];
        $member = new PubgMember();
        if($member->audit(['leagueSignId' => $id, 'isPass' => 1]) == false){
            $response['status'] = 'error';
            $response['message'] = $member->getError();
        }
        return Json::encode($response);
    }
    
    public function actionReject($id){
        $response = ['status' => 'success' , 'message' => '' ];
        $member = new PubgMember();

        if($member->audit(['leagueSignId' => $id,'isPass' => 0]) == false){
            $response['status'] = 'error';
            $response['message'] = $member->getError();
        }
        return Json::encode($response);
    }
    
//    public function actionBan($id){
//        $response = ['status' => 'success' , 'message' => '' ];
//        $member = new PubgMember($id);
//        if($member->ban(['id' => $id]) == false){
//            $response['status'] = 'error';
//            $response['message'] = $member->getError();
//        }
//        return Json::encode($response);
//    }
//
//    public function actionUnban($id){
//        $response = ['status' => 'success' , 'message' => '' ];
//        $member = new PubgMember($id);
//        if($member->unban(['id' => $id]) == false){
//            $response['status'] = 'error';
//            $response['message'] = $member->getError();
//        }
//        return Json::encode($response);
//    }
}
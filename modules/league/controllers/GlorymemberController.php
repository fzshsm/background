<?php
namespace app\modules\league\controllers;


use app\controllers\Controller;
use app\modules\league\api\GloryMatch;
use app\modules\league\api\GloryMember;
use app\modules\league\api\PubgMember;
use app\modules\mall\api\Bonus;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;
use yii\helpers\VarDumper;

class GlorymemberController extends Controller {

    public function actionIndex(){
        $data = [];
        $currentSeason = [];
        $seasonList = [];
        $leagueName = '';
        $request = \Yii::$app->request;
        $leagueId = $request->get('leagueId' , 0);
        $seasonId = $request->get('seasonId' , 0);
        $status = $request->get('status' , $leagueId <= 0 ?  2 : 0);
        $search = $request->get('search' , ['type' => 'all' , 'value' => '']);
        $searchType = $request->get('searchType',$leagueId == 0 ? 0 : 'all');
        $content = $request->get('content');
        $match = new GloryMatch();
        $matchType = $match->types();
        if(!empty($leagueId) && isset($matchType[$leagueId])){
            $leagueName = $matchType[$leagueId]['name'];
        }
        if($leagueId == 0 || $status == 2){
            $seasonId = 0;
        }
        if(isset($matchType[$leagueId]) && !empty($matchType[$leagueId]['childRaceInfos'])){
            $seasonList = $matchType[$leagueId]['childRaceInfos'];
            $currentSeason = array_slice($matchType[$leagueId]['childRaceInfos'] , 0 , 1);
            $currentSeason = array_shift($currentSeason);
            if(!empty($seasonId) && !empty($leagueId)){
                $currentSeason = $seasonList[$seasonId];
            }
            if(empty($seasonId) && $status != 2){
                $seasonId = $currentSeason['id'];
            }
        }

        $search['type'] = $searchType;
        $page = $request->get('page'  , 1);
        $pageSize = $request->get('pageSize' , 15);

        \Yii::trace($request->get() , 'member');
        if(empty($leagueId) || $status == 2){
            $order = 'id';
        }else{
            $order = 'score';
        }
        $member = new GloryMember();
        $pubgMember = new PubgMember();
        if($leagueId == 0){
            $memberData = $pubgMember->listdata($leagueId , $status  , $page , $pageSize , [$search['type'] => trim($content)] ,1);
        }else{
            $memberData = $member->listdata($leagueId , $seasonId , [$search['type'] => trim($content)] , $status , $page , $pageSize , $order);
        }
        //$memberData = $member->listdata($leagueId , $seasonId , [$search['type'] => trim($content)] , $status , $page , $pageSize , $order);
        if(!empty($memberData)){
            foreach($memberData['results'] as $value){
                if($leagueId == 0){
                    $value['league'] = $value['leagueName'];
                    $value['time'] = $value['createTime'];
                    $data[$value['leagueSignId']] = $value;
                }else{
                    $value['league'] = isset($matchType[$value['gameLeagueId']]) ? $matchType[$value['gameLeagueId']]['name'] : null;
                    if(isset($matchType[$value['gameLeagueId']]) && isset($matchType[$value['gameLeagueId']]['childRaceInfos'][$value['seasonId']])){
                        $value['season'] = $matchType[$value['gameLeagueId']]['childRaceInfos'][$value['seasonId']]['name'];
                    }else{
                        $value['season'] = null;
                    }
                    $value['totalCount'] = $value['winCount'] + $value['loseCount'];
                    $value['winRatio'] = ( $value['totalCount'] > 0 ? round($value['winCount'] / $value['totalCount'] , 4) * 100 : 0 ) . '%';
                    $value['time'] = \Yii::$app->formatter->asDate($value['time'] / 1000);
                    if($value['forbidEndTime'] !== null){
                        $value['forbidEndTime'] = \Yii::$app->formatter->asDate($value['forbidEndTime']/1000);
                    }
                    $value['leagueId'] = $leagueId;
                    $value['level'] = $this->getRankLevel($value['level']);
                    $data[$value['id']] = $value;
                }

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
        return $this->render('index' , ['leagueName' => $leagueName , 'currentSeason' => $currentSeason , 'seasonList' => $seasonList,'dataProvider' => $dataProvider,'gameType' => 'glory','seasonId' => $seasonId]);
    }
    
    public function actionUpdate($id){
        $request = \Yii::$app->request;
        $leagueId = $request->get('leagueId',0);
        $seasonId = $request->get('seasonId',0);
        $userId = $request->get('userId',0);
        $leagueName = '';
        $data = [];
        $member = new GloryMember($id);
        $pubgMember = new PubgMember();
        if($request->isPost){
            $postData = $request->post();
            $postData['leagueId'] = $leagueId;
            $postData['seasonId'] = $seasonId;
            $postData['gameType'] = 1;
            $postData['uid'] = $userId;
            if($pubgMember->updateLeagueMemberInfo($postData) == false){
                \Yii::$app->session->setFlash('error' , $member->getError());
            }else{
                \Yii::$app->session->setFlash('success' , '修改信息成功！');
            }
        }

        $data = $pubgMember->leagueMemberInfo($leagueId,$seasonId,$userId,1);

        return $this->render('update' , ['data' => $data , 'leagueName' => $data['leagueName'],'leagueId' => $leagueId]);
    }
    
    public function actionAgree($id){
        $response = ['status' => 'success' , 'message' => '' ];
        $member = new PubgMember();

        if($member->audit(['leagueSignId' => $id,'isPass' => 1]) == false){
            $response['status'] = 'error';
            $response['message'] = $member->getError();
        }
        return Json::encode($response);
    }
    
    public function actionReject($id){
        $response = ['status' => 'success' , 'message' => '' ];
        $member = new PubgMember();
        $request = \Yii::$app->request;
        $postData = $request->get();

        $remark = isset($postData['remark']) ? $postData['remark'] : '';

        if($member->audit(['leagueSignId' => $id, 'isPass' => 0, 'remark' => $remark]) == false){
            $response['status'] = 'error';
            $response['message'] = $member->getError();
        }
        return Json::encode($response);
    }
    
    public function actionBan($id){
        $response = ['status' => 'success' , 'message' => '' ];
        $member = new GloryMember($id);
        if($member->changeStatus(5) == false){
            $response['status'] = 'error';
            $response['message'] = $member->getError();
        }
        return Json::encode($response);
    }
    
    public function actionUnban($id){
        $response = ['status' => 'success' , 'message' => '' ];
        $request = \Yii::$app->request;
        $leagueId = $request->get('leagueId');
        $userId = $request->get('userId');
        $data = [
            'userId' => $userId,
            'leagueId' => $leagueId,
            'gameType' => 1
        ];
        $member = new PubgMember($id);
        if($member->delWhiteList($data) == false){
            $response['status'] = 'error';
            $response['message'] = $member->getError();
        }
        return Json::encode($response);
    }

    protected function getRankLevel($level){
        $data = [
            "0"  => "无",
            "1" =>"倔强青铜III",
            "2"=>"倔强青铜II",
            "3"=>"倔强青铜I",
            "4"=>"秩序白银III",
            "5"=>"秩序白银II",
            "6"=>"秩序白银I",
            "7"=>"荣耀黄金IV",
            "8"=>"荣耀黄金III",
            "9"=>"荣耀黄金II",
            "10"=>"荣耀黄金I",
            "11"=>"尊贵铂金V",
            "12"=>"尊贵铂金IV",
            "13"=>"尊贵铂金III",
            "14"=>"尊贵铂金II",
            "15"=>"尊贵铂金I",
            "16"=>"永恒钻石V",
            "17"=>"永恒钻石IV",
            "18"=>"永恒钻石III",
            "19"=>"永恒钻石II",
            "20"=>"永恒钻石I",
            "21"=>"至尊星耀V",
            "22"=>"至尊星耀IV",
            "23"=>"至尊星耀III",
            "24"=>"至尊星耀II",
            "25"=>"至尊星耀I",
            "26"=>"荣耀王者"
        ];

        return $data[$level];
    }
}
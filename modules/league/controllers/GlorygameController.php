<?php
namespace app\modules\league\controllers;

use app\controllers\Controller;
use app\modules\admin\models\Permission;
use app\modules\admin\models\RolePermission;
use app\modules\league\api\GloryGame;
use app\modules\league\api\GloryMatch;
use app\modules\admin\models\User;
use yii\data\ArrayDataProvider;


class GlorygameController extends Controller {

    public function actionIndex(){
        $data =  [];
        $request = \Yii::$app->request;
        $page = $request->get('page'  , 1);
        $pageSize = $request->get('pageSize' , 10);
        $searchType = $request->get('searchType');
        $content = $request->get('content');
        $date = $request->get('date');
        $leagueId = $request->get('leagueId' , 0);
        $seasonId = $request->get('seasonId' , 0);
        $status = $request->get('status',0);
        $dataType = $request->get('dataType',2);

        $begin = $end = '';
        $matchName = '';
        $currentSeason = 0;
        
        $match = new GloryMatch();
        $matchType = $match->types();
        if(!empty($leagueId)){
            if(isset($matchType[$leagueId])){
                $matchName = $matchType[$leagueId]['name'];
            }
        }
        if(isset($matchType[$leagueId]) && !empty($matchType[$leagueId]['childRaceInfos'])){
            $seasonList = $matchType[$leagueId]['childRaceInfos'];
            if(empty($seasonId)){
                $tempData = $seasonList;
                $currentSeason = array_shift($tempData);
                $seasonId = $currentSeason['id'];
            }else{
                if(isset($seasonList[$seasonId])){
                    $currentSeason = $seasonList[$seasonId];
                }
            }
            \Yii::trace( $currentSeason , 'game.currentSeason');
        }else{
            $seasonList = $this->getSeasonData($matchType);
        }
        if(!empty($date)){
            list($begin , $end) = explode('至' , $date);
        }
        
        $condition = [$searchType => trim($content)];
        if(!empty($leagueId)){
            $condition['leagueId'] = $leagueId;
        }
        if(!empty($seasonId)){
            $condition['seasonId'] = $seasonId;
        }
        
        $game = new GloryGame();
        $gameData = $game->listdata($page , $pageSize , $condition , ['begin' => trim($begin) , 'end' => trim($end)],$status,$dataType);

        if(empty($gameData)){
            \Yii::$app->session->setFlash( 'error' , $game->getError());
        }else{
            foreach ($gameData['results'] as $value){
                $data[$value['id']] = $value;
            }
        }

        $totalCount = isset( $gameData['totalSize'] ) ? $gameData['totalSize'] : 0;
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

        $refreshPer = 0;
        $user = User::findOne(['id' => \Yii::$app->user->id]);
        $permission = Permission::findOne(['module' => 'league' , 'controller' => 'game' , 'action' => 'refresh']);
        if(!empty($permission)){
            $rolePermission = RolePermission::findOne(['role_id' => $user->role_id , 'permission_id' => $permission->id]);
            if(!empty($rolePermission)){
                $refreshPer = 1;
            }
        }
        return $this->render( 'index' ,
            [
                'matchType' => $matchType,
                'currentSeason' =>$currentSeason,
                'seasonList' => $seasonList,
                'matchName'    => $matchName ,
                'dataProvider' => $dataProvider,
                'refeshPer' => $refreshPer,
                'gameType' => 'glory'
            ]
        );
    }
    
    public function actionCancel($id){
        $response = ['status' => 'success' , 'message' => '' ];
        $game = new GloryGame($id);
        if($game->cancel() == false){
            $response['status'] = 'error';
            $response['message'] = $game->getError();
        }
        return $this->asJson($response);
    }
    
    public function actionTransform($id){
        $response = ['status' => 'success' , 'message' => '' ];
        $result = \Yii::$app->request->get('result');
        $game = new GloryGame($id);
        if($game->changeResult($result) == false){
            $response['status'] = 'error';
            $response['message'] = $game->getError();
        }
        return $this->asJson($response);
    }
    
    protected function getSeasonData($matchType){
        $data = [];
        if(!empty($matchType) && is_array( $matchType)){
            foreach($matchType as $value){
                foreach($value['childRaceInfos'] as $v){
                    $data[$v['id']] = $v;
                }
            }
        }
        return $data;
    }

    public function actionRefresh(){
        $response = ['status' => 'success' , 'message' => '' ];
        $game = new GloryGame();
        if($game->refreshQueue() == false){
            $response['status'] = 'error';
            $response['message'] = $game->getError();
        }
        return $this->asJson($response);
    }
}
<?php
namespace app\modules\league\controllers;

use app\controllers\Controller;
use app\modules\admin\models\Permission;
use app\modules\admin\models\RolePermission;
use app\modules\league\api\PubgGame;
use app\modules\league\api\PubgMatch;
use app\modules\admin\models\User;
use yii\data\ArrayDataProvider;


class PubggameController extends Controller {

    public function actionIndex(){
        $data =  [];
        $request = \Yii::$app->request;
        $page = $request->get('page'  , 1);
        $pageSize = $request->get('pageSize' , 15);
        $status = $request->get('status','2,3,4');
        $searchType = $request->get('searchType');
        $content = $request->get('content');
        $date = $request->get('date');

        if($status < 2){
            $status = '2,3,4';
        }

        $time = 0;
        if(!empty($date)){
            list($begin , $end) = explode('至' , $date);
            $time = $begin.','.$end;
        }

        $match = new PubgMatch();

        $matchDatas = $match->datalist('' , $page , $pageSize, $status, $time, $searchType, $content);

        if(empty($matchDatas)){
            \Yii::$app->session->setFlash( 'error' , $match->getError());
        }else{
            foreach ($matchDatas['results'] as $value){
                $value['id'] = $value['matchId'];
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

        return $this->render('../glorygame/index' , ['dataProvider' => $dataProvider, 'gameType' => 'pubg', 'refeshPer' => 0]);
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
}
<?php

namespace app\modules\statistics\controllers;

use app\controllers\Controller;
use app\modules\league\api\Match;
use app\modules\statistics\api\Game;
use yii\helpers\Json;

/**
 * Default controller for the `analyzestatistics` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex(){
        $request = \Yii::$app->request;
        if($request->isAjax){
            $date = $request->get('date' , date('Y-m-d',strtotime('-1 month')).'_'  . date('Y-m-d'));
            $gameType = $request->get('gameType','glory');
            if(!empty($date)){
                list($begin , $end) = explode('_' , $date);

            }

            $game = new Game();

            $data = $game->statistics($begin , $end, $gameType);

            $matchData = isset($data['matchData']) ? $data['matchData'] : [];
            $gamesCount = isset($data['gamesCount']) ? $data['gamesCount'] :[];
            $gameLeaguesCount = isset($data['gameLeaguesCount']) ? $data['gameLeaguesCount'] : [];
            $dateList = isset($data['dateList']) ? $data['dateList'] : [];

            $total = [
                'name' => ['专业赛','竞技赛','娱乐赛','综合'],
                'statistic' => [
                    isset($gamesCount[1]) ? $gamesCount[1] : [],
                    isset($gamesCount[2]) ? $gamesCount[2] : [],
                    isset($gamesCount[3]) ? $gamesCount[3] : [],
                    isset($gamesCount['total']) ? $gamesCount['total'] : []
                ],
            ];

            $major = ['name' => [],'statistic' => []];
            $athletics = ['name' => [],'statistic' => []];
            $entertain = ['name' => [],'statistic' => []];

            foreach ($gameLeaguesCount as $key => $gameLeague){
                $name = ['综合'];
                $statistic = [$total['statistic'][$key-1]];
                foreach ($gameLeague as $k => $v){
                    array_push($name,isset($matchData[$k]) ? $matchData[$k] : '');
                    array_push($statistic,$v);
                }
                switch ($key){
                    case 1:
                        $major = ['name' => $name,'statistic' => $statistic];
                        break;
                    case 2:
                        $athletics = ['name' => $name,'statistic' => $statistic];
                        break;
                    case 3:
                        $entertain = ['name' => $name,'statistic' => $statistic];
                        break;
                }
            }

            if(empty($major['name'])){
                unset($total['name'][0]);
                unset($total['statistic'][0]);
            }

            if(empty($athletics['name'])){
                unset($total['name'][1]);
                unset($total['statistic'][1]);
            }

            if(empty($entertain['name'])){
                unset($total['name'][2]);
                unset($total['statistic'][2]);
            }

            $total['name'] = array_values($total['name']);
            $total['statistic'] = array_values($total['statistic']);

            $game = [$total, $major,$athletics, $entertain,];
            return Json::encode(['dateList' => $dateList ,'game' => $game]);
        }
        return $this->render('index' );
    }
}

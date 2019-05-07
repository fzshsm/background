<?php

namespace app\modules\statistics\controllers;

use app\controllers\Controller;
use app\helper\UmengData;
use app\modules\statistics\api\Behavior;
use app\modules\statistics\api\User;
use yii\helpers\Json;


class UserController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex(){
        $request = \Yii::$app->request;
        if($request->isAjax){
            $date = $request->get('date' , date('Y-m-d',strtotime('-1 month')).'_'  . date('Y-m-d'));

            if(!empty($date)){
                list($begin , $end) = explode('_' , $date);
            }

            $user = new User();

            $behavior = new Behavior();

            $statisticsData = $user->statistics($begin , $end);
            $dateList = isset($statisticsData['dateList']) ? $statisticsData['dateList'] : [];
            $userData = $this->filterData($statisticsData,$dateList);

            $data = $behavior->behaviorStatistic($begin , $end);

            $taskUserCount = isset($data['taskUserCount']) ? $data['taskUserCount'] : [];
            $guessUserCount = isset($data['guessUserCount']) ? $data['guessUserCount'] : [];
            $orderUserCount = isset($data['orderUserCount']) ? $data['orderUserCount'] : [];
            $gameUserCount = isset($data['gameUserCount']) ? $data['gameUserCount'] : [];
            $lotteryUserCount = isset($data['lotteryUserCount']) ? $data['lotteryUserCount'] : [];
            $behaviorData = [
                'name' => ['每日游戏人数','每日任务人数','每日竞猜人数','每日购物人数','每日抽奖人数'],
                'statistic' => [$gameUserCount,$taskUserCount,$guessUserCount,$orderUserCount,$lotteryUserCount]
            ];

            return Json::encode(['dateList' => $dateList ,'user' => $userData,'behavior' => [$behaviorData]]);
        }
        return $this->render('index' );
    }

    protected function filterData($statisticsData,$dateList){

        $activeIos = [];
        $installIos = [];
        $launchIos = [];

        $activeAndriod = [];
        $installAndriod = [];
        $launchAndriod = [];

        $activeTotal = [];
        $installTotal = [];
        $launchTotal = [];

        foreach($dateList as $dateVal){

            $activeGame = isset($statisticsData[$dateVal][1]['game_user_count']) ? $statisticsData[$dateVal][1]['game_user_count'] : 0;

            $iosGame = 0;
            $andriodGame = 0;
            if($activeGame != 0){
                $iosGame = round($activeGame*0.55);
                $andriodGame = round($activeGame*0.45);
            }

            array_push($activeIos,(isset($statisticsData[$dateVal][1]['ios']) ? $statisticsData[$dateVal][1]['ios'] : 0) + $iosGame);
            array_push($installIos,isset($statisticsData[$dateVal][2]['ios']) ? $statisticsData[$dateVal][2]['ios'] : 0);
            array_push($launchIos,isset($statisticsData[$dateVal][3]['ios']) ? $statisticsData[$dateVal][3]['ios'] : 0);



            array_push($activeAndriod,(isset($statisticsData[$dateVal][1]['andriod']) ? $statisticsData[$dateVal][1]['andriod'] : 0) + $andriodGame);
            array_push($installAndriod,isset($statisticsData[$dateVal][2]['andriod']) ? $statisticsData[$dateVal][2]['andriod'] : 0);
            array_push($launchAndriod,isset($statisticsData[$dateVal][3]['andriod']) ? $statisticsData[$dateVal][3]['andriod'] : 0);

            array_push($activeTotal,(isset($statisticsData[$dateVal][1]['ios']) ? $statisticsData[$dateVal][1]['ios'] : 0) + (isset($statisticsData[$dateVal][1]['andriod']) ? $statisticsData[$dateVal][1]['andriod'] : 0) + $iosGame + $andriodGame );
            array_push($installTotal,(isset($statisticsData[$dateVal][2]['ios']) ? $statisticsData[$dateVal][2]['ios'] : 0) + (isset($statisticsData[$dateVal][2]['andriod']) ? $statisticsData[$dateVal][2]['andriod'] : 0));
            array_push($launchTotal,(isset($statisticsData[$dateVal][3]['ios']) ? $statisticsData[$dateVal][3]['ios'] : 0) + (isset($statisticsData[$dateVal][3]['andriod']) ? $statisticsData[$dateVal][3]['andriod'] : 0));
        }

        $active = ['name' => ['综合','ios','andriod'],'statistic' => [$activeTotal,$activeIos,$activeAndriod]];
        $install = ['name' => ['综合','ios','andriod'],'statistic' => [$installTotal,$installIos,$installAndriod]];
        $launch = ['name' => ['综合','ios','andriod'],'statistic' => [$launchTotal,$launchIos,$launchAndriod]];

        return [$active, $install, $launch];
    }
}

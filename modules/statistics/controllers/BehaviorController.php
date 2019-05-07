<?php

namespace app\modules\statistics\controllers;

use app\controllers\Controller;
use app\modules\league\api\Match;
use app\modules\statistics\api\Behavior;
use app\modules\statistics\api\Finance;
use app\modules\statistics\api\Game;
use yii\helpers\Json;

/**
 * Default controller for the `analyzestatistics` module
 */
class BehaviorController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex(){
        $request = \Yii::$app->request;
        if($request->isAjax){
            $date = $request->get('date' , date('Y-m-d',strtotime('-1 week')).'_'  . date('Y-m-d'));
            if(!empty($date)){
                list($begin , $end) = explode('_' , $date);
            }

            $behavior = new Behavior();

            $data = $behavior->behaviorStatistic($begin , $end);

            $taskUserCount = isset($data['taskUserCount']) ? $data['taskUserCount'] : [];
            $guessUserCount = isset($data['guessUserCount']) ? $data['guessUserCount'] : [];
            $orderUserCount = isset($data['orderUserCount']) ? $data['orderUserCount'] : [];
            $gameUserCount = isset($data['gameUserCount']) ? $data['gameUserCount'] : [];
            $lotteryUserCount = isset($data['lotteryUserCount']) ? $data['lotteryUserCount'] : [];
            $dateList = isset($data['dateList']) ? $data['dateList'] : [];

            $behaviorData = [
                'name' => ['每日游戏人数','每日任务人数','每日竞猜人数','每日购物人数','每日抽奖人数'],
                'statistic' => [$gameUserCount,$taskUserCount,$guessUserCount,$orderUserCount,$lotteryUserCount]
            ];

            return Json::encode(['dateList' => $dateList ,'behavior' => [$behaviorData]]);
        }
        return $this->render('index' );
    }

    public function actionLeague(){
        $request = \Yii::$app->request;
        if($request->isAjax){
            $startTime = $request->get('date' ,  date('Y-m-d'));
            $gameType = $request->get('gameType',1);

            $behavior = new Behavior();

            $data = $behavior->behaviorLeague($startTime , $gameType);

            $flagUserCount = isset($data['flagUserCount']) ? $data['flagUserCount'] : [];
            $gameUserCount = isset($data['gameUserCount']) ? $data['gameUserCount'] : [];
            $flagName = isset($data['flagName']) ? $data['flagName'] : [];
            $gameName = isset($data['gameName']) ? $data['gameName'] : [];
            $dateList = isset($data['dateList']) ? $data['dateList'] : [];

            $leagueData = [
                'name' => [],
                'statistic' => []
            ];
            $leaguePieData = [];
            $gamePieData = [];
            foreach ($flagUserCount as $key => $value){
                    array_push($leagueData['name'],$flagName[$key]);
                    array_push($leaguePieData, ['value' => $value,'name' => $flagName[$key]]);
            }

            foreach ($gameUserCount as $key => $value){
                foreach ($value as $k => $v){
                    array_push($gamePieData, ['value' => $v,'name' => $gameName[$k]]);
                }

            }

            $leagueData['statistic'][0] = $leaguePieData;
            $leagueData['statistic'][1] = $gamePieData;

            return Json::encode(['dateList' => $dateList ,'behavior' => [$leagueData]]);
        }
        return $this->render('index' );
    }

    public function actionTask(){
        $request = \Yii::$app->request;
        if($request->isAjax){
            $startTime = $request->get('date' ,  date('Y-m-d'));

            $behavior = new Behavior();

            $data = $behavior->behaviorTask($startTime);

            $taskData = isset($data['taskData']) ? $data['taskData'] : [];
            $currencyData = isset($data['currencyData']) ? $data['currencyData'] : [];
            $taskName = isset($data['taskName']) ? $data['taskName'] : [];
            $allTaskName = isset($data['allTaskName']) ? $data['allTaskName'] : [];
            $dateList = isset($data['dateList']) ? $data['dateList'] : [];

            $taskBehaviorData = [
                'name' => [],
                'statistic' => [[]]
            ];

            $taskPieData = [];
            $currencyPieData = [];
            foreach ($taskData as $key => $value){
                array_push($taskBehaviorData['name'],$taskName[$key]);
//                array_push($taskBehaviorData['statistic'],[$value]);
                array_push($taskPieData,['value' => $value,'name' => $taskName[$key]]);
            }

            foreach ($currencyData as $key => $value){
                foreach ($value as $k => $v){
                    array_push($currencyPieData,['value' => $v, 'name' => $allTaskName[$k]]);
                }
            }

            $taskBehaviorData['statistic'][0] = $taskPieData;
            $taskBehaviorData['statistic'][1] = $currencyPieData;

            return Json::encode(['dateList' => $dateList ,'behavior' => [$taskBehaviorData]]);
        }
        return $this->render('index' );
    }

    public function actionOrder(){
        $request = \Yii::$app->request;
        if($request->isAjax){
            $startTime = $request->get('date' ,  date('Y-m-d'));

            $behavior = new Behavior();

            $data = $behavior->behaviorOrder($startTime);

            $orderData = isset($data['orderData']) ? $data['orderData'] : [];
            $typeName = isset($data['typeName']) ? $data['typeName'] : [];
            $goodsData = isset($data['goodsData']) ? $data['goodsData'] : [];
            $goodsName = isset($data['goodsName']) ? $data['goodsName'] : [];
            $dateList = isset($data['dateList']) ? $data['dateList'] : [];

            $orderBehaviorData = [
                'name' => [],
                'statistic' => [[]]
            ];

            $orderPieData = [];
            $goodsPieData = [];
            foreach ($orderData as $key => $value){
                array_push($orderBehaviorData['name'],$typeName[$key]);
                array_push($orderPieData,['value' => $value,'name' => $typeName[$key]]);
            }

            foreach ($goodsData as $k => $v){
                array_push($goodsPieData,['value' => $v, 'name' => $goodsName[$k]]);
            }

            $orderBehaviorData['statistic'][0] = $orderPieData;
            $orderBehaviorData['statistic'][1] = $goodsPieData;

            return Json::encode(['dateList' => $dateList ,'behavior' => [$orderBehaviorData]]);
        }
        return $this->render('index' );
    }

    public function actionGuess(){
        $request = \Yii::$app->request;
        if($request->isAjax){
            $startTime = $request->get('date' ,  date('Y-m-d'));

            $behavior = new Behavior();

            $data = $behavior->behaviorGuess($startTime);

            $guessData = isset($data['guessData']) ? $data['guessData'] : [];
            $typeName = isset($data['typeName']) ? $data['typeName'] : [];
            $dateList = isset($data['dateList']) ? $data['dateList'] : [];

            $guessBehaviorData = [
                'name' => [],
                'statistic' => []
            ];

            $guessPieData = [
                'name' => [],
                'statistic' => []
            ];
            $guessPie = [];
            foreach ($guessData as $key => $value){
                array_push($guessBehaviorData['name'],$typeName[$key]);
                array_push($guessBehaviorData['statistic'],[$value]);
                array_push($guessPieData['name'],$typeName[$key]);
                array_push($guessPie,['value' => $value,'name' => $typeName[$key]]);
            }
            $guessPieData['statistic'][0] = $guessPie;
            $guessPieData['statistic'][1] = [];
            return Json::encode(['dateList' => $dateList ,'behavior' => [$guessPieData],'barBehavior' => [$guessBehaviorData]]);
        }
        return $this->render('index' );
    }

    public function actionLottery(){
        $request = \Yii::$app->request;
        if($request->isAjax){
            $startTime = $request->get('date' ,  date('Y-m-d'));

            $behavior = new Behavior();

            $data = $behavior->behaviorLottery($startTime);

            $lotteryData = isset($data['lotteryData']) ? $data['lotteryData'] : [];
            $prizeName = isset($data['prizeName']) ? $data['prizeName'] : [];
            $dateList = isset($data['dateList']) ? $data['dateList'] : [];

            $lotteryBehaviorData = [
                'name' => [],
                'statistic' => []
            ];

            $lotteryPieData = [
                'name' => [],
                'statistic' => []
            ];
            $guessPie = [];
            foreach ($lotteryData as $key => $value){
                array_push($lotteryBehaviorData['name'],$prizeName[$key]);
                array_push($lotteryBehaviorData['statistic'],[$value]);
                array_push($lotteryPieData['name'],$prizeName[$key]);
                array_push($guessPie,['value' => $value,'name' => $prizeName[$key]]);
            }
            $lotteryPieData['statistic'][0] = $guessPie;
            $lotteryPieData['statistic'][1] = [];
            return Json::encode(['dateList' => $dateList ,'behavior' => [$lotteryPieData],'barBehavior' => [$lotteryBehaviorData]]);
        }
        return $this->render('index' );
    }

    public function actionGame(){
        $request = \Yii::$app->request;
        if($request->isAjax){
            $startTime = $request->get('date' ,  date('Y-m-d'));
            $gameType = $request->get('gameType',1);
            $flagName = $request->get('flagName');

            $flag = 0;
            switch ($flagName){
                case '专业赛':
                    $flag = 1;
                    break;
                case '竞技赛':
                    $flag = 2;
                    break;
                case '娱乐赛':
                    $flag = 3;
                    break;
                case '赏金赛':
                    $flag = 4;
                    break;
            }

            $behavior = new Behavior();

            $data = $behavior->behaviorGame($startTime,$gameType,$flag);

            $gameUserCount = isset($data['gameUserCount']) ? $data['gameUserCount'] : [];
            $gameName = isset($data['gameName']) ? $data['gameName'] : [];
            $flagName = isset($data['flagName']) ? $data['flagName'] : '';
            $dateList = isset($data['dateList']) ? $data['dateList'] : [];

            $gameBehaviorData = [
                'name' => [],
                'statistic' => []
            ];

            foreach ($gameUserCount as $key => $value){
                array_push($gameBehaviorData['name'],$gameName[$key]);
                array_push($gameBehaviorData['statistic'],[$value]);
            }


            return Json::encode(['dateList' => $dateList ,'behavior' => [$gameBehaviorData],'title' => $flagName]);
        }
        return $this->render('index' );
    }

    public function actionCurrency(){
        $request = \Yii::$app->request;
        if($request->isAjax){
            $startTime = $request->get('date' ,  date('Y-m-d'));
            $flagName = $request->get('flagName');

            $flag = 1;

            if($flagName == '百斗任务'){
                $flag = 2;
            }elseif($flagName == '每日任务'){
                $flag = 0;
            }

            $behavior = new Behavior();

            $data = $behavior->behaviorCurrency($startTime,$flag);

            $taskData = isset($data['taskData']) ? $data['taskData'] : [];
            $taskName = isset($data['taskName']) ? $data['taskName'] : [];
            $flagName = isset($data['flagName']) ? $data['flagName'] : '';
            $dateList = isset($data['dateList']) ? $data['dateList'] : [];

            $taskBehaviorData = [
                'name' => [],
                'statistic' => []
            ];

            foreach ($taskData as $key => $value){
                array_push($taskBehaviorData['name'],$taskName[$key]);
                array_push($taskBehaviorData['statistic'],[$value]);
            }


            return Json::encode(['dateList' => $dateList ,'behavior' => [$taskBehaviorData],'title' => $flagName]);
        }
        return $this->render('index' );
    }

    public function actionGoods(){
        $request = \Yii::$app->request;
        if($request->isAjax){
            $startTime = $request->get('date' ,  date('Y-m-d'));
            $flagName = $request->get('flagName');

            $goodsType = 0;

            switch ($flagName){
                case '充值卡':
                    $goodsType = 1;
                    break;
                case '优惠券':
                    $goodsType = 2;
                    break;
                case '游戏周边':
                    $goodsType = 3;
                    break;
                case '电子产品':
                    $goodsType = 4;
                    break;
            }

            $behavior = new Behavior();

            $data = $behavior->behaviorGoods($startTime,$goodsType);

            $goodsData = isset($data['goodsData']) ? $data['goodsData'] : [];
            $goodsName = isset($data['goodsName']) ? $data['goodsName'] : [];
            $dateList = isset($data['dateList']) ? $data['dateList'] : [];

            $goodsBehaviorData = [
                'name' => [],
                'statistic' => []
            ];

            foreach ($goodsData as $key => $value){
                array_push($goodsBehaviorData['name'],$goodsName[$key]);
                array_push($goodsBehaviorData['statistic'],[$value]);
            }


            return Json::encode(['dateList' => $dateList ,'behavior' => [$goodsBehaviorData],'title' => $flagName]);
        }
        return $this->render('index' );
    }

    public function actionTimesgame(){
        $request = \Yii::$app->request;
        if($request->isAjax){
            $startTime = $request->get('date' ,  date('Y-m-d'));
            $leagueName = $request->get('leagueName');

            $behavior = new Behavior();

            $data = $behavior->behaviorTimesGame($startTime,$leagueName);

            $gameUserCount = isset($data['gameUserCount']) ? $data['gameUserCount'] : [];
            $newUserCount = isset($data['newUserCount']) ? $data['newUserCount'] :[];
            $leagueName = isset($data['leagueName']) ? $data['leagueName'] : [];
            $dateList = isset($data['dateList']) ? $data['dateList'] : [];

            $gameBehaviorData = [
                'name' => [$leagueName],
                'statistic' => [$gameUserCount]
            ];

            $gamePieData = [
                'name' => [],
                'statistic' => [0 =>[],1 =>[]]
            ];
            if(isset($newUserCount['new_user']) && $newUserCount['new_user'] != 0){
                array_push($gamePieData['name'],'新用户');
                array_push($gamePieData['statistic'][0],['value' => $newUserCount['new_user'],'name' => '新用户']);

            }
            if(isset($newUserCount['old_user']) && $newUserCount['old_user'] != 0){
                array_push($gamePieData['name'],'老用户');
                array_push($gamePieData['statistic'][0],['value' => $newUserCount['old_user'],'name' => '老用户']);
            }

            return Json::encode(['dateList' => $dateList ,'behavior' => [$gameBehaviorData],'pieBehavior' => [$gamePieData],'pieDate' => [$startTime],'title' => $leagueName]);
        }
        return $this->render('index' );
    }

}

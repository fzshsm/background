<?php

namespace app\modules\statistics\controllers;

use app\controllers\Controller;
use app\modules\league\api\Match;
use app\modules\statistics\api\Finance;
use app\modules\statistics\api\Game;
use yii\helpers\Json;

/**
 * Default controller for the `analyzestatistics` module
 */
class CurrencyController extends Controller
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

            $finance = new Finance();

            $data = $finance->currencyStatistics($begin , $end);

            $coinBGainCount = isset($data['coin_b_gain_count']) ? $data['coin_b_gain_count'] : [];
            $coinBUseCount = isset($data['coin_b_use_count']) ? $data['coin_b_use_count'] : [];
            $allCoinBGainCount = isset($data['all_coin_b_gain_count']) ? $data['all_coin_b_gain_count'] : [];
            $allCoinBUseCount = isset($data['all_coin_b_use_count']) ? $data['all_coin_b_use_count'] : [];
            $pieGain = isset($data['pieGain']) ? $data['pieGain'] : [];
            $pieUse = isset($data['pieUse']) ? $data['pieUse']: [];
            $typeName = isset($data['typeName']) ? $data['typeName'] : [];
            $dateList = isset($data['dateList']) ? $data['dateList'] : [];

            $pieGainData = ['name' => [],'statistic' => []];
            $pieUseData = ['name' => [], 'statistic' => []];
            $gainData = ['name' => [],'statistic' => []];
            $useData = ['name' => [],'statistic' => []];
            $overallGain = 0;
            $overallUse = 0;


            //豆豆产出明细
            foreach ($coinBGainCount as $key => $value){
                array_push($gainData['name'],isset($typeName[$key]) ? $typeName[$key] : '');
                array_push($gainData['statistic'],$value);
            }

            //豆豆消耗明细
            foreach ($coinBUseCount as $key => $value){
                array_push($useData['name'],isset($typeName[$key]) ? $typeName[$key] : '');
                array_push($useData['statistic'],$value);
            }

            //豆豆总产出和总消耗饼图


            //豆豆产出饼图
            foreach ($pieGain as $key => $value){
                array_push($pieGainData['name'],$typeName[$key]);
                array_push($pieGainData['statistic'],['value' => $value,'name' => $typeName[$key]]);
            }

            //豆豆消耗饼图
            foreach ($pieUse as $key => $value){
                array_push($pieUseData['name'],$typeName[$key]);
                array_push($pieUseData['statistic'],['value' => $value,'name' => $typeName[$key]]);
            }

            $total = [
                'name' => ['豆豆总产出','豆豆总消耗'],
                'statistic' => [$allCoinBGainCount,$allCoinBUseCount]
            ];

            foreach ($allCoinBGainCount as $value){
                $overallGain += $value;
            }

            foreach ($allCoinBUseCount as $value){
                $overallUse += $value;
            }

            $pieOverall = [
                'name' => ['豆豆总产出','豆豆总消耗'],
                'statistic' => [
                    ['value' => $overallGain, 'name' => '豆豆总产出'],
                    ['value' => $overallUse, 'name' => '豆豆总消耗']
                ]
            ];

            return Json::encode(['dateList' => $dateList ,'currency' => [$total,$gainData,$useData,$pieGainData,$pieUseData,$pieOverall]]);
        }
        return $this->render('index' );
    }

    public function actionTask(){
        $request = \Yii::$app->request;
        if($request->isAjax){
            $startTime = $request->get('date' ,  date('Y-m-d'));

            $finance = new Finance();

            $data = $finance->taskStatistics($startTime);

            $taskData = isset($data['taskData']) ? $data['taskData'] : [];
            $taskName = isset($data['taskName']) ? $data['taskName'] : [];
            $dateList = isset($data['dateList']) ? $data['dateList'] : [];

            $taskDetailData = [
                'name' => ['任务占比'],
                'statistic' => [[]]
            ];

            $pieData = [];
            foreach ($taskData as $key => $value){
                array_push($taskDetailData['name'],$taskName[$key]);
                array_push($taskDetailData['statistic'],[$value]);
                array_push($pieData,['value' => $value,'name' => $taskName[$key]]);
            }

            $taskDetailData['statistic'][0] = $pieData;

            return Json::encode(['dateList' => $dateList ,'task' => [$taskDetailData]]);
        }
        return $this->render('index' );
    }
}

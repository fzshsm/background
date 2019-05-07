<?php

namespace app\modules\statistics\controllers;

use app\controllers\Controller;
use app\modules\league\api\Match;
use app\modules\statistics\api\Finance;
use app\modules\statistics\api\Game;
use app\modules\statistics\api\Guess;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;

/**
 * Default controller for the `analyzestatistics` module
 */
class GuessController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex(){
        $request = \Yii::$app->request;
        if($request->isAjax) {
            $date = $request->get('date', date('Y-m-d', strtotime('-1 week')) . '_' . date('Y-m-d'));
            $search = $request->get('search',['ranktime' => '']);
            $pageNo = $request->get('start');
            $pageSize = $request->get('length');
            $order = $request->get('order', [['dir' => '']]);
            $pageNo = $pageNo / $pageSize + 1;
            if (!empty($date)) {
                list($begin, $end) = explode('_', $date);
            }
            if(!empty($order[0]['dir'])){
                $orderBy = $order[0]['dir'];
            }else{
                $orderBy = 'desc';
            }
            $guess = new Guess();
            if(!empty($search['rankTime'])){
                $rankTime = $search['rankTime'];
            }else{
                $rankTime = $end;
            }
            $responseData = [
                'draw' => $request->get('draw'),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => '',
                'rankTime' => $rankTime,
                'profitAmount' => 0,
                'deficitAmount' => 0,
                'guessAmount' => 0,
                'bets' => 0
            ];
            $response = $guess->guessStatistics($begin, $end, $rankTime, $pageNo, $pageSize, $orderBy);
            $rankList = isset($response['rankList']) ? $response['rankList'] : [];

            if (!empty($rankList)) {
                $responseData['recordsTotal'] = $rankList['count'];
                $responseData['recordsFiltered'] = $rankList['count'];
                $responseData['profitAmount'] = number_format($rankList['profitAmount']/10000,2);
                $responseData['deficitAmount'] = number_format($rankList['deficitAmount']/10000,2);
                $responseData['guessAmount'] = (string) number_format(abs(round($rankList['deficitAmount']/10000,2)) + round($rankList['profitAmount']/10000,2),2);

                $responseData['bets'] = $rankList['bets'];

                foreach ($rankList['data'] as $value) {
                    $value['profit_amount'] = number_format($value['profit_amount']/10000,2);
                    $value['home_amount'] = number_format($value['home_amount']/10000,2);
                    $value['guest_amount'] = number_format($value['guest_amount']/10000,2);
                    if($value['result'] == 1){
                        $value['result'] = $value['home_team'];
                    }elseif($value['result'] == 2){
                        $value['result'] = $value['guest_team'];
                    }elseif ($value['result'] == 3){
                        $value['result'] = '通吃';
                    }
                    $responseData['data'][] = $value;
                }
            } else {
                $responseData['error'] = $guess->getError();
            }
            return Json::encode($responseData);
        }
        return $this->render('index');
    }

    public function actionStatistics()
    {
        $request = \Yii::$app->request;
        $date = $request->get('date' , date('Y-m-d',strtotime('-6 days')).'_'  . date('Y-m-d'));
        $rankTime = $request->get('rankTime',date('Y-m-d'));
        $pageNo = $request->get('pageNo',1);
        $pageSize = $request->get('pageSize',10);
        if(!empty($date)){
            list($begin , $end) = explode('_' , $date);
        }

        $guess = new Guess();

        $data = $guess->guessStatistics($begin , $end, $rankTime, $pageNo, $pageSize);

        $deficitData = isset($data['deficitData']) ? $data['deficitData'] : [];
        $profitData = isset($data['profitData']) ? $data['profitData'] : [];
        $betData = isset($data['betData']) ? $data['betData'] : [];
        $guessAmount = isset($data['guessAmount']) ? $data['guessAmount'] : [];
        $profitAmount = isset($data['profitAmount']) ? $data['profitAmount'] : [];
        $deficitAmount = isset($data['deficitAmount']) ? $data['deficitAmount'] : [];

        $dateList = isset($data['dateList']) ? $data['dateList'] : [];

        for ($i=0;$i<count($deficitData);$i++){
            $deficitData[$i] = round($deficitData[$i]/10000,2);
            $profitData[$i] = round($profitData[$i]/10000,2);

            if(abs($deficitData[$i]/10000) > ($betData[$i]/10000)){
                $betData[$i] = (string) (abs($deficitData[$i]) - $profitData[$i]);
            }else{
                $betData[$i] = (string)(abs($deficitData[$i]) + $profitData[$i]);
            }
        }

        $data = [
            'name' => ['投注总额','赔付总额','盈利总额'],
            'statistic' => [ $betData,$deficitData, $profitData]
        ];

        $deficitAmount = round($deficitAmount/10000,2);
        $profitAmount = round($profitAmount/10000,2);
        $guessAmount = round($deficitAmount + $profitAmount,2);

        $overview = [
            'name' => ['投注总额','赔付总额','盈利总额'],
            'statistic' => [[$guessAmount],[$deficitAmount],[$profitAmount]],
        ];

        if($begin == $end){
            $overviewTime = [$end];
        }else{
            $overviewTime = [$begin.' - '.$end];
        }

        return Json::encode(['dateList' => $dateList , 'guess' => [$data], 'overview' => [$overview], 'overviewTime' => $overviewTime]);
    }
}

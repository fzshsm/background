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
class MonitorController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex(){
        $request = \Yii::$app->request;
        if($request->isAjax){
            $date = $request->get('date' , date('Y-m-d H:i:s',strtotime('-1 day')).'_'  . date('Y-m-d H:i:s'));
            if(!empty($date)){
                list($begin , $end) = explode('_' , $date);
            }

            $finance = new Finance();

            $data = $finance->monitorStatistics($begin , $end);

            $rechargeData = isset($data['rechargeData']) ? $data['rechargeData'] : [];
            $balanceData = isset($data['balanceData']) ? $data['balanceData'] : [];
            $useData = isset($data['useData']) ? $data['useData'] : [];
            $realTime = isset($data['realTime']) ? $data['realTime'] : [];
            $dateList = isset($data['dateList']) ? $data['dateList'] : [];

            $monitor = [
                'name' => ['用户总余额价值','狗粮总消耗价值','充值总额'],
                'statistic' => [$balanceData,$useData,$rechargeData]
            ];
            $pie = [
                'name' => ['用户总余额价值','狗粮总消耗价值','充值总额'],
                'statistic' => [
                    ['value' => isset($realTime['balance']) ? $realTime['balance'] : 0, 'name' => '用户总余额价值'],
                    ['value' => isset($realTime['use']) ? $realTime['use'] : 0, 'name' => '狗粮总消耗价值'],
                    ['value' => isset($realTime['recharge']) ? $realTime['recharge'] : 0, 'name' => '充值总额']
                ]
            ];

            return Json::encode(['dateList' => $dateList ,'monitor' => [$monitor,$pie]]);
        }
        return $this->render('index' );
    }
}

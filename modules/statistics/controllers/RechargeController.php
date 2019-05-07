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
class RechargeController extends Controller
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

            $data = $finance->rechargeStatistics($begin , $end);

            $dateList = isset($data['dateList']) ? $data['dateList'] : [];
            $wxCount = isset($data['wxCount']) ? $data['wxCount'] : [];
            $wxNum = isset($data['wxNum']) ? $data['wxNum'] :[];
            $aliPayCount = isset($data['aliPayCount']) ? $data['aliPayCount'] : [];
            $aliPayNum = isset($data['aliPayNum']) ? $data['aliPayNum'] : [];
            $overallCount = isset($data['overallCount']) ? $data['overallCount'] :[];
            $overallNum = isset($data['overallNum']) ? $data['overallNum'] : [];
            //$exchangeCount = isset($data['exchangeCount']) ? $data['exchangeCount'] : [];

            $overviewAr = isset($data['overviewAr']) ? $data['overviewAr'] : [];

            $rechargeData = [
                'name' => ['微信充值总额','支付宝充值总额','综合充值总额'],
                'statistic' => [
                    $wxCount,$aliPayCount,$overallCount
                ]
            ];

            $orderData = [
                'name' => ['微信充值订单数','支付宝充值订单数','综合充值订单数'],
                'statistic' => [$wxNum,$aliPayNum,$overallNum]
            ];

            $arData = [
                'name' => ['ARUP值'],
                'statistic' => [$overviewAr]
            ];

            $recharge = [$rechargeData, $orderData,$arData];
            return Json::encode(['dateList' => $dateList ,'recharge' => $recharge]);
        }
        return $this->render('index' );
    }
}

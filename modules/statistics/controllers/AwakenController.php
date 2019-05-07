<?php

namespace app\modules\statistics\controllers;

use app\controllers\Controller;
use app\modules\league\api\Match;
use app\modules\statistics\api\Awaken;
use app\modules\statistics\api\Behavior;
use app\modules\statistics\api\Finance;
use app\modules\statistics\api\Game;
use yii\helpers\Json;

/**
 * Default controller for the `analyzestatistics` module
 */
class AwakenController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex(){
        $request = \Yii::$app->request;
        if($request->isAjax){
            $type = $request->get('type' , 'week');
            $date = $request->get('date');

            $startTime = $endTime = '';
            if(!empty($date)){
                list($startTime , $endTime) = explode('_' , $date);
            }

            $awaken = new Awaken();

            $data = $awaken->awakenStatistic($type,$startTime,$endTime);

            $gameCount = isset($data['gameCount']) ? $data['gameCount'] : [];
            $userCount = isset($data['userCount']) ? $data['userCount'] : [];
            $leagueName = isset($data['leagueName']) ? $data['leagueName'] : [];
            $newUserCount = isset($data['newUserCount']) ? $data['newUserCount'] : [];
            $dateList = isset($data['dateList']) ? $data['dateList'] : [];
            $maxUserCount = isset($data['maxUserCount']) ? $data['maxUserCount'] : 0;
            $maxGameCount = isset($data['maxGameCount']) ? $data['maxGameCount'] : 0;

            $keys = [];
            if(!empty($gameCount)){
                $keys = array_keys($gameCount);
            }

            $names = [];
            foreach ($keys as $value){
                array_push($names,$leagueName[$value]);
            }

            $count = count($dateList);

            $masterGameData = isset($gameCount[7]) ? $gameCount[7]:[];
            $eliteGameData = isset($gameCount[8]) ? $gameCount[8]:[];
            $reserveGameData = isset($gameCount[96]) ? $gameCount[96]:[];

            $masterUserData = isset($userCount[7]) ? $userCount[7]:[];
            $eliteUserData = isset($userCount[8]) ? $userCount[8]:[];
            $reserveUserData = isset($userCount[96]) ? $userCount[96]:[];

            $masterNewUserData = isset($newUserCount[7]) ? $newUserCount[7]:[];
            $eliteNewUserData = isset($newUserCount[8]) ? $newUserCount[8]:[];
            $reserveNewUserData = isset($newUserCount[96]) ? $newUserCount[96]:[];

            $data = [];
            for ($i=0;$i<$count;$i++){
                $masterData = [
                    isset($masterGameData[$i]) ? $masterGameData[$i] :0,
                    isset($masterUserData[$i]) ? $masterUserData[$i]: 0,
                    isset($masterNewUserData[$i]) ? $masterNewUserData[$i]: 0,
                    '大师组',
                    $dateList[$i]
                ];

                $eliteData = [
                    isset($eliteGameData[$i]) ? $eliteGameData[$i] :0,
                    isset($eliteUserData[$i]) ? $eliteUserData[$i]: 0,
                    isset($eliteNewUserData[$i]) ? $eliteNewUserData[$i]: 0,
                    '精英组',
                    $dateList[$i]
                ];

                $reserveData = [
                    isset($reserveGameData[$i]) ? $reserveGameData[$i] :0,
                    isset($reserveUserData[$i]) ? $reserveUserData[$i]: 0,
                    isset($reserveNewUserData[$i]) ? $reserveNewUserData[$i]: 0,
                    '预备组',
                    $dateList[$i]
                ];

                array_push($data,[$masterData,$eliteData,$reserveData]);
            }

            return Json::encode(['dateList' => $dateList ,'awaken' => $data, 'leagueName' => $names,'maxUserCount' => $maxUserCount,'maxGameCount' => $maxGameCount]);
        }
        return $this->render('index' );
    }

}

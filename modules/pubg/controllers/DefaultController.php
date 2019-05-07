<?php
namespace app\modules\pubg\controllers;


use app\controllers\Controller;
use app\helper\PubgParam;
use app\modules\league\api\PubgCustom;
use app\modules\league\api\PubgData;
use app\modules\league\api\PubgLeague;
use app\modules\league\api\PubgMatch;
use app\modules\pubg\api\Custom;
use function GuzzleHttp\Psr7\str;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;
use yii\helpers\VarDumper;
use yii\web\UploadedFile;
use app\components\QCloudCos;
use app\modules\league\api\GloryMatch;


class DefaultController extends Controller {

    public function actionIndex(){
        $data =  [];
        $request = \Yii::$app->request;
        $page = $request->get('page'  , 1);
        $pageSize = $request->get('pageSize' , 15);
        $name = $request->get('name');

        $pubgCustom = new Custom();
        $customDatas = $pubgCustom->datalist($name , $page , $pageSize);

        if(empty($customDatas)){
            \Yii::$app->session->setFlash( 'error' , $pubgCustom->getError());
        }else{
            foreach ($customDatas['results'] as $value){
                $data[$value['id']] = $value;
            }
        }

        $totalCount = isset( $customDatas['totalSize'] ) ? $customDatas['totalSize'] : 0;
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

        return $this->render('index' , ['dataProvider' => $dataProvider]);
    }

    public function actionCreate(){
        $request = \Yii::$app->request;
        $mode = $request->get('mode',1);
        $pubgCustom = new Custom();

        $data = [];
        if($request->isPost){
            $postData = $request->post();
            $pubgParams = new PubgParam();
            $params = $pubgParams->getParam();

            foreach ($params as $key => $value){

                if(strpos($key,'Wbow')){
                    $params[$key] = $postData['Wbow'];
                }

                if(strpos($key,'Wflaregun')){
                    $params[$key] = $postData['Wflaregun'];
                }

                if(strpos($key,'Aforegrip')){
                    $params[$key] = $postData['Aforegrip'];
                }

                if(isset($postData[$key])){

                    if(in_array($postData[$key],['Basic','Pistols','Shotguns','Western','Crossbow','FpsOnly','TpsOnly'])){
                        $postValue = $postData[$key];
                    }else{
                        $postValue = trim(str_replace(['x','%','s'],'',$postData[$key]));
                    }

                    $stringParams = $pubgParams->getParamsString();

                    if(!in_array($key,$stringParams) && is_numeric($postValue)){
                        $postValue = (float)$postValue;
                    }

                    if(in_array($key,['FPSOnly', 'PublicSpectating', 'KillerSpectating', 'Dbno', 'RedZoneIsActive','BlueZoneStatic','TeamElimination','FlareGunIsActive'])){
                        $params[$key] = $this->getPubgBoole($postValue);
                    }elseif ($key == 'MapId'){
                        $params[$key] = $this->getMapIp($postValue);
                    }else{
                        $params[$key] = $postValue;
                    }
                }

                if(strpos($key,'LandRatio')){
                    if($postData['mode'] == 3){
                        $params[$key] = 0;
                        continue;
                    }
                }
            }

            if($postData['mode'] == 3){
                $params['KillerSpectating'] = false;
                $params['CarePackageFreq'] = 1;
            }

            if($postData['mode'] == 2){
                $params['ZombieCameraView'] = 'FpsAndTps';
            }

            $params = $this->getPubgMode($params,$postData);

//            if($params['Mode'] == 'ZOMBIE'){
//                $executeArgument = $pubgParams->getZombieExecuteArgument();
//            }else
            if ($params['Mode'] == 'WAR'){
                $executeArgument = $pubgParams->getWarExecuteArgument();
            }else{
                $executeArgument = $pubgParams->getNormalExecuteArgument();
            }

            $weapons = $pubgParams->getWeapon();

            $sendParams = $this->getExecuteArgument($params,$executeArgument,$weapons);
            //var_dump(Json::encode($sendParams,JSON_UNESCAPED_SLASHES));exit;
            $sendParams = base64_encode(Json::encode($sendParams,JSON_UNESCAPED_SLASHES));
            //var_dump($sendParams);exit;
            $appParam = $this->getAppParam($postData);

            $sendData = [
                'name'=> $postData['name'],
                'configVal' => $sendParams,
                'remark' => $postData['remark'],
                'mode' => $appParam['mode'],
                'region' => $appParam['region'],
                'perspective' => $appParam['perspective'],
            ];

            if($pubgCustom->update($sendData)){
                \Yii::$app->session->setFlash('success' , "自定义配置成功！");
            }else{
                \Yii::$app->session->setFlash('error' , $pubgCustom->getError());
            }
        }

        $regionList = $this->getPubgRegionList();

        if($mode == 3){
            $data['mode'] = 3;
        }

        return $this->render('create',['data' => $data,'regionList' => $regionList, 'mode' => $mode]);
    }

    public function actionUpdate($id){
        $request = \Yii::$app->request;
        $mode = $request->get('mode');

        $pubgCsutom = new Custom();

        if($request->isPost){
            $postData = $request->post();
            $pubgParams = new PubgParam();
            $params = $pubgParams->getParam();

            $postData['mode'];$postData['Region'];$postData['FPSOnly'];

            foreach ($params as $key => $value){
                if(isset($postData[$key])){

                    if(in_array($postData[$key],['Basic','Pistols','Shotguns','Western','Crossbow','FpsOnly','TpsOnly'])){
                        $postValue = $postData[$key];
                    }else{
                        $postValue = trim(str_replace(['x','%','s'],'',$postData[$key]));
                    }

                    if(is_numeric($postValue)){
                        $postValue = (float)$postValue;
                    }

                    if(in_array($key,['FPSOnly', 'PublicSpectating', 'KillerSpectating', 'Dbno', 'RedZoneIsActive','BlueZoneStatic','TeamElimination'])){
                        $params[$key] = $this->getPubgBoole($postValue);
                    }elseif ($key == 'MapId'){
                        $params[$key] = $this->getMapIp($postValue);
                    }else{
                        $params[$key] = $postValue;
                    }
                }
            }

            if($postData['mode'] == 3){
                $params['KillerSpectating'] = false;
                $params['CarePackageFreq'] = 1;
            }

            if($postData['mode'] == 2){
                $params['ZombieCameraView'] = 'FpsAndTps';
            }

            $params = $this->getPubgMode($params,$postData);

            if ($params['Mode'] == 'WAR'){
                $executeArgument = $pubgParams->getWarExecuteArgument();
            }else{
                $executeArgument = $pubgParams->getNormalExecuteArgument();
            }

            $weapons = $pubgParams->getWeapon();

            $sendParams = $this->getExecuteArgument($params,$executeArgument,$weapons);

            $sendParams = base64_encode(Json::encode($sendParams,JSON_UNESCAPED_SLASHES));

            $appParam = $this->getAppParam($postData);

            $sendData = [
                'id' => $id,
                'name'=> $postData['name'],
                'configVal' => $sendParams,
                'remark' => $postData['remark'],
                'mode' => $appParam['mode'],
                'region' => $appParam['region'],
                'perspective' => $appParam['perspective']
            ];

            if($pubgCsutom->update($sendData)){
                \Yii::$app->session->setFlash('success' , "自定义配置成功！");
            }else{
                \Yii::$app->session->setFlash('error' , $pubgCsutom->getError());
            }
        }

        $detail = $pubgCsutom->detail($id);
        $data = Json::decode(base64_decode($detail['configVal']));

        unset($data['ExecuteArgument']);

        foreach ($data as $k => $v){
            if(in_array($k,['FPSOnly', 'PublicSpectating', 'KillerSpectating', 'Dbno', 'RedZoneIsActive','BlueZoneStatic','TeamElimination','FlareGunIsActive'])){
                $data[$k] = $this->getPubgBooleDetail($v);
            }elseif ($k == 'MapId'){
                $data[$k] = $this->getMapIdDetail($v);
            }
        }

        $data = $this->getPubgModeDetail($data);

        $data['name'] = $detail['name'];
        $data['remark'] = $detail['remark'];
        $data['id'] = $detail['id'];

        $regionList = $this->getPubgRegionList();

        if($mode == 3){
            $data['mode'] = 3;
        }
        //VarDumper::dump($data);exit;

        return $this->render('update',['data' => $data, 'regionList' => $regionList,'mode' => $mode]);
    }

    public function actionDelete($id){
        $response = ['status' => 'success' , 'message' => '' ];
        $pubgCustom = new Custom($id);

        $data = ['id' => $id];
        if($pubgCustom->del($data) == false){
            $response['status'] = 'error';
            $response['message'] = $pubgCustom->getError();
        }
        return Json::encode($response);
    }

    protected function getPubgBoole($value){
        if($value == 1){
            return true;
        }else{
            return false;
        }
    }

    //获取地图参数
    protected function getMapIp($value){
        if($value == 1){
            return '/Game/Maps/Erangel/Erangel_Main';
        }elseif($value == 2){
            return '/Game/Maps/Desert/Desert_Main';
        }else{
            return '/Game/Maps/Savage/Savage_Main';
        }
    }

    //获取游戏模式
    protected function getPubgMode($pubgParam,$postData){
//        $pubgParam['IsWarMode'] = false;
//        $pubgParam['IsZombie'] = false;

        if($postData['mode'] == 2){
            $pubgParam['Mode'] = 'ZOMBIE';
        }elseif($postData['mode'] == 3){
            $pubgParam['Mode'] = 'WAR';
        }elseif($postData['mode'] == 4){
            $pubgParam['Mode'] = 'BATTLEROYALE_ESPORTS';
        }else{
            $pubgParam['Mode'] = 'BATTLEROYALE_CUSTOM';
        }

        return $pubgParam;
    }

    protected function getMapIdDetail($value){
        if($value == '/Game/Maps/Erangel/Erangel_Main'){
            return 1;
        }elseif($value == '/Game/Maps/Desert/Desert_Main'){
            return 2;
        }else{
            return 3;
        }
    }

    protected function getPubgModeDetail($pubgParam){
        $pubgParam['mode'] = 1;
        if($pubgParam['Mode'] == 'ZOMBIE'){
            $pubgParam['mode'] = 2;
        }elseif ($pubgParam['Mode'] == 'WAR'){
            $pubgParam['mode'] = 3;
        }elseif ($pubgParam['Mode'] == 'BATTLEROYALE_ESPORTS'){
            $pubgParam['mode'] = 4;
        }

        return $pubgParam;
    }

    protected function getPubgBooleDetail($value){
        if($value === true){
            return 1;
        }

        return 2;
    }

    protected function getPubgRegionList(){
        return [
            'AS Server' => '亚服',
            'NA Server' => '北美服',
            'OC Server' => '澳服',
            'SEA Server' => '东南亚服',
            'SA Server' => '南美服',
            'EU Server' => '欧服',
            'KRJP Server' => '日韩服',
            'KAKAO Server' => 'kakao服'
        ];
    }

    //获取json配置
    protected function getExecuteArgument($pubgParams,$execumentArgument,$weapons){
        $stringParameters = $execumentArgument['StringParameters'];

        $execumentArgument['MapName'] = $pubgParams['MapId'];
        $execumentArgument['TeamCount'] = $pubgParams['TeamSize'];
        $execumentArgument['MinPlayerCount'] = $pubgParams['MaxPlayers'];
        $execumentArgument['MaxPlayerCount'] = $pubgParams['MaxPlayers'];

        $isCar = $pubgParams['Car'];
        $isWarMode = $pubgParams['Mode'];

        foreach ($stringParameters as $key => $value){
            $firstKey = $value['First'];

            if(strpos($firstKey,'IsZombie') !== false){
                if($pubgParams['Mode'] == 'ZOMBIE'){
                    $stringParameters[$key]['Second'] = true;
                    continue;
                }
            }

            if(strpos($firstKey,'ItemSpawnCategory') !== false){
                if($isWarMode == 'BATTLEWAR_CUSTOM'){
                    $stringParameters[$key]['Second'] = '1';
                    continue;
                }
                $data = explode('.',$firstKey);
                if(isset($pubgParams[$data[1]])){
                    $stringParameters[$key]['Second'] = (string)$pubgParams[$data[1]];
                    continue;
                }
            }

            if(strpos($firstKey,'ItemSpawnSubCategory') !== false){
                $replaceFirstKey = explode('.',$firstKey);
                $findKey = $weapons[$replaceFirstKey[1]].$replaceFirstKey[1];

                $stringParameters[$key]['Second'] = (string)$pubgParams[$findKey];
                continue;
            }

            if(strpos($firstKey,'ThingSpawnGroupRatio') !== false){
                if($isCar){
                    $stringParameters[$key]['Second'] = '1';

                }else{
                    $stringParameters[$key]['Second'] = '0';
                }
                continue;
            }

            if(strpos($firstKey,'MultiplierCarePackage') !== false){
                $stringParameters[$key]['Second'] = (string)$pubgParams['CarePackageFreq'];
                continue;
            }

            if(strpos($firstKey,'ZombieCameraViewBehaviour') !== false){
                $stringParameters[$key]['Second'] = (string)$pubgParams['ZombieCameraView'];
                continue;
            }

            if(strpos($firstKey,'CarePackageNextSpawnTime') !== false){
                $stringParameters[$key]['Second'] = (string)$pubgParams['CarePackagePeriod'];
                continue;
            }

            if(strpos($firstKey,'CarePackageType') !== false){
                $stringParameters[$key]['Second'] = 'CarepackageKit_'.$pubgParams['CarePackageType'];
                continue;
            }

            if(strpos($firstKey,'RespawnKit') !== false){
                $stringParameters[$key]['Second'] = 'RespawnKit_'.$pubgParams['RespawnEquipment'];
                continue;
            }

            if(strpos($firstKey,'StaticBlueZoneSize') !== false){
                $stringParameters[$key]['Second'] = (string)$pubgParams['BlueZoneSize'];
            }

            if(strpos($firstKey,'Phase') !== false){
                $replaceFirstKey = str_replace('.','_',$firstKey);

                if(strpos($replaceFirstKey,'package') != false){
                    $replaceFirstKey = str_replace('package','Package',$replaceFirstKey);
                }
                $stringParameters[$key]['Second'] = (string)$pubgParams[$replaceFirstKey];
                continue;
            }

            if(isset($pubgParams[$firstKey])){
                $thisValue = $pubgParams[$firstKey];
                if(is_bool($pubgParams[$firstKey])){
                    if($pubgParams[$firstKey] === true){
                        $thisValue = 'true';
                    }else{
                        $thisValue = 'false';
                    }
                }

                if(is_int($pubgParams[$firstKey])){
                    $thisValue = (string)$pubgParams[$firstKey];
                }

                $stringParameters[$key]['Second'] = (string)$thisValue;
            }
        }

        if($pubgParams['Mode'] == 'ZOMBIE'){
            array_push($stringParameters,['First' => 'ZombieCameraViewBehaviour','Second' => $pubgParams['ZombieCameraView']]);
        }

//        if($pubgParams['FPCOnly'] === true){
//            array_push($stringParameters,["First"=>"CameraViewBehaviour", "Second"=>"FpsOnly"]);
//        }

        $execumentArgument['StringParameters'] = $stringParameters;

        $pubgParams['ExecuteArgument'] = Json::encode($execumentArgument,JSON_UNESCAPED_SLASHES);

        return $pubgParams;
    }

    protected function getAppParam($postData){
        $mode = $postData['mode'];
        $region = $postData['Region'];
        $FPSOnly = $postData['FPSOnly'];

        $regionList = $this->getPubgRegionList();

        if ($mode == 2){
            $modeName  = '丧尸';
        }elseif ($mode == 3){
            $modeName = '战争';
        }elseif($mode == 4){
            $modeName = '电竞';
        }else{
            $modeName = '普通';
        }

        $regionName = $regionList[$region];

        if($FPSOnly == 1){
            $perspective = '第一人称';
        }else{
            $perspective = '第三人称';
        }

        $data = [
            'mode' => $modeName,
            'region' => $regionName,
            'perspective' => $perspective
        ];

        return $data;
    }
}
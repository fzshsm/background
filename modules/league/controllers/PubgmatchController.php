<?php
namespace app\modules\league\controllers;


use app\controllers\Controller;
use app\helper\PubgParam;
use app\modules\league\api\PubgCustom;
use app\modules\league\api\PubgData;
use app\modules\league\api\PubgLeague;
use app\modules\league\api\PubgMatch;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;
use yii\helpers\VarDumper;
use yii\web\UploadedFile;
use app\components\QCloudCos;
use app\modules\league\api\GloryMatch;


class PubgmatchController extends Controller {

    public function actionIndex($seasonId){
        $data =  [];
        $request = \Yii::$app->request;
        $page = $request->get('page'  , 1);
        $pageSize = $request->get('pageSize' , 15);
        $seasonName = $request->get('seasonName');
        $date = $request->get('date');
        $searchType = $request->get('searchType');
        $content = $request->get('content');
        $leagueId = $request->get('leagueId');

        $time = 0;
        if(!empty($date)){
            list($begin , $end) = explode('至' , $date);
            $time = $begin.','.$end;
        }

        $match = new PubgMatch();
        $matchDatas = $match->datalist($seasonId , $page , $pageSize,0,$time, $searchType, $content);

        if(empty($matchDatas)){
            \Yii::$app->session->setFlash( 'error' , $match->getError());
        }else{
            foreach ($matchDatas['results'] as $value){
                $leagueId = $value['leagueId'];
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

        return $this->render('index' , ['seasonName' => $seasonName , 'seasonId' => $seasonId , 'dataProvider' => $dataProvider, 'leagueId' => $leagueId]);
    }
    
    public function actionCreate($seasonId){
        $request = \Yii::$app->request;
        $seasonName = $request->get('seasonName');
        $leagueId = $request->get('leagueId');
        $mapList = $this->getMap();
        $configList = $this->getPubgConfigList();
        $obList = $this->getObList();

        $pubgLeague = new PubgLeague($leagueId);

        $leagueDetail = $pubgLeague->detail();

        if($request->isPost){
            $match = new PubgMatch();
            $postData = $request->post();
            $postData['seasonId'] = $seasonId;
            $postData['createUserId'] = \Yii::$app->user->identity->id;
            $customerIds = isset($postData['ob']) ? $postData['ob'] : [];
            $tagData = isset($postData['tags']) ? $postData['tags'] : [];

            $tags = '';
            if(!empty($tagData)){
                if(strpos($tagData,'，')){
                    $tagData = str_replace('，',',',$tagData);
                }

                $tagData = preg_split("/[[:punct:]]/i",$tagData);
                foreach ($tagData as $value){
                    if(empty($tags)){
                        $tags = trim($value);
                    }else{
                        $tags = $tags.','.trim($value);
                    }
                }
            }

            $postData['tags'] = $tags;

            if(!empty($customerIds)){
                $customerIds = implode($customerIds,',');
            }

            $postData['ob'] = $customerIds;

            \Yii::trace($postData , 'match.create');
            if( $match->create($postData) ){
                \Yii::$app->session->setFlash('success' , "创建场次成功！");
            }else{
                \Yii::$app->session->setFlash('error' , $match->getError());
            }
        }

        return $this->render( 'create' , ['seasonId' =>$seasonId ,  'seasonName' => $seasonName,'mapList' => $mapList,
            'obList' => $obList, 'configList' => $configList, 'leagueId' => $leagueId, 'leagueDetail' => $leagueDetail]);
    }
    
    public function actionUpdate($id){
        $request = \Yii::$app->request;
        $season = new PubgMatch($id);
        $seasonName = $request->get('seasonName');
        $seasonId = $request->get('seasonId');
        $leagueId = $request->get('leagueId');
        $mapList = $this->getMap();

        $pubgLeague = new PubgLeague($leagueId);

        $leagueDetail = $pubgLeague->detail();

        $obList = $this->getObList();
        $configList = $this->getPubgConfigList();

        if($request->isPost){
            $postData = $request->post();
            $postData['matchId'] = $id;
            $postData['updateUserId'] = \Yii::$app->user->identity->id;
            $customerIds = isset($postData['ob']) ? $postData['ob'] : [];
            if(!empty($customerIds)){
                $customerIds = implode($customerIds,',');
            }
            $tagData = isset($postData['tags']) ? $postData['tags'] : [];

            $tags = '';
            if(!empty($tagData)){

                if(strpos($tagData,'，')){
                    $tagData = str_replace('，',',',$tagData);
                }

                $tagData = preg_split("/[[:punct:]]/i",$tagData);
                foreach ($tagData as $value){
                    if(empty($tags)){
                        $tags = trim($value);
                    }else{
                        $tags = $tags.','.trim($value);
                    }
                }
            }

            $postData['tags'] = $tags;

            $postData['ob'] = $customerIds;

            \Yii::trace($postData , 'season.update');
            if($season->update($postData)){
                \Yii::$app->session->setFlash('success' , "修改赛季场次信息成功！");
            }else{
                \Yii::$app->session->setFlash('error' , $season->getError());
            }
        }
        $matchName = '赛事';
        $data = $season->detail();
        if(empty($data)){
            \Yii::$app->session->setFlash('dataError' , $season->getError());
        }

        $obIds = isset($data['ob']) ? $data['ob'] : [];
        $data['matchId'] = $id;
        return $this->render( 'update' , ['matchName' => $matchName , 'data' => $data,'seasonName' => $seasonName,'seasonId' => $seasonId,
            'mapList' => $mapList, 'obList' => $obList,'obIds' => $obIds, 'configList' => $configList, 'leagueId' => $leagueId, 'leagueDetail' => $leagueDetail]);
    }
    
    protected function getMap(){
        $match = new PubgMatch();
        $data = [];

        $mapData = $match->mapList();
        foreach ($mapData as $value){
            $data[$value['id']] = $value['val'];
        }

        return $data;
    }

    //预约记录保存页面
    public function actionReservation(){
        $request = \Yii::$app->request;
        $id = $request->get('id');
        $leagueId = $request->get('leagueId');
        $seasonId = $request->get('seasonId');
        $robotName = $request->get('robotName');
        $match = new PubgMatch($id);
        $pubgData = new PubgData($id);
        $pubgLeague = new PubgLeague($leagueId);

        $leagueDetail = $pubgLeague->detail();

        $leagueModel = $leagueDetail['leagueModel'];

        if($request->isPost){
            $isRecord = $request->post('isRecord');
            $server = $request->post('server');
            //线下进行比赛先进行游戏记录保存
            if($isRecord == 0 && !empty($server)){
                $mode = $request->post('mode');
                $queue = $request->post('queue');
                $participate = $request->post('participate');
                $map = $request->post('map');
                $gameBeginTime = $request->post('startTime');

                $gameRecord = [
                    'leagueId' => $leagueId,
                    'seasonId' => $seasonId,
                    'gid' => $id,
                    'server' => $server,
                    'mode' => $mode,
                    'map' => $map,
                    'participate' => $participate,
                    'queue' => $queue,
                    'gameBeginTime' => strtotime(trim($gameBeginTime)),
                    'createUser' => \Yii::$app->user->getIdentity()->username,

                ];

                if($pubgData->saveGameRecord($gameRecord)){
                    //获取游戏记录
                    $data = $pubgData->teamInfo();
                    if(empty($data) && empty($robotName)){
                        return $this->render('reservat', ['res' => [],'isRecord' => 0]);
                    }

                    $res = $match->reserDetail();

                    if(isset($res['members']) && empty($res['members'])){
                        \Yii::$app->session->setFlash('dataError', '未获取到参赛人员数据');
                    }

                    if(empty($res)){
                        $res['teamCount'] = 0;
                        \Yii::$app->session->setFlash('dataError', '未获取到参赛人员数据');
                    }
                    $res['gid'] = $data['matchInfo']['gid'];//游戏编号
                    $res['createTime'] = $data['matchInfo']['createTime'];//游戏编号
                    $res['leagueId'] = $leagueId;
                    $res['seasonId'] = $seasonId;
                    if(empty($res['createTime'])){
                        \Yii::$app->session->setFlash('error','未查到游戏记录，无法进行数据录入');
                    }
                    return $this->render('reservat', ['res' => $res,'isRecord' => 1]);
                }else{
                    \Yii::$app->session->setFlash('error' , $pubgData->getError());
                    return $this->render('reservat', ['res' => [],'isRecord' => 0]);
                }
            }
            $teamInfo = [];
            $settleInfo = [];
            $teamid = array_unique($request->post('teamid'));
            $matchId = $request->post('matchId');
            $imgNum = $request->post('imgNum');
            $createTime = $request->post('createTime');

            //图片保存
            $imageNum = 0;
            for($i=1;$i<=$imgNum;$i++){
                $imageName = 'file_'.$i;
                $image = $this->getCover($imageName);

                if($image != false){
                    $imageNum++;
                    $this->uploadCover($id,$imageName,$createTime,$i);
                }else{
                    $imageFile = 'image_'.$i;
                    if($request->post($imageFile)){
                        $imageNum++;
                    }
                }
            }

            if(!empty($teamid) && is_array($teamid)){
                $teamRank = $request->post('teamRank');
                $steamId = $request->post('steamId');
                $killcount = $request->post('killcount');

                foreach($teamid as $key => $val){
                    if(isset($teamRank[$val][0])){
                        $settleInfo[$val]['teamRank'] = $teamRank[$val][0];
                        foreach($steamId[$val] as $k => $v){
                            if(isset($killcount[$val][$v][0])){
                                $settleInfo[$val]['teamPlayers'][$v]['killNum'] = $killcount[$val][$v][0];
                            }
                        }
                    }
                }

                foreach ($settleInfo as $key => $value){
                    foreach ($value['teamPlayers'] as $k => $v){
                        if($leagueModel == 2){
                            $key = 0;
                        }
                        $teamInfo[] = [
                            'teamNumber' => $key,
                            'teamRank' => $value['teamRank'],
                            'killNum' => $v['killNum'],
                            'nickname' => $k
                        ];
                    }
                }
            }
            $result = [
                'gid'=>$matchId,
                'updateUser' => \Yii::$app->user->getIdentity()->username,
                'leagueId' => $leagueId,
                'seasonId' => $seasonId,
                'teamInfo' => $teamInfo,
                'imgNum' => $imageNum
            ];

            if($pubgData->save($result)){
                \Yii::$app->session->setFlash('success' , "录入游戏结果成功！");
                //发送最后结果

                $gameSettlement = $this->filterData($settleInfo,$matchId,$leagueModel);

                if($leagueModel == 2){
                    $settlement = $match->singleMatchSettlement(Json::encode($gameSettlement));
                }else{
                    $settlement = $match->matchSettlement(Json::encode($gameSettlement));
                }

                if(isset($settlement['code']) && ($settlement['code'] != 200)){
                    \Yii::$app->session->setFlash('settlementError' , isset($settlement['msg']) ? $settlement['msg'] : '');
                }

            }else{
                \Yii::$app->session->setFlash('error' , $pubgData->getError());
            }
        }

        //获取游戏记录
        $data = $pubgData->teamInfo();
        if(empty($data) && empty($robotName)){
            return $this->render('reservat', ['res' => [],'isRecord' => 0, 'leagueModel' => $leagueModel]);
        }

        if($leagueModel == 2){
            $matchUsers = $match->matchUserDetail();
            $teamCount = 0;
            $members = [];
            if(!empty($matchUsers)){
                $res['teamCount'] = count($matchUsers);

                foreach ($matchUsers as $key => $matchUser){
                    $members[] = [
                        'nickname' => $matchUser['nickname'],
                        'seatNo' => $key,
                        'userId' => $matchUser['userId']
                    ];
                }
                $res['members'] = $members;
            }else{
               $res = [];
            }

        }else{
            $res = $match->reserDetail();
        }

        if(isset($res['members']) && empty($res['members'])){
            \Yii::$app->session->setFlash('dataError', '未获取到参赛人员数据');
        }

        $reso = $res;

        if(!empty($data['teamInfo'])){
            $members = isset($res['members']) ? $res['members'] : [];
            foreach($members as $key => $val){
                $teamRank = $this->getRank($val['nickname'],$data);
                $killNum = $this->getKill($val['nickname'],$data);
                if($teamRank){
                    $res['members'][$key]['teamRank'] = $teamRank;
                }
                if($killNum !== null){
                    $res['members'][$key]['killNum'] = $killNum;
                }
            }

            $members = isset($res['members']) ? $res['members'] : [];
            $newresult['members'] = [];
            foreach($members as $k => $v){
                if(isset($v['teamRank'])){
                    $newresult['members'][$v['teamRank']][$k]['nickname'] = $v['nickname'];
                    //$newresult['members'][$v['teamRank']][$k]['steamId'] = $v['steamId'];
                    $newresult['members'][$v['teamRank']][$k]['seatNo'] = $v['seatNo'];
                    $newresult['members'][$v['teamRank']][$k]['teamRank'] = $v['teamRank'];
                    if(isset($v['killNum'])){
                        $newresult['members'][$v['teamRank']][$k]['killNum'] = $v['killNum'];
                    }
                }
            }
            $createTime = date('Y-m-d',$data['matchInfo']['createTime']);
            $newresult['images'] = [];

            for ($i=1;$i<=$data['matchInfo']['imgNum'];$i++){
                $imageUrl = "http://starrank-1254164914.file.myqcloud.com/data/pubg/match/".$createTime."/".$id.'-'.$i.".png?".time();
                array_push($newresult['images'],$imageUrl);
            }

            $newresult['memberinfo'] = isset($reso['members']) ? $reso['members'] : [];
            $newresult['teamCount'] = isset($res['teamCount']) ? $res['teamCount'] : 0;
            $newresult['createTime'] = $createTime;
            $newresult['gid'] = $data['matchInfo']['gid'];
            $newresult['leagueId'] = $leagueId;
            $newresult['seasonId'] = $seasonId;

            return $this->render('reservatshow', ['res' => $newresult,'isRecord' => 1, 'leagueModel' => $leagueModel]);
        }else {
            if(empty($res)){
                $res['teamCount'] = 0;
                \Yii::$app->session->setFlash('dataError', '未获取到参赛人员数据');
            }
            $res['gid'] = $data['matchInfo']['gid'];//游戏编号
            $res['createTime'] = $data['matchInfo']['createTime'];//游戏编号
            $res['leagueId'] = $leagueId;
            $res['seasonId'] = $seasonId;
            if(empty($res['createTime'])){
                \Yii::$app->session->setFlash('error','未查到游戏记录，无法进行数据录入');
            }

            return $this->render('reservat', ['res' => $res,'isRecord' => 1,'leagueModel' => $leagueModel]);
        }
    }

    protected function getRank($rs,$data){
        $teamRank = '';
        foreach($data['teamInfo'] as $key => $val){
            if($val['nickname'] == $rs){
                $teamRank = $val['teamRank'];
                break;
            }
        }
        return $teamRank;
    }

    protected function getKill($rs,$data){
        $killNum = '';
        foreach($data['teamInfo'] as $key => $val){
            if($val['nickname'] == $rs){
                $killNum = $val['killNum'];
                break;
            }
        }
        return $killNum;
    }

    protected function getCover($name){
        $cover = UploadedFile::getInstanceByName($name);
        return !empty($cover) ? $cover : false;
    }

    protected function uploadCover($matchId,$name, $createTime = '',$num = 1){
        try{
            $cover = $this->getCover($name);
            if(empty($cover)){
                return false;
            }
            $qCloudCos = new QCloudCos();
            $srcPath = $cover->tempName;
            if(strpos($createTime,'-') == false){
                $createTime = date('Y-m-d',$createTime);
            }
            $dstPath = "/data/pubg/match/".$createTime."/".$matchId.'-'.$num.".png";

            $result = $qCloudCos->upload(QCloudCos::BUCKET_NAME , $srcPath , $dstPath,null,null,0);

            if(!empty($result) && isset($result['code'])){
                \Yii::trace($result , 'Match.Upload.IMG');
                if($result['code'] != 0){
                    throw new \Exception($result['message']);
                }
                return $result['data']['access_url'];
            }
        }catch( \Exception $e){
            $this->_processFileError = $e->getMessage();
            \Yii::warning($this->_processFileError , 'League.Upload.IMG');
        }
        return false;

    }

    protected function getObList(){
        $pubgMatch = new PubgMatch();

        $data = [];
        $response = $pubgMatch->customer();

        if(!is_array($response) || empty($response)){
            $response = [];
        }

        foreach ($response as $value){
            $data[$value['roleOpenId']] = isset($value['roleOpenId'])?$value['roleOpenId']:'';
        }

        return $data;

    }

    protected function filterData($settleInfo,$matchId,$leagueModel = 1){
        $pubgMatch = new PubgMatch($matchId);

        if($leagueModel == 2){
            $userResponse = $pubgMatch->matchUserDetail();

            $userInfo= [];
            foreach ($userResponse as $value){
                $userInfo[$value['nickname']] = $value['userId'];
            }

            foreach ($settleInfo as $key => $value){
                foreach ($value['teamPlayers'] as $k => $v){
                    $matchInfo[] = [
                        'userId' => isset($userInfo[$k]) ? $userInfo[$k] : '',
                        'killCount' => $v['killNum'],
                        'nickname' => $k,
                        'rank' => $value['teamRank'],

                    ];
                }

            }

            $data = [
                'matchId' => $matchId,
                'members' => $matchInfo
            ];
        }else{
            $userResponse = $pubgMatch->reserDetail();
            $members = isset($userResponse['members']) ? $userResponse['members'] : [];
            $userInfo = [];

            foreach ($members as $member){
                $userInfo[$member['nickname']] = $member['userId'];
            }

            $matchInfo = [];

            foreach ($settleInfo as $key => $value){
                $teamPlayers = [];
                foreach ($value['teamPlayers'] as $k => $v){
                    $teamPlayers[] = [
                        'userId' => isset($userInfo[$k]) ? $userInfo[$k] : '',
                        'killCount' => $v['killNum'],
                        'steamId' => $k
                    ];
                }
                $matchInfo[] = [
                    'seatNo' => $key,
                    'rank' => $value['teamRank'],
                    'teamMembers' => $teamPlayers

                ];
            }

            $data = [
                'matchId' => $matchId,
                'teams' => $matchInfo
            ];
        }

        return $data;
    }

    protected function checkCover($url){
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        $result = curl_exec($curl);

        $image = false;
        if ($result !== false) {
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($statusCode == 200) {
                $image = true;
            }
        }
        curl_close($curl);

        return $image;
    }

    protected function getPubgConfigList(){
        $pubgCustom = new PubgCustom();
        $configList = $pubgCustom->configList();
        if(!is_array($configList)){
            $configList = [];
        }
        $data = [];
        foreach ($configList as $value){
            $data[$value['id']] = $value['name'];
        }
        return $data;
    }

    protected function getGameDetail($gid){
        $pubgmatch = new PubgMatch();
        $pubgData = new PubgData($gid);

        $gameRecord = $pubgmatch->getGameRecord($gid);

        if(empty($gameRecord)){
           return [];
        }

        if($pubgData->saveGameRecord($gameRecord)){
            return $pubgData->teamInfo();
        }else{
            return [];
        }
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
}
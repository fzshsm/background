<?php

namespace app\modules\league\controllers;

use app\components\QCloudCos;
use app\controllers\Controller;
use app\modules\league\api\GloryMatch;
use app\modules\league\api\PubgLeague;
use yii\helpers\Json;
use yii\helpers\VarDumper;
use yii\web\UploadedFile;
use yii\data\ArrayDataProvider;


/**
 * Default controller for the `league` module
 */
class DefaultController extends Controller
{
    
    private $_processFileError;

    public function actionIndex(){
        $request = \Yii::$app->request;
        $pageSize = $request->get('pageSize' , 15);
        $type = $request->get('searchType' , 0);
        $page = $request->get('page',1);
        $content = $request->get('content');
        $sort = $request->get('sort', '-id');

        $pubgLeague = new PubgLeague();

        if($sort == 'id'){
            $sort = 'id,asc';
        }else{
            $sort = 'id,desc';
        }

        $data = [];

        $matchDatas = $pubgLeague->listdata($page , $pageSize, $type, $content,1,$sort);
        if(!empty($matchDatas)){
            foreach ($matchDatas['results'] as $value){
                $value['id'] = $value['leagueId'];
                $value['cover'] = isset($value['cover']) ? $value['cover'].'?'.time() : $value['cover'] ;
                $value['typeName'] = $value['leagueModel'];
                $value['flag'] = $value['leagueCategory'];
                $value['level'] = $value['leagueLevel'];
                $value['shareIcon'] = isset($value['shareIcon']) ? $value['shareIcon'].'?'.time() : $value['shareIcon'] ;
                $value['shareCover'] =  isset($value['shareCover']) ? $value['shareCover'].'?'.time() : $value['shareCover'] ;
                $data[$value['id']] = $value;
            }
        }else {
            \Yii::$app->session->setFlash( 'error' , $pubgLeague->getError());
        }

        $totalCount = isset( $matchDatas['totalSize'] ) ? $matchDatas['totalSize'] : 0;

        $responseData = new ArrayDataProvider([
            'sort' => [
                'attributes' => ['id'],
            ],
            'pagination' => [
                'pageSize' => $pageSize,
                'totalCount' => $totalCount
            ]
        ]);
        $responseData->setModels($data);
        $responseData->setTotalCount($totalCount);

        $leagueSorts = $this->getLeagueSorts();
        $leagueSortList[] = ['id' => 0, 'name' => '全部'];
        foreach ($leagueSorts as $key => $value){
            $leagueSortList[$key] = [
                'id' => $key,
                'name' => $value
            ];
        }

        $robotList = $this->getRobotList();
        return $this->render('index',['leagueSortList' => $leagueSortList, 'gameType' => 'glory', 'responseData' => $responseData,'robotList' => $robotList]);
    }

    public function actionCreate(){
        $request = \Yii::$app->request;
        if($request->isPost){
            $postData = $request->post();
//            if($postData['type'] == 2){
//                $rewards = [];
//                foreach ($postData['rewards'] as $val){
//                    if(!empty($val)){
//                        array_push($rewards ,$val);
//                    }
//                }
//                $postData['rewards'] = implode(',',$rewards);
//            }else{
//                $postData['rewards'] = '';
//            }

            $matchTimes = isset($postData['matchTimes']) ? $postData['matchTimes'] : [];

            $openHours = $this->getOpeningHours($matchTimes);

            $postData['openingHours'] = $openHours;

            $pubgLeague = new PubgLeague();
            $postData['createUserId'] = \Yii::$app->user->identity->id;
            $postData['gameType'] = 1;
            $response = $pubgLeague->create($postData);
            if($response){
                \Yii::$app->session->setFlash('success' , "创建联赛成功！");
                GloryMatch::clearTypesCache();

                $leagueId = $response;
                $imageName = ['cover','shareIcon','shareCover','activityIcon','activityPage'];

                foreach ($imageName as $value){
                    if($this->getCover($value)){
                        $image = $this->uploadCover($leagueId,$value);
                        if(!empty($image)){
                            $postData[$value] = $image;
                        }
                    }
                }

                $pubgLeague = new PubgLeague($leagueId);
                $postData['leagueId'] = $leagueId;
                $postData['updateUserId'] = \Yii::$app->user->identity->id;
                if(!($pubgLeague->update($postData))){
                    \Yii::$app->session->setFlash('error' , $pubgLeague->getError());
                }
            }else{
                \Yii::$app->session->setFlash('error' , $pubgLeague->getError());
            }
        }
        $leagueTypes = $this->getLeagueTypes();
        $leagueSorts = $this->getLeagueSorts();

        return $this->render( 'create',['leagueTypes' => $leagueTypes, 'leagueSorts' => $leagueSorts]);
    }
    
    public function actionUpdate($id){
        $request = \Yii::$app->request;
        $pubgLeague = new PubgLeague($id);
        $data = $pubgLeague->detail();
        if($request->isPost){
            $postData = $request->post();
            $imageName = ['cover','shareIcon','shareCover','activityIcon','activityPage'];

            foreach ($imageName as $value){
                $image = $this->getCover($value);
                if($image != false){
                    $newImage = $this->uploadCover($id,$value);

                    if(!empty($newImage)){
                        $postData[$value] = $newImage;
                    }
                }else{
                    $postData[$value] = $data[$value];
                }
            }

            $matchTimes = isset($postData['matchTimes']) ? $postData['matchTimes'] : [];

            $openHours = $this->getOpeningHours($matchTimes);

            $postData['openingHours'] = $openHours;

//            $rewards = [];
//            foreach ($postData['rewards'] as $val){
//                if(!empty($val)){
//                    array_push($rewards ,$val);
//                }
//            }
//            $postData['rewards'] = implode(',',$rewards);
            $postData['updateUserId'] = \Yii::$app->user->identity->id;
            $postData['leagueId'] = $id;
            $postData['gameType'] = 1;
            if($pubgLeague->update($postData)){
                \Yii::$app->session->setFlash('success' , "修改联赛信息成功！");
                GloryMatch::clearTypesCache();
            }else{
                \Yii::$app->session->setFlash('error' , $pubgLeague->getError());
            }
            $data = $pubgLeague->detail();
        }
        
        if(empty($data)){
            \Yii::$app->session->setFlash('dataError' , $pubgLeague->getError());
        }

        $openingHours = $data['openingHours'];

        if(!empty($openingHours)){
            $data['matchTimes'] = explode(',',$openingHours);
        }
        $data['cover'] = isset($data['cover']) ?  $data['cover'].'?'.time() :$data['cover'] ;
        $data['shareIcon'] = isset($data['shareIcon']) ? $data['shareIcon'].'?'.time() : $data['shareIcon'] ;
        $data['shareCover'] = isset($data['shareCover']) ? $data['shareCover'].'?'.time() : $data['shareCover'] ;
        $data['activityIcon'] = isset($data['activityIcon']) ? $data['activityIcon'].'?'.time() : $data['activityIcon'];

        $leagueTypes = $this->getLeagueTypes();
        $leagueSorts = $this->getLeagueSorts();

        return $this->render( 'update' , ['data' => $data, 'leagueTypes' => $leagueTypes ,'leagueSorts' => $leagueSorts]);
    }

    protected function getCover($name){
        $cover = UploadedFile::getInstanceByName($name);
        return !empty($cover) ? $cover : false;
    }

    protected function uploadCover($leagueId,$name){
        try{
            $cover = $this->getCover($name);
            if(empty($cover)){
                return false;
            }
            $qCloudCos = new QCloudCos();
            $srcPath = $cover->tempName;
           // $dstPath = "/league/" . md5($cover->name . time()) . "." . $cover->extension;

            if (YII_ENV_DEV) {
                $namePath = [
                    'shareIcon' => "/league/share/test/".$leagueId.".png",
                    'shareCover' => "/league/sharecover/test/".$leagueId.".png",
                    'cover' => "/league/test/".$leagueId.".png",
                    'activityIcon' => "/league/test/" . md5($cover->name . time()) . ".png",
                    'activityPage'=>"/event/test/".$leagueId.".png",
                ];
            }else{
                $namePath = [
                    'shareIcon' => "/league/share/".$leagueId.".png",
                    'shareCover' => "/league/sharecover/".$leagueId.".png",
                    'cover' => "/league/".$leagueId.".png",
                    'activityIcon' => "/league/" . md5($cover->name . time()) . ".png",
                    'activityPage'=>"/event/".$leagueId.".png",
                ];
            }
            $dstPath = $namePath[$name];

            $result = $qCloudCos->upload(QCloudCos::BUCKET_NAME , $srcPath , $dstPath,null,null,0);

            if(!empty($result) && isset($result['code'])){
                \Yii::trace($result , 'League.Upload.IMG');
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
    
    protected function deleteCover($cover){
        try{
            $coverInfo = parse_url($cover);
            $qCloudCos = new QCloudCos();
            $result = $qCloudCos->delFile(QCloudCos::BUCKET_NAME , $coverInfo['path']);

            if(!empty($result) && isset($result['code']) && $result['code'] != 0){
                throw new \Exception($result['message']);
            }
            return true;
        }catch( \Exception $e){
            $this->_processFileError = $e->getMessage();
            \Yii::warning($this->_processFileError , 'League.Delete.IMG');
        }
        return false;
    }

    protected function getLeagueTypes(){
        $match = new PubgLeague();
        $leagueTypes = $match->leagueTypes(['gameType' => 1]);
        if(!is_array($leagueTypes)){
            $leagueTypes = [];
        }
        $leagueTypesData = [];
        foreach ($leagueTypes as $leagueType){
            $leagueTypesData[$leagueType['id']] = $leagueType['val'];
        }
        return $leagueTypesData;
    }

    protected function getLeagueSorts(){
        $match = new PubgLeague();
        $leagueSorts = $match->leagueSorts(['gameType' => 1]);
        if(!is_array($leagueSorts)){
            $leagueSorts = [];
        }
        $leagueSortsData = [];
        foreach ($leagueSorts as $leagueSort){
            $leagueSortsData[$leagueSort['id']] = $leagueSort['val'];
        }
        return $leagueSortsData;
    }

    protected function getRobotList(){
        $pubgLeague = new PubgLeague();

        $response = $pubgLeague->getRobotList();

        if(!is_array($response)){
            $response = [];
        }

        $robotList = [];

        foreach ($response as $value){
            $robotList[$value['name']] = $value['name'];
        }

        return $robotList;
    }

    protected function getOpeningHours($matchTimes){
        $times = '';

        foreach ($matchTimes as $value){
            if(empty($times)){
                $times = $value;
            }else{
                $times = $times.','.$value;
            }
        }

        return $times;
    }

}

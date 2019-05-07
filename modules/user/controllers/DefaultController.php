<?php

namespace app\modules\user\controllers;

use app\controllers\Controller;
use app\modules\club\api\Club;
use app\modules\club\api\PubgClub;
use app\modules\finance\api\Consumption;
use app\modules\league\api\GloryMatch;
use app\modules\league\api\PubgLeague;
use app\modules\user\api\PubgUser;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;
use app\modules\user\api\User;
use app\components\QCloudCos;
use yii\helpers\VarDumper;
use yii\web\UploadedFile;

/**
 * Default controller for the `User` module
 */
class DefaultController extends Controller
{
    private $_processFileError;
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex(){
        $request = \Yii::$app->request;
        if($request->isAjax){
            $search = $request->get('search' , ['type' => 'all' , 'value' => '']);

            if(empty($search['value'])){
                $search['type'] = 'all';
            }
            $start = $request->get('start');
            $pageSize = $request->get('length');
            $page = $start / $pageSize + 1;
            $responseData = [ 
                    'draw' => $request->get('draw'),
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [ ],
                    'error' => '' 
            ];

            $user = new User();
            $userData = $user->listdata($page , $pageSize , [$search['type'] => trim($search['value'])]);
            if(!empty($userData)){
                $match = new GloryMatch();
                $matchTypes = $match->types();
                $responseData['recordsTotal'] = $userData['totalSize'];
                $responseData['recordsFiltered'] = $userData['totalSize'];
                foreach ($userData['results'] as $value){
//                    $identity = $user->getIdentityValue(["player" => $value['isPlayer'] , "famous" => $value['isFamous']]);
                    $identity = $value['personName'];
                    $responseData['data'][] = [
                            'userNo' => $value['userNo'],
                            'nickName' => $value['nickName'],
                            'gender' => $value['gender'],
                            'qq' => $value['qq'],
//                             'mobile' => $value['mobile'],
                            'gameLeagueId' => isset($matchTypes[$value['gameLeagueId']]) ? $matchTypes[$value['gameLeagueId']]['name'] : '',
//                             'winRatio' => isset($value['gameCount']) && $value['gameCount'] > 0 ? round($value['winCount'] /  $value['gameCount']) : 0,
//                             'medalNum' =>$value['medalNum'],
                            'isRealInfo' => $value['isRealInfo'],
                            'identity' => $identity,
                            'clubName' => $value['clubName'],
                            'status' => $value['isForbind'],
                            'id' => $value['id'],
//                            'roleId' => isset($value['roleId']) ? $value['roleId'] : ''
                    ];
                }
            }else{
                $responseData['error'] = $user->getError();
            }

            return Json::encode($responseData);
        }
        return $this->render('index');
    }
    
    public function actionTest(){
        return $this->render('test');
    }
    
    public function actionUpdate($id){
        $request = \Yii::$app->request;
        $gameType = $request->get('gameType',1);
        $viewMatchTypes = [];
        $match = new GloryMatch();
        $pubgLeague = new PubgLeague();
        $matchTypes = $pubgLeague->getLeagueList(['gameType' => $gameType]);
        if(!is_array($matchTypes)){
            $matchTypes = [];
        }
        foreach($matchTypes as $type){
            $viewMatchTypes[$type['id']] = $type['name'];
        }

        $user = new User($id);
        $pubgUser = new PubgUser($id);
        if($request->isPost){
            $data = $request->post();
            $gameLeagueIds = isset($data['gameLeagueId']) ? $data['gameLeagueId'] : '';
            if(!empty($gameLeagueIds)){
                $gameLeagueIds = implode($gameLeagueIds,',');
            }
            $data['leagueOwns'] = $gameLeagueIds;
            $data['gameType'] = $gameType;
            if($pubgUser->updateUserInfo($data) == false){
                \Yii::$app->session->setFlash('error' , $pubgUser->getError());
            }else{
                \Yii::$app->session->setFlash('success' , "更新用户信息成功！");
            }
        }
        $data = $user->detail();
        $userData = $pubgUser->getUserDetailInfo($gameType);

        if($userData == false){
            \Yii::$app->session->setFlash('error' , $pubgUser->getError());
        }
        if(!is_array($userData)){
            $userData = [];
            $userData['gender'] = 0;
        }
        $userData['id'] = $id;

        return $this->render('update' , ['data' => $userData , 'matchTypes' => $viewMatchTypes,'gameType' => $gameType]);
    }

    public function actionAuthorize($id){
        $request = \Yii::$app->request;
        $user = new User($id);
        $pubgUser = new PubgUser($id);
        $club = new Club();
        if($request->isPost){
            $data = $request->post();
            $gameType = $data['gameType'];

            $cover = $this->uploadCover();

            if($gameType == 2){

                if(empty($cover)){
                    $cover = $data['image'];
                }

                $sendData = [
                    'userId' => $id,
                    'teamId' => $data['teamId'],
                    'teamIdentity' => $data['teamIdentity'],
                    'headImg' => $cover,
                    'memberName' => $data['nickName']
                ];

                if(!empty($data['teamMemberId'])){
                    $sendData['teamMemberId'] = $data['teamMemberId'];
                }

                $response = $pubgUser->pubgAuthorize($sendData);

                if($response == false){
                    \Yii::$app->session->setFlash('error' , $pubgUser->getError());
                }else{
                    \Yii::$app->session->setFlash('success' , "修改用户认证信息成功！");
                }

            }else{
                if(!empty($cover)){
                    $data['headImage'] = $cover;
                }
                $response = $user->updateAuthInfo($data) ;

                if($response == false){
                    \Yii::$app->session->setFlash('error' , $user->getError());
                }else{
                    \Yii::$app->session->setFlash('success' , "修改用户认证信息成功！");
                }
            }
        }

        if(isset($gameType) && ($gameType == 2)){
            $userRole = $pubgUser->getGameRole($id,$gameType);

            if($userRole == false){
                $data = [
                    'headImg' => '',
                    'nickName' => '',
                    'gameType' => 2,
                    'userId' => $id,
                    'personName' => ''
                ];
                \Yii::$app->session->setFlash('error' , '该用户未绑定角色将无法认证战队');
            }else{
                $pubgUserInfo = $pubgUser->pubgUserInfo();
                if($pubgUserInfo == false) {
                    \Yii::$app->session->setFlash('error',$pubgUser->getError());
                }else{
                    $data['nickName'] = $userRole['nickName'];
                    $data['headImg'] = $userRole['headImg'];
                    $pubgUserInfo = $pubgUser->pubgUserInfo();

                    if($pubgUserInfo != false){
                        $data['teamId'] = $pubgUserInfo['teamId'];
                        $data['gameType'] = 2;
                        $data['personName'] = '';
                        $data['userId'] = $id;
                        $data['teamMemberId'] = $pubgUserInfo['teamMemberId'];
                        $data['teamIdentity'] = $pubgUserInfo['teamIdentity'];
                    }
                }
            }
        }else{
            $data = $user->authInfo();
            $data['gameType'] = 1;

            if($data == false){
                \Yii::$app->session->setFlash('error' , $user->getError());
            }
        }

        $clubList = $club->allClubList();
        $clubData['0'] = '';
        if(!empty($clubList)){
            foreach ($clubList as $value){
                $clubData[$value['id']] = $value['name'];
            }
        }

        $pubgTeam = $this->getAllPubgTeam();

        return $this->render('authorize' , ['data' => $data,'clubData' => $clubData, 'pubgTeam' => $pubgTeam]);
    }
    
    public function actionLock($id){
        $response = ['status' => 'success' , 'message' => '' ];
        $user = new User($id);
        if($user->changeState('lock') == false){
            $response['status'] = 'error';
            $response['message'] = $user->getError();
        }
        return Json::encode($response);
    }

    public function actionUnlock($id){
        $response = ['status' => 'success' , 'message' => '' ];
        $user = new User($id);
        if($user->changeState('unlock') == false){
            $response['status'] = 'error';
            $response['message'] = $user->getError();
        }
        return Json::encode($response);
    }

    public function actionUnbind($id){
        $response = ['status' => 'success' , 'message' => '' ];
        $user = new PubgUser($id);
        if($user->clearGloryRole() == false){
            $response['status'] = 'error';
            $response['message'] = $user->getError();
        }
        return Json::encode($response);
    }

    public function actionChat(){
        $response = ['status' => 'success' , 'message' => '' ];
        $request = \Yii::$app->request;

        if($request->isAjax){
            $data = $request->get();
            $user = new User($data['userId']);
            if($data['controlType'] == 1){
                $data['time'] = $data['time'] * 60 * 1000;
                $response['message'] = '禁言成功';
            }else{
                $data['time'] = 0;
                $response['message'] = '解禁成功';
            }
            if($user->controlChat($data['time'],$data['controlType']) == false) {
                $response['status'] = 'error';
                $response['message'] = $user->getError();
            }
            return Json::encode($response);
        }
        return Json::encode($response);
    }

    protected function getCover(){
        $cover = UploadedFile::getInstanceByName('headImage');
        return !empty($cover) ? $cover : false;
    }

    protected function uploadCover(){
        try{
            $cover = $this->getCover();
            if(empty($cover)){
                return false;
            }
            $qCloudCos = new QCloudCos();
            $srcPath = $cover->tempName;
            $dstPath = "/playerimg/" . md5($cover->name . time()) . "." . $cover->extension;
            $result = $qCloudCos->upload(QCloudCos::BUCKET_NAME , $srcPath , $dstPath);
            if(!empty($result) && isset($result['code'])){
                \Yii::trace($result , 'playerimg.Upload.IMG');
                if($result['code'] != 0){
                    throw new \Exception($result['message']);
                }
                return $result['data']['access_url'];
            }
        }catch( \Exception $e){
            $this->_processFileError = $e->getMessage();
            \Yii::warning($this->_processFileError , 'playerimg.Upload.IMG');
        }
        return false;
    }

    protected function getAllPubgTeam(){
        $pubgTeam = new PubgClub();

        $response = $pubgTeam->getPubgAllTeam();

        if(!is_array($response)){
            $response = [];
        }

        $data['0'] = '';

        foreach ($response as $value){
            $data[$value['teamId']] = $value['teamName'];
        }

        return $data;
    }

    public function actionDetail(){
        $request = \Yii::$app->request;

        $userId = $request->get('user_id');
        $gameType = $request->get('gameType');
        $user = new User($userId);
        $pubgUser = new PubgUser($userId);
        $response = ['status' => 'success' , 'message' => '', 'data' => [] ];

        if($gameType == 2){
            $userRole = $pubgUser->getGameRole($userId,$gameType);
            if($userRole == false){
                $data = ['status' => 'error', 'message' => $pubgUser->getError() ];
                return Json::encode($data);
            }else{
                if(!is_array($userRole) || empty($userRole)){
                    $data = ['status' => '201', 'message' => '该用户未绑定角色将无法认证战队' ];
                    return Json::encode($data);
                }

                $data['nickName'] = $userRole['nickName'];
                $data['headImg'] = $userRole['headImg'];
                $pubgUserInfo = $pubgUser->pubgUserInfo();

                if($pubgUserInfo != false){
                    $data['teamId'] = $pubgUserInfo['teamId'];
                    $data['gameType'] = 2;
                    $data['personName'] = '';
                    $data['userId'] = $userId;
                    $data['teamMemberId'] = $pubgUserInfo['teamMemberId'];
                    $data['teamIdentity'] = $pubgUserInfo['teamIdentity'];
                }
            }
        }else{
            $data = $user->authInfo();
            $data['gameType'] = 1;
        }

        $response['data'] = $data;
        return Json::encode($response);
    }

    public function actionRole($id){
        $data = ['status' => 'success' , 'message' => '' ];

        $pubgUser = new PubgUser();
        $response = $pubgUser->getSteamRole($id);
        if($response == false){
            $response['status'] = 'error';
            $response['message'] = $user->getError();
        }else{
            $data['result'] = $response['nickName'];
        }

        return Json::encode($data);
    }

    public function actionBindrole($id){
        $response = ['status' => 'success' , 'message' => '绑定成功' ];
        $nickName = \Yii::$app->request->get('nickName');

        $pubgUser = new PubgUser();
        if($pubgUser->bindSteamRole($id,$nickName) == false){
            $response['status'] = 'error';
            $response['message'] = $pubgUser->getError();
        }

        return Json::encode($response);
    }

    public function actionBag(){
        $data =  [];
        $request = \Yii::$app->request;
        $page = $request->get('page'  , 1);
        $pageSize = $request->get('pageSize' , 15);
        $searchType = $request->get('searchType');
        $status = $request->get('searchStatus',0);
        $content = $request->get('content');
        $date = $request->get('date');
        $userId = $request->get('id');

        $time = 0;
        $nickName = '';
        if(!empty($date)){
            list($begin , $end) = explode('至' , $date);
            $time = $begin.','.$end;
        }

        $pubgUser = new PubgUser();
        $bagListData = $pubgUser->userBagList($userId,$page , $pageSize,$searchType,$status,$content,$time);
        if(empty($bagListData)){
            \Yii::$app->session->setFlash( 'error' , $pubgUser->getError());
        }else{
            foreach ($bagListData['results'] as $value){
                if(empty($nickName)){
                    $nickName = isset($value['nickName']) ? $value['nickName'] : '';
                }
                $data[$value['bagDetailId']] = $value;
            }
        }
        $totalCount = isset( $bagListData['totalSize'] ) ? $bagListData['totalSize'] : 0;
        $dataProvider = new ArrayDataProvider([
            'sort' => [
                'attributes' => ['bagDetailId'],
            ],
            'pagination' => [
                'pageSize' => $pageSize,
                'totalCount' => $totalCount
            ]
        ]);
        $dataProvider->setModels($data);
        $dataProvider->setTotalCount($totalCount);
        $overview = $this->getBagOverview($userId);
        return $this->render('bag' , [ 'dataProvider' => $dataProvider,'data' => ['nickName' => $nickName, 'overview' => $overview]]);
    }

    public function actionMark($id){
        $response = ['status' => 'success' , 'message' => '使用成功' ];
        $nickName = \Yii::$app->request->get('nickName');

        $pubgUser = new PubgUser();
        if($pubgUser->bagUseMark($id) == false){
            $response['status'] = 'error';
            $response['message'] = $pubgUser->getError();
        }

        return Json::encode($response);
    }

    public function actionCurrency($id){
        $response = ['status' => 'success' , 'message' => '已赠送' ];
        $coinB = \Yii::$app->request->get('coinB');
        $remark = \Yii::$app->request->get('remark');

        $pubgUser = new PubgUser();
        if($pubgUser->sendCurrency($id,$coinB,$remark) == false){
            $response['status'] = 'error';
            $response['message'] = $pubgUser->getError();
        }

        return Json::encode($response);
    }

    protected function getBagOverview($userId){
        $pubgUser = new PubgUser();

        $response = $pubgUser->bagOverview($userId);

        if(!is_array($response)){
            $response = [];
        }

        return $response;
    }

    public function actionCurrencylist($id){
        $data =  [];
        $request = \Yii::$app->request;
        $page = $request->get('page'  , 1);
        $pageSize = $request->get('pageSize' , 15);
        $status = $request->get('status',3);

        $consumption = new Consumption();
        $response = $consumption->sendList($page, $pageSize, $id, $status);
        if(empty($response)){
            \Yii::$app->session->setFlash( 'error' , $consumption->getError());
        }else{
            foreach ($response['results'] as $value){
                $value['coinB'] = number_format($value['coinB']);
                $data[$value['id']] = $value;
            }
        }
        $totalCount = isset( $response['totalSize'] ) ? $response['totalSize'] : 0;
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

        return $this->render('currency' , [ 'dataProvider' => $dataProvider]);
    }
}

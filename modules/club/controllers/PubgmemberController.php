<?php

namespace app\modules\club\controllers;

use app\controllers\Controller;
use app\modules\club\api\Member;
use app\modules\club\api\PubgClub;
use app\modules\club\api\PubgMember;
use app\modules\user\api\User;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;

class PubgmemberController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex($id){
        $data =  [];
        $request = \Yii::$app->request;
        $page = $request->get('page'  , 1);
        $pageSize = $request->get('pageSize' , 15);

        $member = new PubgMember();
        $pubgClub = new PubgClub($id);

        $memberListData = $member->listData($id,$page);
        $identityData = $this->getUserTeamIdentity();

        if(empty($memberListData)){
            \Yii::$app->session->setFlash( 'error' , $member->getError());
        }else{
            $memberListData['results'] = empty($memberListData) ? [] : $memberListData['results'];
            foreach ($memberListData['results'] as $value){
                $value['teamIdentityName'] = $identityData[$value['teamIdentity']];
                $data[$value['teamMemberId']] = $value;
            }
        }
        $totalCount = isset( $memberListData['totalSize'] ) ? $memberListData['totalSize'] : 0;
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
        $club = $pubgClub->detail();

        return $this->render('index' , ['dataProvider' => $dataProvider,'clubDetail' => $club]);
    }

    public function actionRemove()
    {
        $response = ['status' => 'success' , 'message' => '' ];
        $request = \Yii::$app->request;
        $teamMemberId = $request->get('teamMemberId');

        $pubgMember = new PubgMember();

        $userId = \Yii::$app->user->identity->id;

        if($pubgMember->remove($teamMemberId,$userId) == false){
            $response['status'] = 'error';
            $response['message'] = $pubgMember->getError();
        }
        return Json::encode($response);
    }

    public function actionUpdate($id){
        $response = ['status' => 'success' , 'message' => '' ];
        $pubgMember = new PubgMember();
        $request = \Yii::$app->request;
        $teamIdentity = $request->get('type');
        $userId = \Yii::$app->user->identity->id;

        if($pubgMember->updateTeamMemberIdentity($id,$teamIdentity,$userId) == false){
            $response['status'] = 'error';
            $response['message'] = $pubgMember->getError();
        }
        return Json::encode($response);
    }

    protected function getUserTeamIdentity(){
        $pubgMember = new PubgMember();

        $data = [];
        $response = $pubgMember->getUserTeamIdentity();

        foreach ($response as $value){
            $data[$value['id']] = $value['val'];
        }

        return $data;

    }
}

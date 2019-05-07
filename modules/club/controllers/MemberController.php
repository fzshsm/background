<?php

namespace app\modules\club\controllers;

use app\controllers\Controller;
use app\modules\club\api\Member;
use app\modules\user\api\User;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;

class MemberController extends Controller
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

        $member = new Member();
        $memberListData = $member->listData($id);

        if(empty($memberListData)){
            \Yii::$app->session->setFlash( 'error' , $member->getError());
        }else{
            $memberListData['results'] = empty($memberListData) ? [] : $memberListData;
            foreach ($memberListData['results'] as $value){
                $data[$value['userId']] = $value;
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

        return $this->render('index' , ['dataProvider' => $dataProvider]);
    }

    public function actionUpdate($id){
        $response = ['status' => 'success' , 'message' => '' ];
        $user = new User($id);
        $request = \Yii::$app->request;
        $type = $request->get('type');

        $data = $user->authInfo();

        $data['userType'] = $type;

        if($user->updateAuthInfo($data) == false){
            $response['status'] = 'error';
            $response['message'] = $user->getError();
        }
        return Json::encode($response);
    }
}

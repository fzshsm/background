<?php
/**
 * Created by IntelliJ IDEA.
 * User: Zhangxinlong
 * Date: 2017/11/3
 * Time: 10:31
 */

namespace app\modules\rating\controllers;


use app\controllers\Controller;
use app\modules\rating\api\Rating;
use app\modules\user\api\User;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;

class DefaultController extends Controller {

    public function actionIndex(){
        $data =  [];
        $request = \Yii::$app->request;
        $searchType = $request->get('searchType' , 'personName');
        $content = $request->get('content');
        $page = $request->get('page'  , 1);
        $pageSize = $request->get('pageSize' , 15);
        
        $rating = new Rating();
        $ratingListData = $rating->listData($searchType , $content , $page , $pageSize);
        if(empty($ratingListData)){
            \Yii::$app->session->setFlash( 'error' , $rating->getError());
        }else{
            $ratingListData['results'] = empty($ratingListData['results']) ? [] : $ratingListData['results'];
            foreach ($ratingListData['results'] as $value){
                $data[$value['recordId']] = $value;
            }
        }
        $totalCount = isset( $ratingListData['totalSize'] ) ? $ratingListData['totalSize'] : 0;
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
        $user = new User();
        $rating = new Rating();
        $authData = $user->authlist();
        $authlist = $this->getAuthlist($authData);
        if($request->isPost){
            $postData = $request->post();
            \Yii::trace($postData , 'rating.create');
            if( $rating->create($postData) ){
                \Yii::$app->session->setFlash('success' , "创建积分成功！");
            }else{
                \Yii::$app->session->setFlash('error' , $rating->getError());
            }
        }
        $gameTypeAndRule = $this->getGameTypeAndRule();
        $gameType = $gameTypeAndRule['gameType'];
        $gameRule = $gameTypeAndRule['gameRule'];
        $scoreList = $this->getUsersScoreList();

        return $this->render('create' , ['authlist' => $authlist ,'gameType' => $gameType,'gameRule' => Json::encode($gameRule),'scoreList' => Json::encode($scoreList)]);
    }
    
    public function actionUpdate($id){
        $request = \Yii::$app->request;
        $user = new User();
        $rating = new Rating($id);
        $authData = $user->authlist();
        $authlist = $this->getAuthlist($authData);

        if($request->isPost){
            $postData = $request->post();
            \Yii::trace($postData , 'rating.create');
            if( $rating->update($postData) ){
                \Yii::$app->session->setFlash('success' , "编辑积分成功！");
            }else{
                \Yii::$app->session->setFlash('error' , $rating->getError());
            }
        }
        $data = $rating->detail();
        $gameTypeAndRule = $this->getGameTypeAndRule();
        $gameType = $gameTypeAndRule['gameType'];
        $gameRule = $gameTypeAndRule['gameRule'];
        $scoreList = $this->getUsersScoreList();

        return $this->render('update' , [ 'data' => $data ,  'authlist' => $authlist , 'gameType' => $gameType,'gameRule' => Json::encode($gameRule),'scoreList' => Json::encode($scoreList)]);
    }
    
    public function actionDelete($id){
        $response = ['status' => 'success' , 'message' => '' ];
        $rating = new Rating($id);
        if($rating->deleteById() == false){
            $response['status'] = 'error';
            $response['message'] = $rating->getError();
        }
        return Json::encode($response);
    }
    
    protected function getAuthlist($authData){
        $authlist = [];
        foreach($authData as $data){
            $authlist[$data['userId']] = "{$data['personName']}-{$data['clubName']}";
        }
        return $authlist;
    }

    protected function getGameTypeAndRule(){
        $rating = new Rating();
        $listData = $rating->gameTypeAndRule();

        $gameType = [];
        $gameRule = [];
        foreach ($listData as $value)
        {
            $gameType[$value['id']] = $value['name'];
            $rules  = $value['rules'];
            foreach ($rules as $rule)
            {
                $gameRule[$value['id']][$rule['id']] = $rule['name'];
            }
        }
        $data = [
            'gameType' => $gameType,
            'gameRule' => $gameRule
        ];

        return $data;
    }

    protected function getUsersScoreList(){

        $rating = new Rating();
        $listData = $rating->getUsersScoreList();

        $data = [];
        foreach ($listData as $value)
        {
            $data[$value['userId']] = $value['score'];
        }

        return $data;
    }
}
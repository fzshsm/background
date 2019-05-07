<?php
namespace app\modules\league\controllers;

use app\controllers\Controller;
use app\modules\league\api\GloryComplaint;
use app\modules\league\api\GloryMatch;
use yii\data\ArrayDataProvider;

/**
 * Default controller for the `User` module
 */
class GlorycomplaintController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $data =  [];
        $request = \Yii::$app->request;
        $page = $request->get('page'  , 1);
        $pageSize = $request->get('pageSize' , 30);
        $searchType = $request->get('searchType');
        $content = $request->get('content');
        $date = $request->get('date');
        
        $begin = $end = '';
        $match = new GloryMatch();
        $matchType = $match->types();
        
        if(!empty($date)){
            list($begin , $end) = explode('至' , $date);
        }
        
        $complaint = new GloryComplaint();
        $complaintData = $complaint->listdata($page , $pageSize , [$searchType => trim($content)] , ['begin' => trim($begin) , 'end' => trim($end)]);
        if(empty($complaintData)){
            \Yii::$app->session->setFlash( 'error' , $match->getError());
        }else{
            foreach ($complaintData['results'] as $value){
                $data[$value['id']] = $value;
            }
        }
        $totalCount = isset( $complaintData['totalSize'] ) ? $complaintData['totalSize'] : 0;
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
        return $this->render('index' , ['matchType' => $matchType , 'dataProvider' => $dataProvider,'gameType' => 'glory']);
    }
    
    public function actionClear($userId){
        $response = ['status' => 'success' , 'message' => '' ];
        $complaint = new GloryComplaint();
        if($complaint->clear($userId) == false){
            $response['status'] = 'error';
            $response['message'] = $complaint->getError();
        }
        return $this->asJson($response);
    }
}

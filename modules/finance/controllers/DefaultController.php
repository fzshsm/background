<?php

namespace app\modules\finance\controllers;

use app\controllers\Controller;
use app\modules\finance\api\Recharge;
use yii\data\ArrayDataProvider;


class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex(){
        $data =  [];
        $request = \Yii::$app->request;
        $page = $request->get('page'  , 1);
        $pageSize = $request->get('pageSize' , 15);
        $date = $request->get('date');
        $searchType = $request->get('searchType');
        $content = $request->get('content');
        $rechargeChannel = $request->get('rechargeChannel');
        $payStatus = $request->get('payStatus');

        $time = 0;
        if(!empty($date)){
            list($begin , $end) = explode('至' , $date);
            $time = $begin.','.$end;
        }

        $recharge = new Recharge();
        $rechargeListData = $recharge->listData($page,$pageSize,$time,$searchType,$content,$rechargeChannel,$payStatus);

        if(empty($rechargeListData)){
            \Yii::$app->session->setFlash( 'error' , $recharge->getError());
        }else{
            $rechargeListData['results'] = empty($rechargeListData['results']) ? [] : $rechargeListData['results'];
            foreach ($rechargeListData['results'] as $value){
                $value['nickName'] = urldecode($value['nickName']);
                $value['createTime'] = date('Y-m-d H:i:s',$value['createTime']/1000);
                if(!empty($value['rechargeTime'])){
                    $value['rechargeTime'] = date('Y-m-d H:i:s',$value['rechargeTime']/1000);
                }
                if(!isset($value['payStatus'])){
                    $value['payStatus'] = 0;
                }
                $data[] = $value;
            }
        }
        $totalCount = isset( $rechargeListData['totalSize'] ) ? $rechargeListData['totalSize'] : 0;
        $dataProvider = new ArrayDataProvider([
            'sort' => [
                'attributes' => ['uid'],
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
}

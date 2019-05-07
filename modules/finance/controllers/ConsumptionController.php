<?php

namespace app\modules\finance\controllers;

use app\controllers\Controller;
use app\modules\finance\api\Consumption;
use app\modules\finance\api\Recharge;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;
use yii\helpers\VarDumper;

class ConsumptionController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex(){
        $data =  [];
        $request = \Yii::$app->request;
        $page = $request->get('page'  , 1);
        $pageSize = $request->get('pageSize' , 30);
        $date = $request->get('date');
        $searchType = $request->get('searchType');
        $content = $request->get('content');
        $type = $request->get('type');


        $time = 0;
        if(!empty($date)){
            list($begin , $end) = explode('至' , $date);
            $time = $begin.','.$end;
        }

        $consumption = new Consumption();
        $consumptionListData = $consumption->listData($page,$pageSize,$time,$searchType,$content,$type);

        if(empty($consumptionListData)){
            \Yii::$app->session->setFlash( 'error' , $consumption->getError());
        }else{
            $consumptionListData['results'] = empty($consumptionListData['results']) ? [] : $consumptionListData['results'];
            foreach ($consumptionListData['results'] as $value){
                $value['nickName'] = urldecode($value['nickName']);
                $value['upDateTime'] = date('Y-m-d H:i:s',$value['upDateTime']/1000);
                $value['consumeOrGainNumber'] = number_format($value['consumeOrGainNumber']);
                $value['finalSystemCoinNumber'] = number_format($value['finalSystemCoinNumber']);
                $data[] = $value;
            }
        }
        $totalCount = isset( $consumptionListData['totalSize'] ) ? $consumptionListData['totalSize'] : 0;
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

    public function actionSend(){
        $data =  [];
        $request = \Yii::$app->request;
        $page = $request->get('page'  , 1);
        $pageSize = $request->get('pageSize' , 15);
        $content = $request->get('content');
        $type = $request->get('type');

        $consumption = new Consumption();
        $consumptionListData = $consumption->sendList($page,$pageSize,$content,$type);

        if(empty($consumptionListData)){
            \Yii::$app->session->setFlash( 'error' , $consumption->getError());
        }else{
            $consumptionListData['results'] = empty($consumptionListData['results']) ? [] : $consumptionListData['results'];
            foreach ($consumptionListData['results'] as $value){
                $value['coinB'] = number_format($value['coinB']);
                $data[$value['id']] = $value;
            }
        }
        $totalCount = isset( $consumptionListData['totalSize'] ) ? $consumptionListData['totalSize'] : 0;
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

        return $this->render('send' , ['dataProvider' => $dataProvider]);
    }

    public function actionAgree($id){
        $response = ['status' => 'success' , 'message' => '' ];
        $consumption = new Consumption();

        if($consumption->audit(['id' => $id,'isPass' => 1]) == false){
            $response['status'] = 'error';
            $response['message'] = $consumption->getError();
        }
        return Json::encode($response);
    }

    public function actionReject($id){
        $response = ['status' => 'success' , 'message' => '' ];
        $consumption = new Consumption();
        $remark = \Yii::$app->request->get('remark');

        if($consumption->audit(['id' => $id, 'isPass' => 0,'remark' => $remark]) == false){
            $response['status'] = 'error';
            $response['message'] = $consumption->getError();
        }
        return Json::encode($response);
    }
}

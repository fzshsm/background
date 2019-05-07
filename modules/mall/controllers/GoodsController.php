<?php
/**
 * Created by IntelliJ IDEA.
 * User: Zhangxinlong
 * Date: 2018/7/12
 * Time: 13:35
 */

namespace app\modules\mall\controllers;


use app\controllers\Controller;
use app\modules\mall\api\Goods;
use yii\data\ArrayDataProvider;

class GoodsController extends Controller {

    
    public function actionOwner(){
        $data =  [];
        $request = \Yii::$app->request;
        $page = $request->get('page'  , 1);
        $pageSize = $request->get('pageSize' , 30);
        $searchType = $request->get('searchType');
        $status = $request->get('status',0);
        $content = trim($request->get('content'));
        
        $goodsCode = '';
        $goodsName = '';
        
        switch($searchType){
            case 'goodsCode' :
                $goodsCode = $content;
                break;
            case 'goodsName' :
                $goodsName = $content;
                break;
        }
        
        
        $goods = new Goods();
        $goodsListData = $goods->owner($page , $pageSize , $status , $goodsCode , $goodsName);
        
        if(empty($goodsListData)){
            \Yii::$app->session->setFlash( 'error' , $goods->getError());
        }else{
            foreach ($goodsListData['results'] as $value){
                if(empty($nickName)){
                    $nickName = isset($value['nickName']) ? $value['nickName'] : '';
                }
                $data[$value['goodsNo']] = $value;
            }
        }
        $totalCount = isset( $goodsListData['totalSize'] ) ? $goodsListData['totalSize'] : 0;
        $dataProvider = new ArrayDataProvider([
            'pagination' => [
                'pageSize' => $pageSize,
                'totalCount' => $totalCount
            ]
        ]);
        $dataProvider->setModels($data);
        $dataProvider->setTotalCount($totalCount);
        return $this->render('owner' , [ 'dataProvider' => $dataProvider]);
    }
    
}
<?php
namespace app\modules\news\api;

use app\components\RequestRemoteApi;

class NewsList extends RequestRemoteApi{

    public function getRequestUrl($action){
        return \Yii::$app->params['pubgApiDomain'] . $action;
    }

    public function listdata($page = 1, $pageSize = 15, $begin, $end, $title, $order = '-id',$gameType){
        $logCategory = "Api.NewsList.listData";

        $condition = array();

        $condition['begin'] = $begin;
        $condition['end']=$end;

        $params = [
            'pageNo' => $page,
            'pageSize' => $pageSize,
            'title' => $title,
            'sort' => $order,
            'gameType' => $gameType
        ];

        if(!empty($begin)){
            $params['date'] = $begin.','.$end;
        }

        \Yii::trace($condition , $logCategory);

        $response = $this->request('/usercenter/news/newsList' , $params);
        $data = [];
        if(!empty($response) && is_array($response)){
            $data = $response;
            \Yii::trace($data , $logCategory);
        }
        return $data;
    }

    public function getOneNews($id)
    {
        $condition = ['all' => ''];
        $logCategory = "Api.NewsList.listData";
        $params = [
            'id' => $id,
        ];
        \Yii::trace($condition , $logCategory);
        $response = $this->request('/usercenter/news/newsDetail' , $params);
        $data = [];
        if(!empty($response) && is_array($response)){
            $data = $response;
            \Yii::trace($data , $logCategory);
        }
        return $data;
    }
    public function updateNews($params ){
        $logCategory = "Api.NewsList.listData";
        \Yii::trace($params , $logCategory);
        $response = $this->request('/usercenter/news/create' , $params,'post');
        return  $response;
    }

    public function updateNewsStatus($id,$status,$userId){
        $condition = ['all' => ''];
        $logCategory = "Api.NewsList.listData";
        $params = [
            'id' => $id,
            'status' => $status,
            'userId' => $userId,
        ];
        \Yii::trace($condition , $logCategory);
        $response = $this->request('/usercenter/news/release' , $params);
        return  $response;
    }
}
?>
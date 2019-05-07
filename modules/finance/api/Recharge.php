<?php
namespace app\modules\finance\api;

use app\components\RequestRemoteApi;

class Recharge extends RequestRemoteApi{

    private $_id;
    private $_error;
    
    public function __construct($messageId = null){
        $this->_id = $messageId;
    }
    
    protected function checkId(){
        if(empty($this->_id) || !is_numeric($this->_id)){
            throw new \Exception('无效的充值编号！');
        }
    }

    public function getRequestUrl($action){
        return \Yii::$app->params['pubgApiDomain'] . $action;
    }
    
    public function listdata($page,$pagesize,$time,$searchType,$content,$rechargeChannel,$payStatus){
        $logCategory = "Api.Recharge.listdata";
        $data = [];
        $params = [
            'pageNo' => $page,
            'pageSize' => $pagesize,
        ];

        if(!empty($time)){
            $params['rechargeTime'] = $time;
        }

        if(!empty($rechargeChannel)){
            $params['rechargeChannel'] = $rechargeChannel;
        }

        if($payStatus != 4){
            $params['payStatus'] = $payStatus;
        }

        if(!empty($content)){
            if($searchType == 'aliOrder' || $searchType == 'wxOrder'){
                $params['channelOrderNumber'] = $content;
                $params['rechargeChannel'] = $searchType == 'aliOrder' ? 1 : 2;
            }else{
                if($searchType == 'nickName'){
                    $content = urlencode($content);
                }
                $params[$searchType] = $content;
            }
        }

        \Yii::trace( $params , $logCategory . '.SendParams');
        
        $responseRequireParams = [
            'uid' => '用户编号',
            "nickName" => "用户昵称",
            "money" => '充值金额',
            "rechargeChannel" => '充值渠道',
            "channelOrderNumber" => "渠道订单号",
            "baidouOrderNumber" => '百斗订单号',
            "rechargeTime" => '订单完成时间',
            "createTime" => '订单创建时间'
        ];
        $response = $this->request('/finance/queryFinanceList' , $params);

        if(!empty($response) && is_array($response)){
            $responseParams = [];
            if(isset($response['results']) && !empty(($response['results']))){
                $responseParams = array_keys($response['results'][0]);
            }
            $this->checkResponseMissParam($responseRequireParams, $responseParams);
            $missParams = $this->getMissParams();
            if(!empty($missParams)){
                $this->warning = $this->getMissParamsMessage($responseRequireParams);
                foreach ($response['results'] as $key => $value){
                    foreach ($missParams as $param){
                        $value[$param] = null;
                        $response['results'][$key] = $value;
                    }
                }
            }
            $data = $response;
            \Yii::trace($data , $logCategory);
        }
        return $data;
    }
}
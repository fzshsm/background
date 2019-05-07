<?php
namespace app\modules\league\api;

use app\components\RequestRemoteApi;

class PubgSubscribe extends RequestRemoteApi{

    private $_id;
    private $_error;
    
    public function __construct($noticeId = null){
        $this->_id = $noticeId;
    }
    
    protected function checkId(){
        if(empty($this->_id) || !is_numeric($this->_id)){
            throw new \Exception('无效的预约编号！');
        }
    }

    public function getRequestUrl($action){
        return \Yii::$app->params['pubgApiDomain'] . $action;
    }
    
    public function getError(){
        return $this->_error;
    }
    
    public function listdata($leagueId,$page,$pageSize,$startTime,$endTime,$teamName){
        $logCategory = "Api.Subscribe.listdata";
        $data = [];

        $params = [
            'page' => $page,
            'pageSzie' => $pageSize
        ];

        if(!empty($leagueId)){
            $params['leagueId'] = $leagueId;
        }

        if(!empty($teamName)){
            $params['teamName'] = $teamName;
        }
        if(!empty($startTime)){
            $params['startTime'] = $startTime;
            $params['endTime'] = $endTime;
        }

        \Yii::trace( $params , $logCategory . '.SendParams');
        
        $responseRequireParams = [
            'id' => '编号',
            'leagueId' => '联赛ID',
            'matchId' => '比赛',
            'leagueName' => '联赛名称',
            'clubName' => '俱乐部名称',
            'clubIcon' => '俱乐部图标',
            'createTime' => '预约时间',
        ];
        $response = $this->request('/pubg/game/subscribe/page' , $params);

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

    public function create($data)
    {
        \Yii::trace($data , 'Api.Notice.create');
        return $this->request('/saveOrUpdateNotice' , $data);
    }

    public function update($data)
    {
        try {
            $this->checkId();
            \Yii::trace($data , 'Api.Notice.update');

            return $this->request('/saveOrUpdateNotice' , $data);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }
    
    public function deleteById(){
        try {
            $this->checkId();
            $params = [
                'id' => $this->_id,
            ];
            $response = $this->request( '/delNotice' , $params);
            return $response;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }
}
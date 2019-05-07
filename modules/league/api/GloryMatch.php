<?php
namespace app\modules\league\api;

use app\components\RequestRemoteApi;

class GloryMatch extends RequestRemoteApi{

    private $_id;
    private $_error;
    
    public function __construct($matchId = null){
        $this->_id = $matchId;
    }
    
    public function checkId(){
        if(empty($this->_id) || !is_numeric($this->_id)){
            throw new \Exception('无效的比赛编号！');
        }
    }

    public function types(){
        $logCategory = "Api.Match.options";
        $data = [];
        $params = [];
        $cacheKey = str_replace('.' , '' , $logCategory);
        $cacheData = \Yii::$app->cache->get($cacheKey);
        if($cacheData == false){
            $response = $this->request('/queryRaceType' , $params);
            if(!empty($response) && is_array($response)){
                foreach($response as $value){
                    if(isset($value['childRaceInfos']) && is_array($value['childRaceInfos'])){
                        $childData = [];
                        foreach($value['childRaceInfos'] as $child){
                            $childData[$child['id']] = $child;
                            $value['childRaceInfos'] = $childData;
                        }
                        krsort($value['childRaceInfos']);
                    }
                    $data[$value['id']] = $value;
                }
                \Yii::$app->cache->set($cacheKey , $data , 1800);
            }
        }else{
            $data = $cacheData;
        }
        \Yii::trace($data , $logCategory);
        return $data;
    }
    
    public static function clearTypesCache(){
        \Yii::$app->cache->delete("ApiMatchoptions");
    }

    public function listdata($leagueId = 0 , $page = 1 , $pageSize = 15, $idDesc = 1, $type = 0, $content = ''){
        $logCategory = "Api.Match.listdata";
        $data = [];

        $params = [
            'pageNo' => $page,
            'pageSize' => $pageSize,
            'parentId' => $leagueId,
            'sort' => $idDesc
        ];

        if(!empty($type)){
            $params['leagueCategory'] = $type;
        }

        if(!empty($content)){
            $params['leagueName'] = $content;
        }

        $responseRequireParams = [
            'id' => '编号',
            'name' => '名称',
            'cover' => 'Logo',
            'reward' => '奖金',
            'level' => '等级',
            'signinCount' => '成员数量',
            'sponsor' => '举办单位',
            'describe' => '简介',
            'status' => '状态',
        ];

        $response = $this->request('/queryDataToGameLeague', $params);

        if(!empty($response) && is_array($response)){
            $responseParams = [];
            if(isset($response['results']) && ($response['results'])){
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
    
    public function create($data){
        $data['createTime'] = \Yii::$app->formatter->asDate(time());
        \Yii::trace($data , 'Api.Match.create');
        return $this->request('/addDataToGameLeague' , $data,'post');
    }
    
    public function detail(){
        try {
            $this->checkId();
            $logCategory = "Api.Match.detail";
            $data = [];
            $params = ['id' => $this->_id];

            $response = $this->request('/getDetailGameLeague' , $params);

            \Yii::trace($response , $logCategory);
            if(!empty($response) && is_array($response)){
                $data = $response;
            }
            return $data;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }
    
    
    public function update($data){
        try {
            $this->checkId();
            \Yii::trace($data , 'Api.Match.update');

            return $this->request('/refreshDataToGameLeague' , $data,'post');
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }
    
    public function disabled(){
        try {
            $this->checkId();
            
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
        return true;
    }

    public function leagueTypes(){
        try {
            \Yii::trace([] , 'Api.Match.leagueTypes');
            return $this->request('/queryLeagueTypes');
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function leagueSorts(){
        try {
            \Yii::trace([] , 'Api.Match.leagueSorts');
            return $this->request('/queryLeagueSorts');
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

}
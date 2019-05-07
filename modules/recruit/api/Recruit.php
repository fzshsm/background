<?php
namespace app\modules\recruit\api;

use app\components\RequestRemoteApi;

class Recruit extends RequestRemoteApi{

    private $_id;
    private $_error;
    
    public function __construct($recruitId = null){
        $this->_id = $recruitId;
    }
    
    protected function checkId(){
        if(empty($this->_id) || !is_numeric($this->_id)){
            throw new \Exception('无效的招聘编号！');
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
    public function listdata($page,$pagesize){
        $logCategory = "Api.Recruit.listdata";
        $data = [];
        $params = [
            'pageNo' => $page,
            'pageSize' => $pagesize
        ];

        \Yii::trace( $params , $logCategory . '.SendParams');
        
        $responseRequireParams = [
            'id' => '编号',
            'clubName' => '战队名称',
            "clubLocation" => "所在地",
            "recruitTypeName" => "招募类型",
            //"applicants" => "应聘人数",
            "positionTypeName" => "职位类型名称",
            "rolerPositionTypeName" => "游戏角色名称",
            "yearRange" => "年龄范围",
            "payRange" => "报酬范围",
            "publishTime" => "发布时间",
            "statusName" => "发布状态名称",
            "publishUserName" => "发布者认证名"
        ];
        $response = $this->request('/queryRecruits' , $params);

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

    public function update($data)
    {
        try {
            $this->checkId();
            \Yii::trace($data , 'Api.Recruit.update');
            return $this->request('/modifyRecruit' , $data);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function deleteById()
    {
        try {
            $this->checkId();

            $params  = [
                'id' => $this->_id
            ];

            \Yii::trace($params , 'Api.Recruit.delete');
            return $this->request('/delRecruit' , $params);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function detail(){
        try {
            $this->checkId();
            $logCategory = "Api.Recruit.detail";
            $data = [];
            $params = [
                'id' => $this->_id,
            ];
            $responseRequireParams = [
                "id" => '编号',
                "clubName" => "战队名称",
                "clubLocation" => "战队所在地",
                "recruitType" => '招募类型',
                "positionType" => '招募职位',
                "rolerPositionType" => '招募游戏角色',
                "recruitObjInfo" =>  '招募对象描述',
                "minYear"=> '最小年龄',
                "maxYear"=> '最大年龄',
                "hasClubExperience"=> '战队经验',
                "minPay"=> '最小报酬',
                "maxPay"=> '最大报酬',
                "leagueId"=> '当前参与联赛',
                "winRateNumber"=>'胜率',
                "remark"=>"备注",
                "time"=>"发布时间",
                "status"=>'发布状态',
                "medals"=>"勋章",
                "medalUrls"=>"勋章图片"
            ];
            $response = $this->request('/getRecruitDetail' , $params);
            //var_dump($response); exit;
            if(!empty($response) && is_array($response)){
                $responseParams = [];
                if(!empty(($response))){
                    $responseParams = array_keys($response);
                }
                $this->checkResponseMissParam($responseRequireParams, $responseParams);
                $missParams = $this->getMissParams();
                if(!empty($missParams)){
                    $this->warning = $this->getMissParamsMessage($responseRequireParams);
                    foreach ($missParams as $param){
                        $response[$param] = null;
                    }
                }
                $data = $response;
                \Yii::trace($data , $logCategory);
            }
            return $data;
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }

    public function getApply($id,$page,$pageSize){
        try {
            $this->checkId();
            $param = [
                'id' => $id,
                'pageNo' => $page,
                'pageSize' => $pageSize
            ];
            \Yii::trace($id , 'Api.Recruit.Applys');
            return $this->request('/queryRecruitApplys' , $param);
        }catch (\Exception $e){
            $this->_error = $e->getMessage();
            return false;
        }
    }
}
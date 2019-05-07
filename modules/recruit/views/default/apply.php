<?php

use app\assets\AppAsset;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

$this->params['breadcrumbs'] = [
    [
        'label' => '招聘管理' ,
        'url'   => \Yii::$app->request->getReferrer() ,
    ] ,
    [
        'label' => '应聘详情' ,
    ] ,
];
AppAsset::registerCss( $this , '@web/plugin/datatables/datatables.min.css' );
AppAsset::registerCss($this , '@web/plugin/daterangepicker/daterangepicker-bs3.css');
AppAsset::registerCss($this, '@web/plugin/viewer/viewer.css');

AppAsset::registerJs($this, '@web/plugin/viewer/viewer.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/moment.min.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/daterangepicker.js');

Pjax::begin();
?>
<div class="row">
    <div class="col-md-8">
        <?php if(Yii::$app->session->hasFlash('error')){ ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?=Yii::$app->session->getFlash('error')?>
            </div>
        <?php }?>
    </div>
</div>
<?php
$form = ActiveForm::begin([
    'id' => 'candidate-form',
    'method' => 'get',
    'options' => ['data-pjax' => true , 'role' => 'form']
] );
?>
<h2 style="text-align: center">招聘详情</h2>
<!--招聘的详细信息展示位-->
<style>
    b{font-size: 1.3rem}
</style>
<div class="row">
    <div class="col-md-2">
        <b>战队：</b>
        <span><?= isset($recruitDetail['clubName'])? $recruitDetail['clubName'] : '' ?></span>
    </div>
    <div class="col-md-2">
        <b>地点：</b>
        <span><?= isset($recruitDetail['clubLocation'])? $recruitDetail['clubLocation'] : '' ?></span>
    </div>
    <div class="col-md-2">
        <b>招募类型：</b>
        <span><?= isset($recruitDetail['recruitType'])? $recruitDetail['recruitType'] : '' ?></span>
    </div>
    <div class="col-md-2">
        <b>招募职位类型：</b>
        <span><?= isset($recruitDetail['positionType'])? $recruitDetail['positionType'] : '' ?></span>
    </div>
    <div class="col-md-2">
        <b>招募游戏角色类型：</b>
        <span><?= isset($recruitDetail['rolerPositionType'])? $recruitDetail['rolerPositionType'] : '' ?></span>
    </div>
    <div class="col-md-2">
        <b>招募对象描述：</b>
        <span><?= isset($recruitDetail['recruitObjInfo'])? $recruitDetail['recruitObjInfo'] : '' ?></span>
    </div>
</div>
<div class="row" style="margin-top: 5px">
    <div class="col-md-2">
        <b>年龄：</b>
        <span><?= isset($recruitDetail['age'])? $recruitDetail['age'] : '' ?></span>
    </div>
    <div class="col-md-2">
        <b>薪资：</b>
        <span><?= isset($recruitDetail['pay'])? $recruitDetail['pay'] : '' ?></span>
    </div>
    <div class="col-md-2">
        <b>战队经验：</b>
        <span><?= isset($recruitDetail['hasClubExperience'])? $recruitDetail['hasClubExperience'] : '' ?></span>
    </div>
    <div class="col-md-2">
        <b>联赛等级：</b>
        <span><?= isset($recruitDetail['leagueId'])? $recruitDetail['leagueId'] : '' ?></span>
    </div>
    <div class="col-md-2">
        <b>胜率：</b>
        <span><?= isset($recruitDetail['winRateNumber'])? $recruitDetail['winRateNumber'] : '' ?>%</span>
    </div>
    <div class="col-md-2">
        <b>时间：</b>
        <span><?= isset($recruitDetail['time'])? $recruitDetail['time'] : '' ?></span>
    </div>
</div>
<div class="row" style="margin-top: 5px">
    <div class="col-md-2">
        <b>战队图标：</b>
        <?=Html::img($recruitDetail['icon'].'?imageMogr2/thumbnail/50x/format/png/interlace/1/quality/100',['height' => '50'])?>
    </div>
    <div class="col-md-9">
        <b>勋章条件：</b>
        <?php foreach ($recruitDetail['medalUrls'] as $medalUrl){  ?>
            <?=Html::img($medalUrl.'?imageMogr2/thumbnail/50x/format/png/interlace/1/quality/100')?>
        <?php } ?>
    </div>

</div>
<div class="row" style="margin-top: 5px;">
    <div class="col-md-12" style="margin-bottom: 20px">
        <b>备注：</b>
        <span><?= isset($recruitDetail['remark'])? $recruitDetail['remark'] : '' ?></span>
    </div>
</div>
<?php
ActiveForm::end();
echo GridView::widget( [
    'id'               => 'recruit' ,
    'dataProvider'     => $dataProvider ,
    'emptyText'        => "暂无应聘人员信息！" ,
    'emptyTextOptions' => [ 'class' => 'text-center' ] ,
    'tableOptions'     => [ 'class' => 'table table-bordered datatable no-footer hover stripe text-center dataTable','style' => 'margin-left:10px;margin-right:10px'] ,
    'options'          => [ 'class' => 'dataTables_wrapper no-footer no-border' ] ,
    'layout'           => "{errors}{items}{pager}" ,
    "columns"          => [
        [
            'label' => '游戏角色名',
            'value' => 'rolerId',
            'headerOptions' => ['width' => '2%']
        ],
        [
            'label' => '战队名',
            'value' => 'teamName',
            'headerOptions' => ['width' => '1%']
        ],
        [
            'label' => 'QQ',
            'value' => 'qq',
            'headerOptions' => ['width' => '1%']
        ],
        [
            'label' => '手机号',
            'value' => 'mobile',
            'headerOptions' => ['width' => '1%']
        ],
        [
            'label' => '认证名',
            'value' => 'roleName',
            'headerOptions' => ['width' => '1%']
        ],
        [
            'label' => '所属联赛',
            'value' => 'leagueName',
            'headerOptions' => ['width' => '1%']
        ],
        [
            'label' => '当前排名',
            'value' => 'rank',
            'headerOptions' => ['width' => '1%']
        ],
        [
            'label' => '当前积分',
            'value' => 'score',
            'headerOptions' => ['width' => '1%']
        ],
        [
            'label' => '当前胜率',
            'value' => 'winRate',
            'headerOptions' => ['width' => '1%']
        ],
        [
            'label' => '当前场次',
            'value' => 'gameCount',
            'headerOptions' => ['width' => '1%']
        ],
        [
           'label' => '勋章',
            'attribute' => 'medalUrls',
            'format'=>'raw',
            'value' =>function($model){
                $imgs = '';
                foreach ($model['medalUrls'] as $medal){
                    $imgs .= Html::img($medal.'?imageMogr2/thumbnail/50x/format/png/interlace/1/quality/100');
                }
                return $imgs;
            }
        ],

    ] ,
    'pager'            => [
        'options'     => [ 'class' => 'pagination dataTables_paginate paging_simple_numbers' ] ,
        'linkOptions' => [ 'class' => 'paginate_button' ] ,
    ] ,
] );
Pjax::end();
?>


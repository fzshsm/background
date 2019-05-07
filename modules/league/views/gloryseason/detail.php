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
        'label' => '联赛管理',
        'url' => Url::to([
            '/league'
        ] )
    ],
    [
        'label' => $matchName,
        'url' => Url::to(['/league/gloryseason' , 'leagueId' => $leagueId])
    ],
    [ 'label' => '奖金详情' ] ,
];
AppAsset::registerCss( $this , '@web/plugin/datatables/datatables.min.css' );
AppAsset::registerCss($this, '@web/plugin/viewer/viewer.css');

AppAsset::registerJs($this, '@web/plugin/viewer/viewer.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/moment.min.js');
Pjax::begin(['id' => 'complaint-filter']);
$form = ActiveForm::begin([
    'id' => 'complaint-filter-form',
    'method' => 'get',
    'options' => ['data-pjax' => true , 'role' => 'form']
] );
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
    <div class="col-sm-12">
        <b>操作人：</b><span><?= $operateUser?></span>
        <b style="margin-left: 10px">发放时间：</b><span><?= $operateDate?></span>
    </div>
    <div class="col-sm-12">
        <?php
        ActiveForm::end();
        echo GridView::widget( [
            'id'               => 'complaint' ,
            'dataProvider'     => $dataProvider ,
            'emptyText'        => "暂无奖金发放详情信息！",
            'emptyCell' => '',
            'emptyTextOptions' => [ 'class' => 'text-center' ] ,
            'tableOptions'     => [ 'class' => 'table table-bordered datatable no-footer hover stripe text-center dataTable','id'=> 'show_image' ] ,
            'options'          => [ 'class' => 'dataTables_wrapper no-footer no-border' ] ,
            'layout'           => "{errors}{items}{pager}" ,
            "columns"          => [
                'nickName:text: 用户昵称',
                'roleName:text:角色昵称',
                'rank:text:名次',
                'bonus:text:奖金',
                'score:text:积分',
                'totalCount:text:总场次',
                'winCount:text:胜场',
                'loseCount:text:败场',
                'tieCount:text:平局',
                'winRatio:text:胜率',
            ] ,
            'pager'            => [
                'options'     => [ 'class' => 'pagination dataTables_paginate paging_simple_numbers' ] ,
                'linkOptions' => [ 'class' => 'paginate_button' ] ,
            ] ,
        ] );


        ?>
    </div>
    <script>
        jQuery( document ).ready( function( $ ){
            table = $('#show_image');
            if (table.find('img').length > 0){
                var viewer = new Viewer(table[0] , {navbar : false, tooltip : false, scalable : false, fullscreen : false,zIndex : 99999});
            }
        });
    </script>


<?php
Pjax::end();
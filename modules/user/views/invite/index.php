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
        'label' => '用户管理' ,
        'url'   => Url::to( [
            '/user' ,
        ] ) ,
    ] ,
    [
        'label' => '邀请列表' ,
    ] ,
];
AppAsset::registerCss( $this , '@web/plugin/datatables/datatables.min.css' );
AppAsset::registerJs($this , '@web/js/common.js');
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
    'id' => 'invite-form',
    'method' => 'get',
    'options' => ['data-pjax' => true , 'role' => 'form']
] );
?>
<?php
ActiveForm::end();
echo GridView::widget( [
    'id'               => 'season' ,
    'dataProvider'     => $dataProvider ,
    'emptyText'        => "暂无邀请信息！" ,
    'emptyTextOptions' => [ 'class' => 'text-center' ] ,
    'tableOptions'     => [ 'class' => 'table table-bordered datatable no-footer hover stripe text-center dataTable' ] ,
    'options'          => [ 'class' => 'dataTables_wrapper no-footer no-border' ] ,
    'layout'           => "{errors}{items}{pager}" ,
    "columns"          => [
        'rolerId:raw:用户角色名',
        'leagueName:text:所属联赛',
        'qq:text:QQ',
        'mobile:text:手机号',
        'count:text:邀请人数',
        [
            'class'    => ActionColumn::className() ,
            'template' => '{detail}' ,
            'header'   => '操作' ,
            'contentOptions' => ['class' => 'actions'],
            'buttons'  => [
                'detail' => function( $url ){
                    $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-eye' ] );
                    return Html::a( $icon . '详情' , $url , [ 'class' => 'btn btn-success btn-sm btn-icon icon-left' ] );
                } ,
            ] ,
        ] ,
    ] ,
    'pager'            => [
        'options'     => [ 'class' => 'pagination dataTables_paginate paging_simple_numbers' ] ,
        'linkOptions' => [ 'class' => 'paginate_button' ] ,
    ] ,
] );
Pjax::end();
?>


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
        'label' => '角色管理' ,
        'url'   => Url::to( [
            '/admin/role' ,
        ] ) ,
    ] ,
    [
        'label' => '角色列表' ,
    ] ,
];
AppAsset::registerCss( $this , '@web/plugin/datatables/datatables.min.css' );
Pjax::begin();
?>
<?php if(Yii::$app->session->hasFlash('error')){ ?>
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <?=Yii::$app->session->getFlash('error')?>
    </div>
<?php }?>
    <div class="form-group col-md-12">
        <a class="btn btn-success btn-square radius-4 pull-right" href="<?=Url::to(['/admin/role/create'])?>"><i class="entypo-plus"></i>创建</a>
    </div>
<?php
$form = ActiveForm::begin([
    'id' => 'role-form',
    'method' => 'get',
    'options' => ['data-pjax' => true , 'role' => 'form']
] );
?>
<?php
ActiveForm::end();
echo GridView::widget( [
    'id'               => 'season' ,
    'dataProvider'     => $dataProvider ,
    'emptyText'        => "暂无角色信息！" ,
    'emptyTextOptions' => [ 'class' => 'text-center' ] ,
    'tableOptions'     => [ 'class' => 'table table-bordered datatable no-footer hover stripe text-center dataTable' ] ,
    'options'          => [ 'class' => 'dataTables_wrapper no-footer no-border' ] ,
    'layout'           => "{errors}{items}{pager}" ,
    "columns"          => [
        'id:raw:ID' ,
        'name:text:名称',
        'description:text:描述',
        'create_time:text:创建时间',
        [
            'class'    => ActionColumn::className() ,
            'template' => '{update}{assigment}' ,
            'header'   => '操作' ,
            'contentOptions' => ['class' => 'actions'],
            'buttons'  => [
                'update' => function( $url ){
                    $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-pencil' ] );
                    return Html::a( $icon . '编辑' , $url, [ 'class' => 'btn btn-default btn-sm btn-icon icon-left' ] );
                } ,
                'assigment' => function ($url) {
                    $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-bars' ] );
                    return Html::a($icon.'编辑权限',$url,['class' => 'btn btn-orange btn-sm btn-icon icon-left']);
                }
            ] ,
        ] ,
    ] ,
    'pager'            => [
        'options'     => [ 'class' => 'pagination dataTables_paginate paging_simple_numbers' ] ,
        'linkOptions' => [ 'class' => 'paginate_button' ] ,
    ] ,
] );
Pjax::end();
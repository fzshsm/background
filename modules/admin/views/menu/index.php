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
        'label' => '菜单管理' ,
        'url'   => Url::to( [
            '/admin/menu' ,
        ] ) ,
    ] ,
    [
        'label' => '菜单列表' ,
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
    <div class="form-group col-md-12">
        <a class="btn btn-success btn-square radius-4 pull-right" href="<?=Url::to(['/admin/menu/create'])?>"><i class="entypo-plus"></i>创建</a>
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
    'emptyText'        => "暂无菜单信息！" ,
    'emptyTextOptions' => [ 'class' => 'text-center' ] ,
    'tableOptions'     => [ 'class' => 'table table-bordered datatable no-footer hover stripe text-center dataTable' ] ,
    'options'          => [ 'class' => 'dataTables_wrapper no-footer no-border' ] ,
    'layout'           => "{errors}{items}{pager}" ,
    "columns"          => [
        'id:raw:ID' ,
        'name:text:菜单名',
        'parent_name:text:父级菜单',
        'url:text:路径',
        [
            'class'    => ActionColumn::className() ,
            'template' => '{class}',
            'header'   => '样式' ,
            'contentOptions' => ['class' => 'actions'],
            'buttons'  => [
                'class' => function( $url,$model,$keys ){
                    if($model['class']){
                        return Html::a('' , '' , [ 'class' => $model['class'].'  text-center' ] );
                    }else{
                        return '';
                    }
                }
            ] ,
        ],
        'rating:text:排序',
        [
            'class'    => ActionColumn::className() ,
            'template' => '{update}{delete}' ,
            'header'   => '操作' ,
            'contentOptions' => ['class' => 'actions'],
            'buttons'  => [
                'update' => function( $url ){
                    $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-pencil' ] );
                    return Html::a($icon.'编辑' , $url , [ 'class' => 'btn btn-default btn-sm btn-icon icon-left' ] );
                } ,
                'delete' => function( $url,$model ){
                    $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-times' ] );
                    return Html::a( $icon.'删除' , $url , [ 'class' => 'delete btn btn-danger btn-sm btn-icon icon-left' ,'data-name' => $model['name']] );
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
<script>
    $('a.delete').click(function(){
        var $that = $(this);
        var username = $(this).attr('data-name')
        var confirmText = $('<span>').addClass('color-orange font-16').html("你确定要删除( "+username+" )这个菜单吗？");
        var successText = "删除成功！";
        showConfirmModal(this , confirmText , successText );
        return false;
    });
</script>

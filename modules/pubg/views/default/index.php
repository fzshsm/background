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
        'label' => '绝地求生管理' ,
        'url'   => Url::to( [
            '/pubg' ,
        ] ) ,
    ] ,
    [ 'label' => '自定义房间配置' ] ,
];
AppAsset::registerCss( $this , '@web/plugin/datatables/datatables.min.css' );
AppAsset::registerCss($this , '@web/plugin/daterangepicker/daterangepicker-bs3.css');
AppAsset::registerCss($this, '@web/plugin/viewer/viewer.css');

AppAsset::registerJs($this, '@web/plugin/viewer/viewer.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/moment.min.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/daterangepicker.js');
AppAsset::registerJs($this , '@web/js/common.js');
Pjax::begin(['id' => 'complaint-filter']);

$form = ActiveForm::begin([
    'id' => 'complaint-filter-form',
    'method' => 'get',
    'options' => ['data-pjax' => true , 'role' => 'form']
] );
?>
    <a href="<?=Url::to(['/pubg/create'])?>" class="btn btn-success btn-square radius-4 pull-left">
        <i class="entypo-plus"></i>
        配置
    </a>

    <div class="col-md-12">
        <div class="form-group pull-right col-sm-6">
            <div class="col-sm-1" style="margin-left: 45%">
                <a href="<?= Url::to(['/pubg'])?>" class="btn btn-default" title="刷新">
                    <i class="fa fa-refresh"></i>
                </a>
            </div>
            <div class="input-group col-sm-5 pull-right">
                <div class="input-group-btn">
                    <button type="button" class="btn btn-success dropdown-toggle btn-width-100"   data-toggle="dropdown">
                        名称
                    </button>
                </div>
                <input type="text" id="name" class="form-control" name="name" placeholder="" value="<?= Yii::$app->request->get('name'); ?>">
                <div class="input-group-btn">
                    <button  type="submit" class="btn btn-success search">
                        <i class="entypo-search"></i>
                    </button>
                </div>
            </div>
        </div>
        <?php
        ActiveForm::end();
        echo GridView::widget( [
            'id'               => 'custom' ,
            'dataProvider'     => $dataProvider ,
            'emptyText'        => "暂无自定义配置信息！" ,
            'emptyCell' => '',
            'emptyTextOptions' => [ 'class' => 'text-center' ] ,
            'tableOptions'     => [ 'class' => 'table table-bordered datatable no-footer hover stripe text-center dataTable' ] ,
            'options'          => [ 'class' => 'dataTables_wrapper no-footer no-border' ] ,
            'layout'           => "{errors}{items}{pager}" ,
            "columns"          => [
                [
                    'label' => '名称',
                    'attribute' => 'name',
                    'value' => 'name',
                    'headerOptions' =>['width' => '10%']
                ],
                [
                    'label' => '简介',
                    'format' => 'raw',
                    'headerOptions' =>['width' => '30%'],
                    'value' => function ($model) {
                        return $model['remark'];
                    }
                ],
                [
                    'class'    => ActionColumn::className() ,
                    'template' => '{update}{delete}' ,
                    'header'   => '操作' ,
                    'contentOptions' => ['class' => 'actions'],
                    'headerOptions' =>['width' => '20%'],
                    'buttons'  => [
                        'update' => function( $url ){
                            $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-pencil' ] );
                            return Html::a( $icon . '编辑' , $url , [ 'class' => 'btn btn-default btn-sm btn-icon icon-left'] );
                        } ,
                        'delete' => function( $url , $model ){
                            $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-times' ] );
                            return Html::a( $icon . '删除' , $url , [ 'class' => 'btn btn-danger btn-sm btn-icon icon-left'  , 'data-name' => $model['name'] ] );
                        } ,
                    ] ,
                ] ,
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
            $('.dropdown-menu.dropdown-green a').click(function(){
                $('#searchType').val($(this).attr('data-searchtype'));
                $('button..btn-success.dropdown-toggle').attr('data-searchtype' , $(this).attr('data-searchtype'));
                $('button.btn-success.dropdown-toggle').html($(this).text() +　'　<span class="caret"></span>');
            });

            $('a.btn-danger').click(function(){
                var name = $(this).attr('data-name');
                var confirmText = $('<span>').addClass('color-orange font-16').html("你确定要删除（  " + name + "  ）这个配置吗？");
                var successText = "删除（ " + name + " ） 配置成功！";
                showConfirmModal(this , confirmText , successText);
                return false;
            });
        });
    </script>
<?php
Pjax::end();
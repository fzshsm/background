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
        'url'   => Url::to( [
            '/recruit' ,
        ] ) ,
    ] ,
    [
        'label' => '招聘列表' ,
    ] ,
];
AppAsset::registerCss( $this , '@web/plugin/datatables/datatables.min.css' );
AppAsset::registerCss($this , '@web/plugin/daterangepicker/daterangepicker-bs3.css');
AppAsset::registerCss($this, '@web/plugin/viewer/viewer.css');

AppAsset::registerJs($this, '@web/plugin/viewer/viewer.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/moment.min.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/daterangepicker.js');
AppAsset::registerJs($this , '@web/js/common.js');
Pjax::begin();
?>
<style>
    th{font-weight:bold;font-size:13px}
    table a.btn{margin-top: 4px}
</style>

<?php
$form = ActiveForm::begin([
    'id' => 'recruit-form',
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

<?php
ActiveForm::end();
echo GridView::widget( [
    'id'               => 'season' ,
    'dataProvider'     => $dataProvider ,
    'emptyText'        => "暂无招聘信息！" ,
    'emptyTextOptions' => [ 'class' => 'text-center' ] ,
    'tableOptions'     => [ 'class' => 'table table-bordered datatable no-footer hover stripe text-center','id' => 'show_image' ] ,
    'options'          => [ 'class' => 'dataTables_wrapper no-footer no-border' ] ,
    'layout'           => "{errors}{items}{pager}" ,
    "columns"          => [
        [
            'label' => 'ID',
            'attribute' => 'id',
            'value' => 'id',
            'headerOptions' =>['width' => '1%']
        ],
        [
            'label' => '战队',
            'value' => 'clubName',
            'headerOptions' =>['width' => '2%']
        ],
        [
            'label' => '图标',
            'format' => [
                'image',
                [
                    'height'=>'35',
                ]
            ],
            'value' => function ($model) {
                return $model['icon'];
            },
            'headerOptions' =>['width' => '4%']
        ],
        [
            'label' => '所在地',
            'value' => 'clubLocation',
            'headerOptions' => ['width' => '1%']
        ],
        [
            'label' => '招募类型',
            'value' => 'recruitTypeName',
            'headerOptions' => ['width' => '1%']
        ],
        [
            'label' => '应聘人数',
            'value' => 'applicants',
            'headerOptions' => ['width' => '1%']
        ],
        [
            'label' => '职位名称',
            'value' => 'positionTypeName',
            'headerOptions' => ['width' => '1%']
        ],
        [
            'label' => '游戏角色',
            'value' => 'rolerPositionTypeName',
            'headerOptions' => ['width' => '1%']
        ],
        [
            'label' => '年龄范围',
            'value' => 'yearRange',
            'headerOptions' => ['width' => '1%']
        ],
        [
            'label' => '报酬范围',
            'value' => 'payRange',
            'headerOptions' => ['width' => '1%']
        ],
        [
            'label' => '发布者',
            'value' => 'publishUserName',
            'headerOptions' => ['width' => '1%']
        ],
        'publishTime:text:发布时间',
        [
            'class'    => ActionColumn::className() ,
            'template' => '{status}' ,
            'header'   => '状态' ,
            'contentOptions' => ['class' => 'text-center'],
            'buttons'  => [
                'status' => function( $url,$model ){
                    switch ($model['statusName']){
                        case '审核成功':
                            return Html::tag( 'span' , '审核成功' , [ 'class' => 'label label-success'] );
                            break;
                        case '等待审核':
                            return Html::tag( 'span' , '等待审核' , [ 'class' => 'label label-warning'] );
                            break;
                        case '审核失败':
                            return Html::tag( 'span' , '审核失败' , [ 'class' => 'label label-danger'] );
                            break;
                        case '失效':
                            return Html::tag( 'span' , '失效' , [ 'class' => 'label label-info'] );
                            break;
                    }
                } ,
            ] ,
        ],
        [
            'class'    => ActionColumn::className() ,
            'template' => '{update}{delete}{apply}' ,
            'header'   => '操作详细' ,
            'contentOptions' => ['class' => 'actions'],
            'buttons'  => [
                'update' => function( $url){
                    $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-eye' ] );
                    return Html::a($icon.'编辑',$url, [ 'class' => 'btn btn-info btn-sm btn-icon icon-left'] );
                } ,
                'delete' => function($url,$model){
                    $icon = Html::tag('i','',['class' => 'fa fa-times']);
                    return Html::a($icon.'删除',$url,['class' => 'btn btn-danger btn-sm btn-icon icon-left','data-id' => $model['id']]);
                },
                'apply' => function( $url){
                    $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-bars' ] );
                    return Html::a($icon.'详情',$url, [ 'class' => 'btn btn-success btn-sm btn-icon icon-left'] );
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
    $(document).ready(function () {
        table = $("#show_image");
        if (table.find('img').length > 0){
            var viewer = new Viewer(table[0] , {navbar : false, tooltip : false, scalable : false, fullscreen : false,zIndex : 99999});
        }
    })

    $('a.btn-danger').click(function(){
        var $that = $(this);
        var recruitId = $(this).attr('data-id');
        var confirmText = $('<span>').addClass('color-orange font-16').html("你确定要删除编号 （" + recruitId + "） 的这条招聘吗？");
        var successText = "删除(" + recruitId + ")成功！";
        showConfirmModal(this , confirmText , successText );
        return false;
    });
    $(document).on('pjax:complete',function(){
        table = $("#show_image");
        if (table.find('img').length > 0){
            var viewer = new Viewer(table[0] , {navbar : false, tooltip : false, scalable : false, fullscreen : false,zIndex : 99999});
        }
    })
</script>


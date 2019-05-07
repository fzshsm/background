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
        'label' => '新闻管理' ,
        'url'   => \Yii::$app->request->getReferrer(),
    ] ,
    [
        'label' => '评论回复列表' ,
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
<?php if(Yii::$app->session->hasFlash('error')){ ?>
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <?=Yii::$app->session->getFlash('error')?>
    </div>
<?php }?>

<?php
$form = ActiveForm::begin([
    'id' => 'reply-form',
    'method' => 'get',
    'options' => ['data-pjax' => true , 'news' => 'form']
] );
?>
<style>th{font-weight:bold;font-size:13px}</style>
<?php
ActiveForm::end();
echo GridView::widget( [
    'captionOptions' => ['style' => 'font-size: 16px; font-weight: bold; color: #000; text-align: center;'],
    'id'               => 'season' ,
    'dataProvider'     => $dataProvider ,
    'emptyText'        => "暂无回复信息！" ,
    'emptyTextOptions' => [ 'class' => 'text-center' ] ,
    'tableOptions'     => [ 'class' => 'table table-bordered datatable no-footer hover stripe text-center dataTable' ,'id' => 'show_image'] ,
    'options'          => [ 'class' => 'dataTables_wrapper no-footer no-border' ] ,
    'layout'           => "{summary}{items}{pager}" ,
    'summary' => '<span style="color: red;font-size: 15px">总共有'.$commentDetail->reply.'条回复评论</span>',
    "columns"          => [
        [
            'label' => 'ID',
            'attribute' => 'id',
            'value' => 'id',
            'headerOptions' =>['width' => '3%'],
            'enableSorting'=>true,
            'format' => 'raw'
        ],
        [
            'attribute' => '昵称',
            'value' => 'nickname',
            'headerOptions' =>['width' => '3%'],
            'enableSorting' => true,
            'contentOptions' => ['style' => 'text-align:left']
        ],
        [
            'label' => '头像',
            'format' => [
                'image',
                [
                    'width'=>'120',
                    'height'=>'80'
                ]
            ],
            'value' => function ($model) {
                return $model['avatar'];
            },
            'headerOptions' =>['width' => '6%'],
        ],
        [
            'attribute' => '内容',
            'value' => 'content',
            'headerOptions' =>['width' => '30%'],
            'contentOptions' => ['style' => 'white-space: normal;text-align:left;', 'width' => '30%'],
        ],
        [
            'label' => '点赞数',
            'attribute' => 'praise',
            'value' => function($model){
                return Html::tag('span',$model['praise'],['class' => 'badge badge-danger']);
            },
            'format' => 'raw',
            'headerOptions' =>['width' => '3%']
        ],
        [
            'label' => '回复时间',
            'attribute' => 'create_time',
            'value'=>
                function($model){
                    return  date("Y.m.d H:i:s",$model['create_time']);
                },
            'headerOptions' =>['width' => '8%']
        ],
        [
            'class'    => ActionColumn::className() ,
            'template' => '{status}' ,
            'header'   => '回复状态' ,
            'contentOptions' => ['class' => 'text-center'],
            'headerOptions' => ['width' => '6%'],
            'buttons'  => [
                'status' => function( $url,$model ){
                    switch ($model['status']){
                        case 'normal':
                            return Html::tag( 'span' , '正常' , [ 'class' => 'label label-success'] );
                            break;
                        case 'delete':
                            return Html::tag( 'span' , '删除' , [ 'class' => 'label label-danger'] );
                            break;
                    }
                } ,
            ] ,
        ],
        [
            'class'    => ActionColumn::className() ,
            'template' => '{delete}{normal}' ,
            'header'   => '操作' ,
            'contentOptions' => ['class' => 'actions'],
            'headerOptions' => ['width' => '7%'],
            'buttons'  => [
                'delete' => function ($url,$model) {
                    $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-times' ] );
                    if($model['status'] == 'normal'){
                        return Html::a($icon.'删除',Url::to(['/news/comment/delete','id' => $model['id']]),['class' => 'delete btn btn-danger btn-sm btn-icon icon-left']);
                    }
                },
                'normal' => function ($url,$model) {
                    $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-check' ] );
                    if($model['status'] == 'delete'){
                        return Html::a($icon.'恢复',Url::to(['/news/comment/normal','id' => $model['id']]),['class' => 'normal btn btn-success btn-sm btn-icon icon-left']);
                    }
                },
            ] ,
        ] ,
    ] ,
    'pager'            => [
        'options'     => [ 'class' => 'pagination dataTables_paginate paging_simple_numbers' ] ,
        'linkOptions' => [ 'class' => 'paginate_button' ] ,
    ] ,
] );

?>

<script>
    $(document).ready(function(){
        table = $("#show_image");
        if (table.find('img').length > 0){
            var viewer = new Viewer(table[0] , {navbar : false, tooltip : false, scalable : false, fullscreen : false,zIndex : 99999});
        }
    })

    //更改评论的状态
    $('a.normal').click(function(){
        var $that = $(this);
        var confirmText = $('<span>').addClass('color-orange font-16').html("你确定要恢复这条评论吗？");
        var successText = "发布成功！";
        showConfirmModal(this , confirmText , successText );
        return false;
    });


    $('a.delete').click(function(){
        var $that = $(this);
        var confirmText = $('<span>').addClass('color-orange font-16').html("你确定要删除这条评论吗？");
        var successText = "删除成功！";
        showConfirmModal(this , confirmText , successText );
        return false;
    });
</script>

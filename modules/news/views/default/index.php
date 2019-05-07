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
        'url'   => Url::to( [
            '/news' ,
        ] ) ,
    ] ,
    [
        'label' => '新闻列表' ,
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
<style>
    th{font-weight:bold;font-size:13px}
    table a.btn{margin-top: 4px}
</style>
<?php
$form = ActiveForm::begin([
    'id' => 'news-form',
    'method' => 'get',
    'options' => ['data-pjax' => true , 'news' => 'form']
] );
?>
<a href="<?=Url::to(['/news/create'])?>" class="btn btn-success btn-square radius-4 pull-left">
    <i class="entypo-plus"></i>
    发布
</a>

<div class="form-group pull-right col-md-7">
    <div class="col-sm-1" >
        <a href="<?= Url::to(['/news'])?>" class="btn btn-default" title="刷新">
            <i class="fa fa-refresh"></i>
        </a>
    </div>
    <div class="col-sm-6">
        <div class="input-group">
            <span class="input-group-addon"><i class="entypo-calendar"></i></span>
            <input type="text" class="form-control cursor-pointer" name="date" id="date" readonly placeholder="发布时间" value="<?= Yii::$app->request->get('date'); ?>" >
        </div>
    </div>
    <div class="input-group col-sm-5 pull-right">
        <div class="input-group-btn">
            <button type="button" class="btn btn-success dropdown-toggle btn-width-100" data-searchtype="title">
                标题　
            </button>
        </div>
        <input type="text" id="title" class="form-control" name="title" placeholder="" value="<?= Yii::$app->request->get('title'); ?>">
        <div class="input-group-btn">
            <button  type="submit" class="btn btn-success search">
                <i class="entypo-search"></i>
            </button>
        </div>
        <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
    </div>
</div>
<div class="col-md-12">
    <ul class="nav nav-tabs bordered" style="margin-bottom: 10px">
        <?php if($gameType == '1'){?>
            <?php $glory='active';$pubg= '';?>
        <?php }else{?>
            <?php $glory='';$pubg= 'active';?>
        <?php }?>
        <li class="<?= $glory?>">
            <a data-status="1" href="<?= Url::to(['/news','status' => 1])?>">
                <span>王者荣耀</span>
            </a>
        </li>
        <li class="<?= $pubg?>">
            <a data-status="2" href="<?= Url::to(['/news','status' => 2])?>">
                <span>绝地求生</span>
            </a>
        </li>
    </ul>
<?php
ActiveForm::end();
echo GridView::widget( [
    'id'               => 'news' ,
    'dataProvider'     => $dataProvider ,
    'emptyText'        => "暂无新闻信息！" ,
    'emptyTextOptions' => [ 'class' => 'text-center' ] ,
    'tableOptions'     => [ 'class' => 'table table-bordered datatable no-footer hover stripe text-center','id'=> 'show_image' ] ,
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
            'label' => '标题',
            'attribute' => 'title',
            'value' => 'title',
            'headerOptions' =>['width' => '8%'],
            'contentOptions' => ['style' => 'text-align:left']
        ],
        [
            'label' => '游戏类型',
            'attribute' => 'gameType',
            'value'=>
                function($model){
                    $text = '王者荣耀';
                    if($model['gameType'] == 2){
                        $text = '绝地求生';
                    }
                    return  $text;
                },
            'headerOptions' => ['width' => '3%'],
        ],
        [
            'label' => '封面',
            'format' => [
                'image',
                [
                    'width'=>'150',
                    'height'=>'80',
                    'name' => 'show_image'
                ]
            ],
            'value' => function ($model) {
                return $model['cover'];
            },
            'headerOptions' =>['width' => '10%']
        ],
        [
            'attribute' => '作者',
            'value' => 'author',
            'headerOptions' =>['width' => '3%'],
            'contentOptions' => ['style' => 'text-align:left']
        ],
        [
            'label' => '浏览量',
            'attribute' => 'view',
            'value' => function($model){
                return Html::tag('span',isset($model['view']) ? $model['view'] : '',['class' => 'badge badge-orange']);

            },
            'format' => 'raw',
            'headerOptions' =>['width' => '1%'],
        ],
        [
            'label' => '点赞数',
            'attribute' => 'praise',
            'value' => function($model){
                return Html::tag('span', isset($model['praise']) ? $model['praise'] : '',['class' => 'badge badge-danger']);

            },
            'format' => 'raw',
            'headerOptions' =>['width' => '1%']
        ],
        [
            'label' => '评论数',
            'attribute' => 'comment',
            'value' => function($model){
                return Html::tag('span',isset($model['comment']) ? $model['comment'] : '',['class' => 'badge badge-info']);

            },
            'format' => 'raw',
            'headerOptions' =>['width' => '1%']
        ],
        [
            'label'=>'位置',
            'attribute' => 'postion',
            'value' => function ($model) {
                $postion = [
                    'normal' => '资讯',
                    'banner' => '轮播',
                    'choice' => '精选',
                ];
                return $postion[$model['postion']];
            },
            'headerOptions' => ['width' => '1%']
        ],
        [
            'label' => '排序',
            'attribute' => 'sequence',
            'value' => 'sequence',
            'headerOptions' =>['width' => '1%']
        ],
        [
            'label' => '发布时间',
            'attribute' => 'releaseTime',
            'value'=>
                function($model){
                    return  date("Y-m-d H:i:s",$model['releaseTime']);
                },
            'headerOptions' => ['width' => '3%'],
        ],
        [
            'class'    => ActionColumn::className() ,
            'template' => '{status}' ,
            'header'   => '新闻状态' ,
            'contentOptions' => ['class' => 'text-center'],
            'headerOptions' => ['width' => '3%'],
            'buttons'  => [
                'status' => function( $url,$model ){
                    switch ($model['status']){
                        case 'release':
                            return Html::tag( 'span' , '发布' , [ 'class' => 'label label-success'] );
                            break;
                        case 'wait':
                            return Html::tag( 'span' , '未发布' , [ 'class' => 'label label-default'] );
                            break;
                        case 'close':
                            return Html::tag( 'span' , '关闭' , [ 'class' => 'label label-danger'] );
                            break;
                    }
                } ,
            ] ,
        ],
        [
            'class'    => ActionColumn::className() ,
            'template' => '{update}{release}' ,
            'header'   => '操作' ,
            'contentOptions' => ['class' => 'actions'],
            'headerOptions' =>['width' => '12%'],
            'buttons'  => [
                'update' => function( $url ){
                    $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-pencil' ] );
                    return Html::a( $icon . '编辑' , $url , [ 'class' => 'btn btn-default btn-sm btn-icon icon-left'] );
                } ,
//                'comment' => function ($url,$model) {
//                    $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-comments' ] );
//                    return Html::a($icon.'评论',Url::to(['/news/comment','id' => $model['id']]),['class' => 'btn btn-orange btn-sm btn-icon icon-left']);
//                },
                'release' => function ($url,$model) {
                    $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-check' ] );
                    if($model['status'] == 'close'){
                        return Html::a($icon.'发布',Url::to(['/news/release','id' => $model['id']]),['class' => 'release btn btn-green btn-sm btn-icon icon-left','data-name' => $model['title']]);
                    }
                },
//                'wait' => function ($url,$model) {
//                    $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-minus-circle' ] );
//                    $new_id = $model['id'];
//                    return Html::button($icon.'待发布',['class' => 'btn btn-orange btn-sm btn-icon icon-left','onclick' => "changeNewStatus($new_id,'wait')"]);
//                },
//                'close' => function ($url,$model) {
//                    $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-times' ] );
//                    if($model['status'] == 'release'){
//                        return Html::a($icon.'关闭',Url::to(['/news/close','id' => $model['id']]),['class' => 'delete btn btn-danger btn-sm btn-icon icon-left','data-name' => $model['title']]);
//                    }
//                }
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
</div>
<script>
    jQuery( document ).ready( function( $ ){
        $('#date').daterangepicker({
            startDate: "<?= date('Y-m-d H:i' , strtotime( "-3 days")) ?>",
            endDate: "<?= date('Y-m-d H:i') ?>",
            showWeekNumbers : false, //是否显示第几周
            timePicker : false, //是否显示小时和分钟
            opens : 'right', //日期选择框的弹出位置
            buttonClasses : [ 'btn btn-default' ],
            applyClass : 'btn-small btn-success',
            cancelClass : 'btn-small',
            format : 'YYYY-MM-DD', //控件中from和to 显示的日期格式
            separator : ' 至 ',
            locale : {
                applyLabel : '确定',
                cancelLabel : '取消',
                fromLabel : '开始时间',
                toLabel : '结束时间',
                daysOfWeek : [ '日', '一', '二', '三', '四', '五', '六' ],
                monthNames : [ '一月', '二月', '三月', '四月', '五月', '六月',
                    '七月', '八月', '九月', '十月', '十一月', '十二月' ],

            }
        }, function(start, end, label) {//格式化日期显示框
            $('#date span').html(start.format('YYYY-MM-DD') + ' 至 ' + end.format('YYYY-MM-DD'));
        }).on('apply.daterangepicker' , function(){
            $('button.search').trigger('click');
        });
        table = $('#show_image');
        if (table.find('img').length > 0){
            var viewer = new Viewer(table[0] , {navbar : false, tooltip : false, scalable : false, fullscreen : false,zIndex : 99999});
        }
    })

    $('a.release').click(function(){
        var $that = $(this);
        var medalName = $(this).attr('data-name');
        var confirmText = $('<span>').addClass('color-orange font-16').html("你确定要发布 " + medalName + " 的这条新闻吗？");
        var successText = "发布" + medalName + "成功！";
        showConfirmModal(this , confirmText , successText );
        return false;
    });


    $('a.delete').click(function(){
        var $that = $(this);
        var medalName = $(this).attr('data-name');
        var confirmText = $('<span>').addClass('color-orange font-16').html("你确定要删除 " + medalName + " 的这条新闻吗？");
        var successText = "删除" + medalName + "成功！";
        showConfirmModal(this , confirmText , successText );
        return false;
    });

    $(document).on('pjax:complete',function(){
        table = $('#show_image');
        if (table.find('img').length > 0){
            var viewer = new Viewer(table[0] , {navbar : false, tooltip : false, scalable : false, fullscreen : false,zIndex : 99999});
        }

        $('#date').daterangepicker({
            startDate: "<?= date('Y-m-d H:i' , strtotime( "-3 days")) ?>",
            endDate: "<?= date('Y-m-d H:i') ?>",
            showWeekNumbers : false, //是否显示第几周
            timePicker : false, //是否显示小时和分钟
            opens : 'right', //日期选择框的弹出位置
            buttonClasses : [ 'btn btn-default' ],
            applyClass : 'btn-small btn-success',
            cancelClass : 'btn-small',
            format : 'YYYY-MM-DD', //控件中from和to 显示的日期格式
            separator : ' 至 ',
            locale : {
                applyLabel : '确定',
                cancelLabel : '取消',
                fromLabel : '开始时间',
                toLabel : '结束时间',
                daysOfWeek : [ '日', '一', '二', '三', '四', '五', '六' ],
                monthNames : [ '一月', '二月', '三月', '四月', '五月', '六月',
                    '七月', '八月', '九月', '十月', '十一月', '十二月' ],

            }
        }, function(start, end, label) {//格式化日期显示框
            $('#date span').html(start.format('YYYY-MM-DD') + ' 至 ' + end.format('YYYY-MM-DD'));
        }).on('apply.daterangepicker' , function(){
            $('button.search').trigger('click');
        });
    })
</script>

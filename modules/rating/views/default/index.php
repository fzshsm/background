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
        'label' => '积分评级' ,
        'url'   => Url::to( [
            '/rating' ,
        ] ) ,
    ] ,
    [
        'label' => ' 积分列表' ,
    ] ,
];
AppAsset::registerCss( $this , '@web/plugin/datatables/datatables.min.css' );
AppAsset::registerCss($this , '@web/plugin/daterangepicker/daterangepicker-bs3.css');

AppAsset::registerJs($this , '@web/plugin/daterangepicker/moment.min.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/daterangepicker.js');
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
    <a class="btn btn-success btn-square radius-4" href="<?=Url::to(['/rating/create'])?>"><i class="entypo-plus"></i>创建</a>
</div>
<?php
$form = ActiveForm::begin([
    'id' => 'rating-form',
    'method' => 'get',
    'options' => ['data-pjax' => true , 'role' => 'form']
] );
?>
<div class="form-group  game-search-filter col-md-12">
    <div class="input-group col-sm-3 pull-right">
        <div class="col-sm-1 padding-left-none">
            <a href="<?= Url::to(['/rating'])?>" class="btn btn-default" title="刷新">
                <i class="fa fa-refresh"></i>
            </a>
        </div>
        <div class="input-group-btn search-type">
            <?php
            $searchType = Yii::$app->request->get('searchType' , 'personName');
            $searchTypeList = ['personName' => '姓名' , 'qq' => 'QQ'];
            ?>
            <input type="hidden" id="searchType" name="searchType" value="<?=$searchType?>">
            <button type="button" class="btn btn-success dropdown-toggle btn-width-100" data-searchtype="<?=$searchType?>"  data-toggle="dropdown">
                <?= $searchTypeList[$searchType] ?>　<span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-green">
                <?php foreach($searchTypeList as $key => $value){ ?>
                    <li><a href="javascript:void(0);" data-searchtype="<?= $key?>"><?= $value?></a></li>
                <?php } ?>
            </ul>
        </div>
        <input type="text" id="content" class="form-control" name="content" placeholder="" value="<?= Yii::$app->request->get('content'); ?>">
        <div class="input-group-btn">
            <button  type="submit" class="btn btn-success search">
                <i class="entypo-search"></i>
            </button>
        </div>
    </div>
</div>
<style>th{font-weight:bold;font-size:13px}</style>
<?php
ActiveForm::end();
echo GridView::widget( [
    'id'               => 'season' ,
    'dataProvider'     => $dataProvider ,
    'emptyText'        => "暂无用户评级信息！" ,
    'emptyTextOptions' => [ 'class' => 'text-center' ] ,
    'tableOptions'     => [ 'class' => 'table table-bordered datatable no-footer hover stripe text-center' ] ,
    'options'          => [ 'class' => 'dataTables_wrapper no-footer no-border' ] ,
    'layout'           => "{errors}{items}{pager}" ,
    "columns"          => [
        'recordId:raw:ID' ,
        'personName:text:姓名',
        'clubName:text:战队',
        'qq:text:QQ',
        'typeName:text:赛事类型',
        [
            'attribute' => 'isCore',
            'label' => '主力',
            'format' => 'raw',
            'value' => function($model){
                $class = $model['isCore'] == 1 ? 'fa fa-check-circle color-green font-18':'fa fa-times-circle color-red font-18';
                return Html::tag('span','',['class' => $class]);
            }
        ],
        [
            'label' => '主力得分',
            'attribute' => 'coreScore',
            'value' => 'coreScore',
            'format' => 'raw',
            'headerOptions' =>['width' => '3%']
        ],
        [
            'attribute' => 'isMvp',
            'label' => 'MVP',
            'format' => 'raw',
            'value' => function($model){
                $class = $model['isMvp'] == 1 ? 'fa fa-check-circle color-green font-18':'fa fa-times-circle color-red font-18';
                return Html::tag('span','',['class' => $class]);
            }
        ],
        [
            'label' => 'MVP得分',
            'attribute' => 'mvpScore',
            'value' => 'mvpScore',
            'format' => 'raw',
            'headerOptions' =>['width' => '3%']
        ],
        'remark:text:规则',
        [
            'label' => '规则得分',
            'attribute' => 'gameScore',
            'value' => 'gameScore',
            'format' => 'raw',
            'headerOptions' =>['width' => '3%']
        ],
        [
            'label' => '当前得分',
            'attribute' => 'score',
            'value' => 'score',
            'format' => 'raw',
            'headerOptions' =>['width' => '3%']
        ],
        [
            'label' => '总分',
            'attribute' => 'totalScore',
            'value' => 'totalScore',
            'format' => 'raw',
            'headerOptions' =>['width' => '3%']
        ],
        [
            'class'    => ActionColumn::className() ,
            'template' => '{update} {delete}' ,
            'header'   => '操作' ,
            'contentOptions' => ['class' => 'actions'],
            'buttons'  => [
                'update' => function( $url ){
                    $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-pencil' ] );
                    return Html::a( $icon . '编辑' , $url , [ 'class' => 'btn btn-default btn-sm btn-icon icon-left' ] );
                } ,
                'delete' => function( $url , $model ){
                    $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-times' ] );
                    return Html::a( $icon . '删除' , $url , [ 'class' => 'btn btn-danger btn-sm btn-icon icon-left'  , 'data-id' => $model['recordId'] ] );
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
    jQuery( document ).ready( function( $ ){
        $('.search-type .dropdown-menu a').click(function(){
            $('#searchType').val($(this).attr('data-searchtype'));
            $('.search-type button.dropdown-toggle').attr('data-searchtype' , $(this).attr('data-searchtype'));
            $('.search-type button.dropdown-toggle').html($(this).text() +　'　<span class="caret"></span>');
        });
        $('a.btn-danger').click(function(e){
            var id = $(this).attr('data-id');
            var confirmText = $('<span>').addClass('color-orange font-16').html("你确定要删除 编号( " + id + " ) 的这条记录吗？");
            var successText = "删除 积分记录( " + id + " ) 成功！";
            showConfirmModal(this , confirmText , successText);
            return false;
        });
    });
    $(document).on('pjax:complete',function(){
        $('.search-type .dropdown-menu a').click(function(){
            $('#searchType').val($(this).attr('data-searchtype'));
            $('.search-type button.dropdown-toggle').attr('data-searchtype' , $(this).attr('data-searchtype'));
            $('.search-type button.dropdown-toggle').html($(this).text() +　'　<span class="caret"></span>');
        });
    })
</script>

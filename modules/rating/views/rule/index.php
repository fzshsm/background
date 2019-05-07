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
        'label' => '赛事规则' ,
        'url'   => Url::to( [
            '/rating/match' ,
        ] ) ,
    ] ,
    [
        'label' => '积分规则' ,
    ] ,
];

AppAsset::registerCss( $this , '@web/plugin/datatables/datatables.min.css' );
AppAsset::registerJs($this , '@web/js/common.js');
Pjax::begin();
?>
<div class="form-group col-md-12">
    <a class="create btn btn-success btn-square radius-4 pull-right"  href="<?= Url::to(['/rating/rule/create','id' => \Yii::$app->request->get('id'),
        'gameName' => \Yii::$app->request->get('gameName')])?>"><i class="entypo-plus"></i>创建</a>
</div>
<?php
$form = ActiveForm::begin([
    'id' => 'rule-form',
    'method' => 'get',
    'options' => ['data-pjax' => true , 'rule' => 'form']
] );
?>
<?php
ActiveForm::end();
echo GridView::widget( [
    'id'               => 'rule' ,
    'dataProvider'     => $dataProvider ,
    'emptyText'        => "暂无积分信息！" ,
    'emptyTextOptions' => [ 'class' => 'text-center' ] ,
    'tableOptions'     => [ 'class' => 'table table-bordered datatable no-footer hover stripe text-center dataTable'] ,
    'options'          => [ 'class' => 'dataTables_wrapper no-footer no-border' ] ,
    'layout'           => "{errors}{items}{pager}" ,
    "columns"          => [
        'id:raw:ID' ,
        [
            'label' => '赛事名',
            'value' => function() use ($gameName){
                return $gameName;
            }
        ],
        [
            'label' => '规则',
            'attribute' => 'type',
            'value' => function($model){
                $typeName = [1 => '主力',2 => 'MVP',3 => '得分理由'];
                return $typeName[$model['type']];
            },
            'format' => 'raw',
            'headerOptions' =>['width' => '5%'],
        ],
        "scoreOne:raw:是",
        "scoreTwo:raw:否",
        "remark:raw:备注：得分理由",
        [
            'attribute' => '更改时间',
            'value' => function ($model) {
                $time = substr($model['time'],0,10);
                return date("Y-m-d H:i:s",$time);
            }
        ],
        [
            'class'    => ActionColumn::className() ,
            'template' => '{update} {delete}' ,
            'header'   => '操作' ,
            'contentOptions' => ['class' => 'actions'],
            'buttons'  => [
                'update' => function( $url ) use ($gameName){
                    $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-pencil' ] );
                    return Html::a( $icon . '编辑' , $url.'&gameName='.$gameName, [ 'class' => 'update btn btn-default btn-sm btn-icon icon-left'] );
                } ,
                'delete' => function( $url , $model ){
                    $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-times' ] );
                    return Html::a( $icon . '删除' , $url , [ 'class' => 'btn btn-danger btn-sm btn-icon icon-left delete-rule','data-id' => $model['id'] ] );
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
    jQuery( document ).ready( function( $ ) {
        $('.delete-rule').click(function (e) {
            var id = $(this).attr('data-id');
            var confirmText = $('<span>').addClass('color-orange font-16').html("你确定要删除 编号( " + id + " ) 的这条积分规则吗？");
            var successText = "删除 积分规则( " + id + " ) 成功！";
            showConfirmModal(this, confirmText, successText);
            return false;
        });
    })
</script>

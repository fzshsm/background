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
    [
        'label' => '积分规则' ,
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
    <a class="create btn btn-success btn-square radius-4 pull-right"  href="<?= Url::to(['/pubg/rule/create'])?>"><i class="entypo-plus"></i>创建</a>
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
    'emptyText'        => "暂无积分规则信息！" ,
    'emptyTextOptions' => [ 'class' => 'text-center' ] ,
    'tableOptions'     => [ 'class' => 'table table-bordered datatable no-footer hover stripe text-center dataTable'] ,
    'options'          => [ 'class' => 'dataTables_wrapper no-footer no-border' ] ,
    'layout'           => "{errors}{items}{pager}" ,
    "columns"          => [
        'configName:text:配置名',
        [
            'label' => '积分规则',
            'format' => 'raw',
            'headerOptions' =>['width' => '20%'],
            'contentOptions' => ['class' => 'text-mess msg'],
            'value' => function ($model) {
                return '<div class="text-intro"  onmousemove="mousemove($(this))" onmouseout="mouseout($(this))">' . $model['rankScore'] . '</div>';
            }
        ],
        "killScore:raw:击杀得分",
        [
            'attribute' => 'status',
            'label' => '状态',
            'format' => 'html',
            'headerOptions' =>['width' => '22%'],
            'value' => function ($model) {
                $el = '';
                $data = intval($model['status']);
                switch ($data) {
                    case 1 :
                        $el = Html::tag('i', '', ['class' => 'fa fa-check-circle color-green font-18', 'title' => '启用']);

                        break;
                    case 2 :
                        $el = Html::tag('i', '', ['class' => 'fa fa-times-circle color-red font-18', 'title' => '禁用']);
                        break;
                }
                return $el;
            }
        ],
        [
            'class'    => ActionColumn::className() ,
            'template' => '{update}{close}{start}',
            'header'   => '操作' ,
            'contentOptions' => ['class' => 'actions'],
            'buttons'  => [
                'update' => function( $url ){
                    $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-pencil' ] );
                    return Html::a( $icon . '编辑' , $url, [ 'class' => 'update btn btn-default btn-sm btn-icon icon-left'] );
                } ,
                'close' => function( $url,$model){
                    if($model['status'] == 1){
                        $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-times' ] );
                        return Html::a($icon.'禁用',Url::to(['/pubg/rule/status','id' => $model['id'],'status' => 2]), [ 'class' => 'btn btn-danger btn-sm btn-icon icon-left','data-name' => $model['configName']] );
                    }
                } ,
                'start' => function($url,$model){
                    if($model['status'] == 2){
                        $icon = Html::tag('i','',['class' => 'fa fa-check']);
                        return Html::a($icon.'启用',Url::to(['/pubg/rule/status','id' => $model['id'],'status' => 1]),['class' => 'start btn btn-success btn-sm btn-icon icon-left','data-name' => $model['configName']]);
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
Pjax::end();
?>

<script>
    $(document).ready(function(){
        $('a.btn-danger').click(function(){
            var configName = $(this).attr('data-name');
            var confirmText = $('<span>').addClass('color-orange font-16').html("确定禁用（" + configName + "） 这份配置吗？");
            var successText = "已禁用(" + configName + ")这份配置！";
            showConfirmModal(this , confirmText , successText );
            return false;
        });

        $('a.start').click(function(){
            var configName = $(this).attr('data-name');
            var confirmText = $('<span>').addClass('color-orange font-16').html("确定启用（" + configName + "） 这份配置吗？");
            var successText = "已启用(" + configName + ") 这份配置！";
            showConfirmModal(this , confirmText , successText );
            return false;
        });
    })
    function mousemove(e){
        e.removeClass('text-intro');
        e.addClass('text-intro-show');
    }
    function mouseout(e){
        e.removeClass('text-intro-show');
        e.addClass('text-intro');
    }
</script>

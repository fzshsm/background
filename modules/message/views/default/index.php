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
        'label' => '消息管理' ,
        'url'   => Url::to( [
            '/message' ,
        ] ) ,
    ] ,
    [
        'label' => '消息列表' ,
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

<?php
$form = ActiveForm::begin([
    'id' => 'message-form',
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
<a href="<?=Url::to(['/message/create'])?>" class="btn btn-success btn-square radius-4 pull-right">
    <i class="entypo-plus"></i>
    推送
</a>
<?php
ActiveForm::end();
echo GridView::widget( [
    'id'               => 'club' ,
    'dataProvider'     => $dataProvider ,
    'emptyText'        => "暂无推送消息信息！" ,
    'emptyTextOptions' => [ 'class' => 'text-center' ] ,
    'tableOptions'     => [ 'class' => 'table table-bordered datatable no-footer hover stripe text-center dataTable'] ,
    'options'          => [ 'class' => 'dataTables_wrapper no-footer no-border' ] ,
    'layout'           => "{errors}{items}{pager}" ,
    "columns"          => [
        [
            'label' => '标题',
            'value' => 'title',
            'headerOptions' => ['width' => '5%'],
            'contentOptions' => ['style' => 'text-align:left']
        ],
        [
            'label' => '内容',
            'value' => 'context',
            'headerOptions' => ['width' => '40%'],
            'contentOptions' => ['style' => 'text-align:left']
        ],
        [
            'label' => '状态',
            'value' => 'status',
            'headerOptions' => ['width' => '2%'],
        ],
        [
            'label' => '发送时间',
            'value' => 'time',
            'headerOptions' => ['width' => '8%'],
        ],
        [
            'label' => '发送人',
            'value' => 'userName',
            'headerOptions' => ['width' => '8%'],
        ],
    ] ,
    'pager'            => [
        'options'     => [ 'class' => 'pagination dataTables_paginate paging_simple_numbers' ] ,
        'linkOptions' => [ 'class' => 'paginate_button' ] ,
    ] ,
] );
Pjax::end();
?>


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
        'label' => '赛事列表' ,
        'url' => '/rating/'
    ] ,
];
AppAsset::registerCss($this, '@web/plugin/viewer/viewer.css');
AppAsset::registerJs($this, '@web/plugin/viewer/viewer.js');
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
    <a class="create btn btn-success btn-square radius-4 pull-right"  href="<?= Url::to(['/rating/match/create'])?>"><i class="entypo-plus"></i>创建</a>
</div>
<?php
$form = ActiveForm::begin([
    'id' => 'game-form',
    'method' => 'get',
    'options' => ['data-pjax' => true , 'game' => 'form']
] );
?>

<?php
ActiveForm::end();
echo GridView::widget( [
    'id'               => 'game' ,
    'dataProvider'     => $dataProvider ,
    'emptyText'        => "暂无赛事信息！" ,
    'emptyTextOptions' => [ 'class' => 'text-center' ] ,
    'tableOptions'     => [ 'class' => 'table table-bordered datatable no-footer hover stripe text-center dataTable' ,'id' => 'show_image'] ,
    'options'          => [ 'class' => 'dataTables_wrapper no-footer no-border' ] ,
    'layout'           => "{errors}{items}{pager}" ,
    "columns"          => [
        'id:raw:ID' ,
        'name:text:赛事名称',
        [
            'label' => '赛事图标',
            'format' => [
                'image',
                [
                    'height'=>'65',
                ]
            ],
            'value' => function ($model) {
                return $model['icon'].'?imageMogr2/thumbnail/x65/format/png/interlace/1/quality/100';
            },
        ],
        [
            'class'    => ActionColumn::className() ,
            'template' => '{update} {delete}{rule}' ,
            'header'   => '操作' ,
            'contentOptions' => ['class' => 'actions'],
            'buttons'  => [
                'update' => function( $url ,$model){
                    $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-pencil' ] );
                    return Html::a( $icon . '编辑' , $url , [ 'class' => 'update btn btn-default btn-sm btn-icon icon-left'] );
                } ,
                'delete' => function( $url , $model ){
                    $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-times' ] );
                    return Html::a( $icon . '删除' , $url , [ 'class' => 'btn btn-danger btn-sm btn-icon icon-left'  , 'data-id' => $model['id'] ] );
                } ,
                'rule' => function( $url , $model ){
                    $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-bars' ] );
                    return Html::a( $icon . '积分规则' , Url::to(['/rating/rule','id' => $model['id'],'gameName' => $model['name']]) , [ 'class' => 'btn btn-info btn-sm btn-icon icon-left'] );
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
        $('a.btn-danger').click(function(e){
            var id = $(this).attr('data-id');
            var confirmText = $('<span>').addClass('color-orange font-16').html("你确定要删除 编号( " + id + " ) 的这条赛事吗？");
            var successText = "删除 赛事( " + id + " ) 成功！";
            showConfirmModal(this , confirmText , successText);
            return false;
        });
        table = $("#show_image");
        if (table.find('img').length > 0){
            var viewer = new Viewer(table[0] , {navbar : false, tooltip : false, scalable : false, fullscreen : false,zIndex : 99999});
        }
    });

    $(document).on('pjax:complete',function(){
        table = $("#show_image");
        if (table.find('img').length > 0){
            var viewer = new Viewer(table[0] , {navbar : false, tooltip : false, scalable : false, fullscreen : false,zIndex : 99999});
        }
    })
</script>

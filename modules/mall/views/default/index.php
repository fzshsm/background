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
        'label' => '商城管理' ,
        'url'   => Url::to( [
            '/mall' ,
        ] ) ,
    ] ,
    [
        'label' => '商城列表' ,
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
    table a.btn{margin-top: 4px}
</style>
<?php
$form = ActiveForm::begin([
    'id' => 'mall-form',
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
<a href="<?=Url::to(['/mall/create'])?>" class="btn btn-success btn-square radius-4 pull-right">
    <i class="entypo-plus"></i>
    创建
</a>
<div class="col-md-12">
<div id="league-search" style="margin-bottom: 10px;margin-top: 5px;float: right" >
    <div class="col-sm-1 refresh" style="margin-right: 5%">
        <a href="<?= Url::to(['/mall'])?>" class="btn btn-default" title="刷新">
            <i class="fa fa-refresh"></i>
        </a>
    </div>

    <div class="input-group league-search-type" style="width: 400px">
        <div class="input-group-btn search-type" style="padding-right: 10px">
            <?php
            $goodsStatus = Yii::$app->request->get('goodsStatus' , 3);
            $goodsStatusList = [0 => '待上架', 1 => '上架', 2 => '下架', 3 => '全部']
            ?>
            <input type="hidden" id="goodsStatus" name="goodsStatus" value="<?=$goodsStatus?>">
            <button type="button" class="btn btn-info dropdown-toggle type" style="width: 80px" data-searchtype="<?= $goodsStatus?>"  data-toggle="dropdown">
                <?= $goodsStatusList[$goodsStatus] ?>　<span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-infoblue searchtype">
                <?php foreach($goodsStatusList as $key => $value){ ?>
                    <li>
                        <a data-searchtype="<?= $key ?>" href="javascript:void(0);"><?= $value ?></a>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <div class="input-group-btn search-type">
            <button type="button" class="btn btn-success  " style="width: 60px" data-searchtype="name"  data-toggle="dropdown">
                商品名
            </button>
        </div>
        <?php
        $goodsName = \Yii::$app->request->get('goodsName','');
        ?>
        <input type="text" id="goodsName" class="form-control" name="goodsName" placeholder="" value="<?= !empty($goodsName) ? $goodsName : ''?>">
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
    'id'               => 'mall' ,
    'dataProvider'     => $dataProvider ,
    'emptyText'        => "暂无商品信息！" ,
    'emptyTextOptions' => [ 'class' => 'text-center' ] ,
    'tableOptions'     => [ 'class' => 'table table-bordered datatable no-footer hover stripe text-center dataTable','id'=> 'show_image' ] ,
    'options'          => [ 'class' => 'dataTables_wrapper no-footer no-border' ] ,
    'layout'           => "{errors}{items}{pager}" ,
    "columns"          => [
        'goodsName:text:商品名',
        [
            'label' => '商品类型',
            'attribute' => 'type',
            'value' => function($model){
                switch ($model['type']){
                    case 1:
                        return Html::tag( 'span' , '充值卡');
                        break;
                    case 2:
                        return Html::tag( 'span' , '优惠券');
                        break;
                    case 3:
                        return Html::tag( 'span' , '游戏周边');
                        break;
                    case 4:
                        return Html::tag( 'span' , '电子产品');
                        break;
                    case 5:
                        return Html::tag( 'span' , '房卡');
                        break;
                }
            },
            'format' => 'raw'
        ],
        [
            'label' => '图标',
            'format' => [
                'image',
                [
                    'height'=>'40',
                ]
            ],
            'value' => function ($model) {
                return $model['goodsImg'];
            },
            'headerOptions' =>['width' => '2%']
        ],
        'price:text:价格',
        'stockCount:text:库存量',
        'limitCount:text:限购量',
        'saleCount:text:销售量',
        'sortWeight:text:权重',
        [
            'label' => '商品状态',
            'attribute' => 'status',
            'value' => function($model){
                switch ($model['status']){
                    case 0:
                        return Html::tag( 'span' , '待上架' , [ 'class' => 'label label-default'] );
                        break;
                    case 1:
                        return Html::tag( 'span' , '已上架' , [ 'class' => 'label label-success'] );
                        break;
                    case 2:
                        return Html::tag( 'span' , '已下架' , [ 'class' => 'label label-primary'] );
                        break;
                }
            },
            'format' => 'raw'
        ],
        [
            'class'    => ActionColumn::className() ,
            'template' => '{update}{unshelve}{shelve}{delete}' ,
            'header'   => '操作' ,
            'contentOptions' => ['class' => 'actions'],
            'headerOptions' =>['width' => '12%'],
            'buttons'  => [
                'update' => function( $url ){
                    $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-pencil' ] );
                    return Html::a( $icon . '编辑' , $url , [ 'class' => 'btn btn-default btn-sm btn-icon icon-left'] );
                } ,
//                'detail' => function( $url,$model){
//                    $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-bars' ] );
//                    return Html::a($icon.'详情',Url::to(['/mall/detail?id=']).$model['id'], [ 'class' => 'btn btn-info btn-sm btn-icon icon-left'] );
//                } ,
                'unshelve' => function($url,$model){
                    $status = $model['status'];
                    if($status == 1){
                        $icon = Html::tag('i','',['class' => 'fa fa-times']);
                        return Html::a($icon.'下架',$url,['class' => 'unshelve btn btn-primary btn-sm btn-icon icon-left','data-name' => $model['goodsName']]);
                    }
                },
                'shelve' => function($url,$model){
                    $status = $model['status'];
                    if($status == 2 || $status == 0){
                        $icon = Html::tag('i','',['class' => 'fa fa-check']);
                        return Html::a($icon.'上架',$url,['class' => 'shelve btn btn-success btn-sm btn-icon icon-left','data-name' => $model['goodsName']]);
                    }
                },
                'delete' => function($url,$model){
                    $icon = Html::tag('i','',['class' => 'fa fa-times']);
                    return Html::a($icon.'删除',$url,['class' => 'btn btn-danger btn-sm btn-icon icon-left','data-name' => $model['goodsName']]);
                }
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
    $(document).ready(function () {
        table = $('#show_image');
        if (table.find('img').length > 0){
            var viewer = new Viewer(table[0] , {navbar : false, tooltip : false, scalable : false, fullscreen : false,zIndex : 99999});
        }

        $('.search-type .dropdown-menu a').click(function(){
            $('#goodsStatus').val($(this).attr('data-searchtype'));
            $('.search-type button.dropdown-toggle').attr('data-searchtype' , $(this).attr('data-searchtype'));
            $('.search-type button.dropdown-toggle').html($(this).text() +　'　<span class="caret"></span>');
            $('button.search').trigger('click');
        });
    });

    $('a.shelve').click(function(){
        var $that = $(this);
        var goodsName = $(this).attr('data-name');
        var confirmText = $('<span>').addClass('color-orange font-16').html("你确定要上架 (" + goodsName + ") 这件商品吗？");
        var successText = "上架(" + goodsName + ")成功！";
        showConfirmModal(this , confirmText , successText );
        return false;
    });

    $('a.unshelve').click(function(){
        var $that = $(this);
        var goodsName = $(this).attr('data-name');
        var confirmText = $('<span>').addClass('color-orange font-16').html("你确定要下架 （" + goodsName + "） 这件商品吗？");
        var successText = "下架(" + goodsName + ")成功！";
        showConfirmModal(this , confirmText , successText );
        return false;
    });

    $('a.btn-danger').click(function(){
        var $that = $(this);
        var goodsId = $(this).attr('data-name');
        var confirmText = $('<span>').addClass('color-orange font-16').html("你确定要删除编号为 （" + goodsId + "） 的这个商品吗？");
        var successText = "删除(" + goodsId + ")成功！";
        showConfirmModal(this , confirmText , successText );
        return false;
    });

</script>

<?php
Pjax::end();
?>
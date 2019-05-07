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
        'label' => '奖金列表' ,
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
<a href="<?=Url::to(['/mall/bonus/create'])?>" class="btn btn-success btn-square radius-4 pull-right">
    <i class="entypo-plus"></i>
    创建
</a>
<div class="col-md-12">
<div id="league-search" style="margin-bottom: 10px;margin-top: 5px;float: right" >
    <div class="col-sm-1 refresh" style="margin-right: 5%">
        <a href="<?= Url::to(['/mall/bonus'])?>" class="btn btn-default" title="刷新">
            <i class="fa fa-refresh"></i>
        </a>
    </div>

    <div class="input-group league-search-type" style="width: 400px">
        <div class="input-group-btn search-type" style="padding-right: 10px">
            <?php
                $searchType = Yii::$app->request->get('searchType' , 0);
                $searchTypeList = [0 => '全部', 1 => '豆豆', 2 => '狗粮', 3 => '人民币'];
            ?>
            <input type="hidden" id="searchType" name="searchType" value="<?=$searchType?>">
            <button type="button" class="btn btn-info dropdown-toggle type" style="width: 80px" data-searchtype="<?= $searchType?>"  data-toggle="dropdown">
                <?= $searchTypeList[$searchType] ?>　<span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-infoblue searchtype">
                <?php foreach($searchTypeList as $key => $value){ ?>
                    <li>
                        <a data-searchtype="<?= $key ?>" href="javascript:void(0);"><?= $value ?></a>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <div class="input-group-btn search-type">
            <button type="button" class="btn btn-success  " style="width: 60px" data-searchtype="name"  data-toggle="dropdown">
                奖金名
            </button>
        </div>
        <?php
        $content = \Yii::$app->request->get('content','');
        ?>
        <input type="text" id="content" class="form-control" name="content" placeholder="" value="<?= !empty($content) ? $content : ''?>">
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
        'name:text:奖金名',
        [
            'label' => '奖金',
            'format' => 'raw',
            'headerOptions' =>['width' => '20%'],
            'contentOptions' => ['class' => 'text-mess msg'],
            'value' => function ($model) {
                return '<div class="text-intro"  onmousemove="mousemove($(this))" onmouseout="mouseout($(this))">' . $model['bonus'] . '</div>';
            }
        ],
        [
            'label' => '类型',
            'attribute' => 'currencyType',
            'value' => function($model){
                switch ($model['currencyType']){
                    case 1:
                        return '豆豆';
                        break;
                    case 2:
                        return '狗粮';
                        break;
                    case 3:
                        return '人民币';
                        break;
                }
            },
            'format' => 'raw'
        ],
        [
            'label' => '状态',
            'attribute' => 'status',
            'value' => function($model){
                $el = '';
                switch (intval($model['status'])){
                    case 1 :
                        $el = Html::tag('i', '', ['class' => 'fa fa-check-circle color-green font-18', 'title' => '启用']);
                        break;
                    case 2 :
                        $el = Html::tag('i', '', ['class' => 'fa fa-times-circle color-red font-18', 'title' => '禁用']);
                        break;
                }
                return $el;
            },
            'format' => 'raw'
        ],
        [
            'class'    => ActionColumn::className() ,
            'template' => '{update}{status}' ,
            'header'   => '操作' ,
            'contentOptions' => ['class' => 'actions'],
            'headerOptions' =>['width' => '12%'],
            'buttons'  => [
                'update' => function( $url ){
                    $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-pencil' ] );
                    return Html::a( $icon . '编辑' , $url , [ 'class' => 'btn btn-default btn-sm btn-icon icon-left'] );
                } ,
                'status' => function( $url,$model){
                    if($model['status'] == 1){
                        $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-times' ] );
                        return Html::a($icon.'禁用',$url.'&status=2', [ 'class' => 'btn btn-danger btn-sm btn-icon icon-left','data-name' => $model['name']] );
                    }elseif ($model['status'] == 2){
                        $icon = Html::tag('i','',['class' => 'fa fa-check']);
                        return Html::a($icon.'启用',$url.'&status=1',['class' => 'start btn btn-success btn-sm btn-icon icon-left','data-name' => $model['name']]);
                    }
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
    $(document).ready(function () {
        table = $('#show_image');
        if (table.find('img').length > 0){
            var viewer = new Viewer(table[0] , {navbar : false, tooltip : false, scalable : false, fullscreen : false,zIndex : 99999});
        }

        $('.search-type .dropdown-menu a').click(function(){
            $('#searchType').val($(this).attr('data-searchtype'));
            $('.search-type button.dropdown-toggle').attr('data-searchtype' , $(this).attr('data-searchtype'));
            $('.search-type button.dropdown-toggle').html($(this).text() +　'　<span class="caret"></span>');
            $('button.search').trigger('click');
        });

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
    });

    function mousemove(e){
        e.removeClass('text-intro');
        e.addClass('text-intro-show');
    }
    function mouseout(e){
        e.removeClass('text-intro-show');
        e.addClass('text-intro');
    }


</script>

<?php
Pjax::end();
?>
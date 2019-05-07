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
    [ 'label' => '房卡管理' ] ,
];
AppAsset::registerCss( $this , '@web/plugin/datatables/datatables.min.css' );
AppAsset::registerCss($this , '@web/plugin/daterangepicker/daterangepicker-bs3.css');
AppAsset::registerCss($this, '@web/plugin/viewer/viewer.css');

AppAsset::registerJs($this, '@web/plugin/viewer/viewer.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/moment.min.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/daterangepicker.js');
AppAsset::registerJs($this , '@web/js/common.js');
Pjax::begin(['id' => 'complaint-filter']);
$form = ActiveForm::begin([
    'id' => 'complaint-filter-form',
    'method' => 'get',
    'options' => ['data-pjax' => true , 'role' => 'form']
] );
?>
    <a href="<?= Url::to(['/mall/roomcard/send'])?>" class="send btn btn-info btn-square radius-4 pull-right">
        <i class="entypo-forward"></i>
        发放
    </a>
    <a href="<?=Url::to(['/mall/roomcard/create'])?>" class="btn btn-success btn-square radius-4 pull-right">
        <i class="entypo-plus"></i>
        创建
    </a>
<div class="col-sm-12">
    <div id="league-search" style="margin-bottom: 10px;margin-top: 5px;float: right" >
        <div class="col-sm-1 refresh" style="margin-right: 5%">
            <a href="<?= Url::to(['/mall/roomcard'])?>" class="btn btn-default" title="刷新">
                <i class="fa fa-refresh"></i>
            </a>
        </div>

        <div class="input-group" style="width: 400px">
            <div class="input-group-btn search-status" style="padding-right: 10px">
                <?php
                    $searchStatus = Yii::$app->request->get('searchType' , 0);
                    $searchStatusList = [0 => '全部', 1 => '联赛', 2 => '游戏', 3 => '无限制'];
                ?>
                <input type="hidden" id="searchType" name="searchType" value="<?=$searchStatus?>">
                <button type="button" class="btn btn-info dropdown-toggle status" style="width: 80px" data-searchstatus="<?= $searchStatus?>"  data-toggle="dropdown">
                    <?= $searchStatusList[$searchStatus] ?>　<span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-infoblue searchstatus">
                    <?php foreach($searchStatusList as $key => $value){ ?>
                        <li>
                            <a data-searchstatus="<?= $key ?>" href="javascript:void(0);"><?= $value ?></a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <div class="input-group-btn search-type">
                <button type="button" class="btn btn-success  " style="width: 60px" data-searchtype="name"  data-toggle="dropdown">
                    房卡名
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
        'id'               => 'complaint' ,
        'dataProvider'     => $dataProvider ,
        'emptyText'        => "暂无房卡信息！" ,
        'emptyCell' => '',
        'emptyTextOptions' => [ 'class' => 'text-center' ] ,
        'tableOptions'     => [ 'class' => 'table table-bordered datatable no-footer hover stripe text-center dataTable','id'=> 'show_image' ] ,
        'options'          => [ 'class' => 'dataTables_wrapper no-footer no-border' ] ,
        'layout'           => "{errors}{items}{pager}" ,
        "columns"          => [
            'roomCardName:text:房卡名',
            [
                'label' => '房卡类型',
                'value' => function ($model){
                    $roomCardType = $model['roomCardType'];

                    switch ($roomCardType){
                        case 1:
                            return '联赛';
                            break;
                        case 2:
                            return '游戏';
                            break;
                        case 3:
                            return '无限制';
                            break;
                    }
                }
            ],
            [
                'label' => '游戏类型',
                'value' => function ($model){
                    if($model['roomCardType'] != 3){
                        if($model['gameType'] == 1){
                            return '王者荣耀';
                        }elseif ($model['gameType'] == 2){
                            return '绝地求生';
                        }
                    }
                }
            ],
            'leagueName:text:联赛名',
            [
                'label' => '房卡图',
                'format' => [
                    'image',
                    [
                        'width'=>'80',
                        'height'=>'80',
                        'name' => 'show_image'
                    ]
                ],
                'value' => function ($model) {
                    return $model['roomCardIcon'];
                },
                'headerOptions' =>['width' => '8%']
            ],
            [
                'class'    => ActionColumn::className() ,
                'template' => ' {update}{detail}' ,
                'header'   => '操作' ,
                'contentOptions' => ['class' => 'actions'],
                'buttons'  => [
                    'update' => function( $url ){
                        $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-pencil' ] );
                        return Html::a( $icon . '编辑' , $url , [ 'class' => 'btn btn-default btn-sm btn-icon icon-left' ] );
                    } ,
                    'detail' => function( $url){
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


?>
</div>
<script>
    var toastrOpts = {
        "closeButton": false,
        "debug": false,
        "progressBar": true,
        "positionClass": "toast-center",
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "2000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
jQuery( document ).ready( function( $ ){
    if ($('#show_image').find('img').length > 0){
        var gloryViewer = new Viewer($('#show_image')[0] , {navbar : false, tooltip : false, scalable : false, fullscreen : false,zIndex : 99999});
    }
    $('.search-status .dropdown-menu a').click(function(){
        $('#searchType').val($(this).attr('data-searchStatus'));
        $('.search-status button.dropdown-toggle').attr('data-searchStatus' , $(this).attr('data-searchStatus'));
        $('.search-status button.dropdown-toggle').html($(this).text() +　'　<span class="caret"></span>');
        $('button.search').trigger('click');
    });
});
$(document).on('pjax:complete',function(){
    if ($('#show_image').find('img').length > 0){
        new Viewer($('#show_image')[0] , {navbar : false, tooltip : false, scalable : false, fullscreen : false,zIndex : 99999});
    }
})
</script>


<?php
Pjax::end();
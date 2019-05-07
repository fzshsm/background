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
        'label' => '房卡管理' ,
        'url'   => Url::to( [
            '/mall/roomcard' ,
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
<div class="col-sm-12">
    <div id="roomcard-search col-sm-12" style="margin-bottom: 10px;margin-top: 5px;float: right" >
        <div class="col-sm-1 refresh" style="">
            <a href="<?= Url::to(['/mall/roomcard/detail','id' => \Yii::$app->request->get('id')])?>" class="btn btn-default" title="刷新">
                <i class="fa fa-refresh"></i>
            </a>
        </div>
        <div class="col-sm-4 padding-left-8">
            <div class="input-group ">
                <span class="input-group-addon"><i class="entypo-calendar"></i></span>
                <input type="text" class="form-control cursor-pointer" name="date" id="date" readonly placeholder="选择日期" value="<?= Yii::$app->request->get('date'); ?>" >
            </div>
        </div>
        <div class="input-group " style="width: 400px">
            <div class="input-group-btn search-status" style="padding-right: 10px">
                <?php
                    $statusType = Yii::$app->request->get('status' , 0);
                    $statusList = [0 => '全部' ,1 => '未使用', 2 => '已使用', 3 => '已过期'];
                ?>
                <input type="hidden" id="status" name="status" value="<?=$statusType?>">
                <button type="button" class="btn btn-info dropdown-toggle status" style="width: 80px" data-searchstatus="<?= $statusType?>"  data-toggle="dropdown">
                    <?= $statusList[$statusType] ?>　<span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-infoblue">
                    <?php foreach($statusList as $key => $value){ ?>
                        <li>
                            <a data-searchstatus="<?= $key ?>" href="javascript:void(0);"><?= $value ?></a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <div class="input-group-btn searchType">
                <?php
                    $searchType = Yii::$app->request->get('searchType' , 'objNo');
                    $searchTypeList = ['objNo' => '房卡编号','nickName' => '所有人', 'useNickName' => '使用人'];
                ?>
                <input type="hidden" id="searchType" name="searchType" value="<?=$searchType?>">
                <button type="button" class="btn btn-success dropdown-toggle type" style="width: 80px" data-searchtype="<?= $searchType?>" data-toggle="dropdown">
                    <?= $searchTypeList[$searchType] ?><span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-green">
                    <?php foreach($searchTypeList as $key => $value){ ?>
                        <li><a href="javascript:void(0);" data-searchtype="<?= $key?>"><?= $value?></a></li>
                    <?php } ?>
                </ul>
            </div>
            <?php
                $content = \Yii::$app->request->get('content','');
            ?>
            <input type="text" id="content" class="form-control" name="content" placeholder="" value="<?= isset($content) ? $content : ''?>">
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
        'emptyText'        => "暂无房卡详情信息！",
        'emptyCell' => '',
        'emptyTextOptions' => [ 'class' => 'text-center' ] ,
        'tableOptions'     => [ 'class' => 'table table-bordered datatable no-footer hover stripe text-center dataTable','id'=> 'show_image' ] ,
        'options'          => [ 'class' => 'dataTables_wrapper no-footer no-border' ] ,
        'layout'           => "{errors}{items}{pager}" ,
        "columns"          => [
            'roomCardNo:text:房卡编号',
            'nickName:text:所有人',
            'useNickName:text:使用人',
            'getDate:text:发放时间',
            'useDate:text:使用时间',
            'status:text:状态',
//            [
//                'label' => '状态',
//                'attribute' => 'status',
//                'value' => function($model){
//                    switch ($model['status']){
//                        case 1:
//                            return Html::tag( 'span' , '未使用' , [ 'class' => 'label label-default'] );
//                            break;
//                        case 2:
//                            return Html::tag( 'span' , '已使用' , [ 'class' => 'label label-primary'] );
//                            break;
//                        case 3:
//                            return Html::tag( 'span' , '已过期' , [ 'class' => 'label label-danger'] );
//                            break;
//                    }
//                },
//                'format' => 'raw'
//            ]
        ] ,
        'pager'            => [
            'options'     => [ 'class' => 'pagination dataTables_paginate paging_simple_numbers' ] ,
            'linkOptions' => [ 'class' => 'paginate_button' ] ,
        ] ,
    ] );


?>
</div>
<script>
jQuery( document ).ready( function( $ ){
    $('.searchType .dropdown-menu a').click(function () {
        $('#searchType').val($(this).attr('data-searchtype'));
        $('button.btn-success.dropdown-toggle.type').attr('data-searchtype', $(this).attr('data-searchtype'));
        $('button.btn-success.dropdown-toggle.type').html($(this).text() + '　<span class="caret"></span>');
    });
    $('.search-status .dropdown-menu a').click(function () {
        $('#status').val($(this).attr('data-searchstatus'));
        $('button.btn-info.dropdown-toggle.status').attr('data-searchstatus', $(this).attr('data-searchstatus'));
        $('button.btn-info.dropdown-toggle.status').html($(this).text() + '　<span class="caret"></span>');
        $('button.search').trigger('click');
    });

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
});
</script>


<?php
Pjax::end();
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
        'label' => '联赛管理' ,
        'url'   => Url::to( [
            '/league' ,
        ] ) ,
    ] ,
    [ 'label' => '投诉管理' ] ,
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
    <div class="form-group pull-right col-sm-6">
        <div class="col-sm-1">
            <a href="<?= Url::to(['/league/glorycomplaint'])?>" class="btn btn-default" title="刷新">
                <i class="fa fa-refresh"></i>
            </a>
        </div>
        <div class="col-sm-6">
            <div class="input-group">
                <span class="input-group-addon"><i class="entypo-calendar"></i></span>
                <input type="text" class="form-control cursor-pointer" name="date" id="date" readonly placeholder="选择日期" value="<?= Yii::$app->request->get('date'); ?>" >
            </div>
        </div>
        <div class="input-group col-sm-5 pull-right">
            <div class="input-group-btn">
                <?php
                    $searchType = Yii::$app->request->get('searchType' , 'nickName');
                    $searchTypeList = ['nickName' => '昵称' , 'roleId' => '游戏角色' , 'gameRecordId' => '游戏编号'];
                ?>
                <input type="hidden" id="searchType" name="searchType" value="<?=$searchType?>">
                <button type="button" class="btn btn-success dropdown-toggle btn-width-100" data-searchtype="<?=$searchType?>"  data-toggle="dropdown">
                    <?= $searchTypeList[$searchType] ?>　<span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-green">
                    <li><a href="javascript:void(0);" data-searchtype="nickName">昵称</a></li>
                    <li><a href="javascript:void(0);" data-searchtype="roleId" >游戏角色 </a></li>
                    <li><a href="javascript:void(0);" data-searchtype="gameRecordId" >游戏编号 </a></li>
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
<div class="col-md-12">

<?php
ActiveForm::end();
    echo GridView::widget( [
        'id'               => 'complaint' ,
        'dataProvider'     => $dataProvider ,
        'emptyText'        => "暂无投诉信息！" ,
        'emptyCell' => '',
        'emptyTextOptions' => [ 'class' => 'text-center' ] ,
        'tableOptions'     => [ 'class' => 'table table-bordered datatable no-footer hover stripe text-center dataTable' ] ,
        'options'          => [ 'class' => 'dataTables_wrapper no-footer no-border' ] ,
        'layout'           => "{errors}{items}{pager}" ,
        "columns"          => [
            'id:raw:ID' ,
            [
                'attribute' => 'leagueId',
                'label' => '联赛',
                'value' => function($model) use ($matchType){
                    return isset($matchType[$model['leagueId']]) ? $matchType[$model['leagueId']]['name'] : '无';
                }
            ],
            'gameRecordId:text:游戏编号',
            'nickName:text:昵称',
            'reportedRoleId:text:游戏角色',
            [
                'attribute' => 'reportNum',
                'label' => '被投诉次数',
                'format' => 'html',
                'value' => function($model){
                    return Html::tag('span' , $model['reportNum'] , ['class' => 'badge badge-warning']);
                }
            ],
            'reportContent:text:投诉理由',
            [
                'attribute' => 'gameScreenshot',
                'label' => '游戏截图',
                'format' => 'html',
                'value' => function($model){
                    $value = '';
                    if(!empty($model['gameScreenshot'])){
                        $value = Html::img( $model['gameScreenshot'] ,
                            [ 'width'  => 50 ,
                                'height' => 50 ,
                                'alt'    => $model['reportedRoleId'] . $model['reportContent'],
                            ]
                        );
                    }
                    return $value;
                }
            ],
            [
                'attribute' => 'time',
                'label' => '时间',
                'value' => function($model){
                    return Yii::$app->formatter->asDate($model['time'] / 1000);
                }
            ],
            'reportRoleId:text:投诉人',
            [
                'class'    => ActionColumn::className() ,
                'template' => ' {clear}' ,
                'header'   => '操作' ,
                'contentOptions' => ['class' => 'actions'],
                'buttons'  => [
                    'clear' => function( $url , $model )use($gameType){
                        $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-times' ] );
                        if($gameType == 'pubg'){
                            $url = '/league/pubgcomplaint/clear';
                        }else{
                            $url = '/league/glorycomplaint/clear';
                        }
                        return Html::a( $icon . '清理' , [$url , 'userId' => $model['userId']] , [ 'class' => 'btn btn-danger btn-sm btn-icon icon-left'  , 'data-user' => $model['reportedRoleId'] ] );
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
jQuery( document ).ready( function( $ ){
    $('.dropdown-menu.dropdown-green a').click(function(){
        $('#searchType').val($(this).attr('data-searchtype'));
        $('button..btn-success.dropdown-toggle').attr('data-searchtype' , $(this).attr('data-searchtype'));
        $('button.btn-success.dropdown-toggle').html($(this).text() +　'　<span class="caret"></span>');
    });

    $('a.btn-danger').click(function(){
        var user = $(this).attr('data-user');
        var confirmText = $('<span>').addClass('color-orange font-16').html("你确定要清理  " + user + "  的所有投诉记录吗？");
        var successText = "清理 " + user + "  投诉记录成功！";
        showConfirmModal(this , confirmText , successText);
        return false;
    });
    
    table = $('#complaint');
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

    $('.nav-tabs a').off('click');
    $('.nav-tabs a').on('click' , function() {
        var $that = $(this);
        var dataStatus = $that.attr('data-status');
        $('.nav-tabs li').removeClass('active');
        $that.closest('li').addClass('active');
    })
});
</script>
<?php
Pjax::end();
<?php

use app\assets\AppAsset;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;

$this->params['breadcrumbs'] = [
    [
        'label' => '联赛管理' ,
        'url'   => Url::to( [
            '/league/pubg' ,
        ] ) ,
    ] ,
    [
        'label' => $seasonName ,
        'url'   => Url::to(['/league/pubgseason','leagueId' => $leagueId]) ,
    ] ,
    [ 'label' => '绝地求生场次管理' ] ,
];

AppAsset::registerCss( $this , '@web/plugin/datatables/datatables.min.css' );
AppAsset::registerCss($this , '@web/plugin/daterangepicker/daterangepicker-bs3.css');
AppAsset::registerCss($this, '@web/plugin/viewer/viewer.css');

AppAsset::registerJs($this, '@web/plugin/viewer/viewer.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/moment.min.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/daterangepicker.js');
AppAsset::registerJs($this , '@web/js/common.js');
Pjax::begin(['id' => 'match']);
$form = ActiveForm::begin([
    'id' => 'complaint-filter-form',
    'method' => 'get',
    'options' => ['data-pjax' => true , 'role' => 'form']
] );
$text = Html::tag('i' , '' , ['class' => 'entypo-plus']) . '创建';
echo Html::a($text , Url::to(['/league/pubgmatch/create' , 'seasonId' => $seasonId,'seasonName' => $seasonName,'leagueId' => $leagueId]) , ['class' => 'btn btn-success btn-square radius-4 pull-right']);

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
<!--    <div id="roomcard-search col-sm-12" style="margin-bottom: 10px;margin-top: 5px;float: right" >-->
<!--        <div class="col-sm-1 refresh" style="">-->
<!--            <a href="--><?php //= Url::to(['/league/pubgmatch','seasonId' => $seasonId, 'seasoName' => $seasonName])?><!--" class="btn btn-default" title="刷新">-->
<!--                <i class="fa fa-refresh"></i>-->
<!--            </a>-->
<!--        </div>-->
<!--        <div class="col-sm-4 padding-left-8">-->
<!--            <div class="input-group ">-->
<!--                <span class="input-group-addon"><i class="entypo-calendar"></i></span>-->
<!--                <input type="text" class="form-control cursor-pointer" name="date" id="date" readonly placeholder="选择日期" value="--><?php //= Yii::$app->request->get('date'); ?><!--" >-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="input-group " style="width: 400px">-->
<!--            <div class="input-group-btn search-status" style="padding-right: 10px">-->
<!--                --><?php
//                $statusType = Yii::$app->request->get('status' , 0);
//                $statusList = [0 => '全部' ,1 => '预约中', 2 => '进行中', 3 => '已结束', 4 => '结束且已结算'];
//                ?>
<!--                <input type="hidden" id="status" name="status" value="--><?//=$statusType?><!--">-->
<!--                <button type="button" class="btn btn-info dropdown-toggle status" style="width: 80px" data-searchstatus="--><?//= $statusType?><!--"  data-toggle="dropdown">-->
<!--                    --><?//= $statusList[$statusType] ?><!--　<span class="caret"></span>-->
<!--                </button>-->
<!--                <ul class="dropdown-menu dropdown-infoblue">-->
<!--                    --><?php //foreach($statusList as $key => $value){ ?>
<!--                        <li>-->
<!--                            <a data-searchstatus="--><?//= $key ?><!--" href="javascript:void(0);">--><?//= $value ?><!--</a>-->
<!--                        </li>-->
<!--                    --><?php //} ?>
<!--                </ul>-->
<!--            </div>-->
<!--            <div class="input-group-btn searchType">-->
<!--                --><?php
//                $searchType = Yii::$app->request->get('searchType' , 'matchId');
//                $searchTypeList = ['matchId' => '场次编号','title' => '房间名'];
//                ?>
<!--                <input type="hidden" id="searchType" name="searchType" value="--><?//=$searchType?><!--">-->
<!--                <button type="button" class="btn btn-success dropdown-toggle type" style="width: 80px" data-searchtype="--><?//= $searchType?><!--" data-toggle="dropdown">-->
<!--                    --><?//= $searchTypeList[$searchType] ?><!--<span class="caret"></span>-->
<!--                </button>-->
<!--                <ul class="dropdown-menu dropdown-green">-->
<!--                    --><?php //foreach($searchTypeList as $key => $value){ ?>
<!--                        <li><a href="javascript:void(0);" data-searchtype="--><?//= $key?><!--">--><?//= $value?><!--</a></li>-->
<!--                    --><?php //} ?>
<!--                </ul>-->
<!--            </div>-->
<!--            --><?php
//            $content = \Yii::$app->request->get('content','');
//            ?>
<!--            <input type="text" id="content" class="form-control" name="content" placeholder="" value="--><?//= isset($content) ? $content : ''?><!--">-->
<!--            <div class="input-group-btn">-->
<!--                <button  type="submit" class="btn btn-success search">-->
<!--                    <i class="entypo-search"></i>-->
<!--                </button>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<?php
ActiveForm::end();
    echo GridView::widget( [
        'id'               => 'match' ,
        'dataProvider'     => $dataProvider ,
        'emptyText'        => "暂无{$seasonName}赛季场次信息！" ,
        'emptyTextOptions' => [ 'class' => 'text-center' ] ,
        'tableOptions'     => [ 'class' => 'table table-bordered datatable no-footer hover stripe text-center dataTable' ] ,
        'options'          => [ 'class' => 'dataTables_wrapper no-footer no-border' ] ,
        'layout'           => "{errors}{items}{pager}" ,
        "columns"          => [
            'matchId:text:场次编号',
            'title:raw:房间名' ,
            'map:text:地图' ,
            'password:text:密码' ,
            'robotId:text:机器人',
            [
                'attribute' => 'bookingCount' ,
                'label'     => '预约数' ,
                'format'    => 'html' ,
                'value' => function($model){
                    return Html::tag( 'span' , $model['bookingCount'] , ['class' => 'badge badge-info']);
                }
            ] ,
            'startTime:text:开始时间' ,
            'endTime:text:结束时间' ,
            'createTime:text:创建时间' ,
            [
                'attribute' => 'status' ,
                'label'     => '状态' ,
                'format' => 'html',
                'value' => function($model){
                    $className = "";
                    $text = "";
                    switch ($model['status']){
                        case 1 :
                            $className = ' label-default';
                            $text = '预约中';
                            break;
                        case 2 :
                            $className = ' label-success';
                            $text = '进行中';
                            break;
                        case 3 :
                            $className = ' label-primary';
                            $text = '结束';
                            break;
                        case 4:
                            $className = ' label-primary';
                            $text = '结束且结算完成';
                            break;
                    }
                    return Html::tag( 'span' , $text , ['class' => 'label ' . $className]);
                }
            ] ,
            [
                'class'    => ActionColumn::className() ,
                'template' => '{update}{reservation}' ,
                'header'   => '操作' ,
                'contentOptions' => ['class' => 'actions'],
                'buttons'  => [
                    'update' => function( $url ) use($seasonName,$seasonId,$leagueId){
                        $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-pencil' ] );
                        return Html::a( $icon . '编辑' , $url.'&seasonId='.$seasonId.'&seasonName='.$seasonName.'&leagueId='.$leagueId , [ 'class' => 'btn btn-default btn-sm btn-icon icon-left' ] );
                    } ,
                    'reservation' => function($url,$model){
                        $icon = Html::tag('i', '', ['class' => 'fa fa-save']);

                        if($model['status'] > 1){
                            return Html::a($icon. '保存', $url.'&leagueId='.$model['leagueId'].'&seasonId='.$model['seasonId'].'&robotName='.$model['robotName'], ['class' => 'btn btn-success btn-sm btn-icon icon-left']);
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

    ?>
<script>
    jQuery( document ).ready( function( $ ){

        $('.searchType .dropdown-menu a').click(function () {
            $('#searchType').val($(this).attr('data-searchtype'));
            $('button.btn-success.dropdown-toggle.type').attr('data-searchtype', $(this).attr('data-searchtype'));
            $('button.btn-success.dropdown-toggle.type').html($(this).text() + '　<span class="caret"></span>');
        });

        $('#date').daterangepicker({
            startDate: "<?= date('Y-m-d H:i:s' , strtotime( "-3 days")) ?>",
            endDate: "<?= date('Y-m-d H:i:s') ?>",
            showWeekNumbers : false, //是否显示第几周
            timePicker : true, //是否显示小时和分钟
            timePickerIncrement : 5, //时间的增量，单位为分钟
            timePicker12Hour : false, //是否使用12小时制来显示时间
            opens : 'right', //日期选择框的弹出位置
            buttonClasses : [ 'btn btn-default' ],
            applyClass : 'btn-small btn-success',
            cancelClass : 'btn-small',
            format : 'YYYY-MM-DD HH:mm', //控件中from和to 显示的日期格式
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
            $('#date span').html(start.format('YYYY-MM-DD HH:mm') + ' 至 ' + end.format('YYYY-MM-DD HH:mm'));
        }).on('apply.daterangepicker' , function(){
            $('button.search').trigger('click');
        });
    });

    $(document).on('pjax:complete',function(){
        $('.searchType .dropdown-menu a').click(function () {
            $('#searchType').val($(this).attr('data-searchtype'));
            $('button.btn-success.dropdown-toggle.type').attr('data-searchtype', $(this).attr('data-searchtype'));
            $('button.btn-success.dropdown-toggle.type').html($(this).text() + '　<span class="caret"></span>');
        });
    })
</script>
<?php
Pjax::end();
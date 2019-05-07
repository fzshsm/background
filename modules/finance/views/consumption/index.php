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
        'label' => '财务管理' ,
        'url'   => Url::to( [
            '/finance' ,
        ] ) ,
    ] ,
    [
        'label' => '豆豆流水日志列表' ,
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
    'id' => 'consumption-form',
    'method' => 'get',
    'options' => ['data-pjax' => true , 'consumption' => 'form']
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

<div id="roomcard-search col-sm-12" style="margin-bottom: 10px;margin-top: 5px;float: right" >
    <div class="col-sm-1 refresh" style="">
        <a href="<?= Url::to(['/finance/consumption'])?>" class="btn btn-default" title="刷新">
            <i class="fa fa-refresh"></i>
        </a>
    </div>
    <div class="col-sm-4 padding-left-8">
        <div class="input-group ">
            <span class="input-group-addon"><i class="entypo-calendar"></i></span>
            <input type="text" class="form-control cursor-pointer" name="date" id="date" readonly placeholder="选择日期"  value="<?= Yii::$app->request->get('date')?>" >
        </div>
    </div>
    <div class="input-group " style="width: 400px">
        <div class="input-group-btn search-consumption-type" style="padding-right: 10px">
            <?php
            $consumptionType = Yii::$app->request->get('type' , 0);
            $consumptionTypeList = [0 => '全部' ,1 => '签到', 2 => '邀请人奖励', 4 => '每局游戏奖励', 5 => '赛季结算', 6 => '赛季奖励', 7 => '狗粮兑换', 8 => '购物', 9 => '充值', 10 => '竞猜下单',
            11 => '竞猜退款',12 => '竞猜收益',13 => '竞猜回滚',14 => '注册奖励',15 => '荣耀绑定',16 => '吃鸡绑定',17 => '每日app比赛',18 => '参与竞猜',19 => '邀请队友参赛',20 => '分享资讯',
                21 => '充值奖励',22 => '首次参赛'];
            ?>
            <input type="hidden" id="type" name="type" value="<?=$consumptionType?>">
            <button type="button" class="btn btn-info dropdown-toggle status" style="width: 120px" data-searchConsumptionType="<?= $consumptionType?>"  data-toggle="dropdown">
                <?= $consumptionTypeList[$consumptionType] ?>　<span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-infoblue">
                <?php foreach($consumptionTypeList as $key => $value){ ?>
                    <li>
                        <a data-searchConsumptionType="<?= $key ?>" href="javascript:void(0);"><?= $value ?></a>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <div class="input-group-btn searchType">
            <?php
                $searchType = Yii::$app->request->get('searchType' , 'uid');
                $searchTypeList = ['uid' => '用户ID','nickName' => '用户昵称'];
            ?>
            <input type="hidden" id="searchType" name="searchType" value="<?=$searchType?>">
            <button type="button" class="btn btn-success dropdown-toggle type" style="width: 100px" data-searchtype="<?= $searchType?>" data-toggle="dropdown">
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
        <a href="<?= \Yii::$app->params['pubgApiDomain'].'/finance/exportSystemCoinLogList?upDateTime='.date('Y-m-d',strtotime('-1 month')).' 00:00:00,'.date('Y-m-d').' 23:59:59&token=Bearer '.\Yii::$app->cache->get('login-'.\Yii::$app->user->id) ?>"
           id="download_url" class="btn btn-success  pull-right">
            <i class="fa fa-download"></i>导出
        </a>
        <input type="hidden" id="startDate" value="<?= date("Y-m-d", strtotime('-1 month'))?>">
        <input type="hidden" id="endDate" value="<?= date("Y-m-d")?>">
        <input type="hidden" id="token" value="<?='Bearer '.\Yii::$app->cache->get('login-'.\Yii::$app->user->id)?>">
        <input type="hidden" id="domainUrl" value="<?= \Yii::$app->params['pubgApiDomain'].'/finance/exportSystemCoinLogList' ?>">
    </div>
</div>
<?php
ActiveForm::end();
echo GridView::widget( [
    'id'               => 'finance' ,
    'dataProvider'     => $dataProvider ,
    'emptyText'        => "暂无豆豆日志信息！" ,
    'emptyTextOptions' => [ 'class' => 'text-center' ] ,
    'tableOptions'     => [ 'class' => 'table table-bordered datatable no-footer hover stripe text-center dataTable'] ,
    'options'          => [ 'class' => 'dataTables_wrapper no-footer no-border' ] ,
    'layout'           => "{errors}{items}{pager}" ,
    "columns"          => [
        [
            'label' => '用户编号',
            'attribute' => 'uid',
            'value' => 'uid',
            'headerOptions' =>['width' => '2%']
        ],
        [
            'label' => '用户昵称',
            'attribute' => 'nickName',
            'value' => 'nickName',
            'headerOptions' =>['width' => '2%']
        ],
        [
            'label'=>'类型',
            'attribute' => 'isGain',
            'value' => function ($model) {
                if($model['isGain']){
                    return '获得';
                }else{
                    return '消耗';
                }
            },
            'headerOptions' => ['width' => '1%']
        ],
        [
            'label'=>'方式',
            'attribute' => 'type',
            'value' => function ($model) {
                $type = intval($model['type']);

                switch ($type){
                    case 1:
                        return '签到';
                        break;
                    case 2:
                        return '邀请人奖励';
                        break;
                    case 4:
                        return '每局游戏奖励';
                        break;
                    case 5:
                        return '赛季结算';
                        break;
                    case 6:
                        return '赛季奖励';
                        break;
                    case 7:
                        return '狗粮兑换';
                        break;
                    case 8:
                        return '购物';
                        break;
                    case 9:
                        return '充值';
                        break;
                    case 10:
                        return '竞猜下单';
                        break;
                    case 11:
                        return '竞猜退款';
                        break;
                    case 12:
                        return '竞猜收益';
                        break;
                    case 13:
                        return '竞猜回滚';
                        break;
                    case 14:
                        return '注册奖励';
                        break;
                    case 15:
                        return '荣耀绑定';
                        break;
                    case 16:
                        return '吃鸡绑定';
                        break;
                    case 17:
                        return '每日参与比赛';
                        break;
                    case 18:
                        return '参与竞猜';
                        break;
                    case 19:
                        return '邀请队友参赛';
                        break;
                    case 20:
                        return '分享资讯';
                        break;
                    case 21:
                        return '充值奖励';
                        break;
                    case 22:
                        return '首次参赛';
                        break;
                }
            },
            'headerOptions' => ['width' => '1%']
        ],
        [
            'label' => '变动数量',
            'attribute' => 'consumeOrGainNumber',
            'value' => 'consumeOrGainNumber',
            'headerOptions' =>['width' => '2%']
        ],
        [
            'label' => '豆豆总量',
            'attribute' => 'finalSystemCoinNumber',
            'value' => 'finalSystemCoinNumber',
            'headerOptions' =>['width' => '2%']
        ],
        [
            'label' => '时间',
            'attribute' => 'upDateTime',
            'value'=>'upDateTime',
            'headerOptions' => ['width' => '6%'],
        ],
    ],
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
        $('.search-consumption-type .dropdown-menu a').click(function () {
            $('#type').val($(this).attr('data-searchConsumptionType'));
            $('button.btn-info.dropdown-toggle.status').attr('data-searchConsumptionType', $(this).attr('data-searchConsumptionType'));
            $('button.btn-info.dropdown-toggle.status').html($(this).text() + '　<span class="caret"></span>');
            $('button.search').trigger('click');
        });

        var startDate = $("#startDate").val();
        var endDate = $("#endDate").val();
        var date = $("#date").val();

        if(date != false){
            var arrDate = strDate(date);
            startDate = arrDate[0];
            endDate = arrDate[1]
        }

        $('#date').daterangepicker({
            startDate: startDate,
            endDate: endDate,
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
        updateUrl();
    })

    function updateUrl(){
        var type = $("#type").val();
        var content = $("#content").val();
        var searchType = $("#searchType").val();
        var startDate = $("#startDate").val();
        var endDate = $("#endDate").val();
        var token = $("#token").val();
        var domainUrl = $("#domainUrl").val()
        var date = $("#date").val()

        if(date != false){
            var arrDate = strDate(date);
            startDate = arrDate[0];
            endDate = arrDate[1]
        }

        var downloadUrl = domainUrl+'?upDateTime='+startDate+' 00:00:00,'+endDate+' 23：59：59&token='+token;

        if(type != false){
            downloadUrl = downloadUrl+'&type='+type
        }

        if(content != false){
            downloadUrl = downloadUrl+'&'+searchType+'='+content
        }
        $("#download_url").attr('href',downloadUrl);
    }

    function strDate(date){
        var arrDate =date.split("至");

        arrDate[0] =  arrDate[0].replace(/(^\s*)|(\s*$)/g, "");
        arrDate[1] =  arrDate[1].replace(/(^\s*)|(\s*$)/g, "");

        return arrDate;
    }
</script>
<?php
Pjax::end();
?>

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
        'label' => '充值列表' ,
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
    'id' => 'recharge-form',
    'method' => 'get',
    'options' => ['data-pjax' => true , 'recharge' => 'form']
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
    <div class="col-sm-1 refresh">
        <a href="<?= Url::to(['/finance'])?>" class="btn btn-default" title="刷新">
            <i class="fa fa-refresh"></i>
        </a>
    </div>
    <div class="col-sm-3 padding-left-8">
        <div class="input-group ">
            <span class="input-group-addon"><i class="entypo-calendar"></i></span>
            <input type="text" class="form-control cursor-pointer" style="width: 200px" name="date" id="date" readonly placeholder="选择交易完成时间" value="<?= Yii::$app->request->get('date'); ?>" >
        </div>
    </div>
    <div class="input-group col-sm-7" style="width: 500px;float: right" >
        <div class="input-group-btn search-recharge-channel" style="padding-right: 10px">
            <?php
            $statusType = Yii::$app->request->get('rechargeChannel' , 0);
            $statusList = [0 => '全部渠道' ,1 => '支付宝', 2 => '微信'];
            ?>
            <input type="hidden" id="rechargeChannel" name="rechargeChannel" value="<?=$statusType?>">
            <button type="button" class="btn btn-info dropdown-toggle status" style="width: 100px" data-searchChannel="<?= $statusType?>"  data-toggle="dropdown">
                <?= $statusList[$statusType] ?>　<span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-infoblue">
                <?php foreach($statusList as $key => $value){ ?>
                    <li>
                        <a data-searchChannel="<?= $key ?>" href="javascript:void(0);"><?= $value ?></a>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <div class="input-group-btn search-pay-status" style="padding-right: 10px">
            <?php
            $payStatus = Yii::$app->request->get('payStatus' , 4);
            $payStatusList = [4 => '全部状态' ,0 => '待支付', 1 => '交易失败', 2 => '交易成功', 3 => '已退款'];
            ?>
            <input type="hidden" id="payStatus" name="payStatus" value="<?=$payStatus?>">
            <button type="button" class="btn btn-blue dropdown-toggle pay-status" style="width: 120px" data-searchPayStatus="<?= $payStatus?>"  data-toggle="dropdown">
                <?= $payStatusList[$payStatus] ?>　<span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-darkblue">
                <?php foreach($payStatusList as $key => $value){ ?>
                    <li>
                        <a data-searchPayStatus="<?= $key ?>" href="javascript:void(0);"><?= $value ?></a>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <div class="input-group-btn searchType">
            <?php
            $searchType = Yii::$app->request->get('searchType' , 'uid');
            $searchTypeList = ['uid' => '用户ID','nickName' => '用户昵称', 'channelOrderNumber' => '渠道订单号'];
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
        <input type="text" id="content" class="form-control" style="width: 150px" name="content" placeholder="" value="<?= isset($content) ? $content : ''?>">
        <div class="input-group-btn">
            <button  type="submit" class="btn btn-success search">
                <i class="entypo-search"></i>
            </button>
        </div>
        <a href="<?= \Yii::$app->params['pubgApiDomain'].'/finance/exportFinanceList?rechargeTime='.date('Y-m-d',strtotime('-1 month')).' 00:00:00,'.date('Y-m-d').' 23:59:59&token=Bearer '.\Yii::$app->cache->get('login-'.\Yii::$app->user->id) ?>"
           id="download_url"  class="btn btn-success pull-right">
            <i class="fa fa-download"></i>导出
        </a>
        <input type="hidden" id="startDate" value="<?= date("Y-m-d", strtotime('-1 month'))?>">
        <input type="hidden" id="endDate" value="<?= date("Y-m-d")?>">
        <input type="hidden" id="token" value="<?='Bearer '.\Yii::$app->cache->get('login-'.\Yii::$app->user->id)?>">
        <input type="hidden" id="domainUrl" value="<?= \Yii::$app->params['pubgApiDomain'].'/finance/exportFinanceList' ?>">
    </div>
</div>
<?php
ActiveForm::end();
echo GridView::widget( [
    'id'               => 'finance' ,
    'dataProvider'     => $dataProvider ,
    'emptyText'        => "暂无充值信息！" ,
    'emptyTextOptions' => [ 'class' => 'text-center' ] ,
    'tableOptions'     => [ 'class' => 'table table-bordered datatable no-footer hover stripe text-center dataTable'] ,
    'options'          => [ 'class' => 'dataTables_wrapper no-footer no-border' ] ,
    'layout'           => "{errors}{items}{pager}" ,
    "columns"          => [
        [
            'label' => '订单号',
            'attribute' => 'baidouOrderNumber',
            'value' => 'baidouOrderNumber',
            'headerOptions' =>['width' => '2%']
        ],
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
            'label' => '充值金额',
            'attribute' => 'money',
            'value' => 'money',
            'headerOptions' =>['width' => '2%']
        ],
        [
            'class'    => ActionColumn::className() ,
            'template' => '{rechargeChannel}' ,
            'header'   => '充值渠道' ,
            'contentOptions' => ['class' => 'text-center'],
            'headerOptions' => ['width' => '2%'],
            'buttons'  => [
                'rechargeChannel' => function($url,$model){
                    if($model['rechargeChannel'] == 1){
                        return  Html::img('@web/images/alipay.jpg', [ 'height'=> '25', 'width' => '25', 'title' => '支付宝']);
                    }else{
                        return  Html::img('@web/images/wx.jpg', [ 'height'=> '25', 'width' => '25', 'title' => '微信']);
                    }
                } ,
            ] ,
        ],
        [
            'label' => '渠道订单号',
            'attribute' => 'channelOrderNumber',
            'value' => 'channelOrderNumber',
            'headerOptions' =>['width' => '2%']
        ],
        [
            'attribute' => 'payStatus' ,
            'label'     => '状态' ,
            'format' => 'html',
            'headerOptions' => ['width' => '2%'],
            'value' => function($model){
                $className = "";
                $text = "";
                switch ($model['payStatus']){
                    case 0 :
                        $className = 'fa fa-minus-circle color-orange font-18';
                        $text = '待支付';
                        break;
                    case 1 :
                        $className = 'fa fa-times-circle color-red font-18';
                        $text = '支付失败';
                        break;
                    case 2 :
                        $className = 'fa fa-check-circle color-green font-18';
                        $text = '支付成功';
                        break;
                    case 3 :
                        $className = 'fa fa-mail-forward color-gray font-18';
                        $text = '已退款';
                        break;
                }
                return  Html::tag('i', '', ['class' => $className, 'title' => $text]);
            }
        ] ,
        [
            'label' => '交易完成时间',
            'attribute' => 'rechargeTime',
            'value'=>'rechargeTime',
            'headerOptions' => ['width' => '6%'],
        ],
        [
            'label' => '创建时间',
            'attribute' => 'createTime',
            'value'=>'createTime',
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
        $('.search-recharge-channel .dropdown-menu a').click(function () {
            $('#rechargeChannel').val($(this).attr('data-searchChannel'));
            $('button.btn-info.dropdown-toggle.status').attr('data-searchChannel', $(this).attr('data-searchChannel'));
            $('button.btn-info.dropdown-toggle.status').html($(this).text() + '　<span class="caret"></span>');
            $('button.search').trigger('click');
        });
        $('.search-pay-status .dropdown-menu a').click(function () {
            $('#payStatus').val($(this).attr('data-searchPayStatus'));
            $('button.btn-blue.dropdown-toggle.pay-status').attr('data-searchPayStatus', $(this).attr('data-searchPayStatus'));
            $('button.btn-blue.dropdown-toggle.pay-status').html($(this).text() + '　<span class="caret"></span>');
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
        var payStatus = $("#payStatus").val();
        var content = $("#content").val();
        var searchType = $("#searchType").val();
        var startDate = $("#startDate").val();
        var endDate = $("#endDate").val();
        var rechargeChannel = $("#rechargeChannel").val();
        var token = $("#token").val();
        var domainUrl = $("#domainUrl").val();
        var date = $("#date").val();

        if(date != false){
            var arrDate = strDate(date);
            startDate = arrDate[0];
            endDate = arrDate[1]
        }

        var downloadUrl = domainUrl+'?rechargeTime='+startDate+' 00:00:00,'+endDate+' 23：59：59&token='+token;

        if(payStatus != 4){
            downloadUrl = downloadUrl+'&payStatus='+payStatus;
        }

        if(rechargeChannel != false){
            downloadUrl = downloadUrl+'&rechargeChannel='+rechargeChannel;
        }

        if(content != false){
            downloadUrl = downloadUrl+'&'+searchType+'='+content;
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
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
        'label' => '订单列表' ,
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
    th{font-weight:bold;font-size:13px}
    table a.btn{margin-top: 4px}
</style>

<?php
$form = ActiveForm::begin([
    'id' => 'detail-form',
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
<div class="form-group pull-right col-sm-6">
        <div class="col-sm-1" style="margin-left: 15%">
            <a href="<?= Url::to(['/mall/order'])?>" class="btn btn-default" title="刷新">
                <i class="fa fa-refresh"></i>
            </a>
        </div>
    <div class="col-sm-3 ">
        <div class="input-group ">
            <span class="input-group-addon"><i class="entypo-calendar"></i></span>
            <input type="text" class="form-control cursor-pointer" style="width: 200px;" name="date" id="date" readonly placeholder="选择购买时间" value="<?= Yii::$app->request->get('date'); ?>" >
        </div>
    </div>
    <div class="input-group league-search-type col-sm-5 pull-right" style="margin-bottom: 10px">
        <div class="input-group-btn searchType">
            <?php
            $searchType = Yii::$app->request->get('searchType', 'userNo');
            $searchTypeList = [ 'userNo' => '用户ID','nickName' => '用户昵称', 'mobile' => '手机号'];
            ?>
            <input type="hidden" id="searchType" name="searchType" value="<?= $searchType ?>">
            <button type="button" class="btn btn-success dropdown-toggle btn-width-100 type"
                    data-searchtype="<?= $searchType ?>" data-toggle="dropdown">
                <?= $searchTypeList[$searchType] ?>　<span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-green">
                <?php foreach($searchTypeList as $key => $value){ ?>
                    <li><a href="javascript:void(0);" data-searchtype="<?= $key?>"><?= $value?></a></li>
                <?php } ?>
            </ul>
        </div>
        <input type="text" id="content" class="form-control" name="content" placeholder="" value="<?= Yii::$app->request->get('content'); ?>">
        <div class="input-group-btn">
            <button  type="submit" class="btn btn-success search">
                <i class="entypo-search"></i>
            </button>
        </div>
        <a href="<?= \Yii::$app->params['pubgApiDomain'].'/shop/exportOrderList?buyTime='.date('Y-m-d',strtotime('-1 month')).' 00:00:00,'.date('Y-m-d').' 23:59:59&token=Bearer '.\Yii::$app->cache->get('login-'.\Yii::$app->user->id) ?>"
           id="download_url"  class="btn btn-success pull-right">
            <i class="fa fa-download"></i>导出
        </a>
        <input type="hidden" id="startDate" value="<?= date("Y-m-d", strtotime('-1 month'))?>">
        <input type="hidden" id="endDate" value="<?= date("Y-m-d")?>">
        <input type="hidden" id="token" value="<?='Bearer '.\Yii::$app->cache->get('login-'.\Yii::$app->user->id)?>">
        <input type="hidden" id="domainUrl" value="<?= \Yii::$app->params['pubgApiDomain'].'/shop/exportOrderList' ?>">
    </div>
</div>
<?php
ActiveForm::end();
echo GridView::widget( [
    'id'               => 'goods' ,
    'dataProvider'     => $dataProvider ,
    'emptyText'        => "暂无商品消费信息！" ,
    'emptyTextOptions' => [ 'class' => 'text-center' ] ,
    'tableOptions'     => [ 'class' => 'table table-bordered datatable no-footer hover stripe text-center'] ,
    'options'          => [ 'class' => 'dataTables_wrapper no-footer no-border' ] ,
    'layout'           => "{errors}{items}{pager}" ,
    "columns"          => [
        [
            'label' => '订单号',
            'value' => 'orderNo',
            'headerOptions' => ['width' => '2%']
        ],
        [
            'label' => '商品名',
            'value' => 'goodsName',
            'headerOptions' => ['width' => '1%']
        ],
        [
            'label' => '商品图',
            'format' => [
                'image',
                [
                    'height'=>'40',
                ]
            ],
            'value' => function ($model) {
                return $model['goodsIcon'];
            },
            'headerOptions' =>['width' => '2%']
        ],
        [
            'label' => '用户ID',
            'value' => 'userNo',
            'headerOptions' => ['width' => '1%']
        ],
        [
            'label' => '用户昵称',
            'value' => 'nickName',
            'headerOptions' => ['width' => '1%']
        ],
        [
            'label' => '收货人',
            'value' => 'contactsName',
            'headerOptions' => ['width' => '1%']
        ],
        [
            'label' => '手机号',
            'value' => 'mobile',
            'headerOptions' => ['width' => '1%']
        ],
        [
            'label' => '身份证',
            'value' => 'idCard',
            'headerOptions' => ['width' => '1%']
        ],
        [
            'label' => '快递公司',
            'value' => 'expressName',
            'headerOptions' =>['width' => '1%']
        ],
        [
            'label' => '快递号',
            'value' => 'expressNo',
            'headerOptions' => ['width' => '2%']
        ],
        [
            'label' => '购买时间',
            'value' => 'buyTime',
            'headerOptions' => ['width' => '1%']
        ],
        [
            'label' => '购买数量',
            'value' => 'buyCount',
            'headerOptions' => ['width' => '1%']
        ],
        [
            'label' => '购买总金额',
            'value' => 'totalOrderFee',
            'headerOptions' => ['width' => '2%']
        ],
        [
            'class'    => ActionColumn::className() ,
            'template' => '{status}' ,
            'header'   => '交易状态' ,
            'contentOptions' => ['class' => 'text-center'],
            'headerOptions' => ['width' => '3%'],
            'buttons'  => [
                'status' => function( $url,$model ){
                    switch ($model['status']){
                        case 1:
                            return Html::tag( 'span' , '待发货' , [ 'class' => 'label label-default'] );
                            break;
                        case 2:
                            return Html::tag( 'span' , '已发货' , [ 'class' => 'label label-success'] );
                            break;

                    }
                } ,
            ] ,
        ],
        [
            'class'    => ActionColumn::className() ,
            'template' => '{express}' ,
            'header'   => '操作' ,
            'contentOptions' => ['class' => 'actions'],
            'headerOptions' =>['width' => '8%'],
            'buttons'  => [
                'express' => function ($url,$model) {
                    $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-pencil' ] );
                    return Html::a($icon."快递",'#', [ 'class' => 'express btn btn-green btn-sm btn-icon icon-left',
                        'type' => 'button','data-id' => $model['shopOrderId'],'data-expressNo' => $model['expressNo'],'data-expressName' => $model['expressName']] );
                },
            ] ,
        ] ,
    ] ,
    'pager'            => [
        'options'      => [ 'class' => 'pagination dataTables_paginate paging_simple_numbers' ] ,
        'linkOptions' => [ 'class' => 'paginate_button' ] ,
    ] ,
] );

?>
<!--modal start-->
<div class="modal fade in" id="express-number" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">录入快递</h4>
            </div>
            <div class="modalbody">
                <div class="form-group">
                    <label for="reply" class="col-sm-2 control-label">快递公司</label>
                    <div class="input-group col-sm-8">
                        <input type="text" class="form-control" autocomplete="false" name="expressName"  id="expressName" value="" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="reply" class="col-sm-2 control-label">快递单号</label>
                    <div class="input-group col-sm-8">
                        <input type="text" class="form-control" autocomplete="false" name="expressNo"  id="expressNo" value="" >
                    </div>
                </div>
            </div>
            <input type="hidden" id="order_id" value="0">
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" id="subExpress">提交更改</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>
<!--modal end-->
<script>
    $(document).ready(function(){
        $('.searchType .dropdown-menu a').click(function () {
            $('#searchType').val($(this).attr('data-searchtype'));
            $('button.btn-success.dropdown-toggle').attr('data-searchtype', $(this).attr('data-searchtype'));
            $('button.btn-success.dropdown-toggle').html($(this).text() + '　<span class="caret"></span>');
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
    $('a.express').click(function(){
        var orderId = $(this).attr('data-id');
        var expressNo = $(this).attr('data-expressNo');
        var expressName = $(this).attr('data-expressName');
        $("#order_id").val(orderId);
        $("#expressNo").val(expressNo);
        $("#expressName").val(expressName);
        $("#express-number").modal('show');
        $("#express-number").on('hide.bs.modal',function () {
            $("#express").val('');
            $("#order_id").val('');
        })
    });
    $("#subExpress").click(function(){
        var orderId = $("#order_id").val();
        var expressNo = $("#expressNo").val();
        var expressName = $("#expressName").val();

        $.ajax({
            url:"<?= Url::to(['/mall/order/express'])?>",
            data:{
                shopOrderId:orderId,
                expressNo:expressNo,
                expressName:expressName
            },
            dataType:'json',
            type:'get',
            success : function(response){
                if(response.status == 'success'){
                    toastr.success('快递录入成功！' , '' , $(this).toastrOpts);
                    window.location.reload();
                }else{
                    toastr.error(response.message , '' , $(this).toastrOpts);
                    $("#express-number").modal('hide');
                }
            }
        })
    })

    function updateUrl(){
        var content = $("#content").val();
        var searchType = $("#searchType").val();
        var startDate = $("#startDate").val();
        var endDate = $("#endDate").val();
        var token = $("#token").val();
        var domainUrl = $("#domainUrl").val();
        var date = $("#date").val();

        if(date != false){
            var arrDate = strDate(date);
            startDate = arrDate[0];
            endDate = arrDate[1]
        }

        var downloadUrl = domainUrl+'?buyTime='+startDate+' 00:00:00,'+endDate+' 23：59：59&token='+token;

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
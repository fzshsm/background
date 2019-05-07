<?php
use app\assets\AppAsset;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->params['breadcrumbs'] = [
    [
        'label' => '充值统计',
        'url' => Url::to([
            '/statistics/finance'
        ] )
    ]
];
AppAsset::registerCss($this , '@web/plugin/daterangepicker/daterangepicker-bs3.css');
AppAsset::registerCss($this , '@web/plugin/vertical-timeline/css/component.css');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/moment.min.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/daterangepicker.js');
AppAsset::registerJs($this , '@web/plugin/' . (YII_DEBUG ? 'echarts.js' : 'echarts.min.js'));
Pjax::begin(['options' => ['class' => 'container-fluid']]);
$form = ActiveForm::begin([
    'id' => 'date-form',
    'method' => 'get',
    'options' => ['data-pjax' => true , 'role' => 'form']
] );
?>
<div class="col-md-6 pull-right">
    <div class="col-sm-4 pull-right">
        <div class="input-group">
            <span class="input-group-addon"><i class="entypo-calendar"></i></span>
            <input type="text" class="form-control cursor-pointer" name="date" id="date" readonly placeholder="选择日期" value="<?= str_replace('_' , ' 至 ' , Yii::$app->request->get('date')); ?>" >
            <input type="hidden" id="hidden_date">
        </div>
    </div>
    <div class="col-sm-1 pull-right refresh">
        <a href="javascript:void(0);" class="btn btn-default" title="刷新">
            <i class="fa fa-refresh"></i>
        </a>
    </div>
</div>
<?php
ActiveForm::end();
?><div>
    <ul class="nav nav-tabs border"><!-- available classes "bordered", "right-aligned" -->
        <li class="active">
            <a href="#recharge-chart" data-toggle="tab">
                <span >充值金额</span>
            </a>
        </li>
        <li>
            <a href="#order-chart" data-toggle="tab">
                <span>充值订单数</span>
            </a>
        </li>
        <li>
            <a href="#ar-chart" data-toggle="tab">
                <span>ARUP</span>
            </a>
        </li>
    </ul>
    <div class="tab-content" >
        <div id="recharge-chart" class="tab-pane fade in active col-md-10 chart-height-420"></div>
        <div id="order-chart" class="tab-pane fade col-md-10 chart-height-420"></div>
        <div id="ar-chart" class="tab-pane fade in  col-md-10 chart-height-420"></div>
    </div>
</div>

<?php
Pjax::end();
?>
<script>
jQuery( document ).ready( function( $ ){
    getData();
    getDate();

    $(".refresh a").click(function(){
        $("#date").val('');
        $("#hidden_date").val('');
        getDate();
        getData();
    })
});

function getData(){
    var date = $("#hidden_date").val();
    var data = {};
    if(date != ''){
        data = {date:date}
    }

    $.ajax({
        url:"<?= Url::to(['/statistics/recharge'])?>",
        data:data,
        type:'get',
        dateType:'json',
        success:function(response){
            var dataObj=eval("("+response+")");
            var recharge = dataObj.recharge;
            var dateList = dataObj.dateList;

            var charts = [];
            var chart1 = echarts.init(document.getElementById("recharge-chart"));
            var chart2 = echarts.init(document.getElementById("order-chart"));
            var chart3 = echarts.init(document.getElementById("ar-chart"));
            chart1.setOption(chartOption('recharge',dateList,recharge));
            chart2.setOption(chartOption('order',dateList,recharge));
            chart3.setOption(chartOption('ar',dateList,recharge));
            charts.push(chart1);
            charts.push(chart2);
            charts.push(chart3);

            $(window).resize(function () {
                for (var i = 0; i < charts.length; i++) {
                    charts[i].resize();
                }
            });
            //解决tab切换不显示问题 在加载窗口后重新渲染。
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                for (var i = 0; i < charts.length; i++) {
                    charts[i].resize();
                }
            });
        }
    })
}

function chartOption(chartName,dateList,recharge) {

    var title,type;
    var seriesData = [];
    var color = ['#00d305','#56abe4','#CD2626'];
    switch (chartName){
        case 'recharge':
            title = '充值金额(元)';
            type = 0;
            break;
        case 'order':
            title = '充值订单数(笔)';
            type = 1;
            break;
        case 'ar':
            title = 'ARUP(元/人)';
            type= 2;
    }

    for(i in recharge[type]['statistic']){
        var colorType = color[i]
        if(type == 2){
            colorType = color[2]
        }
        seriesData.push(
            {
                name: recharge[type]['name'][i],
                type: 'line',
                smooth: true,
                symbol: 'circle',
                symbolSize: 5,
                showSymbol: false,
                barWidth:15,
                data:recharge[type]['statistic'][i],
                itemStyle: {
                    normal: {
                        color: colorType
                    }
                },
                markPoint : {
                    symbolSize: [100,50],
                    data : [
                        {type : 'max', name: '最大值'}
                    ]
                }
            },
        )
    }

    return {
        title: {
            text: '',
            subtext: title
        },
        tooltip: {
            trigger: 'axis'
        },
        legend: {
            data:recharge[type]['name'],
            textStyle: {
                color: '#333',
            },
            type: 'scroll',
            width:'70%',
            left:'12%',
            top: '2%'
        },
        toolbox: {
            show: true,
            feature: {
                dataZoom: {
                    yAxisIndex: 'none'
                },
                dataView: {readOnly: false},
                magicType: {type: ['line', 'bar']},
                restore: {},
                saveAsImage: {}
            }
        },
        xAxis: {
            type: 'category',

            data: dateList,
            axisLabel: {
                textStyle: {
                    color: '#000'
                }
            }
        },
        yAxis: {
            type: 'value',
            axisLabel: {
                formatter: '{value} ',
                textStyle: {
                    color: '#000'
                }
            }
        },
        series: seriesData
    }
}

function getDate() {
    $('#date').daterangepicker({
        startDate: "<?= date('Y-m-d H:i' , strtotime( "-1 month")) ?>",
        endDate: "<?= date('Y-m-d H:i' , time()) ?>",
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
        var date = $('#date').val();
        date = date.replace(/( 至 )/gi , function($){
            return '_';
        });
        $('#hidden_date').val(date);
        getData()
    });
}

</script>

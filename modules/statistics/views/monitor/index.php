<?php
use app\assets\AppAsset;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->params['breadcrumbs'] = [
    [
        'label' => '充值监控',
        'url' => Url::to([
            '/statistics/finance/monitor'
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

    <div id="monitor-chart" class=" col-md-10" style="height: 650px !important">
        <div id="monitor-bar" class="col-md-8" style="height: 650px !important"></div>
        <div id="monitor-pie" class="col-md-4 " style=" height: 500px !important"></div>
    </div>

</div>

<?php
Pjax::end();
?>
<script>
var auto
jQuery( document ).ready( function( $ ){
    getData();
    getDate();
    var t = 0;
    $(".refresh a").click(function(){
        $("#date").val('');
        $("#hidden_date").val('');
        getDate();
        getData();
    })

    auto = window.setInterval(function(){getData()},65000);
});

function getData(){
    var date = $("#hidden_date").val();
    var data = {};
    if(date != ''){
        data = {date:date}
    }

    $.ajax({
        url:"<?= Url::to(['/statistics/monitor'])?>",
        data:data,
        type:'get',
        dateType:'json',
        success:function(response){
            var dataObj=eval("("+response+")");
            var monitor = dataObj.monitor;
            var dateList = dataObj.dateList;

            var charts = [];
            var chart1 = echarts.init(document.getElementById("monitor-bar"));
            var chart2 = echarts.init(document.getElementById("monitor-pie"));

            chart1.setOption(chartOption('monitor-bar',dateList,monitor,'bar'));
            chart2.setOption(pieChart('monitor-pie',monitor));

            charts.push(chart1);
            charts.push(chart2);

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

function chartOption(chartName,dateList,monitor) {

    var title,type;
    var seriesData = [];
    var color = ['#ee9a00','#636363','#CD2626'];
    switch (chartName){
        case 'monitor-bar':
            title = '充值监控';
            type = 0;
            break;
    }

    for(i in monitor[type]['statistic']){
        if(i == 2){
            seriesData.push(
                {
                    name:monitor[type]['name'][i],
                    type:'line',
                    xAxisIndex: 1,
                    yAxisIndex: 1,
                    symbolSize: 8,
                    hoverAnimation: false,
                    data:monitor[type]['statistic'][i],
                    itemStyle: {
                        normal: {
                            color: color[i],

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
        }else{
            seriesData.push(
                {
                    name:monitor[type]['name'][i],
                    type:'line',
                    symbolSize: 8,
                    hoverAnimation: false,
                    data:monitor[type]['statistic'][i],
                    itemStyle: {
                        normal: {
                            color: color[i],

                        }
                    },
                    markPoint : {
                        data : [
                            {type : 'max', name: '最大值'}
                        ]
                    }
                },
            )
        }
    }
    var timeData = dateList;


    return option = {
        title: {
            text: '充值实时监控',
            subtext: '',
            x: 'center'
        },
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                animation: false
            },
            formatter:function(params){

                var balanceName = '';
                var useName = '';
                var allName = '';
                var balance = '';
                var use = '';
                var all = '';

                if(params[1].seriesName == '用户总余额价值'){
                    balanceName = params[1].seriesName;
                    balance = params[1].value;
                    useName = params[2].seriesName;
                    use = params[2].value;
                    allName = params[0].seriesName;
                    all = params[0].value;
                }
                if(params[0].seriesName == '用户总余额价值'){
                    balanceName = params[0].seriesName;
                    balance = params[0].value;
                    useName = params[1].seriesName;
                    use = params[1].value;
                    allName = params[2].seriesName;
                    all = params[2].value;
                }

                var title = params[0].name+"<br/>";


                var balanceRatio = Math.round(balance / all * 10000) / 100.00 + "%";
                var useRatio = Math.round(use / all * 10000) / 100.00 + "%";

                title += balanceName+ ' : ' + balance+"元("+balanceRatio+")<br/>";
                title += useName+ ' : ' + use+"元("+useRatio+")<br/>";
                title += allName+ ' : ' + all+"元(100%)<br/>";
                return title;
            }
        },
        legend: {
            data:['用户总余额价值','狗粮总消耗价值','充值总额'],
            x: 'left'
        },
        toolbox: {
            feature: {
                dataZoom: {
                    yAxisIndex: 'none'
                },
                restore: {},
                saveAsImage: {}
            }
        },
        axisPointer: {
            link: {xAxisIndex: 'all'}
        },
        dataZoom: [
            {
                show: true,
                realtime: true,
                start: 40,
                end: 100,
                xAxisIndex: [0, 1]
            },
            {
                type: 'inside',
                realtime: true,
                start: 40,
                end: 100,
                xAxisIndex: [0, 1]
            }
        ],
        grid: [{
            left: 50,
            right: 50,
            height: '35%'
        }, {
            left: 50,
            right: 50,
            top: '55%',
            height: '35%'
        }],
        xAxis : [
            {
                type : 'category',
                boundaryGap : false,
                axisLine: {onZero: true},
                data: timeData
            },
            {
                gridIndex: 1,
                type : 'category',
                boundaryGap : false,
                axisLine: {onZero: true},
                data: timeData,
                position: 'bottle'
            }
        ],
        yAxis : [
            {
                name : '狗粮价值(元)',
                type : 'value',
            },
            {
                gridIndex: 1,
                name : '充值总额(元)',
                type : 'value',
                //inverse: true,
            }
        ],
        series : seriesData
    };
}

function getDate() {
    $('#date').daterangepicker({
        startDate: "<?= date('Y-m-d H:i:s' , strtotime( "-1 day")) ?>",
        endDate: "<?= date('Y-m-d H:i:s' , time()) ?>",
        showWeekNumbers : false, //是否显示第几周
        timePicker : true, //是否显示小时和分钟
        timePickerIncrement : 5, //时间的增量，单位为分钟
        timePicker12Hour : false, //是否使用12小时制来显示时间
        opens : 'right', //日期选择框的弹出位置
        buttonClasses : [ 'btn btn-default' ],
        applyClass : 'btn-small btn-success',
        cancelClass : 'btn-small',
        format : 'YYYY-MM-DD HH:mm:ss', //控件中from和to 显示的日期格式
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
        $('#date span').html(start.format('YYYY-MM-DD HH:mm:ss') + ' 至 ' + end.format('YYYY-MM-DD HH:mm:ss'));
    }).on('apply.daterangepicker' , function(){
        var date = $('#date').val();
        date = date.replace(/( 至 )/gi , function($){
            return '_';
        });
        $('#hidden_date').val(date);
        getData()
        clearInterval(auto);
    });
}

function pieChart(chartName,monitor){

    var title,type;

    switch (chartName){
        case 'monitor-pie':
            title = '充值实时监控';
            type = 1;
            break;
    }

    return {
        title : {
            text: '',
            subtext: title,
            x:'center'
        },
        tooltip : {
            trigger: 'item',
            formatter: function(p) {
                return p.seriesName+"<br/>"+p.name+":"+formatNum(p.value)+"元("+p.percent+"%)";
            }
        },
        legend: {
            orient : 'vertical',
            x : 'left',
            data:monitor[type]['name']
        },
        toolbox: {
            show : true,
            feature : {
                mark : {show: true},
                dataView : {show: true, readOnly: false},
                magicType : {
                    show: true,
                    type: ['pie', 'funnel'],
                    option: {
                        funnel: {
                            x: '25%',
                            width: '50%',
                            funnelAlign: 'left',
                            max: 1548
                        }
                    }
                },
                restore : {show: true},
                saveAsImage : {show: true}
            }
        },
        calculable : true,
        series : [
            {
                name:title,
                type:'pie',
                radius : '55%',
                center: ['50%', '60%'],
                label:{            //饼图图形上的文本标签
                    normal:{
                        show:true,
                        position:'inner', //标签的位置
                        textStyle : {
                            fontWeight : 200 ,
                            fontSize : 10    //文字的字体大小
                        },
                        formatter:'{d}%'
                    }
                },
                itemStyle: {
                    normal: {
                        color:
                        function(params) {
                            // build a color map as your need.
                            var colorList = [
                                '#ee9a00','#636363','#CD2626'
                            ];
                            return colorList[params.dataIndex]
                        }
                    }
                },
                data:monitor[type]['statistic']
            }
        ]
    };
}

function formatNum(strNum) {
    if(strNum.length <= 3) {
        return strNum;
    }
    if(!/^(\+|-)?(\d+)(\.\d+)?$/.test(strNum)) {
        return strNum;
    }
    var a = RegExp.$1,
        b = RegExp.$2,
        c = RegExp.$3;
    var re = new RegExp();
    re.compile("(\\d)(\\d{3})(,|$)");
    while(re.test(b)) {
        b = b.replace(re, "$1,$2$3");
    }
    return a + "" + b + "" + c;
}

</script>

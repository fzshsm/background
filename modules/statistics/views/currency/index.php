<?php
use app\assets\AppAsset;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->params['breadcrumbs'] = [
    [
        'label' => '豆豆统计',
        'url' => Url::to([
            '/statistics/finance/currency'
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
            <a href="#overall-chart" data-toggle="tab">
                <span >综合</span>
            </a>
        </li>
        <li>
            <a href="#currency-gain-chart" data-toggle="tab">
                <span>产出明细</span>
            </a>
        </li>
        <li>
            <a href="#currency-use-chart" data-toggle="tab">
                <span>消耗明细</span>
            </a>
        </li>
<!--        <li>-->
<!--            <a href="#wx-chart" data-toggle="tab">-->
<!--                <span>微信</span>-->
<!--            </a>-->
<!--        </li>-->
    </ul>
    <div class="tab-content" >
        <div id="overall-chart" class="tab-pane fade in active col-md-12" style="height: 650px !important">
            <div id="overall-bar" class="col-md-9" style="height: 650px !important"></div>
            <div id="overall-pie" class="col-md-3 chart-height-420"></div>
        </div>
        <div id="currency-gain-chart" class="tab-pane fade col-md-12 chart-height-420">
            <div id="currency-gain-line" class="col-md-8 chart-height-420"></div>
            <div id="currency-gain-pie" class="col-md-4 chart-height-420"></div>
        </div>
        <div id="currency-use-chart" class="tab-pane fade col-md-12 chart-height-420">
            <div id="currency-use-line" class="col-md-8 chart-height-420"></div>
            <div id="currency-use-pie" class="col-md-4 chart-height-420"></div>
        </div>
<!--        <div id="wx-chart" class="tab-pane fade col-md-10 chart-height-420"></div>-->
    </div>
</div>

<div>
    <div id="task-detail-chart" class="col-md-12" style="height: 450px !important">
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
        $("#task-detail-chart").hide();
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
        url:"<?= Url::to(['/statistics/currency'])?>",
        data:data,
        type:'get',
        dateType:'json',
        success:function(response){
            var dataObj=eval("("+response+")");
            var currency = dataObj.currency;
            var dateList = dataObj.dateList;

            var charts = [];
            var chart1 = echarts.init(document.getElementById("overall-bar"));
            var chart2 = echarts.init(document.getElementById("currency-gain-line"));
            var chart3 = echarts.init(document.getElementById("currency-use-line"));
            var chart4 = echarts.init(document.getElementById("currency-gain-pie"));
            var chart5 = echarts.init(document.getElementById("currency-use-pie"));
            var chart6 = echarts.init(document.getElementById("overall-pie"));
            chart1.setOption(chartOtherOption('overall',dateList,currency),true);
            chart2.setOption(chartClickOption('gain',dateList,currency),true);
            chart3.setOption(chartOption('use',dateList,currency,'bar'),true);
            chart4.setOption(pieChart('gain-pie',currency),true);
            chart5.setOption(pieChart('use-pie',currency),true);
            chart6.setOption(pieChart('overall-pie',currency),true);

            chart2.on("click", function (param){
                var time = param.name;
                if(param.seriesName == '任务产出'){
                    getTaskDetail(time)
                    $("#task-detail-chart").show();
                }

            });
            charts.push(chart1);
            charts.push(chart2);
            charts.push(chart3);
            charts.push(chart4);
            charts.push(chart5);
            charts.push(chart6);

            $(window).resize(function () {
                for (var i = 0; i < charts.length; i++) {
                    charts[i].resize();
                }
            });
            //解决tab切换不显示问题 在加载窗口后重新渲染。
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                if(e.currentTarget.hash != '#currency-gain-chart'){
                    $("#task-detail-chart").hide();
                }
                for (var i = 0; i < charts.length; i++) {
                    charts[i].resize();
                }
            });
        }
    })
}

function chartOption(chartName,dateList,currency,chartType) {

    var title,type;
    var seriesData = [];
    var color = ['#ee9a00','#636363','#c23531','#61a0a8'];
    switch (chartName){
        case 'gain':
            title = '豆豆产出明细';
            type = 1;
            break;
        case 'use':
            title = '豆豆消耗明细';
            type = 2;
            break;
    }

    for(i in currency[type]['statistic']){
        seriesData.push(
            {
                name: currency[type]['name'][i] ,
                type: chartType,
                smooth: true,
                symbol: 'circle',
                symbolSize: 5,
                showSymbol: false,
                barWidth:10,
                stack: '豆豆',
                data:currency[type]['statistic'][i],
                itemStyle: {
                    normal: {
                        color: color[i]
                    }
                },
            },
        )
    }

    return {

        title: {
            text: '',
            subtext: title
        },
        tooltip: {
            trigger: 'axis',
            formatter:function(params){
                var len = params.length;
                var all = 0;
                var ratio = 0
                var val = 0;
                var title = params[0].name+"<br/>";
                for(var i =0;i<len;i++){
                    all = all + parseInt(params[i].value);
                }

                for(var j = 0;j<len;j++){
                    val = params[j].value;
                    if(all == 0){
                        ratio = "0%";
                    }else{
                        ratio = Math.round(val / all * 10000) / 100.00 + "%";
                    }

                    title += params[j].seriesName+ ' : ' + formatNum(val)+"("+ratio+")<br/>";
                }
                return title;
            }
        },
        legend: {
            data:currency[type]['name'],
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
                magicType: {type: ['bar','stack']},
                textStyle: {
                    color: '#615a5a'
                },
                restore: {},
                saveAsImage: {}
            }
        },
        xAxis: {
            type: 'category',
            //boundaryGap: false,
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
                formatter: '{value}',
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

function pieChart(chartName,currency){

    var title,type;

    switch (chartName){
        case 'gain-pie':
            title = '豆豆产出分布';
            type = 3;
            break;
        case 'use-pie':
            title = '豆豆消耗分布';
            type = 4;
            break;
        case 'overall-pie':
            title = '豆豆总产出与总消耗';
            type = 5;
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
            // formatter: "{a} <br/>{b} : {c} ({d}%)"
            formatter: function(p) {
                return p.seriesName+"<br/>"+p.name+":"+formatNum(p.value)+"("+p.percent+"%)";
            }
        },
        legend: {
            orient : 'vertical',
            x : 'left',
            data:currency[type]['name']
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
                                    '#ee9a00','#636363','#c23531','#61a0a8'
                                ];
                                return colorList[params.dataIndex]
                            }
                    }
                },
                data:currency[type]['statistic']
            }
        ],
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

function chartOtherOption(chartName,dateList,currency) {

    var title,type;
    var seriesData = [];
    var color = ['#ee9a00','#636363','#CD2626'];
    switch (chartName){
        case 'overall':
            title = '豆豆综合数据';
            type = 0;
            break;
    }

    for(i in currency[type]['statistic']){
        if(i == 1){
            seriesData.push(
                {
                    name:currency[type]['name'][i],
                    type:'bar',
                    xAxisIndex: 1,
                    yAxisIndex: 1,
                    symbolSize: 8,
                    hoverAnimation: false,
                    data:currency[type]['statistic'][i],
                    itemStyle: {
                        normal: {
                            color: color[i],

                        }
                    },
                },
            )
        }else{
            seriesData.push(
                {
                    name:currency[type]['name'][i],
                    type:'bar',
                    hoverAnimation: false,
                    symbolSize: 8,
                    data:currency[type]['statistic'][i],
                    itemStyle: {
                        normal: {
                            color: color[i],
                        }
                    },
                },
            )
        }
    }
    var timeData = dateList;

    return option = {
        title: {
            text: '豆豆产出与消耗',
            subtext: '',
            x: 'center'
        },
        tooltip: {
            trigger: 'axis',

            formatter:function(params){

                var gainName = '';
                var gain = 0;
                var useName = '';
                var use = 0;
                var gainRatio = "0%";
                var useRatio = "0%";

                if(params[1].seriesName == '豆豆总产出'){
                    gainName = params[1].seriesName;
                    gain = params[1].value;
                    useName = params[0].seriesName;
                    use = params[0].value;

                }
                if(params[1].seriesName == '豆豆总消耗'){
                    useName = params[0].seriesName;
                    use = params[0].value;
                    gainName = params[1].seriesName;
                    gain = params[1].value;
                }

                var title = params[0].name+"<br/>";
                var all = parseInt(use) + parseInt(gain)

                if(all != 0){
                    gainRatio = Math.round(gain / all * 10000) / 100.00 + "%";
                    useRatio = Math.round(use / all * 10000) / 100.00 + "%";
                }

                title += gainName+ ' : ' + formatNum(gain)+"("+gainRatio+")<br/>";
                title += useName+ ' : ' + formatNum(use)+"("+useRatio+")<br/>";
                return title;
            }
        },
        legend: {
            data:['豆豆总产出','豆豆总消耗'],
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
        grid: [{
            left: 90,
            right: 50,
            height: '35%'
        }, {
            left: 90,
            right: 50,
            top: '55%',
            height: '35%'
        }],
        xAxis : [
            {
                type : 'category',
                boundaryGap : true,
                axisLine: {onZero: true},
                data: timeData
            },
            {
                gridIndex: 1,
                type : 'category',
                boundaryGap : true,
                axisLine: {onZero: true},
                data: timeData,
                position: 'bottle'
            }
        ],
        yAxis : [
            {
                name : '豆豆每日总产出',
                type : 'value',
            },
            {
                gridIndex: 1,
                name : '豆豆每日总消耗',
                type : 'value',
                //inverse: true
            }
        ],
        series : seriesData
    };
}

function chartClickOption(chartName,dateList,currency) {

    var title,type;
    var seriesData = [];
    var color = ['#ee9a00','#636363','#c23531','#61a0a8'];
    switch (chartName){
        case 'gain':
            title = '豆豆产出明细';
            type = 1;
            break;
    }

    for(i in currency[type]['statistic']){
        seriesData.push(
            {
                name:currency[type]['name'][i],
                type:'bar',
                smooth: true,
                symbol: 'circle',
                symbolSize: 5,
                showSymbol: false,
                barWidth:10,
                stack:'人数',
                data:currency[type]['statistic'][i],
                itemStyle: {
                    normal: {
                        color: color[i],

                    }
                },
            },
        )
    }
    var timeData = dateList;


    return option = {
        title: {
            text: '',
            subtext: '豆豆产出明细',
        },
        tooltip: {
            trigger:'axis'
        },
        legend: {
            data:currency[type]['name'],
            textStyle: {
                color: '#333',
            },
            type: 'scroll',
            width:'70%',
            left:'10%',
            top: '2%'
        },

        toolbox: {
            show: true,
            right:'5%',
            feature: {
                dataZoom: {
                    yAxisIndex: 'none'
                },
                dataView: {readOnly: false},
                magicType: {type: ['bar','stack']},
                textStyle: {
                    color: '#615a5a'
                },
                restore: {},
                saveAsImage: {}
            }
        },
        axisPointer: {
            link: {xAxisIndex: 'all'}
        },

        xAxis : [
            {
                type : 'category',
                axisTick : {show: false},
                data : timeData
            }
        ],
        yAxis : [
            {
                type : 'value',
            }
        ],
        series : seriesData
    };
}

function getTaskDetail(time){

    $.ajax({
        url:"<?= Url::to(['/statistics/currency/task'])?>",
        data:{date:time},
        type:'get',
        dateType:'json',
        success:function(response){
            var dataObj=eval("("+response+")");
            var behavior = dataObj.task;
            var dateList = dataObj.dateList;

            var chart1 = echarts.init(document.getElementById("task-detail-chart"));

            chart1.setOption(chartBarAndPieOption('currency',dateList,behavior,'bar'),true);
            chart1.resize()
        }
    })
}

function chartBarAndPieOption(chartName,dateList,behavior,chartType) {

    var title,type;
    var seriesData = [];
    var color = ['#ee9a00','#636363','#CD2626','#1874CD','#00CD00','#B03060','#8F8F8F','#8B1A1A','#87CEFF','#FF7F00','#FF00FF','#141414','#00FA9A','#EEEE00'];
    switch (chartName){
        case 'currency':
            title = '任务产出详情统计';
            type = 0;
            break;
    }

    var lengthName = [].concat(behavior[type]['name']);
    lengthName.shift();
    for(i in behavior[type]['statistic']){
        if(i == 0){
            seriesData.push(
                {
                    name: behavior[type]['name'][i] ,
                    type: 'pie',
                    tooltip : {
                        trigger: 'item',
                        formatter: function(p) {
                            return p.seriesName+"<br/>"+p.name+":"+formatNum(p.value)+"("+p.percent+"%)";
                        }
                    },
                    center: [document.getElementById('task-detail-chart').offsetWidth - 290, 225],
                    radius: [30, 90],

                    itemStyle :　{
                        normal : {

                            color:function(params) {
                                var colorList = [
                                    '#ee9a00','#636363','#CD2626','#1874CD','#00CD00','#B03060','#8F8F8F','#8B1A1A','#87CEFF','#FF7F00','#FF00FF','#141414','#00FA9A','#EEEE00'
                                ];
                                return colorList[params.dataIndex]
                            },
                            label: {        //此处为指示线文字
                                show: true,
                                textStyle: {
                                    fontWeight: 200,
                                    fontSize: 10    //文字的字体大小
                                },
                                formatter: function (p) {   //指示线对应文字
                                    var data = p.name+'('+p.percent+'%)';
                                    return data;
                                }
                            },
                            labelLine: {    //指示线状态
                                length: 20
                            }

                        },
                    },
                    data:behavior[type]['statistic'][i],
                }
            )
        }else{
            seriesData.push(
                {
                    name: behavior[type]['name'][i] ,
                    type: chartType,
                    smooth: true,
                    symbol: 'circle',
                    symbolSize: 5,
                    showSymbol: false,
                    barWidth:35,
                    barGap: '120%',
                    data:behavior[type]['statistic'][i],
                    itemStyle: {
                        normal: {
                            color: color[i-1]
                        }
                    },
                    label: {
                        normal: {
                            show: true,
                            position: 'top',
                            formatter:function(p){
                                return formatNum(p.value)
                            }
                        }
                    },
                },
            )
        }
    }

    return {
        title: {
            text: title,
            subtext: '',
            left:'center'
        },
        tooltip: {
            trigger: 'axis',
        },
        legend: {
            data:lengthName,
            textStyle: {
                color: '#333',
            },
            type: 'scroll',
            width:'70%',
            left:'12%',
            top: '5%'
        },
        toolbox: {
            show: true,
            right:'5%',
            feature: {
                dataZoom: {
                    yAxisIndex: 'none'
                },
                dataView: {readOnly: false},
                magicType: {type: ['bar','stack']},
                textStyle: {
                    color: '#615a5a'
                },
                restore: {},
                saveAsImage: {}
            }
        },
        xAxis: [
            {
                type : 'category',
                axisTick : {show: false},
                boundaryGap : false,
                data : dateList
            }
        ],
        yAxis: [
            {
                type : 'value',
            }
        ],
        series: seriesData
    }
}

</script>

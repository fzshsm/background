<?php
use app\assets\AppAsset;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->params['breadcrumbs'] = [
    [
        'label' => '用户统计',
        'url' => Url::to([
            '/statistics/user'
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
            <a href="#active-chart" data-toggle="tab">
                <span >活跃用户</span>
            </a>
        </li>
        <li>
            <a href="#install-chart" data-toggle="tab">
                <span>新增用户</span>
            </a>
        </li>
        <li>
            <a href="#launch-chart" data-toggle="tab">
                <span>启动次数</span>
            </a>
        </li>
        <li>
            <a href="#behavior-chart" data-toggle="tab">
                <span>用户行为</span>
            </a>
        </li>
    </ul>
    <div class="tab-content" >
        <div id="active-chart" class="tab-pane fade in active col-md-12 chart-height-420"></div>
        <div id="install-chart" class="tab-pane fade col-md-12 chart-height-420"></div>
        <div id="launch-chart" class="tab-pane fade col-md-12 chart-height-420"></div>
        <div id="behavior-chart" class="tab-pane fade col-md-12 chart-height-420"></div>
    </div>
</div>
<div class="col-md-12">
    <div id="game-currency-chart" class="col-md-7" style="height: 450px !important">
    </div>
    <div id="league-task-chart" class="col-md-5" style="height: 450px !important">
    </div>
</div>

<div class="col-md-12">
    <div id="times-game-chart" class="col-md-7" style="height: 450px !important">
    </div>
    <div id="times-game-pie-chart" class="col-md-5" style="height: 450px !important">
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
        $("#league-task-chart").hide();
        $("#game-currency-chart").hide();
        $("#times-game-chart").hide();
        $("#times-game-pie-chart").hide();
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
        url:"<?= Url::to(['/statistics/user'])?>",
        data:data,
        type:'get',
        dateType:'json',
        success:function(response){
            var dataObj=eval("("+response+")");
            var user = dataObj.user;
            var dateList = dataObj.dateList;
            var behavior = dataObj.behavior;

            var charts = [];
            var chart1 = echarts.init(document.getElementById("active-chart"));
            var chart2 = echarts.init(document.getElementById("install-chart"));
            var chart3 = echarts.init(document.getElementById("launch-chart"));
            var chart4 = echarts.init(document.getElementById("behavior-chart"));

            chart1.setOption(chartOption('active',dateList,user),true);
            chart2.setOption(chartOption('install',dateList,user),true);
            chart3.setOption(chartOption('launch',dateList,user),true);
            chart4.setOption(chartOtherOption('behavior',dateList,behavior),true);

            chart4.on("click", function (param){
                var time = param.name;

                if(param.seriesName == '每日游戏人数'){
                    getLeagueOrTaskDetail(time,1)
                    getGameOrCurrencyDetail(time, 1, '每日联赛');
                    $("#times-game-chart").hide()
                    $("#times-game-pie-chart").hide();
                }

                if(param.seriesName == '每日任务人数'){
                    getLeagueOrTaskDetail(time,2)
                    getGameOrCurrencyDetail(time, 2, '每日任务');
                    $("#times-game-chart").hide()
                    $("#times-game-pie-chart").hide();
                }

                if(param.seriesName == '每日购物人数'){
                    console.log(1)
                    $("#times-game-chart").hide()
                    $("#times-game-pie-chart").hide();
                    getLeagueOrTaskDetail(time,3)
                    getGameOrCurrencyDetail(time, 3, '每日购物');
                }

                if(param.seriesName == '每日竞猜人数'){
                    $("#times-game-chart").hide()
                    $("#times-game-pie-chart").hide();
                    getLeagueOrTaskDetail(time,4)
                }

                if(param.seriesName == '每日抽奖人数'){
                    getLeagueOrTaskDetail(time,5)
                    $("#times-game-chart").hide()
                    $("#times-game-pie-chart").hide();
                }

                $("#game-currency-chart").show();
                $("#league-task-chart").show();

            });

            charts.push(chart1);
            charts.push(chart2);
            charts.push(chart3);
            charts.push(chart4);

            $(window).resize(function () {
                for (var i = 0; i < charts.length; i++) {
                    charts[i].resize();
                }
            });
            //解决tab切换不显示问题 在加载窗口后重新渲染。
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                if(e.currentTarget.hash != '#behavior-chart'){
                    $("#game-currency-chart").hide();
                    $("#league-task-chart").hide();
                    $("#times-game-chart").hide()
                    $("#times-game-pie-chart").hide();
                }
                for (var i = 0; i < charts.length; i++) {
                    charts[i].resize();
                }
            });
        }
    })
}

function chartOption(chartName,dateList,user) {

    var title,type,unit;
    var seriesData = [];
    switch (chartName){
        case 'active':
                title = '活跃用户(人)';
                type = 0;
            break;
        case 'install':
                title = '新增用户(人)';
                type = 1;
            break;
        case 'launch':
                title = '启动次数(次)';
                type = 2;
            break;
    }

    for(i in user[type]['statistic']){
        seriesData.push(
            {
                name: user[type]['name'][i] ,
                type: 'line',
                smooth: true,
                symbol: 'circle',
                symbolSize: 5,
                showSymbol: false,
                data:user[type]['statistic'][i],
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
        textStyle: {
            color: '#ffff00'
        },
        title: {
            text: '',
            subtext: title
        },
        tooltip: {
            trigger: 'axis'
        },
        legend: {
            data:user[type]['name'],
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
            boundaryGap: false,
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

function chartOtherOption(chartName,dateList,behavior) {

    var title,type;
    var seriesData = [];
    var color = ['#CD2626','#ee9a00','#4876FF','#9400D3','#636363','#1874CD'];
    switch (chartName){
        case 'behavior':
            title = '用户行为统计';
            type = 0;
            break;
    }

    for(i in behavior[type]['statistic']){
        seriesData.push(
            {
                name:behavior[type]['name'][i],
                type:'bar',
                smooth: true,
                symbol: 'circle',
                symbolSize: 5,
                showSymbol: false,
                barWidth:10,
                stack:'人数',
                data:behavior[type]['statistic'][i],
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
            subtext: '每日用户行为统计',
        },
        tooltip: {
            trigger:'axis'
        },
        legend: {
            data:behavior[type]['name'],
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
                name: '单位:人',
                type : 'value',
            }
        ],
        series : seriesData
    };
}

function detailChartOption(chartName,dateList,behavior,chartType,titleName,colorVal) {

    var title,type;
    var seriesData = [];
    var color = [ '#CD2626','#4876FF','#0f990f','#636363','#B03060','#ee9a00','#8B1A1A','#87CEFF','#FF7F00','#FF00FF',' #FF4500','#8B4513'];

    switch (chartName){
        case 'game':
            title = titleName+'统计';
            type = 0;
            break;
        case 'currency':
            title = titleName+'统计';
            type = 0;
            break;
        case 'goods':
            title = titleName+'统计';
            type = 0;
            break;
        case 'guess':
            title = titleName+'统计';
            type = 0;
            break;
        case 'times-game':
            title = titleName+'分时段统计';
            type = 0;
            break;
        case 'lottery':
            title = titleName+'统计';
            type = 0;
            break;
    }

    for(i in behavior[type]['statistic']){
        var thisColor = colorVal;
        if(thisColor == null){
            thisColor = color[i]
        }
        seriesData.push(
            {
                name: behavior[type]['name'][i] ,
                type: chartType,
                smooth: true,
                symbol: 'circle',
                symbolSize: 5,
                showSymbol: false,
                barWidth:22,
                barGap:'80%',
                data:behavior[type]['statistic'][i],
                itemStyle: {
                    normal: {
                        color: thisColor
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

    return {
        title: {
            text: title,
            subtext: '',
            left: 'center'
        },
        tooltip: {
            trigger: 'axis',
        },
        legend: {
            data:behavior[type]['name'],
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
                data : dateList,
            }
        ],
        yAxis: [
            {
                name: '单位:人',
                type : 'value',
            }
        ],
        series: seriesData
    }
}

function getLeagueOrTaskDetail(time,type){
    var leagueUrl = "<?= Url::to(['/statistics/behavior/league'])?>";
    var taskUrl = "<?= Url::to(['/statistics/behavior/task'])?>";
    var orderUrl =  "<?= Url::to(['/statistics/behavior/order'])?>";
    var guessUrl =  "<?= Url::to(['/statistics/behavior/guess'])?>";
    var lotteryUrl =  "<?= Url::to(['/statistics/behavior/lottery'])?>";

    var url = leagueUrl;
    var chartType = 'league';
    var title = '';

    switch (type){
        case 2:
            url = taskUrl;
            chartType = 'task';
            title = '每日任务'
            break;
        case 3:
            url = orderUrl;
            chartType = 'order';
            title  = '每日购物';
            break;
        case 4:
            url = guessUrl;
            chartType = 'guess';
            title = '每日竞猜'
            break;
        case 5:
            url = lotteryUrl;
            chartType = 'lottery';
            title = '每日抽奖';
            break;
    }

    $.ajax({
        url:url,
        data:{date:time},
        type:'get',
        dateType:'json',
        success:function(response){
            var dataObj=eval("("+response+")");
            var behavior = dataObj.behavior;
            var dateList = dataObj.dateList;
            if(type == 4 || type == 5){
                var barbehavior= dataObj.barBehavior;
                var chartBar = echarts.init(document.getElementById("game-currency-chart"));
                chartBar.setOption(detailChartOption(chartType,dateList,barbehavior,'bar',title),true);
                chartBar.resize()
            }

            var chartPie = echarts.init(document.getElementById("league-task-chart"));

            chartPie.setOption(pieChart(chartType,behavior,dateList[0]),true);
            chartPie.off('click');
            chartPie.on("click", function (param){
                var time = dateList[0];
                var taskArr = ['百斗任务', '每日任务'];
                var leagueArr = ['专业赛', '竞技赛','娱乐赛', '赏金赛'];
                var orderArr = ['充值卡','优惠券','游戏周边','电子产品'];
                if(taskArr.indexOf(param.name) !== -1){
                    getGameOrCurrencyDetail(time, 2, param.name);
                    $("#game-currency-chart").show()
                }

                if(leagueArr.indexOf(param.name) !== -1){
                    getGameOrCurrencyDetail(time, 1, param.name);
                    $("#game-currency-chart").show()
                }

                if(orderArr.indexOf(param.name) !== -1){
                    getGameOrCurrencyDetail(time, 3, param.name);
                    $("#game-currency-chart").show()
                }
            });
            chartPie.resize()
        }
    })
}

function getGameOrCurrencyDetail(time,type,name){
    var leagueUrl = "<?= Url::to(['/statistics/behavior/game'])?>";
    var taskUrl = "<?= Url::to(['/statistics/behavior/currency'])?>";
    var orderUrl = "<?= Url::to(['/statistics/behavior/goods'])?>"

    var url ,chartType;

    switch (type){
        case 1:
            url = leagueUrl;
            chartType = 'game';
            break;
        case 2:
            url = taskUrl;
            chartType = 'currency';
            break;
        case 3:
            url = orderUrl;
            chartType = 'goods';
            break;
    }

    $.ajax({
        url:url,
        data:{date:time,flagName:name},
        type:'get',
        dateType:'json',
        success:function(response){
            var dataObj=eval("("+response+")");
            var behavior = dataObj.behavior;
            var dateList = dataObj.dateList;
            var title = dataObj.title;

            var chartBar = echarts.init(document.getElementById("game-currency-chart"));
            chartBar.setOption(detailChartOption(chartType,dateList,behavior,'bar',title),true);

            chartBar.off('click');
            if(chartType == 'game'){
                chartBar.on("click", function (param){
                    getTimesGame(param.name,param.seriesName,param.color);
                    $("#game-currency-chart").show()
                });
            }
            chartBar.resize()
        }
    })
}

function pieChart(chartName,behavior,date){

    var title,type,masterTitle,subTitle,masterRadius;

    switch (chartName){
        case 'league':
            title = '联赛用户行为统计-'+date;
            masterTitle = '联赛类型占比';
            subTitle = '联赛占比';
            type = 0;
            masterRadius = [0,'25%'];
            break;
        case 'task':
            title = '任务用户行为统计-'+date;
            masterTitle = '任务类型占比';
            subTitle = '任务占比';
            type = 0;
            masterRadius = [0,'25%'];
            break;
        case 'order':
            title = '购物用户行为统计-'+date;
            masterTitle = '购物类型占比';
            subTitle = '';
            type = 0;
            masterRadius = [0,'25%'];
            break;
        case 'guess':
            title = '竞猜用户行为统计-'+date;
            masterTitle = '竞猜类型占比';
            subTitle = '';
            type = 0;
            masterRadius = [0,'55%'];
            break;
        case 'lottery':
            title = '抽奖用户行为统计-'+date;
            masterTitle = '中奖类型占比';
            subTitle = '';
            type = 0;
            masterRadius = [0,'55%'];
            break;
        case 'timesGame':
            title = '新老参赛用户统计-'+date;
            masterTitle = '新老参赛占比';
            subTitle = '';
            type = 0;
            masterRadius = [0,'55%'];
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
                return p.seriesName+"<br/>"+p.name+":"+formatNum(p.value)+"("+p.percent+"%)";
            }
        },
        legend: {
            orient : 'vertical',
            x : 'left',
            data:behavior[type]['name']
        },
        series : [
            {
                name:masterTitle,
                type:'pie',
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
                                var colorList = [
                                    '#CD2626','#4876FF','#0f990f','#636363','#B03060','#ee9a00','#8B1A1A','#87CEFF','#FF7F00','#FF00FF',' #FF4500','#8B4513','#191970'
                                ];
                                return colorList[params.dataIndex]
                            }
                    }
                },

                selectedMode: 'single',
                radius: masterRadius,
                labelLine: {
                    normal: {
                        show: false
                    }
                },
                data:behavior[0]['statistic'][0]
            },
            {
                name:subTitle,
                type:'pie',
                radius: ['35%', '50%'],
                tooltip : {
                    trigger: 'item',
                    formatter: function(p) {
                        return p.seriesName+"<br/>"+p.name+":"+formatNum(p.value)+"("+p.percent+"%)";
                    }
                },
                itemStyle: {
                    normal: {
                        color:
                            function(params) {
                                // build a color map as your need.
                                var colorList = [
                                    '#CD2626','#4876FF','#0f990f','#636363','#B03060','#ee9a00','#8B1A1A','#87CEFF','#FF7F00','#FF00FF',' #FF4500','#8B4513','#191970'
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
                    }
                },
                data:behavior[0]['statistic'][1]
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

function getTimesGame(time,leagueName,color){
        $.ajax({
            url:'/statistics/behavior/timesgame',
            data:{date:time,leagueName:leagueName},
            type:'get',
            dataType:'json',
            success:function(response){
                var behavior = response.behavior;
                var dateList = response.dateList;
                var title = response.title;
                var pieBehavior = response.pieBehavior;
                var pieDate = response.pieDate
                $("#times-game-chart").show()
                $("#times-game-pie-chart").show();
                var chartBar = echarts.init(document.getElementById("times-game-chart"));
                var chartPie = echarts.init(document.getElementById("times-game-pie-chart"));
                chartBar.setOption(detailChartOption('times-game',dateList,behavior,'bar',time +' '+ title,color),true);
                chartPie.setOption(pieChart('timesGame',pieBehavior,pieDate),true);
                chartBar.resize()
                chartPie.resize();

            }
        })
}

</script>

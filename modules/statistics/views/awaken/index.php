<?php
use app\assets\AppAsset;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->params['breadcrumbs'] = [
    [
        'label' => '联赛统计',
        'url' => Url::to([
            '/statistics/awaken'
        ] )
    ]
];
AppAsset::registerCss($this , '@web/plugin/daterangepicker/daterangepicker-bs3.css');
AppAsset::registerCss($this , '@web/plugin/vertical-timeline/css/component.css');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/moment.min.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/daterangepicker.js');
AppAsset::registerJs($this, '@web/plugin/layer/layer.js');
AppAsset::registerJs($this , '@web/plugin/' . (YII_DEBUG ? 'echarts.js' : 'echarts.min.js'));
Pjax::begin(['options' => ['class' => 'container-fluid']]);
$form = ActiveForm::begin([
    'id' => 'date-form',
    'method' => 'get',
    'options' => ['data-pjax' => true , 'role' => 'form']
] );
?>

<div class=" col-sm-2 pull-right">
    <div class="radio radio-replace color-blue radio-inline">
        <input type="radio"  name="type"  value="week" checked>
        <label class="tooltip-default" data-type="week" >
            周
        </label>
    </div>
    <div class="radio radio-replace color-blue radio-inline">
        <input type="radio"  name="type"  value="month" >
        <label class="tooltip-default" data-type="month">
            月
        </label>
    </div>
    <div class="radio radio-replace color-blue radio-inline">
        <input type="radio"  name="type"  value="quarter" >
        <label class="tooltip-default" data-type="quarter">
            季度
        </label>
    </div>
</div>

<div class="col-md-5 pull-right" id="show_date">
    <div class="col-sm-5 pull-right">
        <div class="input-group">
            <span class="input-group-addon"><i class="entypo-calendar"></i></span>
            <input type="text" class="form-control cursor-pointer" name="date" id="date" style="width: 180px" readonly placeholder="选择日期" value="<?= str_replace('_' , ' 至 ' , Yii::$app->request->get('date')); ?>" >
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
?>
<div>
    <ul class="nav nav-tabs border"><!-- available classes "bordered", "right-aligned" -->
        <li class="active">
            <a href="#show-behavior-chart" data-toggle="tab">
                <span>专业赛</span>
            </a>
        </li>
    </ul>
    <div class="tab-content" >
        <div id="show-behavior-chart" class="tab-pane fade in active col-md-12">
            <div id="behavior-chart" class="tab-pane fade in active col-md-8" style="height: 600px"></div>
            <div id="game-pie-chart" class="col-md-4" style="height: 500px;margin-top: 5%"></div>
        </div>

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
        getData();
    })

    $('input[name=type]').click(function(){
        $("#game-pie-chart").hide();
        getData()
    })

    $(".tooltip-default").click(function(){
        $("#game-pie-chart").hide();
        var type = $(this).attr('data-type');
        getData(type)
    })
});

function getDate() {
    $('#date').daterangepicker({
        startDate: "<?= date("Y-m-d",mktime(0, 0 , 0,date("m")-2,1,date("Y"))) ?>",
        endDate: "<?= date ( "Y-m-d", mktime ( 23, 59, 59, date ( "m" ), date ( "t" ), date ( "Y" ) ) ) ?>",
        showWeekNumbers : false, //是否显示第几周
        timePicker : false, //是否显示小时和分钟
        opens : 'left', //日期选择框的弹出位置
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
        getData();
    });
}

function getData(timeType){
    var type = $('input[name=type]:checked').val()
    if(arguments.length > 0){
        type = timeType
    }

    var index = layer.load(2,{
        shade:[0.4,'#fff']
    })
    var date = $("#hidden_date").val();

    if(type == 'week'){
        $("#show_date").show()
    }else{
        $("#show_date").hide()
    }

    $.ajax({
        url:"<?= Url::to(['/statistics/awaken'])?>",
        data:{type:type,date:date},
        type:'get',
        dateType:'json',
        success:function(response){
            var dataObj=eval("("+response+")");
            var awaken = dataObj.awaken;
            var dateList = dataObj.dateList;
            var leagueNames = dataObj.leagueName;
            var maxUserCount = dataObj.maxUserCount;
            var maxGameCount = dataObj.maxGameCount;

            var charts = [];
            var chart1 = echarts.init(document.getElementById("behavior-chart"));
            layer.close(index);
            chart1.setOption(chartOption(dateList,awaken,leagueNames,type,maxUserCount,maxGameCount),true);
            chart1.on("click", function (param){
                var data = param.data;
                var userCount = data[1]
                var newUser = data[2]
                var leagueName = data[3];
                var time = data[4]

                getLeaguePie(leagueName,userCount,newUser,time)
            });
            charts.push(chart1);

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

function chartOption(dateList,awaken,leagueNames,type,maxUserCount,maxGameCount) {

    var padding = [0,0,0,0] ;
    var seriesData = [];
    var timelineData = [];
    if(type == 'week'){
        padding = [0,0,0,-30]
    }
    var itemStyle = {
        normal: {
            opacity: 0.8,
            shadowBlur: 10,
            shadowOffsetX: 0,
            shadowOffsetY: 0,
            shadowColor: 'rgba(0, 0, 0, 0.5)',
            color:
                function(params) {
                    var colorList = [
                        '#CD2626','#4876FF','#0f990f','#636363','#B03060','#ee9a00','#8B1A1A','#87CEFF','#FF7F00','#FF00FF',' #FF4500','#8B4513','#191970'
                    ];
                    return colorList[params.dataIndex]
                }
        }
    };

    for (var n = 0; n < dateList.length; n++) {
        timelineData.push(dateList[n]);
        seriesData.push({
            title: {
                show: true,
                'text': dateList[n] + ''
            },
            series: {
                name: dateList[n],
                type: 'scatter',
                itemStyle: itemStyle,
                data: awaken[n],
                symbolSize: function(val) {
                    var thisVal = parseInt(val[0]) + parseInt(val[1]);
                    var thisSize = thisVal;
                    if(thisVal > 50){
                        thisSize = thisVal/4.5
                        if(thisSize > 150){
                            thisSize = 150;
                        }
                    }
                    return  thisSize;
                }
            }
        });
    }

    // Schema:
    var schema = [
        {name: 'Game', index: 0, text: '游戏局数', unit: '局'},
        {name: 'User', index: 1, text: '总用户', unit: '人'},
        {name: 'NewUser', index: 2, text: '新用户', unit: '人'},
        {name: 'LeagueName', index: 3, text: '联赛', unit: ''},
        {name: 'Time', index: 4, text: '时间', unit: ''},
    ];

    return {
        baseOption: {
            timeline: {
                axisType: 'category',
                orient: 'vertical',
                autoPlay: false,
                inverse: true,
                playInterval: 1500,
                left: null,
                right: 0,
                top: 0,
                bottom: 30,
                width: 65,
                padding: 20,
                height: null,
                label: {
                    normal: {
                        textStyle: {
                            color: '#000000',
                        },
                    },
                    emphasis: {
                        textStyle: {
                            color: '#696969'
                        }
                    },
                    padding:padding,
                    fontSize:'10'
                },
                symbol: 'none',
                lineStyle: {
                    color: '#696969'
                },
                checkpointStyle: {
                    color: '#696969',
                    borderColor: '#696969',
                    borderWidth: 2
                },
                controlStyle: {
                    showNextBtn: false,
                    showPrevBtn: false,
                    normal: {
                        color: '#696969',
                        borderColor: '#696969'
                    },
                    emphasis: {
                        color: '#696969',
                        borderColor: '#696969'
                    }
                },
                data: timelineData
            },
            backgroundColor: '#ffffff',
            title: [{
                text: dateList[0],
                textAlign: 'center',
                left: '73%',
                top: '65%',
                textStyle: {
                    fontSize: 20,
                    color: 'rgba(0, 0, 0, 0.7)'
                }
            }, {
                text: '专业赛',
                left: 'center',
                top: 10,
                textStyle: {
                    color: '#000000',
                    fontWeight: 'normal',
                    fontSize: 20
                }
            }],
            tooltip: {
                padding: 5,
                backgroundColor: '#222',
                borderColor: '#777',
                borderWidth: 1,
                formatter: function (obj) {
                    var value = obj.value;
                    return schema[4].text + '：' + value[4] + '<br>'
                        + schema[3].text + '：' + value[3] + '<br>'
                        + schema[1].text + '：' + value[1] + schema[1].unit + '<br>'
                        + schema[0].text + '：' + value[0] + schema[0].unit + '<br>'
                        + schema[2].text + '：' + value[2] + schema[2].unit + '<br>';
                }
            },
            grid: {
                top: 100,
                containLabel: true,
                left: 30,
                right: '110'
            },
            xAxis: {
                type: 'value',
                name: '游戏局数',
                max: maxGameCount,
                min: 0,
                nameGap: 25,
                nameLocation: 'middle',
                nameTextStyle: {
                    fontSize: 18
                },
                splitLine: {
                    show: false
                },
                axisLine: {
                    lineStyle: {
                        color: '#000000'
                    }
                },
                axisLabel: {
                    formatter: '{value} 局'
                }
            },
            yAxis: {
                type: 'value',
                name: '参赛人数',
                max: maxUserCount,
                nameTextStyle: {
                    color: '#000000',
                    fontSize: 18
                },
                axisLine: {
                    lineStyle: {
                        color: '#000000'
                    }
                },
                splitLine: {
                    show: false
                },
                axisLabel: {
                    formatter: '{value} 人'
                }
            },
            visualMap: [
                {
                    show: true,
                    top:'2%',
                    left:'15%',
                    align:'left',
                    dimension: 3,
                    categories: leagueNames,
                    calculable: true,
                    precision: 0.1,
                    textGap: 30,
                    textStyle: {
                        color: '#000000'
                    },
                    inRange: {
                        color: (function () {
                            //var colors = ['#bcd3bb', '#e88f70', '#edc1a5', '#9dc5c8', '#e1e8c8', '#7b7c68', '#e5b5b5', '#f0b489', '#928ea8', '#bda29a'];
                            var colors = [ '#CD2626','#4876FF','#0f990f','#636363','#B03060','#ee9a00','#8B1A1A','#87CEFF','#FF7F00','#FF00FF',' #FF4500','#8B4513'];
                            return colors.concat(colors);
                        })()
                    }
                }
            ],
            series: [
                {
                    type: 'scatter',
                    itemStyle: itemStyle,
                    data: awaken[0],
                    symbolSize: function(val) {
                        var thisSize = 10;
                        if(val[1] != 0){
                            thisSize = (val[0] / val[1])*30;
                            if(thisSize < 10){
                                thisSize = 10;
                            }
                        }
                        return thisSize;
                    }
                }
            ],
            animationDurationUpdate: 1000,
            animationEasingUpdate: 'quinticInOut'
        },
        options: seriesData
    }
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

function getLeaguePie(leagueName,userCount,newUser,time){

    var title = leagueName;

    var awaken = {'name':['老用户','新用户'],'statistic':[{'value':(parseInt(userCount) - parseInt(newUser)),'name':'老用户'},{'value':newUser,'name':'新用户'}]}

    awaken = [awaken]
    var chartBar = echarts.init(document.getElementById("game-pie-chart"));
    chartBar.setOption(pieChart(awaken,title,time),true);
    chartBar.resize()
    $("#game-pie-chart").show()
}

function pieChart(awaken,titleName,time){

    var title,type,masterTitle;
    title = titleName + '新老用户占比('+time+')';
    type = 0;

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
            data:awaken[type]['name']
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
                            x: '35%',
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
                                var colorList = [
                                    '#CD2626','#4876FF','#0f990f','#636363','#B03060',
                                ];
                                return colorList[params.dataIndex]
                            }
                    }
                },
                data:awaken[type]['statistic']
            }
        ],
    };
}



</script>

<?php
use app\assets\AppAsset;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;

$this->params['breadcrumbs'] = [
    [
        'label' => '竞猜统计',
        'url' => Url::to([
            '/statistics/guess'
        ] )
    ]
];
AppAsset::registerCss($this, '@web/plugin/select2/select2-bootstrap.css');
AppAsset::registerCss($this, '@web/plugin/select2/select2.css');
AppAsset::registerCss($this , '@web/plugin/daterangepicker/daterangepicker-bs3.css');
AppAsset::registerCss($this , '@web/plugin/vertical-timeline/css/component.css');
AppAsset::registerCss($this, '@web/plugin/shCircleLoader/jquery.shCircleLoader.css');
AppAsset::registerCss($this, '@web/plugin/datatables/datatables.min.css');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/moment.min.js');
AppAsset::registerJs($this, '@web/plugin/datatables/datatables.min.js');
AppAsset::registerJs($this, '@web/plugin/select2/select2.min.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/daterangepicker.js');
AppAsset::registerJs($this , '@web/plugin/' . (YII_DEBUG ? 'echarts.js' : 'echarts.min.js'));
AppAsset::registerJs($this, '@web/plugin/shCircleLoader/jquery.shCircleLoader-min.js');
Pjax::begin(['options' => ['class' => 'container-fluid']]);
$form = ActiveForm::begin([
    'id' => 'date-form',
    'method' => 'get',
    'options' => ['data-pjax' => true , 'role' => 'form']
] );
?>
<script type="text/javascript" src="<?= Url::to('@web/js/guess/guessDatatableFormat.js')?>"></script>
<script type="text/javascript" src="<?= Url::to('@web/js/datatable.custom.js')?>"></script>
<script type="text/javascript">
    jQuery( document ).ready( function( $ ) {
        jQuery('.loadding').shCircleLoader();
        var format = GuessDatatableFormat;
        format.toastrOpts = toastrOpts;
        format.ajaxUrl = '<?= Url::to(['/statistics/guess']); ?>';
        format.datatable = customDatatable(jQuery( '#guess-data') , format);
    } );
</script>
<div class="col-md-6 pull-right">
    <div class="col-sm-4 pull-right">
        <div class="input-group">
            <span class="input-group-addon"><i class="entypo-calendar"></i></span>
            <input type="text" class="form-control cursor-pointer" style="width: 200px;float: right" name="date" id="date" readonly placeholder="选择日期" value="<?= str_replace('_' , ' 至 ' , Yii::$app->request->get('date')); ?>" >
            <input type="hidden" id="hidden_date">
            <input type="hidden" id="end_time">
            <input type="hidden" id="today_time" value="<?= date('Y-m-d')?>">
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
    <div id="guess-chart" class="col-md-12" style="height: 650px !important">
        <div id="guess-bar" class="col-md-8" style="height: 650px !important"></div>
        <div id="overview-bar" class="col-md-4" style="margin-top:3%;height: 620px !important"></div>

    </div>
</div>

<div id="guess-time" class="hide">
    <div class="input-group" style="float: left;margin-right: 5%">
        <span  id="rankTime" style="font-size: 20px;font-weight:bold"><?= date('Y-m-d')?></span>
    </div>
</div>
<table class="table table-bordered datatable no-footer hover stripe" id="guess-data" cellspacing="0" >
    <thead>
    <tr>
        <td width="4%">盘口名</td>
        <td width="4%">盈利(元)</td>
        <td width="4%">主队</td>
        <td width="2%">下注数</td>
        <td width="4%">下注金额(元)</td>
        <td width="4%">客队</td>
        <td width="2%">下注数</td>
        <td width="4%">下注金额(元)</td>
        <td width="2%">平均赔率</td>
        <td width="2%">赛果</td>
    </tr>
    </thead>
</table>


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
        getGuessData($("#today_time").val())
    })
    getGuessData($("#today_time").val())
});

function getData(){
    var date = $("#hidden_date").val();
    var data = {};
    if(date != ''){
        data = {date:date}
    }

    $.ajax({
        url:"<?= Url::to(['/statistics/guess/statistics'])?>",
        data:data,
        type:'get',
        dateType:'json',
        success:function(response){
            var dataObj=eval("("+response+")");
            var guess = dataObj.guess;
            var overview = dataObj.overview;
            var dateList = dataObj.dateList;
            var overviewTime = dataObj.overviewTime;
            var charts = [];
            var chart1 = echarts.init(document.getElementById("guess-bar"));
            var chart2 = echarts.init(document.getElementById("overview-bar"));

            chart1.setOption(chartOption('guess-bar',dateList,guess,'bar'));
            chart2.setOption(overviewOption('overview',overviewTime,overview));
            chart1.on("click", function (param){
                getGuessData(param.name)
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

function chartOption(chartName,dateList,guess) {

    var title,type;
    var seriesData = [];
    var color = ['#4876FF','#CD2626','#0f990f'];
    switch (chartName){
        case 'guess-bar':
            title = '竞猜统计';
            type = 0;
            break;
    }

    for(i in guess[type]['statistic']){
        if(i == 2){
            seriesData.push(
                {
                    name:guess[type]['name'][i],
                    type:'bar',
                    data:guess[type]['statistic'][i],
                    label: {
                        normal: {
                            show: true,
                            position: 'top',
                            formatter:function(p){
                                return formatNum(p.value)
                            }
                        }
                    },
                    itemStyle: {
                        normal: {
                            color: color[i]
                        }
                    },

                },
            )
        }else{
            var position = 'top';
            if(i == 1){
                position = 'bottom'
            }
            seriesData.push(
                {
                    name:guess[type]['name'][i],
                    type:'bar',
                    stack: '总量',
                    data:guess[type]['statistic'][i],
                    label: {
                        normal: {
                            show: true,
                            position: position,
                            formatter:function(p){
                                return formatNum(p.value)
                            }
                        }
                    },
                    itemStyle: {
                        normal: {
                            color: color[i]
                        }
                    },
                },
            )
        }
    }
    var timeData = dateList;

    return option = {
        tooltip : {
            trigger: 'axis',
            axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
            },
        },
        toolbox: {
            right:'5%',
            show : true,
            feature : {
                mark : {show: true},
                dataView : {show: true, readOnly: false},
                magicType: {show: true, type: ['bar']},
                restore : {show: true},
                saveAsImage : {show: true}
            }
        },
        legend: {
            data:['投注总额','赔付总额','盈利总额'],
            x: 'left',
            left:'10%'
        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        yAxis : [
            {
                name: '单位:元',
                type : 'value',
            }
        ],
        xAxis: [
            {
                type : 'category',
                axisTick : {show: false},
                data : timeData
            }
        ],
        series : seriesData
    };
}


function getDate() {
    $('#date').daterangepicker({
        startDate: "<?= date('Y-m-d' , strtotime( "-6 day")) ?>",
        endDate: "<?= date('Y-m-d' , time()) ?>",
        showWeekNumbers : false, //是否显示第几周
        timePicker : false, //是否显示小时和分钟
        timePickerIncrement : 5, //时间的增量，单位为分钟
        timePicker12Hour : false, //是否使用12小时制来显示时间
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
        $("#end_time").val(end.format('YYYY-MM-DD'))
    }).on('apply.daterangepicker' , function(){
        var date = $('#date').val();
        date = date.replace(/( 至 )/gi , function($){
            return '_';
        });
        $('#hidden_date').val(date);
        getData()
        clearInterval(auto);
        $("#guess_time").html($("#date").val())
        getGuessData($("#end_time").val())
    });
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

function getGuessData(rankTime){

    var format = GuessDatatableFormat;
    format.datatable.on('preXhr.dt' , function(e , settings , data){
        data.search = {
            "rankTime" : rankTime,
        };
    });

    format.datatable.ajax.reload();
}

function overviewOption(chartName,dateList,overview) {

    var title,type;
    var seriesData = [];
    var color = ['#4876FF','#CD2626','#0f990f'];
    switch (chartName){
        case 'overview':
            title = '竞猜统计';
            type = 0;
            break;
    }

    for(i in overview[type]['statistic']){
        seriesData.push(
            {
                name:overview[type]['name'][i],
                type:'bar',
                barWidth: 35,
                barGap:'80%',
                data:overview[type]['statistic'][i],
                label: {
                    normal: {
                        show: true,
                        position: 'top',
                        formatter:function(p){
                            return formatNum(p.value)
                        }
                    }
                },
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
        },
        legend: {
            data:overview[type]['name'],
            left:'15%'
        },
        toolbox: {
            show : true,
            right:'5%',
            feature : {
                mark : {show: true},
                dataView : {show: true, readOnly: false},
                magicType: {show: true, type: ['bar']},
                restore : {show: true},
                saveAsImage : {show: true}
            }
        },
        xAxis: {
            type: 'category',
            data: dateList,
            axisLabel:{
                interval:0,
                rotate:0,
                textStyle:{
                    fontSize:15
                }
            },
        },
        yAxis : {
            name : '单位:元',
            type: 'value',
        },
        series: seriesData
    }
}

</script>

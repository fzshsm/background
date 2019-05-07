<?php
use app\assets\AppAsset;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->params['breadcrumbs'] = [
    [
        'label' => '数据统计',
        'url' => Url::to([
            '/statistics'
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
<div class="form-group col-sm-1 pull-left game-type">
    <?php
    $gameType = Yii::$app->request->get('gameType' , 'glory');
    $gameTypeList = ['glory' => '王者荣耀', 'pubg' => '绝地求生'];
    ?>
    <button type="button" id="game-type" class="btn btn-blue dropdown-toggle" data-game-type="<?= $gameType ?>"  data-toggle="dropdown">
        <?= $gameTypeList[$gameType]?>　<span class="caret"></span>
    </button>
    <input type="hidden" id="gameType" name="gameType" value="<?= $gameType?>">
    <ul class="dropdown-menu dropdown-blue">
        <?php foreach ($gameTypeList as $key => $value){?>
            <li>
                <a data-game-type="<?= $key?>" href="javascript:void(0);"><?= $value?></a>
            </li>
        <?php }?>
    </ul>
</div>
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
            <a href="#total-game-chart" data-toggle="tab">
                <span>综合</span>
            </a>
        </li>
        <li id="major">
            <a href="#major-game-chart" data-toggle="tab">
                <span>专业赛</span>
            </a>
        </li>
        <li id="athletic">
            <a href="#athletic-game-chart" data-toggle="tab">
                <span>竞技赛</span>
            </a>
        </li>
        <li id="entertain">
            <a href="#entertain-game-chart" data-toggle="tab">
                <span>娱乐赛</span>
            </a>
        </li>
    </ul>
    <div class="tab-content" >
        <div id="total-game-chart" class="tab-pane fade in active col-md-10 chart-height-420"></div>
        <div id="major-game-chart" class="tab-pane fade col-md-10 chart-height-420"></div>
        <div id="athletic-game-chart" class="tab-pane fade col-md-10 chart-height-420"></div>
        <div id="entertain-game-chart" class="tab-pane fade col-md-10 chart-height-420"></div>
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

    $('.game-type .dropdown-menu a').click(function(){
        var gameType = $(this).attr('data-game-type');
        $('#gameType').val(gameType);
        $('.game-type button.dropdown-toggle').attr('data-game-type' , $(this).attr('data-game-type'));
        $('.game-type button.dropdown-toggle').html($(this).text() +　'　<span class="caret"></span>');
        getData()
    });
});

function getData(){
    var date = $("#hidden_date").val();
    var gameType = $("#gameType").val();
    var data = {gameType:gameType};
    if(date != ''){
        data = {
            date:date,
            gameType:gameType
        }
    }

    $.ajax({
        url:"<?= Url::to(['/statistics'])?>",
        data:data,
        type:'get',
        dateType:'json',
        success:function(response){
            var dataObj=eval("("+response+")");
            var game = dataObj.game;
            var dateList = dataObj.dateList;
            var charts = [];

            var chart1 = echarts.init(document.getElementById("total-game-chart"));
            var chart2 = echarts.init(document.getElementById("major-game-chart"));
            var chart3 = echarts.init(document.getElementById("athletic-game-chart"));
            var chart4 = echarts.init(document.getElementById("entertain-game-chart"));
            chart1.setOption(chartOption('total',dateList,game),true);
            chart2.setOption(chartOption('major',dateList,game),true);
            chart3.setOption(chartOption('athletic',dateList,game),true);
            chart4.setOption(chartOption('entertain',dateList,game),true);
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
                for (var i = 0; i < charts.length; i++) {
                    charts[i].resize();
                }
            });

            if((game[1].name.length) == 0){
                $("#major").css('display','none');
            }else{
                $("#major").css('display', 'block')
            }
            if((game[2].name.length) == 0){
                $("#athletic").css('display','none');
            }else{
                $("#athletic").css('display','block')
            }
            if((game[3].name.length) == 0){
                $("#entertain").css('display','none');
            }else{
                $("#entertain").css('display','block')
            }
        }
    })
}

function chartOption(chartName,dateList,game) {

    var title,type;
    var seriesData = [];
    switch (chartName){
        case 'total':
                title = '每日游戏总数(局)';
                type = 0;
            break;
        case 'major':
                title = '专业赛游戏总数(局)';
                type = 1;
            break;
        case 'athletic':
                title = '竞技赛游戏总数(局)';
                type = 2;
            break;
        case 'entertain':
                title = '娱乐赛游戏总数(局)';
                type = 3;
            break;
    }

    for(i in game[type]['statistic']){
        seriesData.push(
            {
                name: game[type]['name'][i] ,
                type: 'line',
                smooth: true,
                symbol: 'circle',
                symbolSize: 5,
                showSymbol: false,
                data:game[type]['statistic'][i],
                markPoint: {
                    symbolSize: [100,50],
                    data: [
                        {type: 'max', name: '最大值'},
                    ]
                }
            }
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
            data:game[type]['name'],
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

</script>

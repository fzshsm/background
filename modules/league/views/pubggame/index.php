<?php

use app\assets\AppAsset;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\helpers\Html;

$this->params['breadcrumbs'] = [
    [
        'label' => '赛事管理',
        'url' => Url::to([
            '/league'
        ] )
    ]
];
if(!empty($matchName)){
    $this->params['breadcrumbs'][] = ['label' => $matchName , 'url' => ''];
}
$this->params['breadcrumbs'][] = ['label' => '绝地求生游戏列表'];
AppAsset::registerCss($this , '@web/plugin/daterangepicker/daterangepicker-bs3.css');
AppAsset::registerCss($this , '@web/plugin/vertical-timeline/css/component.css');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/moment.min.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/daterangepicker.js');
AppAsset::registerJs($this , '@web/js/common.js');
Pjax::begin();
$form = ActiveForm::begin([
    'id' => 'game-filter-form',
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
    <div class="col-sm-1 padding-left-8" >
        <div class="btn-group game-status">
            <?php
            $gameStatus = Yii::$app->request->get('status' , 1);
            $gameStatusList = [0 => ['game' => '王者荣耀','url' => Url::to(['/league/glorygame'])], 1 => ['game' => '绝地求生','url' => Url::to(['/league/pubggame'])]];
            ?>
            <input type="hidden" id="status" name="status" value="<?=$gameStatus?>">
            <button id="game-status" type="button" class="btn btn-blue dropdown-toggle" data-toggle="dropdown" data-status="<?=$gameStatus?>">
                <?= $gameStatusList[$gameStatus]['game']?>　<span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-darkblue">
                <?php foreach($gameStatusList as $key => $value){ ?>
                    <li><a href="<?= $value['url']?>" data-status="<?= $key?>"><?= $value['game']?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
<div class="col-sm-1 padding-left-none game-refresh">
    <a href="<?= Url::to(['/league/pubggame'])?>" class="btn btn-default" title="刷新">
        <i class="fa fa-refresh"></i>
    </a>
</div>
<div class="form-group  game-search-filter">
    <?php if(!empty($currentSeason) && !empty($seasonList)){ ?>
    <div class="col-sm-1 padding-left-8">
        <div class="btn-group season-filter">
            <input type="hidden" id="seasonId" name="seasonId" value="<?=$currentSeason['id'];?>">
            <button id="season" type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" data-seasonid="<?=$currentSeason['id']?>">
                <?=$currentSeason['name']?>　<span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-infoblue" role="menu">
                <?php foreach($seasonList as $season){ ?>
                    <li>
                        <a data-seasonid="<?= $season['id']?>" href="javascript:void(0);"><?= $season['name']?></a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <?php } ?>
    <div class="col-sm-1 padding-left-8">
        <div class="btn-group game-status">
            <?php
            $gameStatus = Yii::$app->request->get('status' , 0);
            $gameStatusList = [0 => '全部', 4 => '游戏中' , 5 => '游戏结束' , 6 => '等待结算'];
            ?>
            <input type="hidden" id="status" name="status" value="<?=$gameStatus?>">
            <button id="game-status" type="button" class="btn btn-blue dropdown-toggle" data-toggle="dropdown" data-status="<?=$gameStatus?>">
                <?= $gameStatusList[$gameStatus]?>　<span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-darkblue">
                <?php foreach($gameStatusList as $key => $value){ ?>
                    <li><a href="javascript:void(0);" data-status="<?= $key?>"><?= $value?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
<!--    <div class="col-sm-1 padding-left-8" >-->
<!--        <div class="btn-group data-type">-->
<!--            --><?php
//            $dataType = Yii::$app->request->get('dataType' , 2);
//            $dataTypeList = [ 2 => '全部', 0 => '分组模式' , 1 => '匹配模式'];
//            ?>
<!--            <input type="hidden" id="dataType" name="dataType" value="--><?//=$dataType?><!--">-->
<!--            <button  type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" data-type="--><?//=$dataType?><!--">-->
<!--                --><?//= $dataTypeList[$dataType]?><!--　<span class="caret"></span>-->
<!--            </button>-->
<!--            <ul class="dropdown-menu dropdown-white" style="background-color: #d6d6d8">-->
<!--                --><?php //foreach($dataTypeList as $key => $value){ ?>
<!--                    <li><a href="javascript:void(0);" data-type="--><?//= $key?><!--">--><?//= $value?><!--</a></li>-->
<!--                --><?php //} ?>
<!--            </ul>-->
<!--        </div>-->
<!--    </div>-->
    <div class="col-sm-3 padding-left-8">
        <div class="input-group">
            <span class="input-group-addon"><i class="entypo-calendar"></i></span>
            <input type="text" class="form-control cursor-pointer" name="date" id="date" readonly placeholder="选择日期" value="<?= Yii::$app->request->get('date'); ?>" >
        </div>
    </div>
    <div class="input-group col-sm-4">
        <div class="input-group-btn search-type">
            <?php
            $searchType = Yii::$app->request->get('searchType' , 'gameRecordId');
            $searchTypeList = ['gameRecordId' => '游戏编号' , 'nickName' => '昵称' , 'roleId' => '游戏角色'];
            ?>
            <input type="hidden" id="searchType" name="searchType" value="<?=$searchType?>">
            <button type="button" class="btn btn-success dropdown-toggle btn-width-100" data-searchtype="<?=$searchType?>"  data-toggle="dropdown">
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
        <?php if($refeshPer == 1){?>
        <div class="col-sm-1 padding-left-6">
            <div class="btn-group">
                <a href="<?= Url::to(['/league/pubggame/refresh'])?>" class="btn btn-red  refresh-game">刷新游戏队列</a>
            </div>
        </div>
        <?php }?>
    </div>
</div>
<?php
ActiveForm::end();
echo ListView::widget([
    'id' => 'game',
    'dataProvider' => $dataProvider,
    'emptyText' =>'暂无游戏信息！',
    'layout' => '<div class="top-pager">{pager}</div><ul class="cbp_tmtimeline custom-variant">{items}</ul>{pager}',
    'itemView' => '_item',
    'itemOptions' => ['tag' => 'li'],
    'viewParams' => ['seasonData' => $seasonList , 'leagueData' => $matchType]
]);
?>
<div class="modal fade" id="game-transform">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modalbody">
                <div class='color-orange font-16 mb-15 ml-15'>你确定更改 编号( <span class="game-id"></span> ) 的游戏结果吗？</div>
                <div class="col-sm-5">
                    <div class="radio radio-replace radio-inline">
                        <input type="radio"  name="result"  value="2">
                        <label class="tooltip-default">
                            A队胜
                        </label>
                    </div>
                    <div class="radio radio-replace radio-inline">
                        <input type="radio"  name="result"  value="3">
                        <label class="tooltip-default" >
                            B队胜
                        </label>
                    </div>
                    <div class="radio radio-replace radio-inline">
                        <input type="radio"  name="result"  value="4">
                        <label class="tooltip-default">
                            平局
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default cancel">取消</button>
                <button type="button" class="btn btn-info confirm">确定</button>
            </div>
        </div>
    </div>
</div>
<script>
    jQuery( document ).ready( function( $ ){
        $('.season-filter .dropdown-menu a').click(function(){
            $('#seasonId').val($(this).attr('data-seasonid'));
            $('.season-filter button.dropdown-toggle').attr('data-seasonid' , $(this).attr('data-seasonid'));
            $('.season-filter button.dropdown-toggle').html($(this).text() +　'　<span class="caret"></span>');
            $('button.search').trigger('click');
        });
        $('.search-type .dropdown-menu a').click(function(){
            $('#searchType').val($(this).attr('data-searchtype'));
            $('.search-type button.dropdown-toggle').attr('data-searchtype' , $(this).attr('data-searchtype'));
            $('.search-type button.dropdown-toggle').html($(this).text() +　'　<span class="caret"></span>');
        });
        $('.game-status .dropdown-menu a').click(function(){
            $('#status').val($(this).attr('data-status'));
            $('.game-status button.dropdown-toggle').attr('data-status' , $(this).attr('data-status'));
            $('.game-status button.dropdown-toggle').html($(this).text() +　'　<span class="caret"></span>');
            $('button.search').trigger('click');
        });
        $('.data-type .dropdown-menu a').click(function(){
            $('#dataType').val($(this).attr('data-type'));
            $('.data-type button.dropdown-toggle').attr('data-type' , $(this).attr('data-type'));
            $('.data-type button.dropdown-toggle').html($(this).text() +　'　<span class="caret"></span>');
            $('button.search').trigger('click');
        });
        $('.game-cancel').on('click' , function(){
            var gameId = $(this).attr('data-game-id');
            var confirmText = $('<span>').addClass('color-orange font-16').html("你确定要关闭 编号( " + gameId + " ) 的这场游戏吗？");
            var successText = "关闭 游戏( " + gameId + " ) 成功！";
            showConfirmModal(this , confirmText , successText);
            return false;
        });
        $('.game-transform').on('click' , function(){
            var gameId = $(this).attr('data-game-id');
            var result = $(this).attr('data-result');
            $('#game-transform .game-id').html(gameId);
            $('#game-transform input[value=' + result +']').trigger('click');
            jQuery('#game-transform').modal('show');
            jQuery('#game-transform .confirm').off('click');
            jQuery('#game-transform .confirm').on('click' , function(){
                var newResult = $('#game-transform input[name=result]:checked').val();
                console.log(newResult);
                $.ajax({
                    url : '<?= Url::to(['/league/pubggame/transform'])?>',
                    type : 'get',
                    data : 'id=' + gameId + '&result=' + newResult,
                    dataType : 'json',
                    success : function(response){
                        if(response.status == 'success'){
                            window.location.reload();
                        }else{
                            toastr.error(response.message , '' , $(this).toastrOpts);
                        }
                    }
                });
                jQuery('#game-transform').modal('hide');
            });
            return false;
        });
        
        $('#date').daterangepicker({
            startDate: "<?= date('Y-m-d H:i' , strtotime( "-3 days")) ?>",
            endDate: "<?= date('Y-m-d H:i') ?>",
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
            console.log(start);
            console.log(end);
            $('#date span').html(start.format('YYYY-MM-DD') + ' 至 ' + end.format('YYYY-MM-DD'));
        }).on('apply.daterangepicker' , function(){
            $('button.search').trigger('click');
        });

        $('.refresh-game').on('click' , function(){
            var confirmText = $('<span>').addClass('color-orange font-16').html("你确定要刷新游戏队列吗？");
            var successText = "刷新游戏队列成功！";
            showConfirmModal(this , confirmText , successText,function(){});
            return false;
        });
        modalInit();
        replaceCheckboxes();
    });
</script>
<?php
Pjax::end();
?>
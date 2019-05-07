<?php
/**
 * Created by IntelliJ IDEA.
 * User: Zhangxinlong
 * Date: 2017/11/3
 * Time: 10:34
 */

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

AppAsset::registerCss($this, '@web/plugin/select2/select2.css');
AppAsset::registerJs($this, '@web/plugin/select2/select2.min.js');

AppAsset::registerJs($this , '@web/plugin/daterangepicker/moment.min.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/daterangepicker.js');

AppAsset::registerCss($this , '@web/plugin/daterangepicker/daterangepicker-bs3.css');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/moment.min.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/daterangepicker.js');

Pjax::begin(['id' => 'rating']);
?>
<div class="row">
    <div class="col-md-8">
        <?php if(Yii::$app->session->hasFlash('dataError')){ ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?=Yii::$app->session->getFlash('dataError')?>
            </div>
        <?php }?>
        <?php if(Yii::$app->session->hasFlash('error')){ ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?=Yii::$app->session->getFlash('error')?>
            </div>
        <?php }?>
        <?php if(Yii::$app->session->hasFlash('success')){?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?=Yii::$app->session->getFlash('success')?>
            </div>
        <?php }?>
    </div>
</div>
<?php
$form = ActiveForm::begin([
    'id' => 'rating-form',
    'method' => 'post',
    'options' => [
        'data-pjax' => true,
        'role' => 'form',
        'class' => 'form-horizontal form-groups-bordered validate'
    ]
] );
?>
<div class="row">
    <div class="col-md-8">
        <div class="panel panel-primary" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <?= isset($data['personName']) ? "{$data['personName']} 积分" : '创建积分'?>
                </div>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label for="status" class="col-sm-3 control-label">姓名</label>
                    <div class="col-sm-5">
                        <?=Html::dropDownList('userId' , isset($data['userId']) ? $data['userId'] : 1 , $authlist , ['data-allow-clear' => 'true' , 'id' => 'userId' ,'onchange' => 'getScore()']);?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="reward" class="col-sm-3 control-label">当前积分</label>
                    <div class="col-sm-5">
                        <p id="totalScore"><?= isset($data['totalScore']) ? $data['totalScore'] : 0;?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="status" class="col-sm-3 control-label">赛事类型</label>
                    <div class="col-sm-5">
                        <?=Html::dropDownList('gameTypeId' , isset($data['type']) ? $data['type'] : 1 , $gameType , ['data-allow-clear' => 'true'  , 'id' => 'gameTypeId' ,'onchange' => 'getRule()']);?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="status" class="col-sm-3 control-label">积分规则</label>
                    <div class="col-sm-5">
                        <?=Html::dropDownList('ruleId' , isset($data['ruleId']) ? $data['ruleId'] : 0 , [ 0 => '请选择'] , ['data-allow-clear' => 'true'  , 'id' => 'ruleId' ]);?>
                    </div>
                    <input type="hidden" id="gameRuleId" value="<?=isset($data['ruleId']) ? $data['ruleId']:0?>">
                </div>
                <div class="form-group">
                    <label for="isCore" class="col-sm-3 control-label">主力</label>
                    <div class="col-sm-5">
                        <?= Html::radioList('isCore',isset($data['isCore'])?$data['isCore']:0,['0'=>'否','1'=>'是'],['class' => 'radio','itemOptions' =>['labelOptions' =>['style' =>'margin-left:2%','class' => 'radio checkbox-inline']]]) ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="isMvp" class="col-sm-3 control-label">MVP</label>
                    <div class="col-sm-5">
                        <?= Html::radioList('isMvp',isset($data['isMvp'])?$data['isMvp']:0,['0'=>'否','1'=>'是'],['class' => 'radio','itemOptions' =>['labelOptions' =>['style' =>'margin-left:2%','class' => 'checkbox-inline']]]) ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="matchTime" class="col-sm-3 control-label">比赛时间</label>
                    <div class="col-sm-5">
                        <div class="input-group">
                            <input type="text" readonly class="form-control" name="gameTime" id="gameTime" value="<?= isset($data['gameTime']) ? $data['gameTime'] : (date('Y-m-d H:i:s') );?>" >
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                </div>
                <?= isset( $data['id'] ) ? Html::hiddenInput("recordId" , $data['id']) : ''?>
            </div>
        </div>
    </div>
</div>
<div class="form-group default-padding form-button">
    <button type="submit" class="btn btn-success">保　存</button>
    <a href="<?= \Yii::$app->request->getReferrer()?>" class="btn btn-default">返　回</a>
</div>

<script language="JavaScript">
    var oriGameType = $("#gameTypeId").val();
    jQuery( document ).ready( function( $ ){
        $('#userId').select2();
        $('#type').select2( {
            minimumResultsForSearch: -1
        });
        $("#gameTypeId").select2();
        $("#ruleId").select2({
            minimumResultsForSearch: -1
        })

        $('#gameTime').daterangepicker({
            startDate: "<?= date('Y-m-d H:i:s') ?>",
            showWeekNumbers : false, //是否显示第几周
            timePicker : true, //是否显示小时和分钟
            timePickerIncrement : 5, //时间的增量，单位为分钟
            timePicker12Hour : false, //是否使用12小时制来显示时间
            opens : 'right', //日期选择框的弹出位置
            buttonClasses : [ 'btn btn-default' ],
            applyClass : 'btn-small btn-success',
            cancelClass : 'btn-small',
            format : 'YYYY-MM-DD HH:mm:ss', //控件中from和to 显示的日期格式
            singleDatePicker : true,
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
            $('#gameTime span').html(start.format('YYYY-MM-DD HH:mm:ss'));
        });
        getRule()
    });

    function getRule()
    {
        var gameType = $("#gameTypeId").val();
        var gameRule = <?=$gameRule?>;
        var gameRuleId = $("#gameRuleId").val()
        var count = 1;

        $("#ruleId").empty()
        $("#ruleId").append('<option value=0>请选择</option>');
        $("#ruleId").val(0)
        $("#ruleId").select2("val", 0);
        for(var i in gameRule)
        {
            if(i == gameType){
                count++;
                for (var k in gameRule[i])
                {
                    $("#ruleId").append("<option value="+k+">"+gameRule[i][k]+"</option>");
                }
            }
            if(count > 1){
                break;
            }
        }
        if(oriGameType == gameType){
            $("#ruleId").select2("val", gameRuleId);
        }
    }

    function getScore(){
        var userId = $("#userId").val();
        var scoreList = <?=$scoreList?>

        for(var i in scoreList){
            if(userId == i){
                $("#totalScore").html(scoreList[i]);
                break;
            }
        }
    }

</script>
<?php
ActiveForm::end();
Pjax::end();
?>

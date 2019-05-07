<?php

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

AppAsset::registerCss($this, '@web/plugin/select2/select2.css');
AppAsset::registerCss($this , '@web/plugin/daterangepicker/daterangepicker-bs3.css');
AppAsset::registerJs($this, '@web/plugin/select2/select2.min.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/moment.min.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/daterangepicker.js');

Pjax::begin(['id' => 'season']);
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
        'id' => 'season-form',
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
                        <?= isset($data['name']) ? "{$data['name']} 信息" : '创建场次'?>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">房间名</label>
                        <div class="col-sm-5 ">
                            <input type="text" class="form-control" id="title" name="title" value="<?= isset($data['title']) ? $data['title'] : '';?>" onkeyup="value=value.replace(/[^\w\.\/\u4E00-\u9FA5]/ig,'')">
                        </div>
                    </div>
                    <?php if(isset($leagueDetail['leagueModel']) && $leagueDetail['leagueModel'] == 2 ){ ?>
                    <div class="form-group" id="display_peopleNum">
                        <label for="name" class="col-sm-3 control-label">人数(创建后不能修改)</label>
                        <div class="col-sm-5 ">
                            <input type="text" class="form-control" id="peopleNum" name="peopleNum" value="<?= isset($data['peopleNum']) ? $data['peopleNum'] : 1;?>" onkeyup="this.value=this.value.replace(/\D/g,'')">
                        </div>
                    </div>
                    <div class="form-group" >
                        <label for="name" class="col-sm-3 control-label">标签(例:空投,复活)</label>
                        <div class="col-sm-5 ">
                            <input type="text" class="form-control" id="tags" placeholder="多个标签以逗号分隔" autocomplete="false"  name="tags" value="<?= isset($data['tags']) ? $data['tags'] : '';?>" >
                        </div>
                    </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="voice" class="col-sm-3 control-label">房卡验证</label>
                        <div class="col-sm-5">
                            <?= Html::radioList('signCondition',isset($data['signCondition']) ? $data['signCondition'] : 0, ['0' => '否','1' => '是'],
                                ['class' => 'radio','itemOptions' =>['labelOptions' => ['style' =>'margin-left:2%','class' => 'checkbox-inline']]]) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="voice" class="col-sm-3 control-label">是否需要密码</label>
                        <div class="col-sm-5">
                            <?= Html::radioList('hasLock',isset($data['hasLock']) ? $data['hasLock'] : 0, [0 => '是',1 => '否'],
                                ['class' => 'radio','itemOptions' =>['labelOptions' => ['style' =>'margin-left:2%','class' => 'checkbox-inline']]]) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sponsor" class="col-sm-3 control-label">地图</label>
                        <div class="col-sm-5">
                            <?= Html::dropDownList('map',isset($data['map']) ? $data['map'] : 0, $mapList,['id' => 'map'])?>
                        </div>
                    </div>
                    <div class="form-group" id="pubg_display">
                        <label for="config" class="col-sm-3 control-label">自定义配置</label>
                        <div class="col-sm-5">
                            <div class="input-group" id="teamList">
                                <span class="input-group-addon"><i class="fa fa-flag-checkered"></i></span>
                                <?= Html::dropDownList('configId',isset($data['configId'])?$data['configId']:0, $configList,['id' => 'configId'])?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="endTime" class="col-sm-3 control-label">开始时间</label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input type="text" readonly class="form-control" name="startTime" id="startTime" value="<?= isset($data['startTime']) ? $data['startTime'] : (date('Y-m-d H:i') );?>" >
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="startTime" class="col-sm-3 control-label">结束时间</label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input type="text" readonly class="form-control" name="endTime" id="endTime" value="<?= isset($data['endTime']) ? $data['endTime'] : (date('Y-m-d H:i', strtotime('+ 2 hours')) );?>" >
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="customer" class="col-sm-3 control-label" >OB客服</label>
                        <div class="col-sm-5">
                            <?=Html::checkboxList('ob',$obIds,$obList,['class'=>'form-control checkbox' ,'style' => 'height:100px', 'itemOptions' =>['labelOptions' =>['style' =>'margin-left:2%','class' => 'checkbox-inline']]]);?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="status" class="col-sm-3 control-label">状态</label>
                        <div class="col-sm-5">
                            <?=Html::dropDownList('status' , isset($data['status']) ? $data['status'] : 1 , [ 1 => '预约中' , 2 => '进行中' , 3 => '结束', 4=> '结束且已结算'],['id' => 'status']);?>
                        </div>
                    </div>
                    <?= isset( $data['matchId'] ) ? Html::hiddenInput("matchId" , $data['matchId'],['id' => 'matchId']) : ''?>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group default-padding form-button">
        <button type="submit" class="btn btn-success" id="saveMatch">保　存</button>
        <a href="<?= \Yii::$app->request->getReferrer()?>" class="btn btn-default">返　回</a>
    </div>

<script language="JavaScript">
    jQuery( document ).ready( function( $ ){
        $("#map").select2({
            minimumResultsForSearch: -1
        })

        $("#server").select2()
        $('#configId').select2();
        $('#status').select2();
        $("#matchType").select2({
            minimumResultsForSearch: -1
        });

        var matchId = $("#matchId").val()
        console.log(matchId)
        if(matchId != undefined){
            $("#peopleNum").attr('disabled',true);
        }

        $('#startTime').daterangepicker({
            startDate: "<?= isset($data['startTime']) ? $data['startTime'] : date('Y-m-d H:i') ?>",
            showWeekNumbers : false, //是否显示第几周
            timePicker : true, //是否显示小时和分钟
            timePickerIncrement : 5, //时间的增量，单位为分钟
            timePicker12Hour : false, //是否使用12小时制来显示时间
            singleDatePicker: true,
            opens : 'right', //日期选择框的弹出位置
            buttonClasses : [ 'btn btn-default' ],
            applyClass : 'btn-small btn-success',
            cancelClass : 'btn-small',
            format : 'YYYY-MM-DD HH:mm', //控件中from和to 显示的日期格式
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
            $('#startTime span').html(start.format('YYYY-MM-DD HH:mm'));
        });

        $('#endTime').daterangepicker({
            startDate: "<?= isset($data['endTime']) ? $data['endTime'] : date('Y-m-d H:i', strtotime('+ 2 hours ')) ?>",
            showWeekNumbers : false, //是否显示第几周
            timePicker : true, //是否显示小时和分钟
            timePickerIncrement : 5, //时间的增量，单位为分钟
            timePicker12Hour : false, //是否使用12小时制来显示时间
            singleDatePicker: true,
            opens : 'right', //日期选择框的弹出位置
            buttonClasses : [ 'btn btn-default' ],
            applyClass : 'btn-small btn-success',
            cancelClass : 'btn-small',
            format : 'YYYY-MM-DD HH:mm', //控件中from和to 显示的日期格式
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
            $('#endTime span').html(start.format('YYYY-MM-DD HH:mm'));
        });

        $('#season a').click(function(e){
            e.preventDefault();
            location.href = $(this).attr('href');
        });

        $("#saveMatch").click(function(){
            $("#peopleNum").attr('disabled',false);
            var startTime = $("#startTime").val();
            var endTime = $("#endTime").val();

            var startTime = new Date(startTime.replace(/-/g, '/'));
            var endTime = new Date(endTime.replace(/-/g, '/'));

            var diffTime = endTime.getTime()-startTime.getTime();
            if(diffTime < 2400000){
                toastr.error('开始时间与结束时间间隔不能少于40分钟' , '' , toastrOpts);
                return false;
            }

            var obs = $('input[type=checkbox]:checked').length;
            if(obs < 1){
                toastr.error('OB客服不能为空' , '' , toastrOpts);
                return false;
            }

            return true;
        });
    });

</script>
<?php
    ActiveForm::end();
    Pjax::end();
?>

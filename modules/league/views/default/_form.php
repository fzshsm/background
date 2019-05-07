<?php

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

AppAsset::registerCss($this, '@web/plugin/select2/select2.css');
AppAsset::registerJs($this, '@web/plugin/select2/select2.min.js');
AppAsset::registerJs($this, '@web/plugin/fileinput.js');
AppAsset::registerCss( $this , '@web/plugin/datatables/datatables.min.css' );
AppAsset::registerCss($this , '@web/plugin/daterangepicker/daterangepicker-bs3.css');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/moment.min.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/daterangepicker.js');
AppAsset::registerJs($this, '@web/plugin/layer/layer.js');
AppAsset::registerJs($this, '@web/plugin/jquery.bootstrap.wizard.min.js');
AppAsset::registerJs($this,'@web/plugin/jquery.validate.min.js');
AppAsset::registerJs($this , '@web/js/common.js');
Pjax::begin(['id' => 'match']);
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
        'id' => 'match-form',
        'method' => 'post',
        'options' => [
            'enctype' => 'multipart/form-data',
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
                        编辑王者荣耀联赛信息
                    </div>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">名称</label>
                        <div class="col-sm-5 ">
                            <input type="text" class="form-control" id="leagueName" name="leagueName" value="<?= isset($data['leagueName']) ? $data['leagueName'] : '';?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="type" class="col-sm-3 control-label">联赛分类</label>
                        <div class="col-sm-5">
                            <?= Html::dropDownList('leagueCategory',isset($data['leagueCategory']) ? $data['leagueCategory'] : 1, $leagueSorts,['id' => 'leagueCategory','disabled' => true])?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="type" class="col-sm-3 control-label">联赛类型</label>
                        <div class="col-sm-5">
                            <?= Html::dropDownList('leagueModel',isset($data['leagueModel']) ? $data['leagueModel'] : 1, $leagueTypes,['id' => 'leagueModel','disabled' => true])?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="freeTrial" class="col-sm-3 control-label">免审核</label>
                        <div class="col-sm-5">
                            <?= Html::radioList('freeTrial',isset($data['freeTrial']) ? $data['freeTrial'] : 1,['1' => '否','2' => '是'],
                                ['class' => 'radio','itemOptions' =>['labelOptions' =>['style' =>'margin-left:2%;','class' => 'checkbox-inline']]]) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="voice" class="col-sm-3 control-label">语音</label>
                        <div class="col-sm-5">
                            <?= Html::radioList('voice',isset($data['voice']) ? $data['voice'] : 0, ['0' => '不开启','1' => '开启'],
                                ['class' => 'radio','itemOptions' =>['labelOptions' =>['style' =>'margin-left:2%','class' => 'checkbox-inline']]]) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="isCheckKingCard" class="col-sm-3 control-label">大王卡验证</label>
                        <div class="col-sm-5">
                            <?= Html::radioList('isCheckKingCard',isset($data['isCheckKingCard']) ? $data['isCheckKingCard'] : 0, ['0' => '不开启','1' => '开启'],
                                ['class' => 'radio','itemOptions' =>['labelOptions' =>['style' =>'margin-left:2%','class' => 'checkbox-inline']]]) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cover" class="col-sm-3 control-label" data-validate="required">封面</label>
                        <div class="col-sm-5">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;" data-trigger="fileinput">
                                    <img src="<?= isset($data['cover']) && !empty($data['cover']) ? $data['cover'] : Url::to('@web/images/200x200.png')?>" alt="...">
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px"></div>
                                    <span class="btn btn-white btn-file" style="display: none">
                                        <input type="file" name="cover" accept="image/*" onchange="checkUploadImage(this)">
                                    </span>
                                    <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="shareIcon" class="col-sm-3 control-label" data-validate="required">分享logo</label>
                        <div class="col-sm-5">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;" data-trigger="fileinput">
                                    <img src="<?= isset($data['shareIcon']) && !empty($data['shareIcon']) ? $data['shareIcon'] : Url::to('@web/images/200x200.png') ?>" alt="...">
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px"></div>
                                <span class="btn btn-white btn-file" style="display: none">
                                    <input type="file" name="shareIcon" accept="image/*" onchange="checkUploadImage(this)">
                                </span>
                                <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="shareCover" class="col-sm-3 control-label" data-validate="required">分享联赛图</label>
                        <div class="col-sm-5">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;" data-trigger="fileinput">
                                    <img src="<?= isset($data['shareCover']) && !empty($data['shareCover']) ? $data['shareCover'] : Url::to('@web/images/200x200.png') ?>" alt="...">
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px"></div>
                                <span class="btn btn-white btn-file" style="display: none">
                                    <input type="file" name="shareCover" accept="image/*" onchange="checkUploadImage(this)">
                                </span>
                                <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="activityIcon" class="col-sm-3 control-label" data-validate="required">活动图</label>
                        <div class="col-sm-5">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;" data-trigger="fileinput">
                                    <img src="<?= isset($data['activityIcon']) && !empty($data['activityIcon']) ? $data['activityIcon'] : Url::to('@web/images/1242x220.png') ?>" alt="...">
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 1242px; max-height: 220px"></div>
                                <span class="btn btn-white btn-file" style="display: none">
                                    <input type="file" name="activityIcon" accept="image/*" onchange="checkUploadImage(this)">
                                </span>
                                <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="activityPage" class="col-sm-3 control-label" data-validate="required">活动页面图</label>
                        <div class="col-sm-5">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail " style="width: 200px; height: 200px;" data-trigger="fileinput">
                                    <img src="<?= isset($data['activityPage']) && !empty($data['activityPage']) ? $data['activityPage'] : Url::to('@web/images/noimg.png') ?>"  alt="...">
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 1242px; max-height: 220px"></div>
                                <span class="btn btn-white btn-file" style="display: none">
                                    <input type="file" name="activityPage" accept="image/*" onchange="checkUploadImage(this)">
                                </span>
                                <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="activityUrl" class="col-sm-3 control-label">活动URL</label>
                        <div class="col-sm-5 ">
                            <input type="text" class="form-control" id="activityUrl" name="activityUrl" value="<?= isset($data['activityUrl']) ? $data['activityUrl'] : '';?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sortWeight" class="col-sm-3 control-label">排序权重</label>
                        <div class="col-sm-5">
                            <div class="input-spinner">
                                <button type="button" class="btn btn-default btn-sm">-</button>
                                <input type="text" class="form-control size-1" data-min="0" name="sortWeight" value="<?= isset($data['sortWeight']) ? $data['sortWeight'] : 0;?>">
                                <button type="button" class="btn btn-default btn-sm">+</button>
                            </div>
                        </div>
                    </div>
                        <?php
                            $matchTimeNum = (isset($data['matchTimes']) && !empty($data['matchTimes'])) ? count($data['matchTimes']) : 1;
                            for ($i=0;$i<$matchTimeNum;$i++){
                        ?>
                        <?php if($i == 0){ ?>
                            <div class="form-group openHours" id="matchTime-<?= ($i+1)?>">
                                <label for="matchTime" class="col-sm-3 control-label">匹配时间段</label>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <input type="text" readonly class="form-control" name="matchTimes[]" id="matchTime_<?= ($i+1)?>" value="<?= isset($data['matchTimes'][$i]) ? $data['matchTimes'][$i] : (date('H:i') . '-' . date('H:i' , strtotime("+2 hours")));?>" >
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    </div>
                                </div>
                                <!-- 根据联赛类型进行判断是否需要多个时间段  -->
                                <div class="col-sm-2">
                                    <a href="javascript:void(0);" class="btn btn-success btn-sm radius-4">
                                        <i class="entypo-plus"></i>
                                    </a>
                                    <a href="javascript:void(0);" class="btn btn-danger btn-sm radius-4">
                                        <i class="entypo-minus"></i>
                                    </a>
                                </div>
                                <!--  -->
                            </div>
                        <?php }else{?>
                            <div class="form-group openHours"  id="matchTime-<?= ($i+1)?>">
                                <label for="matchTime" class="col-sm-3 control-label"></label>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <input type="text" readonly class="form-control" name="matchTimes[]" id="matchTime_1" value="<?= isset($data['matchTimes'][$i]) ? $data['matchTimes'][$i] : (date('H:i') . '-' . date('H:i' , strtotime("+2 hours")));?>" >
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                        <?php }?>
                    <?php }?>
                    <div class="form-group">
                        <label for="reward" class="col-sm-3 control-label">奖金</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="reward" id="reward" value="<?= isset($data['reward']) ? $data['reward'] : '';?>" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sponsor" class="col-sm-3 control-label">举办方</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="sponsor" id="sponsor" value="<?= isset($data['sponsor']) ? $data['sponsor'] : '';?>" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="describe" class="col-sm-3 control-label">简介</label>
                        <div class="col-sm-5">
                            <textarea id="leagueDescribe" name="leagueDescribe" class="form-control autogrow" rows="10" ><?= isset($data['leagueDescribe']) ? $data['leagueDescribe'] : '';?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="status" class="col-sm-3 control-label">状态</label>
                        <div class="col-sm-5">
                            <?=Html::dropDownList('status' , isset($data['status']) ? $data['status'] : 1 , [ 1 => '未开始' , 2 => '进行中' , 3 => '已关闭'],['id' => 'status']);?>
                        </div>
                    </div>

                    <?= isset( $data['id'] ) ? Html::hiddenInput("leagueId" , $data['id']) : ''?>
                    <input type="hidden" id="controlStatus" value="<?= isset($data['status']) ? $data['status'] : 1?>">
                </div>
            </div>
        </div>
    </div>
    <div class="form-group default-padding form-button">
        <button type="submit" class="btn btn-success save">保　存</button>
        <a href="<?= \Yii::$app->request->getReferrer()?>" class="btn btn-default">返　回</a>
    </div>
<script language="JavaScript">
    jQuery( document ).ready( function( $ ){
        controlStatus = $("#controlStatus").val();
        $('form select').select2( {
            minimumResultsForSearch: -1
        });
        $('#match a').click(function(e){
            e.preventDefault();
            location.href = $(this).attr('href');
        });
        getType();

        //增加时间选择器
        $("a.btn-success").click(function () {
            var matchTimeNum = $("input[name='matchTimes\[\]']").length;
            var newMatchTimeNum = matchTimeNum + 1;
            var matchTimeId = 'matchTime_' + newMatchTimeNum;
            var divMatchTimeId = 'matchTime-'+newMatchTimeNum
            $("#matchTime-" + matchTimeNum).after(
                "<div class='form-group openHours' id="+divMatchTimeId+">" +
                "<label for='matchTime' class='col-sm-3 control-label'></label>" +
                "<div class='col-sm-5'>" +
                "<div class='input-group'>" +
                "<input type='text' readonly class='form-control' name='matchTimes[]' id="+matchTimeId+" value='<?= (date('H:i') . '-' . date('H:i' , strtotime("+2 hours")));?>' >"+
                "<span class='input-group-addon'><i class='fa fa-calendar'></i></span>"+
                "</div>"+
                "</div>"+
                "</div>");

            initializeDate(newMatchTimeNum)
        });

        //删除时间选择器
        $("a.btn-danger").click(function () {
            var matchTimeNum = $("input[name='matchTimes\[\]']").length;

            if (matchTimeNum <= 1) {
                toastr.error('至少1个' , '' , toastrOpts);
                return false;
            }
            $("#matchTime-" + matchTimeNum).remove()
        });

        $(":submit").click(function(){
            $("#leagueModel").attr('disabled',false);
            $("#leagueCategory").attr('disabled',false);
        });
    });

    function getType(){
        var leagueModel = $("#leagueModel").val();
        if(leagueModel == 9){
            $(".openHours").show();
        }else{
            $(".openHours").hide();
        }
    }

    function  initializeDate(id,startTime,endTime){
        $('#matchTime_'+id).daterangepicker({
            startDate: "<?=  date('H:i') ?>",
            endDate: "<?=  date('H:i' , strtotime( "+2 hours")) ?>",
            showWeekNumbers : false, //是否显示第几周
            timePicker : true, //是否显示小时和分钟
            timePickerIncrement : 5, //时间的增量，单位为分钟
            timePicker12Hour : false, //是否使用12小时制来显示时间
            opens : 'right', //日期选择框的弹出位置
            buttonClasses : [ 'btn btn-default' ],
            applyClass : 'btn-small btn-success',
            cancelClass : 'btn-small',
            format : 'HH:mm', //控件中from和to 显示的日期格式
            separator : '-',
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
            $('#matchTime_'+id +' span').html(start.format('HH:mm') + '-' + end.format('HH:mm'));
        });
    }

</script>
<?php
    ActiveForm::end();
    Pjax::end();
?>

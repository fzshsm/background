<?php

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

AppAsset::registerCss($this , '@web/plugin/daterangepicker/daterangepicker-bs3.css');

AppAsset::registerJs($this , '@web/plugin/daterangepicker/moment.min.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/daterangepicker.js');

AppAsset::registerCss($this , '@web/plugin/daterangepicker/daterangepicker-bs3.css');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/moment.min.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/daterangepicker.js');

AppAsset::registerCss($this, '@web/plugin/select2/select2.css');
AppAsset::registerJs($this, '@web/plugin/select2/select2.min.js');

AppAsset::registerJs($this, '@web/plugin/fileinput.js');
AppAsset::registerJs($this, '@web/plugin/layer/layer.js');
Pjax::begin(['id' => 'news']);
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
        'id' => 'recruit-form',
        'method' => 'post',
        'options' => [
            'data-pjax' => true,
            'role' => 'form',
            'class' => 'form-horizontal form-groups-bordered validate'
        ]
    ] );
    ?>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary" data-collapsed="0">
                <div class="panel-heading">
                    <div class="panel-title">
                        招聘信息
                    </div>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="clubName" class="col-sm-3 control-label">战队名称</label>
                        <div class="col-sm-5 ">
                            <input type="text" readonly  disabled  class="form-control" id="clubName" name="clubName" autocomplete="false" required value="<?= isset($data['clubName']) ? $data['clubName'] : '';?>">
                            <input type="hidden" value="<?= isset($data['clubName']) ? $data['clubName'] : '';?>" name="hidden_clubName" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="teamLogo" class="col-sm-3 control-label" data-validate="required">战队图标</label>
                        <div class="col-sm-5">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="height: 100px;">
                                    <img src="<?= isset($data['icon']) && !empty($data['icon']) ? $data['icon'] : Url::to('@web/images/noimg.png')?>" alt="...">
                                </div>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="clubAddress" class="col-sm-3 control-label">战队所在地</label>
                        <div class="col-sm-5 ">
                            <input type="text" class="form-control" id="clubLocation" name="clubLocation" autocomplete="false" required value="<?= isset($data['clubLocation']) ? $data['clubLocation'] : '';?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">招募类型</label>
                        <div class="col-sm-5">
                        <?=Html::dropDownList('recruitType' , isset($data['recruitType']) ? $data['recruitType'] : 0 , [0 => 'KPL',1 => '战队',2 => '业余战队',3 => '次级联赛']);?>
                    </div>
                    </div>
                    <div class="form-group">
                        <label for="remark" class="col-sm-3 control-label">招募职位</label>
                        <div class="col-sm-5 ">
                            <?=Html::dropDownList('positionType' , isset($data['positionType']) ? $data['positionType'] : 0 , [0 => '主力',1 => '替补',2 => '试训']);?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reward" class="col-sm-3 control-label">招募游戏角色</label>
                        <div class="col-sm-5">
                            <?=Html::dropDownList('rolerPositionType' , isset($data['rolerPositionType']) ? $data['rolerPositionType'] : 1 , [1 => '射手',2 => '打野',3 => '中路',4 => '边路',5 => '辅助']);?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">招募对象描述</label>
                        <div class="col-sm-5 ">
                            <input type="text" class="form-control" id="recruitObjInfo" name="recruitObjInfo" autocomplete="false" required value="<?= isset($data['recruitObjInfo']) ? $data['recruitObjInfo'] : '';?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reward" class="col-sm-3 control-label">联赛要求</label>
                        <div class="col-sm-5">
                            <?=Html::dropDownList('leagueId' , isset($data['leagueId']) ? $data['leagueId'] : 1 , $matchTypes,['onchange' => 'changeMedal()','id' =>'leagueId']);?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="status" class="col-sm-3 control-label">战队经验</label>
                        <div class="col-sm-5">

                            <?= Html::radioList('hasClubExperience',isset($data['hasClubExperience'])?$data['hasClubExperience']:0,['0'=>'没有','1'=>'有'],['class' => 'radio',
                                'itemOptions' =>['labelOptions' =>['style' =>'margin-left:2%']]]) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">胜率(1-100)</label>
                        <div class="col-sm-5 ">
                            <div class="input-spinner">
                                <button type="button" class="btn btn-default btn-sm">-</button>
                                <input type="text" class="form-control size-1" data-min="1" data-max="100" name="winRateNumber" value="<?= isset($data['winRateNumber']) ? $data['winRateNumber'] : 1;?>">
                                <button type="button" class="btn btn-default btn-sm">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="clubAddress" class="col-sm-3 control-label">最小年龄</label>
                        <div class="col-sm-5 ">
                            <input type="text" class="form-control" id="minYear" name="minYear" autocomplete="false" required value="<?= isset($data['minYear']) ? $data['minYear'] : '';?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="clubAddress" class="col-sm-3 control-label">最大年龄</label>
                        <div class="col-sm-5 ">
                            <input type="text" class="form-control" id="minYear" name="maxYear"  autocomplete="false" required value="<?= isset($data['maxYear']) ? $data['maxYear'] : 0;?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="clubAddress" class="col-sm-3 control-label">最少薪资</label>
                        <div class="col-sm-5 ">
                            <input type="text" class="form-control" id="minPay" name="minPay" autocomplete="false" required value="<?= isset($data['minPay']) ? $data['minPay'] : 1000;?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="clubAddress" class="col-sm-3 control-label">最大薪资</label>
                        <div class="col-sm-5 ">
                            <input type="text" class="form-control" id="maxPay" name="maxPay" autocomplete="false" required value="<?= isset($data['maxPay']) ? $data['maxPay'] : 1000;?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="remark" class="col-sm-3 control-label">备注</label>
                        <div class="col-sm-5 ">
                            <textarea  class="form-control" id="remark" name="remark" rows="12" cols="5">
                            </textarea>
                            <input type="hidden" id="hidden_remark" value="<?= isset($data['remark']) ? $data['remark'] : '';?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="matchTime" class="col-sm-3 control-label">发布时间</label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input type="text" readonly class="form-control" name="time" id="time" value="<?= isset($data['time']) ? $data['time'] : (date('Y-m-d H:i') );?>" >
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reward" class="col-sm-3 control-label">状态</label>
                        <div class="col-sm-5">
                            <?=Html::dropDownList('status' , isset($data['status']) ? $data['status'] : 0 , [0 => '等待审核',1 => '审核成功',2 => '审核失败',3 => '失效']);?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="medal" class="col-sm-3 control-label">勋章</label>
                        <div class="col-sm-5" id="change_medal">
                            <?=Html::checkboxList('medals',$medalsIds,$medals,['class'=>'form-control checkbox' ,'style' => 'height:180px', 'itemOptions' =>['labelOptions' =>['style' =>'margin-left:2%','class' => 'checkbox-inline','onclick' =>"checkNum('medals[]',5)"]]]);?>
                        </div>
                    </div>
                    <input type="hidden" id="hide_medalIds" value="<?= $medalsIdsJs?>">
                </div>
            </div>
        
        </div>
    </div>
    <div class="form-group default-padding form-button">
        <button type="submit" class="btn btn-success">保　存</button>
        <a href="<?=\Yii::$app->request->getReferrer()?>" class="btn btn-default">返　回</a>
    </div>
<?php
    ActiveForm::end();
    Pjax::end();
?>
<script>

    $(document).ready(function(){
        $('form select').select2( {
            minimumResultsForSearch: -1
        });
        $('#time').daterangepicker({
            startDate: "<?= date('Y-m-d H:i') ?>",
            showWeekNumbers : false, //是否显示第几周
            timePicker : true, //是否显示小时和分钟
            timePickerIncrement : 5, //时间的增量，单位为分钟
            timePicker12Hour : false, //是否使用12小时制来显示时间
            opens : 'right', //日期选择框的弹出位置
            buttonClasses : [ 'btn btn-default' ],
            applyClass : 'btn-small btn-success',
            cancelClass : 'btn-small',
            format : 'YYYY-MM-DD HH:mm', //控件中from和to 显示的日期格式
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
            $('#time span').html(start.format('YYYY-MM-DD HH:mm:ss'));
        });
        checkNum('medals[]',5);
        var remark = $("#hidden_remark").val();
        $("#remark").val(remark);
    })

    function checkNum(name,num){
        var choiceArr = document.getElementsByName(name);
        var a=0;
        for(var i=0;i<choiceArr.length;i++)
            if(choiceArr[i].checked){
                a=a+1;
            }
        if(a==num){
            for(var i=0;i<choiceArr.length;i++)
                if(!choiceArr[i].checked)
                    choiceArr[i].disabled='disabled';
        }else{
            for(var i=0;i<choiceArr.length;i++)
                choiceArr[i].removeAttribute('disabled');
        }
    }

    function changeMedal(){
        var $that = $(this)
        var leagueId = $("#leagueId").val();

        var url = "<?= Url::to(['/recruit/medal'])?>"
        $.ajax({
            dataType:"json",
            url:url,
            type:"get",
            data:{leagueId:leagueId},
            success:function(response){
                if(response.status == 'success'){
                   var medals = response.data;
                   var htmlMedal = "<div class='form-control checkbox' style='height: 120px;'>";
                    $("#change_medal").html();
                   for(i in medals){
                       htmlMedal = htmlMedal+"<label class='checkbox-inline' style='margin-left:2%' onclick=\"checkNum('medals[]',5)\">" +
                           "<input type='checkbox' name='medals[]' value="+i+" >" +medals[i]+"</label>";
                   }
                   $("#change_medal").html(htmlMedal+'</div>');

                    var medalsIds = $("#hide_medalIds").val()

                        medalsIds =  medalsIds.split(",");
                    for(var i=0;i<medalsIds.length;i++){
                        $("input[name='medals[]']").each(function(){
                            if($(this).val()==medalsIds[i]){
                                $(this).attr("checked","checked");
                            }
                        })
                    }
                }else{
                    toastr.error(response.message , '' , $that.toastrOpts);
                }
            }
        })
    }

    $(document).on('pjax:complete',function(){
        $('form select').select2( {
            minimumResultsForSearch: -1
        });
        $('#time').daterangepicker({
            startDate: "<?= date('Y-m-d H:i') ?>",
            showWeekNumbers : false, //是否显示第几周
            timePicker : true, //是否显示小时和分钟
            timePickerIncrement : 5, //时间的增量，单位为分钟
            timePicker12Hour : false, //是否使用12小时制来显示时间
            opens : 'right', //日期选择框的弹出位置
            buttonClasses : [ 'btn btn-default' ],
            applyClass : 'btn-small btn-success',
            cancelClass : 'btn-small',
            format : 'YYYY-MM-DD HH:mm', //控件中from和to 显示的日期格式
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
            $('#time span').html(start.format('YYYY-MM-DD HH:mm:ss'));
        });
        checkNum('medals[]',5);
        var remark = $("#hidden_remark").val();
        $("#remark").val(remark);
    })


</script>

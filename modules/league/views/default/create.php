<?php

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

$this->params['breadcrumbs'] = [
    [
        'label' => '联赛管理',
        'url' => Url::to([
            '/league'
        ])
    ],
    [
        'label' => '创建王者荣耀联赛'
    ]
];
AppAsset::registerCss($this, '@web/plugin/select2/select2.css');
AppAsset::registerJs($this, '@web/plugin/select2/select2.min.js');
AppAsset::registerJs($this, '@web/plugin/fileinput.js');
AppAsset::registerJs($this, '@web/plugin/layer/layer.js');
AppAsset::registerJs($this, '@web/plugin/jquery.bootstrap.wizard.min.js');
AppAsset::registerJs($this, '@web/plugin/jquery.validate.min.js');
//AppAsset::registerCss($this,'@web/css/neon-forms.css');
AppAsset::registerJs($this, '@web/plugin/layer/layer.js');
AppAsset::registerJs($this , '@web/js/common.js');
AppAsset::registerCss($this , '@web/plugin/daterangepicker/daterangepicker-bs3.css');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/moment.min.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/daterangepicker.js');
Pjax::begin(['id' => 'match']);
?>
<div class="row">
    <div class="col-md-8">
        <?php if (Yii::$app->session->hasFlash('dataError')) { ?>
            <script>layer.close(controlLayer);</script>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <?= Yii::$app->session->getFlash('dataError') ?>
            </div>
        <?php } ?>
        <?php if (Yii::$app->session->hasFlash('error')) { ?>
            <script>layer.close(controlLayer);</script>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <?= Yii::$app->session->getFlash('error') ?>
            </div>
        <?php } ?>
        <?php if (Yii::$app->session->hasFlash('success')) { ?>
            <script>layer.close(controlLayer);</script>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <?= Yii::$app->session->getFlash('success') ?>
            </div>
        <?php } ?>
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
        'class' => 'form-horizontal    validate'
    ]
]);
?>
<div class="row">
    <div class="col-md-8">
        <div class="panel panel-primary form-wizard" data-collapsed="0" id="matchWizard">
            <div class="panel-heading">
                <div class="panel-title">
                    创建王者荣耀联赛信息
                </div>
            </div>
            <div class="steps-progress" style="margin-top: 5%;">
                <div class="progress-indicator" style="width:0px"></div>
            </div>
            <ul id="tab-control">
                <li class="active"><a href="#tab-first" data-toggle="tab" style="color: white;"><span>1</span>First</a></li>
                <li><a href="#tab-second" data-toggle="tab" style="color: white;"><span>2</span>Second</a></li>
                <li><a href="#tab-third" data-toggle="tab" style="color: white;"><span>3</span>Third</a></li>
                <li><a href="#tab-fourth" data-toggle="tab" style="color: white;"><span>4</span>Fourth</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane panel-body active" id="tab-first">
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">名称</label>
                        <div class="col-sm-5 ">
                            <input type="text" class="form-control" id="leagueName" name="leagueName" autocomplete="false" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="type" class="col-sm-3 control-label">联赛分类</label>
                        <div class="col-sm-5">
                            <?= Html::dropDownList('leagueCategory', isset($data['leagueCategory']) ? $data['leagueCategory'] : 1, $leagueSorts) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="type" class="col-sm-3 control-label">联赛类型</label>
                        <div class="col-sm-5">
                            <?= Html::dropDownList('leagueModel', isset($data['leagueModel']) ? $data['leagueModel'] : 1 ,$leagueTypes, ['onchange' => 'getType()', 'id' => 'leagueModel']) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="freeTrial" class="col-sm-3 control-label">免审核</label>
                        <div class="col-sm-5">
                            <?= Html::radioList('freeTrial',isset($data['freeTrial'])?$data['freeTrial'] : 1,['1' => '否','2' => '是'],
                                ['class' => 'radio','itemOptions' =>['labelOptions' =>['style' =>'margin-left:2%','class' => 'checkbox-inline']]]) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="voice" class="col-sm-3 control-label">语音</label>
                        <div class="col-sm-5">
                            <?= Html::radioList('voice',isset($data['voice'])?$data['voice'] : 0,['0' => '不开启','1' => '开启'],
                            ['class' => 'radio','itemOptions' =>['labelOptions' =>['style' =>'margin-left:2%','class' => 'checkbox-inline']]]) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="isCheckKingCard" class="col-sm-3 control-label">大王卡验证</label>
                        <div class="col-sm-5">
                            <?= Html::radioList('isCheckKingCard',isset($data['isCheckKingCard'])?$data['isCheckKingCard'] : 0,['0' => '不开启','1' => '开启'],
                                ['class' => 'radio','itemOptions' =>['labelOptions' =>['style' =>'margin-left:2%','class' => 'checkbox-inline']]]) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cover" class="col-sm-3 control-label" data-validate="required">封面</label>
                        <div class="col-sm-5">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;" data-trigger="fileinput">
                                    <img src="<?= isset($data['cover']) && !empty($data['cover']) ? $data['cover'] : Url::to('@web/images/200x200.png') ?>" alt="...">
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px"></div>
                                <span class="btn btn-white btn-file" style="display: none">
                                    <input type="file" name="cover" accept="image/*" onchange="checkUploadImage(this)">
                                </span>
                                <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-body tab-pane" id="tab-second">
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
                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px"></div>
                                <span class="btn btn-white btn-file" style="display: none">
                                    <input type="file" name="activityIcon" accept="image/*" onchange="checkUploadImage(this)">
                                </span>
                                <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="activityUrl" class="col-sm-3 control-label">活动URL</label>
                        <div class="col-sm-5 ">
                            <input type="text" class="form-control" id="activityUrl" name="activityUrl" value="">
                        </div>
                    </div>
                </div>
                <div class="panel-body tab-pane" id="tab-third">
<!--                    <div class="form-group bounty">-->
<!--                        <label for="playerLevel" class="col-md-3 control-label">玩家等级</label>-->
<!--                        <div class="col-sm-5">-->
<!--                            <div class="input-spinner">-->
<!--                                <button type="button" class="btn btn-default btn-sm">-</button>-->
<!--                                <input type="text" class="form-control size-1" data-min="0" name="signLevel" value="20">-->
<!--                                <button type="button" class="btn btn-default btn-sm">+</button>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="form-group bounty">-->
<!--                        <label for="peas" class="col-sm-3 control-label">每局消耗豆值</label>-->
<!--                        <div class="col-sm-5 ">-->
<!--                            <input type="text" class="form-control" id="perGameCost" name="perGameCost" value="0">-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="form-group bounty">-->
<!--                        <label for="rebuy" class="col-sm-3 control-label">赏金rebuy</label>-->
<!--                        <div class="col-sm-5 ">-->
<!--                            <input type="text" class="form-control" id="joinGameLeagueCost" name="joinGameLeagueCost" value="0">-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="form-group bounty">-->
<!--                        <label for="taxRate" class="col-sm-3 control-label">抽成比例</label>-->
<!--                        <div class="col-sm-5">-->
<!--                            <input type="number" step="0.001" class="form-control" name="taxRate" value="0.001">-->
<!--                        </div>-->
<!--                    </div>-->
                    <div class="form-group">
                        <label for="sortWeight" class="col-sm-3 control-label">排序权重</label>
                        <div class="col-sm-5">
                            <div class="input-spinner">
                                <button type="button" class="btn btn-default btn-sm">-</button>
                                <input type="text" class="form-control size-1" data-min="0" name="sortWeight" value="0">
                                <button type="button" class="btn btn-default btn-sm">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reward" class="col-sm-3 control-label">奖金</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="reward" id="reward" value="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sponsor" class="col-sm-3 control-label">举办方</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="sponsor" id="sponsor" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="describe" class="col-sm-3 control-label">简介</label>
                        <div class="col-sm-5">
                            <textarea id="leagueDescribe" name="leagueDescribe" class="form-control autogrow" rows="10" style="min-height: 10rem"><?= ''; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="status" class="col-sm-3 control-label">状态</label>
                        <div class="col-sm-5">
                            <?= Html::dropDownList('status', 1, [1 => '未开始', 2 => '进行中', 3 => '已关闭']); ?>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab-fourth">
                    <div class="form-group" id="matchTime-1">
                        <label for="matchTime" class="col-sm-3 control-label">队伍匹配时间段</label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input type="text" readonly class="form-control" name="matchTimes[]" id="matchTime_1" value="<?= (date('H:i') . '-' . date('H:i' , strtotime("+2 hours")));?>" >
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <a href="#" class="btn btn-success btn-sm radius-4">
                                <i class="entypo-plus"></i>
                            </a>
                            <a href="#" class="btn btn-danger btn-sm radius-4">
                                <i class="entypo-minus"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <ul class="pager wizard">
                    <li class="previous first" id="submit-return"><a href="<?= \Yii::$app->request->getReferrer() ?>" class="btn btn-default" style="cursor: pointer" >返　回</a></li>
                    <li class="previous" id="submit-previous"><a href="#">上一步</a></li>
                    <li class="next" id="submit-next"><a href="#">下一步</a></li>
                    <li id="submit-save" style="display: none;float: right"><button type="submit"  class="btn btn-success subCreate" >保　存</button></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script language="JavaScript">
    var controlLayer;
    jQuery(document).ready(function ($) {
        $('form select').select2({
            minimumResultsForSearch: -1
        });
        $('#match a').click(function (e) {
            e.preventDefault();
            location.href = $(this).attr('href');
        });

        $("a.btn-success").click(function () {
            var rewardNum = $("input[name='rewards\[\]']").length;
            var newNum = rewardNum + 1;
            var newRewardId = 'rewards_' + newNum;
            $("#" + "rewards_" + rewardNum).after(
                "<div class='form-group' id=" + newRewardId + ">" +
                "<label for='reward' class='col-sm-3 control-label'>" + newNum + ":</label>" +
                "<div class='col-sm-5'>" +
                "<input type='text' class='form-control' name='rewards[]' value='' >" +
                "</div></div>");
        });
        $("a.btn-danger").click(function () {
            var rewardNum = $("input[name='rewards\[\]']").length;

            if (rewardNum <= 3) {
                layer.msg('至少3个', {time: 1000})
                return false;
            }
            $("#" + "rewards_" + rewardNum).remove()
        });

        if ($.isFunction($.fn.bootstrapWizard)) {
            var $this = $(".form-wizard"),
                $progress = $this.find(".steps-progress div"),
                _index = $this.find('> ul > li.active').index();

            // Validation
            var checkFormWizardValidaion = function (tab, navigation, index) {
                if ($this.hasClass('validate')) {
                    var $valid = $this.valid();

                    if (!$valid) {
                        $this.data('validator').focusInvalid();
                        return false;
                    }
                }
                return true;
            };
            $this.bootstrapWizard({
                tabClass: "",
                onTabShow: function ($tab, $navigation, index) {
                    setCurrentProgressTab($this, $navigation, $tab, $progress, index);

                    //控制返回、保存、上下翻页按钮
                    var leagueModel = $("#leagueModel").val();
                    if(index == 0){
                        $('#submit-return').show();
                        $('#submit-previous').hide();
                        $("#submit-save").hide()
                        $("#submit-next").show()
                    }
                    if(index > 0){
                        $("#submit-return").hide();
                        $("#submit-save").hide();
                        $("#submit-previous").show();
                        $("#submit-next").show()
                    }

                    if (index == 2) {
                        if (leagueModel != 9) {
                            $("#submit-save").show()
                            $("#submit-next").hide()
                        }else{
                            $("#submit-save").hide()
                            $("#submit-next").show()
                        }
                    }
                    if (index == 3) {
                        $("#submit-save").show()
                        $("#submit-next").hide()
                    }
                },

                onNext: checkFormWizardValidaion,
                onTabClick: checkFormWizardValidaion
            });

            $this.data('bootstrapWizard').show(_index);
        }

        function setCurrentProgressTab($rootwizard, $nav, $tab, $progress, index) {
            $tab.prevAll().addClass('completed');
            $tab.nextAll().removeClass('completed');

            var items = $nav.children().length,
                pct = parseInt((index + 1) / items * 100, 10),
                $first_tab = $nav.find('li:first-child'),
                margin = (1 / (items * 2) * 100) + '%';

            if ($first_tab.hasClass('active')) {
                $progress.width(0);
            }
            else {
                if (rtl()) {
                    $progress.width($progress.parent().outerWidth(true) - $tab.prev().position().left - $tab.find('span').width() / 2);
                }
                else {
                    $progress.width(((index - 1) / (items - 1)) * 100 + '%');
                }
            }
            $progress.parent().css({
                marginLeft: margin,
                marginRight: margin
            });
        }
        getType();


        $('.subCreate').on('click',function(){
            controlLayer = layer.load(2,{shade:[0.4,'#fff']})
        })

        initializeDate(1)

        //增加时间选择器
        $("a.btn-success").click(function () {
            var matchTimeNum = $("input[name='matchTimes\[\]']").length;
            var newMatchTimeNum = matchTimeNum + 1;
            var matchTimeId = 'matchTime_' + newMatchTimeNum;
            var divMatchTimeId = 'matchTime-'+newMatchTimeNum
            $("#matchTime-" + matchTimeNum).after(
                "<div class='form-group' id="+divMatchTimeId+">" +
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
            var matchTimeNum = $("input[name='matchTime\[\]']").length;

            if (matchTimeNum <= 1) {
                toastr.error('至少1个' , '' , toastrOpts);
                return false;
            }
            $("#matchTime-" + matchTimeNum).remove()
        });
    });

    function getType() {
        var leagueModel = $("#leagueModel").val();
        var ul = document.getElementById('tab-control');
        var li = ul.getElementsByTagName('li');
        if (leagueModel == 9) {
            $(".bounty").show();
//            $(".bounty" + " :input").each(function () {
//                if ($(this).attr('class') == 'form-control') {
//                    $(this).attr('required', true);
//                }
//            });

            if (li.length <= 3) {
                $("#tab-control").append("<li class><a href='#tab-fourth' data-toggle='tab' style='color:white'><span>4</span> Fourth</a></li>");
                $("#submit").hide();
                $('#matchWizard').data('bootstrapWizard').resetWizard();
            }
        } else {
            $(".bounty").hide();
            if (li.length > 3) {
                ul.removeChild(li[3]);
                $('#matchWizard').data('bootstrapWizard').resetWizard();
            }
        }
    }

    function  initializeDate(id,startTime,endTime){
        $('#matchTime_'+id).daterangepicker({
            startDate: "<?= date('H:i') ?>",
            endDate: "<?= date('H:i' , strtotime( "+2 hours")) ?>",
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


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

Pjax::begin(['id' => 'seasonUpdate']);
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
                        <?= isset($data['name']) ? "{$data['name']} 信息" : '创建赛季'?>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">名称</label>
                        <div class="col-sm-5 ">
                            <input type="text" class="form-control" id="seasonName" name="seasonName" value="<?= isset($data['seasonName']) ? $data['seasonName'] : '';?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reward" class="col-sm-3 control-label">奖金</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="reward" id="reward" value="<?= isset($data['reward']) ? $data['reward'] : '';?>" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="status" class="col-sm-3 control-label">奖金方案</label>
                        <div class="col-sm-5">
                            <?=Html::dropDownList('bonusConfigId' , isset($data['bonusConfigId']) ? $data['bonusConfigId'] : 1 , $bonusConfigList);?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sponsor" class="col-sm-3 control-label">举办方</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="sponsor" id="sponsor" value="<?= isset($data['sponsor']) ? $data['sponsor'] : '';?>" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="matchTime" class="col-sm-3 control-label">比赛时间</label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input type="text" readonly class="form-control" name="matchTime" id="matchTime" value="<?= isset($data['matchTime']) ? $data['matchTime'] : (date('Y-m-d H:i') . ' 至 ' . date('Y-m-d H:i' , strtotime("+7 days")));?>" >
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="describe" class="col-sm-3 control-label">简介</label>
                        <div class="col-sm-5">
                            <textarea id="describe" name="describe" class="form-control autogrow" rows="10" ><?= isset($data['describe']) ? $data['describe'] : '';?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="status" class="col-sm-3 control-label">状态</label>
                        <div class="col-sm-5">
                            <?=Html::dropDownList('status' , isset($data['status']) ? $data['status'] : 1 , [ 1 => '未开始' , 2 => '进行中' , 3 => '已关闭']);?>
                        </div>
                    </div>
                    <?= isset( $data['id'] ) ? Html::hiddenInput("id" , $data['id']) : ''?>
                </div>
            </div>
        
        </div>
    </div>
    <div class="form-group default-padding form-button">
        <button type="submit" class="btn btn-success">保　存</button>
        <a href="<?= \Yii::$app->request->getReferrer() ?>" class="btn btn-default">返　回</a>
    </div>

<script language="JavaScript">
    jQuery( document ).ready( function( $ ){
        $('form select').select2( {
            minimumResultsForSearch: -1
        });
        $('#matchTime').daterangepicker({
            startDate: "<?= isset($data['startTime']) ? $data['startTime'] : date('Y-m-d H:i') ?>",
            endDate: "<?= isset($data['endTime']) ? $data['endTime'] : date('Y-m-d H:i' , strtotime( "+7 days")) ?>",
            showWeekNumbers : false, //是否显示第几周
            timePicker : true, //是否显示小时和分钟
            timePickerIncrement : 5, //时间的增量，单位为分钟
            timePicker12Hour : false, //是否使用12小时制来显示时间
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
            $('#matchTime span').html(start.format('YYYY-MM-DD HH:mm') + ' - ' + end.format('YYYY-MM-DD HH:mm'));
        });
        $('#season a').click(function(e){
            e.preventDefault();
            location.href = $(this).attr('href');
        });
    });
</script>
<?php
    ActiveForm::end();
    Pjax::end();
?>

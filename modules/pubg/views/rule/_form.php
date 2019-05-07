<?php

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;


AppAsset::registerJs($this, '@web/plugin/layer/layer.js');
Pjax::begin(['id' => 'rule']);
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
        'id' => 'rule-form',
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
                        积分规则
                    </div>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="status" class="col-sm-3 control-label">配置名:</label>
                        <div class="col-sm-4">
                           <input type="text" class="form-control" name="configName"  value="<?= isset($data['configName']) ? $data['configName'] : '' ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="status" class="col-sm-3 control-label">击杀得分(击杀一个的分数):</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="killScore"  value="<?= isset($data['killScore']) ? $data['killScore'] : 0 ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="status" class="col-sm-3 control-label">名次(例:1或1-10):</label>
                        <div class="col-sm-2">
                            <label for="status" class=" control-label">得分:</label>
                        </div>
                    </div>
                    <?php
                        $rankScoreNum = (isset($data['rankScore']) && !empty($data['rankScore'])) ? count($data['rankScore']) : 1;
                        for ($i = 0; $i < $rankScoreNum; $i++) {
                    ?>
                        <div class="form-group" id="rankScore_<?= $i + 1 ?>">
                            <div class="col-sm-3">
                                <input type="text" class="form-control input-style" style="" name="ranks[]" value="<?= (isset($data['rankScore'][$i]['rank']) && !empty($data['rankScore'][$i]['rank'])) ? $data['rankScore'][$i]['rank'] : 1?>" onkeyup="value=value.replace(/[^\d\-]/ig,'')">
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control input-color" name="rankScore[]" value="<?= (isset($data['rankScore'][$i]['score']) && !empty($data['rankScore'][$i]['score'])) ? $data['rankScore'][$i]['score']:0 ?>">
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-5">
                            <a href="#" class="btn btn-success btn-sm radius-4">
                                <i class="entypo-plus"></i>
                            </a>
                            <a href="#" class="btn btn-danger btn-sm radius-4">
                                <i class="entypo-minus"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group default-padding form-button">
        <button type="submit" class="btn btn-success">保　存</button>
        <a href="<?= \Yii::$app->request->getReferrer()?>" class="btn btn-default">返　回</a>
    </div>

<script language="JavaScript">
    jQuery( document ).ready( function( $ ){
        $("a.btn-success").click(function () {
            var rankScoreNum = $("input[name='rankScore\[\]']").length;
            var newNum = rankScoreNum + 1;
            var newRankScoreId = 'rankScore_' + newNum;
            $("#rankScore_"+ rankScoreNum).after(
                "<div class='form-group' id=" + newRankScoreId + ">" +
                "<div class='col-sm-3'>" +
                "<input type='text' class='form-control input-style' style='width: 100px;float: right' name='ranks[]' value='' >" +
                "</div>" +
                "<div class='col-sm-4'>" +
                "<input type='text' class='form-control input-color' name='rankScore[]' value='0' >" +
                "</div></div>");
        });
        $("a.btn-danger").click(function () {
            var rankScoreNum = $("input[name='rankScore\[\]']").length;

            if (rankScoreNum <= 1) {
                layer.msg('至少1个', {time: 1000})
                return false;
            }
            $("#rankScore_" + rankScoreNum).remove()
        });
    });


</script>
<?php
ActiveForm::end();
Pjax::end();
?>
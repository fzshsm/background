<?php

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

AppAsset::registerCss($this, '@web/plugin/select2/select2.css');
AppAsset::registerJs($this, '@web/plugin/select2/select2.min.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/moment.min.js');
AppAsset::registerJs($this, '@web/plugin/fileinput.js');
AppAsset::registerJs($this , '@web/js/common.js');
AppAsset::registerJs($this, '@web/plugin/layer/layer.js');
Pjax::begin(['id' => 'team']);
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
    'id' => 'goods-form',
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
                    <?= isset($data['name']) ? "{$data['name']} 信息" : '创建奖金'?>
                </div>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label for="name" class="col-sm-3 control-label">奖金名</label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" autocomplete="false" name="name" required id="name" value="<?= isset($data['name'])?$data['name']:''?>" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="status" class="col-sm-3 control-label">商品状态</label>
                    <div class="col-sm-5">
                        <?= Html::dropDownList('currencyType',isset($data['currencyType'])?$data['currencyType']:1,[1 => '豆豆',2 => '狗粮'])?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="status" class="col-sm-3 control-label">名次(例:1或1-10):</label>
                    <div class="col-sm-2">
                        <label for="status" class=" control-label">奖励:</label>
                    </div>
                </div>
                <?php
                    $num = (isset($data['bonus']) && !empty($data['bonus'])) ? count($data['bonus']) : 1;
                    for ($i = 0; $i < $num; $i++) {
                ?>
                    <div class="form-group" id="rewards_<?= $i + 1 ?>">
                        <div class="col-sm-3">
                            <input type="text" class="form-control input-style" style="" name="ranks[]" value="<?= (isset($data['bonus']) && !empty($data['bonus'])) ? $data['bonus'][$i]['rank'] : 1?>" onkeyup="value=value.replace(/[^\d\-]/ig,'')">
                        </div>
                        <div class="col-sm-5">
                            <input type="text" class="form-control input-color" name="rewards[]" value="<?= (isset($data['bonus']) && !empty($data['bonus'])) ? $data['bonus'][$i]['bonus'] : 0?>" onkeyup="value=value.replace(/[^\-?\d.]/g,'')">
                        </div>
                    </div>
                <?php } ?>
                <div class="form-group bounty">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-5">
                        <a href="#" class="btn btn-success btn-lg radius-4">
                            <i class="entypo-plus"></i>
                        </a>
                        <a href="#" class="btn btn-danger btn-lg radius-4">
                            <i class="entypo-minus"></i>
                        </a>
                    </div>
                </div>

                <?= isset( $data['id'] ) ? Html::hiddenInput("id" , $data['id']) : '' ?>
            </div>
        </div>
    </div>
</div>
<div class="form-group default-padding form-button">
    <button type="submit" class="btn btn-success">保　存</button>
    <a href="<?= \Yii::$app->request->getReferrer()?>" class="btn btn-default">返　回</a>
</div>
<?php
ActiveForm::end();
Pjax::end();
?>
<script>
    $(document).ready(function(){
        $('form select').select2({
            minimumResultsForSearch: -1
        })

        $("a.btn-success").click(function () {
            var rewardNum = $("input[name='rewards\[\]']").length;
            var newNum = rewardNum + 1;
            var newRewardId = 'rewards_' + newNum;
            $("#" + "rewards_" + rewardNum).after(
                "<div class='form-group' id=" + newRewardId + ">" +
                "<div class='col-sm-3'>" +
                "<input type='text' class='form-control input-style' style='width: 100px;float: right' name='ranks[]' value='' >" +
                "</div>" +
                "<div class='col-sm-5'>" +
                "<input type='text' class='form-control input-color' name='rewards[]' value='' >" +
                "</div></div>");
        });
        $("a.btn-danger").click(function () {
            var rewardNum = $("input[name='rewards\[\]']").length;

            if (rewardNum <= 1) {
                layer.msg('至少1个', {time: 1000})
                return false;
            }
            $("#" + "rewards_" + rewardNum).remove()
        });
    });
    $(document).on('pjax:complete',function(){
        $('form select').select2({
            minimumResultsForSearch: -1
        })
    })


</script>


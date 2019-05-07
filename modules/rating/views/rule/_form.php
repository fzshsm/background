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
AppAsset::registerJs($this, '@web/plugin/fileinput.js');
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
                        赛事积分规则
                    </div>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="status" class="col-sm-2 control-label">规则纬度</label>
                        <div class="col-sm-6">
                            <?=Html::dropDownList('type' , isset($data['type']) ? $data['type'] : 1 , [1=>'主力',2=>'MVP',3=>'得分理由'] , ['data-allow-clear' => 'true' , 'id' => 'type','onchange' => 'getType()' ]);?>
                        </div>
                    </div>
                    <div class="form-group scoreOne">
                        <label for="scoreOne" class="col-sm-2 control-label" id="scoreOneName"></label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" autocomplete="false" name="scoreOne"  id="scoreOne" value="<?= isset($data['scoreOne'])?$data['scoreOne']:'' ?>" >
                        </div>
                    </div>
                    <div class="form-group scoreTwo">
                        <label for="scoreTwo" class="col-sm-2 control-label">否</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" autocomplete="false" name="scoreTwo"  id="scoreTwo" value="<?= isset($data['scoreTwo'])?$data['scoreTwo']:'' ?>" >
                        </div>
                    </div>
                    <div class="form-group remark">
                        <label for="remark" class="col-sm-2 control-label">备注</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" autocomplete="false" placeholder="不能为空"
                                   name="remark"  id="remark" value="<?= isset($data['remark'])?$data['remark']:'' ?>" >
                        </div>
                    </div>
                    <input type="hidden" name="pid" id="pid" value="<?= isset($data['pid'])?$data['pid']:\Yii::$app->request->get('id')?>">
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
        $('#userId').select2();
        $('#type').select2( {
            minimumResultsForSearch: -1
        });
        getType();
    });
    function getType() {
        var type = $("#type").val();

        if(type == 3){
            $("#scoreOneName").html('得分')
            $(".scoreTwo").hide();
            $(".scoreOne").show()
            $(".remark").show();
            $("#scoreTwo").val(0);

        }else{
            $("#scoreOneName").html('是')
            $(".remark").hide();
            $(".scoreOne").show();
            $(".scoreTwo").show();
            $("#remark").val('');
        }
    }

</script>
<?php
ActiveForm::end();
Pjax::end();
?>
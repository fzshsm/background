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
    'id' => 'club-form',
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
                    战队信息
                </div>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">战队名称</label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" autocomplete="false" name="name" required id="name" value="<?= isset($data['name'])?$data['name']:'' ?>" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="teamLogo" class="col-sm-2 control-label" data-validate="required">战队图标</label>
                    <div class="col-sm-7">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail" style="width: 320px; height: 200px;" data-trigger="fileinput">
                                <img src="<?= isset($data['icon']) && !empty($data['icon']) ? $data['icon'] : Url::to('@web/images/noimg.png')?>" alt="...">
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px"></div>
                            <span class="btn btn-white btn-file" style="display: none">
                                <input type="file" name="icon" id="icon" accept="image/*">
                            </span>
                            <input type="hidden" name="image"  value="<?= isset($data['icon'])?$data['icon']:'' ?>">
                            <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="teamType" class="col-sm-2 control-label" data-validate="required">战队类型</label>
                    <div class="col-sm-5">
                        <?=Html::dropDownList('type' , isset($data['type']) ? $data['type'] : 0 , $clubType, ['data-allow-clear' => 'true' , 'id' => 'type']);?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="freeExamine" class="col-sm-2 control-label" data-validate="required">免审核</label>
                    <div class="col-sm-5">
                        <?= Html::radioList('freeExamine',isset($data['freeExamine']) && !empty($data['freeExamine']) ? $data['freeExamine']:1,[1 => '否',2 => '是'],['class' => 'radio','itemOptions' =>['labelOptions' =>['style' =>'margin-left:2%','class' => 'radio checkbox-inline']]]) ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="teamDescription" class="col-sm-2 control-label" data-validate="required">战队描述</label>
                    <div class="col-sm-5">
                        <textarea name="desc" id="desc"  class="form-control autogrow" rows="5"><?= isset($data['desc']) ? $data['desc'] : '';?></textarea>
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
<script>
    jQuery( document ).ready( function( $ ){
        $('#type').select2();
    })

</script>
<?php
ActiveForm::end();
Pjax::end();
?>



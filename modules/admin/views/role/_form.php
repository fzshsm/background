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
        'id' => 'user-form',
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
                        角色信息
                    </div>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">角色名</label>
                        <div class="col-sm-5 ">
                            <input type="text" class="form-control" id="name" name="name" autocomplete="false" required value="<?= isset($data['name']) ? $data['name'] : '';?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reward" class="col-sm-3 control-label">描述</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="description" autocomplete="false" required id="description" value="<?= isset($data['description']) ? $data['description'] : '';?>" >
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


<?php
    ActiveForm::end();
    Pjax::end();
?>

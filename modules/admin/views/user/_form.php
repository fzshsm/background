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
                        用户信息
                    </div>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">用户名</label>
                        <div class="col-sm-5 ">
                            <input type="text" class="form-control" id="name" name="name" autocomplete="false" required value="<?= isset($data['name']) ? $data['name'] : '';?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-group">
                            <label for="status" class="col-sm-3 control-label">角色</label>
                            <div class="col-sm-5">
                                <?=Html::dropDownList('role_id' , isset($data['role_id']) ? $data['role_id'] : 0 , $roleList , ['data-allow-clear' => 'true' , 'id' => 'role_id' ]);?>
                            </div>
                        </div>
                    </div>
                    <?php if(!isset($type) ){ ?>
                        <div class="form-group">
                            <label for="reward" class="col-sm-3 control-label">密码</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="password" autocomplete="false" required id="password" value="<?= isset($data['password']) ? $data['password'] : '';?>" >
                            </div>
                        </div>
                    <?php }?>
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
        $('form select').select2( {
            minimumResultsForSearch: -1
        });
    });
</script>
<?php
    ActiveForm::end();
    Pjax::end();
?>

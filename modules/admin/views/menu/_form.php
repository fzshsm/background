<?php

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

AppAsset::registerJs($this, '@web/plugin/select2/select2.min.js');
AppAsset::registerCss($this, '@web/plugin/select2/select2.css');
AppAsset::registerCss($this , '@web/plugin/daterangepicker/daterangepicker-bs3.css');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/moment.min.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/daterangepicker.js');

Pjax::begin(['id' => 'admin']);
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
        'id' => 'menu-form',
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
                        菜单信息
                    </div>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">菜单名</label>
                        <div class="col-sm-7 ">
                            <input type="text" class="form-control" id="name" name="name" autocomplete="false" required value="<?= isset($data['name']) ? $data['name'] : '';?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-group">
                            <label for="status" class="col-sm-3 control-label">父级菜单</label>
                            <div class="col-sm-7">
                                <?=Html::dropDownList('parent_id' , isset($data['parent_id']) ? $data['parent_id'] : 0 , $menuList , ['data-allow-clear' => 'true' , 'id' => 'parent_id' ]);?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reward" class="col-sm-3 control-label">路径</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="url" autocomplete="false" required id="url" value="<?= isset($data['url']) ? $data['url'] : '';?>" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reward" class="col-sm-3 control-label">样式属性(父级菜单)</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="class" autocomplete="false"  id="class" value="<?= isset($data['class']) ? $data['class'] : '';?>" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sortWeight" class="col-sm-3 control-label">排序</label>
                        <div class="col-sm-5">
                            <div class="input-spinner">
                                <button type="button" class="btn btn-default btn-sm">-</button>
                                <input type="text" class="form-control size-1" data-min="0" name="rating" value="<?= isset($data['rating']) ? $data['rating'] : 0;?>">
                                <button type="button" class="btn btn-default btn-sm">+</button>
                            </div>
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
        $('form select').select2( {
            minimumResultsForSearch: -1
        });
    });
</script>
<?php
    ActiveForm::end();
    Pjax::end();
?>

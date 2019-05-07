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
    'id' => 'message-form',
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
                    创建推送信息
                </div>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label for="name" class="col-sm-3 control-label">消息标题</label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" autocomplete="false" name="title" required id="title" value="" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="name" class="col-sm-3 control-label">内容</label>
                    <div class="col-sm-5">
                        <textarea name="content" id="content" required class="form-control autogrow" ></textarea>
                    </div>
                </div>
<!--                <div class="form-group">-->
<!--                    <label for="teamLogo" class="col-sm-3 control-label" data-validate="required">分享图片</label>-->
<!--                    <div class="col-sm-7">-->
<!--                        <div class="fileinput fileinput-new" data-provides="fileinput">-->
<!--                            <div class="fileinput-new thumbnail" style="width: 320px; height: 200px;" data-trigger="fileinput">-->
<!--                                <img src="--><?//= Url::to('@web/images/noimg.png')?><!--" alt="...">-->
<!--                            </div>-->
<!--                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px"></div>-->
<!--                            <span class="btn btn-white btn-file" style="display: none">-->
<!--                                <input type="file" name="icon" id="icon" accept="image/*">-->
<!--                            </span>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
                <div class="form-group">
                    <label for="teamType" class="col-sm-3 control-label" data-validate="required">返回APP页面</label>
                    <div class="col-sm-5">
                        <?=Html::dropDownList('msgType' , 0, [0=> '提示',1=>'新闻',2=>'招募'], [ 'id' => 'type','onchange' => 'getType()']);?>
                    </div>
                </div>
                <div class="form-group" id="hidden_type">
                    <label for="teamDescription" class="col-sm-3 control-label" data-validate="required" id="type_name"></label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" autocomplete="false" name="bizId" required id="bizId" value="">
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
        $('#type').select2({
            minimumResultsForSearch: -1
        });
        getType()
    })

    function getType(){
        var type = $("#type").val();

        $("#hidden_type").show();
        $("#bizId").val('')
        if(type == 1){
            $("#type_name").html('新闻ID(去新闻列表查找)');
        }else if(type == 2){
            $("#type_name").html('招聘ID(去招聘列表查找)');
        }else{
            $("#hidden_type").hide()
            $("#bizId").val(0)
        }
    }

</script>
<?php
ActiveForm::end();
Pjax::end();
?>



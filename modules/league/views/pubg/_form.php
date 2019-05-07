<?php

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

AppAsset::registerCss($this, '@web/plugin/select2/select2.css');
AppAsset::registerJs($this, '@web/plugin/select2/select2.min.js');
AppAsset::registerJs($this, '@web/plugin/fileinput.js');
AppAsset::registerCss( $this , '@web/plugin/datatables/datatables.min.css' );
AppAsset::registerCss($this , '@web/plugin/daterangepicker/daterangepicker-bs3.css');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/moment.min.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/daterangepicker.js');
AppAsset::registerJs($this, '@web/plugin/layer/layer.js');
AppAsset::registerJs($this, '@web/plugin/jquery.bootstrap.wizard.min.js');
AppAsset::registerJs($this,'@web/plugin/jquery.validate.min.js');
AppAsset::registerJs($this , '@web/js/common.js');
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
        'id' => 'match-form',
        'method' => 'post',
        'options' => [
            'enctype' => 'multipart/form-data',
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
                        编辑绝地求生联赛信息
                    </div>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">名称</label>
                        <div class="col-sm-5 ">
                            <input type="text" class="form-control" id="leagueName" name="leagueName" value="<?= isset($data['leagueName']) ? $data['leagueName'] : '';?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="type" class="col-sm-3 control-label">联赛分类</label>
                        <div class="col-sm-5">
                            <?= Html::dropDownList('leagueCategory',isset($data['leagueCategory']) ? $data['leagueCategory'] : 1, $leagueSorts)?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="type" class="col-sm-3 control-label">联赛类型</label>
                        <div class="col-sm-5">
                            <?= Html::dropDownList('leagueModel',isset($data['leagueModel']) ? $data['leagueModel'] : 1, $leagueTypes,['id' => 'leagueModel'])?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="freeTrial" class="col-sm-3 control-label">免审核</label>
                        <div class="col-sm-5">
                            <?= Html::radioList('freeTrial',isset($data['freeTrial']) ? $data['freeTrial'] : 0,['0' => '否','1' => '是'],
                                ['class' => 'radio','itemOptions' =>['labelOptions' =>['style' =>'margin-left:2%;','class' => 'checkbox-inline']]]) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="validatePlayers" class="col-sm-3 control-label">是否踢人</label>
                        <div class="col-sm-5">
                            <?= Html::radioList('validatePlayers',isset($data['validatePlayers']) ? $data['validatePlayers'] : 0,['0' => '是','1' => '否'],
                                ['class' => 'radio','itemOptions' =>['labelOptions' =>['style' =>'margin-left:2%;','class' => 'checkbox-inline']]]) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="voice" class="col-sm-3 control-label">语音</label>
                        <div class="col-sm-5">
                            <?= Html::radioList('voice',isset($data['voice']) ? $data['voice'] : 0, ['0' => '不开启','1' => '开启'],
                                ['class' => 'radio','itemOptions' =>['labelOptions' =>['style' =>'margin-left:2%','class' => 'checkbox-inline']]]) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="medal" class="col-sm-3 control-label">队伍人数</label>
                        <div class="col-sm-5">
                            <?=Html::checkboxList('teamAllowCount',(isset($teamAllowCount) && !empty($teamAllowCount)) ? $teamAllowCount : 4,[1 => '单排',2 => '双排' ,3 => '三排', 4 => '四排'],['class'=>'form-control checkbox' ,'style' => 'height:50px', 'itemOptions' =>['labelOptions' =>['style' =>'margin-left:2%','class' => 'checkbox-inline']]]);?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cover" class="col-sm-3 control-label" data-validate="required">封面</label>
                        <div class="col-sm-5">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;" data-trigger="fileinput">
                                    <img src="<?= isset($data['cover']) && !empty($data['cover']) ? $data['cover'] : Url::to('@web/images/200x200.png')?>" alt="...">
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px"></div>
                                    <span class="btn btn-white btn-file" style="display: none">
                                        <input type="file" name="cover" accept="image/*" onchange="checkUploadImage(this)">
                                    </span>
                                    <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
                            </div>
                        </div>
                    </div>
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
                        <label for="sortWeight" class="col-sm-3 control-label">排序权重</label>
                        <div class="col-sm-5">
                            <div class="input-spinner">
                                <button type="button" class="btn btn-default btn-sm">-</button>
                                <input type="text" class="form-control size-1" data-min="0" name="sortWeight" value="<?= isset($data['sortWeight']) ? $data['sortWeight'] : 0;?>">
                                <button type="button" class="btn btn-default btn-sm">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reward" class="col-sm-3 control-label">奖金</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="reward" id="reward" value="<?= isset($data['reward']) ? $data['reward'] : '';?>" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sponsor" class="col-sm-3 control-label">举办方</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="sponsor" id="sponsor" value="<?= isset($data['sponsor']) ? $data['sponsor'] : '';?>" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="leagueDescribe" class="col-sm-3 control-label">简介</label>
                        <div class="col-sm-5">
                            <textarea id="leagueDescribe" name="leagueDescribe" class="form-control autogrow" rows="10" ><?= isset($data['leagueDescribe']) ? $data['leagueDescribe'] : '';?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="status" class="col-sm-3 control-label">状态</label>
                        <div class="col-sm-5">
                            <?=Html::dropDownList('status' , isset($data['status']) ? $data['status'] : 1 , [ 1 => '未开始' , 2 => '进行中' , 3 => '已关闭', 4 => '关闭且完成结算'],['id' => 'status']);?>
                        </div>
                    </div>
                    <?= isset( $data['id'] ) ? Html::hiddenInput("id" , $data['id']) : ''?>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group default-padding form-button">
        <button type="submit" class="btn btn-success" onclick="return check()">保　存</button>
        <a href="<?= \Yii::$app->request->getReferrer() ?>" class="btn btn-default">返　回</a>
    </div>
<script language="JavaScript">
    jQuery( document ).ready( function( $ ){
        controlStatus = $("#controlStatus").val();
        $('form select').select2( {
            minimumResultsForSearch: -1
        });
        $('#match a').click(function(e){
            e.preventDefault();
            location.href = $(this).attr('href');
        });
    });

    function check(){
        var teamAllowCount = $('input[type=checkbox]:checked').length;
        if(teamAllowCount < 1){
            layer.alert('队伍人数不能为空')
            return false;
        }
        return true;
    }

</script>
<?php
    ActiveForm::end();
    Pjax::end();
?>

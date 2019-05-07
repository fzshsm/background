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
AppAsset::registerJs($this , '@web/js/common.js');
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
                    <?= isset($data['roomCardName']) ? "{$data['roomCardName']} 信息" : '创建房卡'?>
                </div>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label for="name" class="col-sm-3 control-label">房卡名称</label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" autocomplete="false" name="roomCardName" required id="roomCardName" value="<?= isset($data['roomCardName'])?$data['roomCardName']:''?>" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="teamLogo" class="col-sm-3 control-label" data-validate="required">商品图(120*120)</label>
                    <div class="col-sm-7">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail" style="width: 120px; height: 120px;" data-trigger="fileinput">
                                <img src="<?= isset($data['roomCardIcon']) && !empty($data['roomCardIcon']) ? $data['roomCardIcon'] : Url::to('@web/images/noimg.png')?>" alt="...">
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px"></div>
                            <span class="btn btn-white btn-file" style="display: none">
                                <input type="file" name="roomCardIcon" id="roomCardIcon" accept="image/*" onchange="checkUploadImage(this)">
                            </span>
                            <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="describe" class="col-sm-3 control-label">描述</label>
                    <div class="col-sm-5">
                        <textarea id="roomCardDesc" name="roomCardDesc" class="form-control autogrow" rows="5" ><?= isset($data['roomCardDesc']) ? $data['roomCardDesc'] : '';?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="status" class="col-sm-3 control-label">房卡类型</label>
                    <div class="col-sm-5">
                        <?= Html::dropDownList('roomCardType',isset($data['roomCardType'])?$data['roomCardType']:1,[3 => '无限制',2 => '游戏',1 => '联赛'],['onchange' => 'getRoomCardType()','id' => 'roomCardType'])?>
                    </div>
                </div>
                <div class="form-group" id="display_game">
                    <label for="voice" class="col-sm-3 control-label">游戏</label>
                    <div class="col-sm-5">
                        <?= Html::radioList('gameType',isset($data['gameType']) ? $data['gameType'] : 1, [1 => '王者荣耀', 2 => '绝地求生'],
                            ['class' => 'radio','itemOptions' =>['labelOptions' =>['style' =>'margin-left:2%','class' => 'checkbox-inline']],'onchange' => 'getLeague()']) ?>
                    </div>
                </div>
                <div class="form-group" id="display_league">
                    <label for="status" class="col-sm-3 control-label">联赛</label>
                    <div class="col-sm-5">
                        <?= Html::dropDownList('leagueId',isset($data['leagueId'])?$data['leagueId'] :0,$leagueList,['id' => 'leagueId']) ?>
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
        $('#roomCardType').select2({
            minimumResultsForSearch: -1
        })
        $('#leagueId').select2({});
        getRoomCardType();
    });
    $(document).on('pjax:complete',function(){
        $('#roomCardType').select2({
            minimumResultsForSearch: -1
        })
        $('#leagueId').select2({})
        getRoomCardType();
    })

    function getRoomCardType(){
        var roomCardType = $("#roomCardType").val();
        console.log(roomCardType)
        if(roomCardType == 1){
            $("#display_game").show();
            $("#display_league").show();
            getLeague();
        }else if(roomCardType == 2){
            $("#display_league").hide();
            $("#display_game").show();
        }else if(roomCardType == 3){
            $("#display_game").hide();
            $("#display_league").hide()
        }
    }

    function getLeague(){
        var roomCardType = $("#roomCardType").val();

        if(roomCardType != 1){
            return false;
        }

        var gameType = $('input[name="gameType"]:checked').val();

        $.ajax({
            url:'<?= Url::to(['/mall/roomcard/league'])?>',
            data:{gameType:gameType},
            dataType:'json',
            success:function(res){
                if(res.status == 'success'){
                    var leagueList = res.result;

                    $("#leagueId").empty();
                    var defaultValue = '';
                    for(var i=0;i<leagueList.length;i++){
                        if(i == 0){
                            defaultValue = leagueList[i]['id'];
                        }
                        $("#leagueId").append("<option value="+leagueList[i]['id']+">"+leagueList[i]['name']+"</option>")
                    }
                    $("#leagueId").val(defaultValue).trigger('change')
                }
            }
        })
    }

</script>


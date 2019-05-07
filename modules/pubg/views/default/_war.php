<?php

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

AppAsset::registerCss($this, '@web/plugin/select2/select2.css');
AppAsset::registerJs($this, '@web/plugin/select2/select2.min.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/moment.min.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/moment.min.js');
AppAsset::registerJs($this, '@web/plugin/layer/layer.js');
Pjax::begin(['id' => 'custom']);
?>
<div class="row">
    <div class="col-md-8">
        <?php if(Yii::$app->session->hasFlash('dataError')){ ?>
            <script>layer.close(controlLayer);</script>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?=Yii::$app->session->getFlash('dataError')?>
            </div>
        <?php }?>
        <?php if(Yii::$app->session->hasFlash('error')){ ?>
            <script>layer.close(controlLayer);</script>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?=Yii::$app->session->getFlash('error')?>
            </div>
        <?php }?>
        <?php if(Yii::$app->session->hasFlash('success')){?>
            <script>layer.close(controlLayer);</script>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?=Yii::$app->session->getFlash('success')?>
            </div>
        <?php }?>
    </div>
</div>
<style>
    .td-input{width: 60%;text-align: center;}
</style>
<?php
//$form = ActiveForm::begin([
//    'id' => 'custom-form',
//    'method' => 'post',
//    'options' => [
//        'data-pjax' => true,
//        'role' => 'form',
//        'class' => 'form-horizontal form-groups-bordered validate'
//    ]
//] );
?>
<form id="news-form" method="post" class="form-horizontal form-groups-bordered validate"  enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-4">
                <div class="panel panel-primary" data-collapsed="0">
                    <div class="panel-heading">
                        <div class="panel-title">
                            创建自定义房间
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="name" class="col-sm-3 control-label">自定义配置名称</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="name" name="name" value="<?= isset($data['name']) ? $data['name'] : '';?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="leagueDescribe" class="col-sm-3 control-label">简介</label>
                            <div class="col-sm-8">
                                <textarea id="remark" name="remark" class="form-control autogrow" rows="5" ><?= isset($data['remark']) ? $data['remark'] : '';?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="reward" class="col-sm-3 control-label">游戏模式</label>
                            <div class="col-sm-8">
                                <?=Html::dropDownList('mode' , isset($data['mode']) ? $data['mode'] : 1 , [ 1 => '普通模式', 2 => '丧尸模式', 3 =>'战争模式'],
                                    ['onchange' => 'getMode()', 'id' => 'mode']);?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="reward" class="col-sm-3 control-label">服务器</label>
                            <div class="col-sm-8">
                                <?=Html::dropDownList('Region' , isset($data['Region']) ? $data['Region'] : 'As Server' ,
                                    $regionList);?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="reward" class="col-sm-3 control-label">地图</label>
                            <div class="col-sm-8">
                                <?=Html::dropDownList('MapId' , isset($data['MapId']) ? $data['MapId'] : 1 , [ 1 => '海岛',2 => '沙漠', 3 => '米拉马尔']);?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="reward" class="col-sm-3 control-label">天气</label>
                            <div class="col-sm-8">
                                <?=Html::dropDownList('MapOpt' , isset($data['MapOpt']) ? $data['MapOpt'] : 1 , [ 'Clear' => '晴天', 'Rainy' => '雨天', 'Foggy' => '雾','Dark' => '黄昏']);?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="reward" class="col-sm-3 control-label">视角模式</label>
                            <div class="col-sm-8">
                                <?=Html::dropDownList('FPSOnly' , isset($data['FPSOnly']) ? $data['FPSOnly'] : 2 , [ '1' => '第一人称视角','2' => '第三人称视角']);?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="panel panel-primary" data-collapsed="0">
                    <div class="panel-body">
                        <ul class="nav nav-tabs border"><!-- available classes "bordered", "right-aligned" -->
                            <li class="active">
                                <a href="#basic_options" data-toggle="tab">
                                    <span>基础配置</span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" >
                            <div id="basic_options" class="tab-pane fade in active col-md-8">
                                <div class="form-group">
                                    <label class="control-label pubg-custom-label">玩家</label>
                                    <div class="slider slider-green pubg-custom-slide" id="slider-MaxPlayers" data-min="16" data-max="100" data-value="<?= isset($data['MaxPlayers']) ? $data['MaxPlayers'] : 24 ?>" data-fill="#MaxPlayers"></div>
                                    <input type="hidden" class="form-control" id="MaxPlayers" name="MaxPlayers" value="<?= isset($data['MaxPlayers']) ? $data['MaxPlayers'] : 24 ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label pubg-custom-label">组队玩家</label>
                                    <div class="slider slider-green pubg-custom-slide" data-min="2" data-max="10" data-value="<?= isset($data['TeamSize']) ? $data['TeamSize'] : 8 ?>" data-fill="#TeamSize" ></div>
                                    <input type="hidden" class="form-control" id="TeamSize" name="TeamSize" value="<?= isset($data['TeamSize']) ? $data['TeamSize'] : 8 ?>" />
                                </div>

                                <div class="form-group">
                                    <label class="control-label pubg-custom-label">目标分数</label>
                                    <div class="slider slider-green pubg-custom-slide" data-min="50" data-max="500" data-value="<?= isset($data['GoalScore']) ? $data['GoalScore'] : 150 ?>" data-fill="#GoalScore" ></div>
                                    <input type="hidden" class="form-control" id="GoalScore" name="GoalScore" value="<?= isset($data['GoalScore']) ? $data['GoalScore'] : 150 ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label pubg-custom-label">时间限制</label>
                                    <div class="slider slider-green pubg-custom-slide" data-min="300" data-max="1800" data-postfix="s" data-value="<?= isset($data['TimeLimit']) ? $data['TimeLimit'] : 900 ?>" data-fill="#TimeLimit" ></div>
                                    <input type="hidden" class="form-control" id="TimeLimit" name="TimeLimit" value="<?= isset($data['TimeLimit']) ? $data['TimeLimit'] : 900 ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2">团队殊死战</label>
                                    <div class="col-sm-5 pubg-custom-radio ">
                                        <?= Html::radioList('TeamElimination',isset($data['TeamElimination']) ? $data['TeamElimination'] : 1,['1' => '是','2' => '否'],
                                            ['class' => 'radio','itemOptions' =>['labelOptions' =>['style' =>'margin-left:2%;','class' => 'checkbox-inline']]]) ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Dbno" class="col-sm-2">击倒后施救</label>
                                    <div class="col-sm-5 pubg-custom-radio">
                                        <?= Html::radioList('Dbno',isset($data['Dbno']) ? $data['Dbno'] : 1,['1' => '是','2' => '否'],
                                            ['class' => 'radio','itemOptions' =>['labelOptions' =>['style' =>'margin-left:2%;','class' => 'checkbox-inline']],
                                                'onchange' => 'getDbno()','id' => 'Dbno']) ?>
                                    </div>
                                </div>
                                <div class="display_dbno">
                                    <label>击倒后施救时间</label>
                                    <div class="slider slider-green" data-min="1" data-max="20" data-postfix=" s" data-value="<?= isset($data['ReviveCastingTime']) ? $data['ReviveCastingTime'] : 10 ?>" data-fill="#ReviveCastingTime"></div>
                                    <input type="hidden" class="form-control" id="ReviveCastingTime" name="ReviveCastingTime" value="<?= isset($data['ReviveCastingTime']) ? $data['ReviveCastingTime'] : '10s' ?>" />
                                </div>
                                <div class="display_dbno">
                                    <label class="control-label">被击倒时每秒扣除的血量</label>
                                    <div class="slider slider-green" data-min="1" data-max="20"  data-value="<?= isset($data['GroggyDamagePerSecond']) ? $data['GroggyDamagePerSecond'] : 5?>" data-fill="#GroggyDamagePerSecond"></div>
                                    <input type="hidden" class="form-control" id="GroggyDamagePerSecond" name="GroggyDamagePerSecond" value="<?= isset($data['GroggyDamagePerSecond']) ? $data['GroggyDamagePerSecond'] : 5 ?>" />
                                </div>
                                <div class="display_dbno">
                                    <label class="control-label">复活后的生命值</label>
                                    <div class="slider slider-green" data-min="10" data-max="70"  data-value="<?= isset($data['HealthByRevive']) ? $data['HealthByRevive'] : 50?>" data-fill="#HealthByRevive"></div>
                                    <input type="hidden" class="form-control" id="HealthByRevive" name="HealthByRevive" value="<?= isset($data['HealthByRevive']) ? $data['HealthByRevive'] : 50 ?>" />
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4">蓝色区域</label>
                                    <div class="col-sm-4 pubg-custom-radio">
                                        <?= Html::radioList('BlueZoneStatic',isset($data['BlueZoneStatic']) ? $data['BlueZoneStatic'] : 1,['1' => '固定','2' => 'war royale'],
                                            ['class' => 'radio','itemOptions' =>['labelOptions' =>['style' =>'margin-left:2%;','class' => 'checkbox-inline']],'onchange' => 'getBlueZoneStatic()']) ?>
                                    </div>
                                </div>
                                <div class=" display_bluezone_air">
                                    <label class="control-label ">蓝圈大小</label>
                                    <div class="slider slider-green " data-min="0.02" data-max="0.5"  data-value="<?= isset($data['BlueZoneSize']) ? $data['BlueZoneSize'] : 0.05 ?>"  data-step="0.01" data-fill="#BlueZoneSize"></div>
                                    <input type="hidden" class="form-control" id="BlueZoneSize" name="BlueZoneSize" value="<?= isset($data['BlueZoneSize']) ? $data['BlueZoneSize'] : '0.05' ?>" />
                                </div>

                                <div class="display_bluezone_royale">
                                    <label class="control-label ">关闭复活(需复活时间比率)</label>
                                    <div class="slider slider-green " data-min="0" data-max="0.7"  data-value="<?= isset($data['RespawnOffTimeLeftRatio']) ? $data['RespawnOffTimeLeftRatio'] : 0.2 ?>"  data-step="0.1" data-fill="#RespawnOffTimeLeftRatio"></div>
                                    <input type="hidden" class="form-control" id="RespawnOffTimeLeftRatio" name="RespawnOffTimeLeftRatio" value="<?= isset($data['RespawnOffTimeLeftRatio']) ? $data['RespawnOffTimeLeftRatio'] : '0.2' ?>" />
                                </div>

                                <div class="form-group" style="margin-top: 1%">
                                    <label class="col-sm-4">重生类型</label>
                                    <div class="col-sm-4 pubg-custom-radio">
                                        <?= Html::radioList('RespawnType',isset($data['RespawnType']) ? $data['RespawnType'] : 'AIR',['AIR' => 'AIR'],
                                            ['class' => 'radio','itemOptions' =>['labelOptions' =>['style' =>'margin-left:2%;','class' => 'checkbox-inline']]]) ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label  pubg-custom-label">重生时限</label>
                                    <div class="slider slider-green pubg-custom-slide" data-min="30" data-max="180"  data-value="<?= isset($data['RespawnPeriod']) ? $data['RespawnPeriod'] : 40 ?>"  data-fill="#RespawnPeriod"></div>
                                    <input type="hidden" class="form-control" id="RespawnPeriod" name="RespawnPeriod" value="<?= isset($data['RespawnPeriod']) ? $data['RespawnPeriod'] : '40' ?>" />
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4">重生装备</label>
                                    <div class="col-sm-8">
                                        <?=Html::dropDownList('RespawnEquipment' , isset($data['RespawnEquipment']) ? $data['RespawnEquipment'] : 'CQB' , [ 'CQB' => 'CQB','SMG' => 'SMG', 'Soldier' => 'Soldier',
                                        'Sniper' => 'Sniper', 'Random' => 'Random', 'Crossbow' => 'Crossbow', 'Western' => 'Western','Overpower' => 'Overpower','Bomb' => 'Bomb','Melee' => 'Melee',
                                        'Frypan' => 'Frypan','AR' => 'AR','Pistols' => 'Pistols','Shotguns' => 'Shotguns']);?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4">空投包裹类型</label>
                                    <div class="col-sm-8">
                                        <?=Html::dropDownList('CarePackageType' , isset($data['CarePackageType']) ? $data['CarePackageType'] : 'Basic' , ['Basic' => 'Basic', 'AR Kit' => 'AR Kit', 'SR Kit' => 'SR Kit', 'Off' => 'Off']);?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label  pubg-custom-label">下次空投包裹类型</label>
                                    <div class="slider slider-green pubg-custom-slide" data-min="30" data-max="300" data-postfix=" s"   data-value="<?= isset($data['CarePackagePeriod']) ? $data['CarePackagePeriod'] : 90 ?>"  data-fill="#CarePackagePeriod"></div>
                                    <input type="hidden" class="form-control" id="CarePackagePeriod" name="CarePackagePeriod" value="<?= isset($data['CarePackagePeriod']) ? $data['CarePackagePeriod'] : '90s' ?>" />
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group default-padding form-button">
        <button type="submit" class="btn btn-success customSave">保　存</button>
        <a href="<?= \Yii::$app->request->getReferrer()?>" class="btn btn-default">返　回</a>
    </div>
    <input type="hidden" name="id" value="<?= isset($data['id']) ? $data['id'] : '' ?>" id="customId">
    <input  type="hidden" name="_csrf" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
</form>

<script language="JavaScript">
    var controlLayer;
    jQuery( document ).ready( function( $ ) {
        $('form select').select2({
            minimumResultsForSearch: -1
        });
        getDbno();
        getBlueZoneStatic()
    })

    function getDbno(){
        var Dbno = $("input[name='Dbno']:checked").val()

        if(Dbno == 1){
            $(".display_dbno").show()
        }else{
            $(".display_dbno").hide()
        }
    }

    function getMode(){
        var mode = $("#mode").val()
        var customId  = $("#customId").val();
        if(mode != 3){
            if(customId == ''){
                window.location.href='<?= Url::to(['/pubg/create','mode' => 1])?>'
            }else{
                window.location.href='<?= Url::to(['/pubg/update','mode' => 1,'id' => isset($data['id']) ? $data['id'] : ''])?>'
            }
        }
    }

    function getBlueZoneStatic(){
        var blueZoneStatic = $("input[name='BlueZoneStatic']:checked").val()

        if(blueZoneStatic == 1){
            $(".display_bluezone_air").show()
            $(".display_bluezone_royale").hide()
        }else{
            $(".display_bluezone_air").hide()
            $(".display_bluezone_royale").show()
        }
    }

    $('.customSave').on('click',function(){
        controlLayer = layer.load(2,{shade:[0.4,'#fff']})
    })


</script>

<?php
//ActiveForm::end();
Pjax::end();
?>

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
                                <?=Html::dropDownList('mode' , isset($data['mode']) ? $data['mode'] : 1 , [ 1 => '普通模式', 2 => '丧尸模式', 3 =>'战争模式',4 => '电竞模式'],
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
                                <?=Html::dropDownList('MapId' , isset($data['MapId']) ? $data['MapId'] : 1 , [ 1 => '海岛',2 => '沙漠',3 => '米拉马尔']);?>
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
                        <div class="form-group" id="display_zombie">
                            <label for="reward" class="col-sm-3 control-label">丧尸视角模式</label>
                            <div class="col-sm-8">
                                <?=Html::dropDownList('ZombieCameraView' , isset($data['ZombieCameraView']) ? $data['ZombieCameraView'] : 'TpsOnly' , [ 'FpsOnly' => '第一人称视角','TpsOnly' => '第三人称视角']);?>
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
                            <li >
                                <a href="#advanced_options" data-toggle="tab">
                                    <span>高级配置</span>
                                </a>
                            </li>

                        </ul>
                        <div class="tab-content" >
                            <div id="basic_options" class="tab-pane fade in active col-md-8">
                                <div class="form-group">
                                    <label class="control-label pubg-custom-label">队员数</label>
                                    <div class="slider slider-green pubg-custom-slide" id="slider-MaxPlayers" data-min="32" data-max="130" data-value="<?= isset($data['MaxPlayers']) ? $data['MaxPlayers'] : 100 ?>" data-fill="#MaxPlayers"></div>
                                    <input type="hidden" class="form-control" id="MaxPlayers" name="MaxPlayers" value="<?= isset($data['MaxPlayers']) ? $data['MaxPlayers'] : 100 ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label pubg-custom-label">组队人数</label>
                                    <div class="slider slider-green pubg-custom-slide" data-min="1" data-max="10" data-value="<?= isset($data['TeamSize']) ? $data['TeamSize'] : 4 ?>" data-fill="#TeamSize" ></div>
                                    <input type="hidden" class="form-control" id="TeamSize" name="TeamSize" value="<?= isset($data['TeamSize']) ? $data['TeamSize'] : 4 ?>" />
                                </div>
                                <div class="form-group">
                                    <label for="Dbno" class="col-sm-2">复活</label>
                                    <div class="col-sm-5 pubg-custom-radio">
                                        <?= Html::radioList('Dbno',isset($data['Dbno']) ? $data['Dbno'] : 1,['1' => '是','2' => '否'],
                                            ['class' => 'radio','itemOptions' =>['labelOptions' =>['style' =>'margin-left:2%;','class' => 'checkbox-inline']],
                                                'onchange' => 'getDbno()','id' => 'Dbno']) ?>
                                    </div>
                                </div>
                                <div class="display_dbno">
                                    <label>复活时间</label>
                                    <div class="slider slider-green" data-min="1" data-max="20" data-postfix=" s" data-value="<?= isset($data['ReviveCastingTime']) ? $data['ReviveCastingTime'] : 10 ?>" data-fill="#ReviveCastingTime"></div>
                                    <input type="hidden" class="form-control" id="ReviveCastingTime" name="ReviveCastingTime" value="<?= isset($data['ReviveCastingTime']) ? $data['ReviveCastingTime'] : '10s' ?>" />
                                </div>
                                <div class="display_dbno">
                                    <label class="control-label">击倒伤害</label>
                                    <div class="slider slider-green" data-min="0.1" data-max="2" data-postfix=" x" data-step="0.1" data-value="<?= isset($data['MultiplierPunchDamage']) ? $data['MultiplierPunchDamage'] : 1?>" data-fill="#MultiplierPunchDamage"></div>
                                    <input type="hidden" class="form-control" id="MultiplierPunchDamage" name="MultiplierPunchDamage" value="<?= isset($data['MultiplierPunchDamage']) ? $data['MultiplierPunchDamage'] : '1x' ?>" />
                                </div>

                                <label>缩圈速度</label>
                                <div class="slider slider-green" data-min="1" data-max="2" data-postfix=" x" data-value="<?= isset($data['PlayzoneProgress']) ? $data['PlayzoneProgress'] : 1 ?>" data-step="0.1" data-fill="#PlayzoneProgress" style="margin-left: 1%;margin-top: 1%;"></div>
                                <input type="hidden" class="form-control" id="PlayzoneProgress" name="PlayzoneProgress" value="<?= isset($data['PlayzoneProgress']) ? $data['PlayzoneProgress'] : '1x' ?>" />
                                <div>
                                    <table class="table responsive">
                                        <thead>
                                        <tr>
                                            <th class="col-sm-1">#</th>
                                            <th class="col-sm-1">延迟(s)</th>
                                            <th class="col-sm-1">等待(s)</th>
                                            <th class="col-sm-1">移动(s)</th>
                                            <th class="col-sm-1">每秒伤害</th>
                                            <th class="col-sm-1">收缩速度</th>
                                            <th class="col-sm-1">扩张速度</th>
                                            <th class="col-sm-1">土地比率</th>
                                            <th class="col-sm-1">安全区空投</th>
                                            <th class="col-sm-1">安全区外空投</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>第一圈</td>
                                            <td><input class="td-input" type="text" name="Phase1_StartDelay" value="120"></td>
                                            <td><input class="td-input" type="text" name="Phase1_WarningDuration" value="300"></td>
                                            <td><input class="td-input" type="text" name="Phase1_ReleaseDuration" value="300"></td>
                                            <td><input class="td-input" type="text" name="Phase1_GasDamagePerSecond" value="0.4"></td>
                                            <td><input class="td-input" type="text" name="Phase1_RadiusRate" value="0.4"></td>
                                            <td><input class="td-input" type="text" name="Phase1_SpreadRatio" value="0.5"></td>
                                            <td><input class="td-input" type="text" name="Phase1_LandRatio" value="0"></td>
                                            <td><input class="td-input" type="text" name="Phase1_AddWhiteZoneCarePackage" value="0"></td>
                                            <td><input class="td-input" type="text" name="Phase1_AddOutsideZoneCarePackage" value="8"></td>
                                        </tr>
                                        <tr>
                                            <td>第二圈</td>
                                            <td><input class="td-input" type="text" name="Phase2_StartDelay" value="0"></td>
                                            <td><input class="td-input" type="text" name="Phase2_WarningDuration" value="200"></td>
                                            <td><input class="td-input" type="text" name="Phase2_ReleaseDuration" value="140"></td>
                                            <td><input class="td-input" type="text" name="Phase2_GasDamagePerSecond" value="0.6"></td>
                                            <td><input class="td-input" type="text" name="Phase2_RadiusRate" value="0.65"></td>
                                            <td><input class="td-input" type="text" name="Phase2_SpreadRatio" value="0.5"></td>
                                            <td><input class="td-input" type="text" name="Phase2_LandRatio" value="0"></td>
                                            <td><input class="td-input" type="text" name="Phase2_AddWhiteZoneCarePackage" value="2"></td>
                                            <td><input class="td-input" type="text" name="Phase2_AddOutsideZoneCarePackage" value="0"></td>
                                        </tr>
                                        <tr>
                                            <td>第三圈</td>
                                            <td><input class="td-input" type="text" name="Phase3_StartDelay" value="0"></td>
                                            <td><input class="td-input" type="text" name="Phase3_WarningDuration" value="150"></td>
                                            <td><input class="td-input" type="text" name="Phase3_ReleaseDuration" value="90"></td>
                                            <td><input class="td-input" type="text" name="Phase3_GasDamagePerSecond" value="0.8"></td>
                                            <td><input class="td-input" type="text" name="Phase3_RadiusRate" value="0.5"></td>
                                            <td><input class="td-input" type="text" name="Phase3_SpreadRatio" value="0.5"></td>
                                            <td><input class="td-input" type="text" name="Phase3_LandRatio" value="0"></td>
                                            <td><input class="td-input" type="text" name="Phase3_AddWhiteZoneCarePackage" value="1"></td>
                                            <td><input class="td-input" type="text" name="Phase3_AddOutsideZoneCarePackage" value="0"></td>
                                        </tr>
                                        <tr>
                                            <td>第四圈</td>
                                            <td><input class="td-input" type="text" name="Phase4_StartDelay" value="0"></td>
                                            <td><input class="td-input" type="text" name="Phase4_WarningDuration" value="120"></td>
                                            <td><input class="td-input" type="text" name="Phase4_ReleaseDuration" value="60"></td>
                                            <td><input class="td-input" type="text" name="Phase4_GasDamagePerSecond" value="1"></td>
                                            <td><input class="td-input" type="text" name="Phase4_RadiusRate" value="0.5"></td>
                                            <td><input class="td-input" type="text" name="Phase4_SpreadRatio" value="0.5"></td>
                                            <td><input class="td-input" type="text" name="Phase4_LandRatio" value="0"></td>
                                            <td><input class="td-input" type="text" name="Phase4_AddWhiteZoneCarePackage" value="1"></td>
                                            <td><input class="td-input" type="text" name="Phase4_AddOutsideZoneCarePackage" value="0"></td>
                                        </tr>
                                        <tr>
                                            <td>第五圈</td>
                                            <td><input class="td-input" type="text" name="Phase5_StartDelay" value="0"></td>
                                            <td><input class="td-input" type="text" name="Phase5_WarningDuration" value="120"></td>
                                            <td><input class="td-input" type="text" name="Phase5_ReleaseDuration" value="40"></td>
                                            <td><input class="td-input" type="text" name="Phase5_GasDamagePerSecond" value="3"></td>
                                            <td><input class="td-input" type="text" name="Phase5_RadiusRate" value="0.5"></td>
                                            <td><input class="td-input" type="text" name="Phase5_SpreadRatio" value="0.5"></td>
                                            <td><input class="td-input" type="text" name="Phase5_LandRatio" value="0"></td>
                                            <td><input class="td-input" type="text" name="Phase5_AddWhiteZoneCarePackage" value="1"></td>
                                            <td><input class="td-input" type="text" name="Phase5_AddOutsideZoneCarePackage" value="-99"></td>
                                        </tr>
                                        <tr>
                                            <td>第六圈</td>
                                            <td><input class="td-input" type="text" name="Phase6_StartDelay" value="0"></td>
                                            <td><input class="td-input" type="text" name="Phase6_WarningDuration" value="90"></td>
                                            <td><input class="td-input" type="text" name="Phase6_ReleaseDuration" value="30"></td>
                                            <td><input class="td-input" type="text" name="Phase6_GasDamagePerSecond" value="5"></td>
                                            <td><input class="td-input" type="text" name="Phase6_RadiusRate" value="0.5"></td>
                                            <td><input class="td-input" type="text" name="Phase6_SpreadRatio" value="0.5"></td>
                                            <td><input class="td-input" type="text" name="Phase6_LandRatio" value="0"></td>
                                            <td><input class="td-input" type="text" name="Phase6_AddWhiteZoneCarePackage" value="0"></td>
                                            <td><input class="td-input" type="text" name="Phase6_AddOutsideZoneCarePackage" value="0"></td>
                                        </tr>
                                        <tr>
                                            <td>第七圈</td>
                                            <td><input class="td-input" type="text" name="Phase7_StartDelay" value="0"></td>
                                            <td><input class="td-input" type="text" name="Phase7_WarningDuration" value="90"></td>
                                            <td><input class="td-input" type="text" name="Phase7_ReleaseDuration" value="30"></td>
                                            <td><input class="td-input" type="text" name="Phase7_GasDamagePerSecond" value="7"></td>
                                            <td><input class="td-input" type="text" name="Phase7_RadiusRate" value="0.5"></td>
                                            <td><input class="td-input" type="text" name="Phase7_SpreadRatio" value="0.5"></td>
                                            <td><input class="td-input" type="text" name="Phase7_LandRatio" value="0"></td>
                                            <td><input class="td-input" type="text" name="Phase7_AddWhiteZoneCarePackage" value="-99"></td>
                                            <td><input class="td-input" type="text" name="Phase7_AddOutsideZoneCarePackage" value="0"></td>
                                        </tr>
                                        <tr>
                                            <td>第八圈</td>
                                            <td><input class="td-input" type="text" name="Phase8_StartDelay" value="0"></td>
                                            <td><input class="td-input" type="text" name="Phase8_WarningDuration" value="60"></td>
                                            <td><input class="td-input" type="text" name="Phase8_ReleaseDuration" value="30"></td>
                                            <td><input class="td-input" type="text" name="Phase8_GasDamagePerSecond" value="9"></td>
                                            <td><input class="td-input" type="text" name="Phase8_RadiusRate" value="0.5"></td>
                                            <td><input class="td-input" type="text" name="Phase8_SpreadRatio" value="0.5"></td>
                                            <td><input class="td-input" type="text" name="Phase8_LandRatio" value="0"></td>
                                            <td><input class="td-input" type="text" name="Phase8_AddWhiteZoneCarePackage" value="0"></td>
                                            <td><input class="td-input" type="text" name="Phase8_AddOutsideZoneCarePackage" value="0"></td>
                                        </tr>
                                        <tr>
                                            <td>第九圈</td>
                                            <td><input class="td-input" type="text" name="Phase9_StartDelay" value="180"></td>
                                            <td><input class="td-input" type="text" name="Phase9_WarningDuration" value="15"></td>
                                            <td><input class="td-input" type="text" name="Phase9_ReleaseDuration" value="15"></td>
                                            <td><input class="td-input" type="text" name="Phase9_GasDamagePerSecond" value="11"></td>
                                            <td><input class="td-input" type="text" name="Phase9_RadiusRate" value="0.001"></td>
                                            <td><input class="td-input" type="text" name="Phase9_SpreadRatio" value="0.5"></td>
                                            <td><input class="td-input" type="text" name="Phase9_LandRatio" value="0"></td>
                                            <td><input class="td-input" type="text" name="Phase9_AddWhiteZoneCarePackage" value="0"></td>
                                            <td><input class="td-input" type="text" name="Phase9_AddOutsideZoneCarePackage" value="0"></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4" style="margin-left: 1%">是否显示最后一圈位置</label>
                                    <div class="col-sm-4 pubg-custom-radio">
                                        <?= Html::radioList('BlueZoneStatic',isset($data['BlueZoneStatic']) ? $data['BlueZoneStatic'] : 2,['1' => '是','2' => '否'],
                                            ['class' => 'radio','itemOptions' =>['labelOptions' =>['style' =>'margin-left:2%;','class' => 'checkbox-inline']]]) ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label pubg-custom-circle-label">最终蓝圈固定比率</label>
                                    <div class="slider slider-green pubg-custom-circle-slide" data-min="0" data-max="100" data-value="<?= isset($data['EndCircleLocationRate']) ? $data['EndCircleLocationRate'] : 0 ?>" data-postfix=" %" data-step="0.1" data-fill="#EndCircleLocationRate"></div>
                                    <input type="hidden" class="form-control" id="EndCircleLocationRate" name="EndCircleLocationRate" value="<?= isset($data['EndCircleLocationRate']) ? $data['EndCircleLocationRate'] : '0%' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label pubg-custom-circle-label">最后一圈的位置-城市</label>
                                    <div class="slider slider-green pubg-custom-circle-slide" data-min="0" data-max="10" data-value="<?= isset($data['EndCircleLocationArea1']) ? $data['EndCircleLocationArea1'] : 1 ?>" data-postfix=" x" data-fill="#EndCircleLocationArea1"></div>
                                    <input type="hidden" class="form-control" id="EndCircleLocationArea1" name="EndCircleLocationArea1" value="<?= isset($data['EndCircleLocationArea1']) ? $data['EndCircleLocationArea1'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label pubg-custom-circle-label">最后一圈的位置-平野</label>
                                    <div class="slider slider-green pubg-custom-circle-slide" data-min="0" data-max="10" data-value="<?= isset($data['EndCircleLocationArea2']) ? $data['EndCircleLocationArea2'] : 1 ?>" data-postfix=" x" data-fill="#EndCircleLocationArea2"></div>
                                    <input type="hidden" class="form-control" id="EndCircleLocationArea2" name="EndCircleLocationArea2" value="<?= isset($data['EndCircleLocationArea2']) ? $data['EndCircleLocationArea2'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label pubg-custom-circle-label">最后一圈的位置-山区</label>
                                    <div class="slider slider-green pubg-custom-circle-slide" data-min="0" data-max="10" data-value="<?= isset($data['EndCircleLocationArea3']) ? $data['EndCircleLocationArea3'] : 1 ?>" data-postfix=" x" data-fill="#EndCircleLocationArea3"></div>
                                    <input type="hidden" class="form-control" id="EndCircleLocationArea3" name="EndCircleLocationArea3" value="<?= isset($data['EndCircleLocationArea3']) ? $data['EndCircleLocationArea3'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2">使用轰炸区</label>
                                    <div class="col-sm-5 pubg-custom-radio">
                                        <?= Html::radioList('RedZoneIsActive',isset($data['RedZoneIsActive']) ? $data['RedZoneIsActive'] : 1,['1' => '是', '2' => '否'],
                                            ['class' => 'radio','itemOptions' =>['labelOptions' =>['style' =>'margin-left:2%;','class' => 'checkbox-inline']]]) ?>
                                    </div>
                                </div>
                                <div class="form-group display_red_zone">
                                    <label class="control-label pubg-custom-circle-label">开始时间</label>
                                    <div class="slider slider-green pubg-custom-circle-slide" data-min="0.25" data-max="5" data-value="<?= isset($data['MultiplierRedZoneStartTime']) ? $data['MultiplierRedZoneStartTime'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#MultiplierRedZoneStartTime"></div>
                                    <input type="hidden" class="form-control" id="MultiplierRedZoneStartTime" name="MultiplierRedZoneStartTime" value="<?= isset($data['MultiplierRedZoneStartTime']) ? $data['MultiplierRedZoneStartTime'] : '1x' ?>" />
                                </div>
                                <div class="form-group display_red_zone">
                                    <label class="control-label pubg-custom-circle-label">生存时间</label>
                                    <div class="slider slider-green pubg-custom-circle-slide" data-min="0.1" data-max="5" data-value="<?= isset($data['MultiplierRedZoneEndTime']) ? $data['MultiplierRedZoneEndTime'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#MultiplierRedZoneEndTime"></div>
                                    <input type="hidden" class="form-control" id="MultiplierRedZoneEndTime" name="MultiplierRedZoneEndTime" value="<?= isset($data['MultiplierRedZoneEndTime']) ? $data['MultiplierRedZoneEndTime'] : '1x' ?>" />
                                </div>
                                <div class="form-group display_red_zone">
                                    <label class="control-label pubg-custom-circle-label">爆炸警告时间</label>
                                    <div class="slider slider-green pubg-custom-circle-slide" data-min="0.1" data-max="5" data-value="<?= isset($data['MultiplierRedZoneExplosionDelay']) ? $data['MultiplierRedZoneExplosionDelay'] : 1 ?>" data-postfix=" x" data-steo="0.1" data-fill="#MultiplierRedZoneExplosionDelay"></div>
                                    <input type="hidden" class="form-control" id="MultiplierRedZoneExplosionDelay" name="MultiplierRedZoneExplosionDelay" value="<?= isset($data['MultiplierRedZoneExplosionDelay']) ? $data['MultiplierRedZoneExplosionDelay'] : '1x' ?>" />
                                </div>
                                <div class="form-group display_red_zone">
                                    <label class="control-label pubg-custom-circle-label">爆炸持续时间</label>
                                    <div class="slider slider-green pubg-custom-circle-slide" data-min="0.2" data-max="5" data-value="<?= isset($data['MultiplierRedZoneDuration']) ? $data['MultiplierRedZoneDuration'] : 1 ?>" data-postfix=" x" data-step="0.2" data-fill="#MultiplierRedZoneDuration"></div>
                                    <input type="hidden" class="form-control" id="MultiplierRedZoneDuration" name="MultiplierRedZoneDuration" value="<?= isset($data['MultiplierRedZoneDuration']) ? $data['MultiplierRedZoneDuration'] : '1x' ?>" />
                                </div>
                                <div class="form-group display_red_zone">
                                    <label class="control-label pubg-custom-circle-label">爆炸区</label>
                                    <div class="slider slider-green pubg-custom-circle-slide" data-min="0.2" data-max="1.4" data-value="<?= isset($data['MultiplierRedZoneArea']) ? $data['MultiplierRedZoneArea'] : 1 ?>" data-postfix=" x" data-step="0.2" data-fill="#MultiplierRedZoneArea"></div>
                                    <input type="hidden" class="form-control" id="MultiplierRedZoneArea" name="MultiplierRedZoneArea" value="<?= isset($data['MultiplierRedZoneArea']) ? $data['MultiplierRedZoneArea'] : '1x' ?>" />
                                </div>
                                <div class="form-group display_red_zone">
                                    <label class="control-label pubg-custom-circle-label">爆炸总次数</label>
                                    <div class="slider slider-green pubg-custom-circle-slide" data-min="0.2" data-max="2" data-value="<?= isset($data['MultiplierRedZoneExplosionDensity']) ? $data['MultiplierRedZoneExplosionDensity'] : 1 ?>" data-postfix=" x" data-step="0.2" data-fill="#MultiplierRedZoneExplosionDensity"></div>
                                    <input type="hidden" class="form-control" id="MultiplierRedZoneExplosionDensity" name="MultiplierRedZoneExplosionDensity" value="<?= isset($data['MultiplierRedZoneExplosionDensity']) ? $data['MultiplierRedZoneExplosionDensity'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label pubg-custom-circle-label">空投频率</label>
                                    <div class="slider slider-green pubg-custom-circle-slide" data-min="0" data-max="5" data-value="<?= isset($data['CarePackageFrep']) ? $data['CarePackageFrep'] : 2.2 ?>" data-postfix=" x" data-step="1" data-fill="#CarePackageFreq"></div>
                                    <input type="hidden" class="form-control" id="CarePackageFreq" name="CarePackageFreq" value="<?= isset($data['CarePackageFrep']) ? $data['CarePackageFrep'] : '2.2x' ?>" />
                                </div>
                                <!--                            <div class="form-group">-->
                                <!--                                <label class="control-label pubg-custom-circle-label">观察者服务器</label>-->
                                <!--                            </div>-->
                                <!--                            <div class="form-group">-->
                                <!--                                <label class="col-sm-3" style="margin-left: 1%">死亡后变为观察者</label>-->
                                <!--                                <div class="col-sm-5 pubg-custom-radio">-->
                                <!--                                    --><?php //= Html::radioList('player_to_observer',isset($data['player_to_observer']) ? $data['player_to_observer'] : 1,/*['1' => 'ONLY HOST','2' => '是', '3' => '否'],
                                //                                        ['class' => 'radio','itemOptions' =>['labelOptions' =>['style' =>'margin-left:2%;','class' => 'checkbox-inline']]]) */?>
                                <!--                                </div>-->
                                <!--                            </div>-->
                                <div class="form-group">
                                    <label class="col-sm-3" style="margin-left: 1%">将淘汰者转换为观战者</label>
                                    <div class="col-sm-5 pubg-custom-radio">
                                        <?= Html::radioList('PublicSpectating',isset($data['PublicSpectating']) ? $data['PublicSpectating'] : 1,[1 => '开启',2 => '关闭'],
                                            ['class' => 'radio','itemOptions' =>['labelOptions' =>['style' =>'margin-left:2%;','class' => 'checkbox-inline']]]) ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3" style="margin-left: 1%">攻击方视角观战</label>
                                    <div class="col-sm-5 pubg-custom-radio">
                                        <?= Html::radioList('KillerSpectating',isset($data['KillerSpectating']) ? $data['KillerSpectating'] : 1,[1 => '开启',2 => '关闭'],
                                            ['class' => 'radio','itemOptions' =>['labelOptions' =>['style' =>'margin-left:2%;','class' => 'checkbox-inline']]]) ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3" style="margin-left: 1%">是否开启信号枪</label>
                                    <div class="col-sm-5 pubg-custom-radio">
                                        <?= Html::radioList('FlareGunIsActive',isset($data['FlareGunIsActive']) ? $data['FlareGunIsActive'] : 1,[1 => '开启',2 => '关闭'],
                                            ['class' => 'radio','itemOptions' =>['labelOptions' =>['style' =>'margin-left:2%;','class' => 'checkbox-inline']]]) ?>
                                    </div>
                                </div>
                            </div>
                            <div id="advanced_options" class="tab-pane fade col-md-8">
                                <div class="form-group">
                                    <label class="control-label">武器</label>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">狙击步枪类</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['WSniperRifle']) ? $data['WSniperRifle'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#WSniperRifle"></div>
                                    <input type="hidden" class="form-control" id="WSniperRifle" name="WSniperRifle" value="<?= isset($data['WSniperRifle']) ? $data['WSniperRifle'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">98K</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['WSniperRifles_kar98k']) ? $data['WSniperRifles_kar98k'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#WSniperRifles_kar98k"></div>
                                    <input type="hidden" class="form-control" id="WSniperRifles_kar98k" name="WSniperRifles_kar98k" value="<?= isset($data['WSniperRifles_kar98k']) ? $data['WSniperRifles_kar98k'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">M24</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['WSniperRifles_m24']) ? $data['WSniperRifles_m24'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#WSniperRifles_m24"></div>
                                    <input type="hidden" class="form-control" id="WSniperRifles_m24" name="WSniperRifles_m24" value="<?= isset($data['WSniperRifles_m24']) ? $data['WSniperRifles_m24'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">精确射手步枪(DMR)</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['WDMR']) ? $data['WDMR'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#WDMR"></div>
                                    <input type="hidden" class="form-control" id="WDMR" name="WDMR" value="<?= isset($data['WDMR']) ? $data['WDMR'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">mini14</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['WDMR_mini14']) ? $data['WDMR_mini14'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#WDMR_mini14"></div>
                                    <input type="hidden" class="form-control" id="WDMR_mini14" name="WDMR_mini14" value="<?= isset($data['WDMR_mini14']) ? $data['WDMR_mini14'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">SKS</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['WDMR_sks']) ? $data['WDMR_sks'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#WDMR_sks"></div>
                                    <input type="hidden" class="form-control" id="WDMR_sks" name="WDMR_sks" value="<?= isset($data['WDMR_sks']) ? $data['WDMR_sks'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">VSS</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['WDMR_vss']) ? $data['WDMR_vss'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#WDMR_vss"></div>
                                    <input type="hidden" class="form-control" id="WDMR_vss" name="WDMR_vss" value="<?= isset($data['WDMR_vss']) ? $data['WDMR_vss'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">自动装填步枪</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['WDMR_slr']) ? $data['WDMR_slr'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#WDMR_slr"></div>
                                    <input type="hidden" class="form-control" id="WDMR_slr" name="WDMR_slr" value="<?= isset($data['WDMR_slr']) ? $data['WDMR_slr'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">qbu</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['WDMR_qbu']) ? $data['WDMR_qbu'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#WDMR_qbu"></div>
                                    <input type="hidden" class="form-control" id="WDMR_qbu" name="WDMR_qbu" value="<?= isset($data['WDMR_qbu']) ? $data['WDMR_qbu'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">突击步枪类</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['WAssaultRifle']) ? $data['WAssaultRifle'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#WAssaultRifle"></div>
                                    <input type="hidden" class="form-control" id="WAssaultRifle" name="WAssaultRifle" value="<?= isset($data['WAssaultRifle']) ? $data['WAssaultRifle'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">AKM</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['WAssaultRifles_akm']) ? $data['WAssaultRifles_akm'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#WAssaultRifles_akm"></div>
                                    <input type="hidden" class="form-control" id="WAssaultRifles_akm" name="WAssaultRifles_akm" value="<?= isset($data['WAssaultRifles_akm']) ? $data['WAssaultRifles_akm'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">M416</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['WAssaultRifles_m416']) ? $data['WAssaultRifles_m416'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#WAssaultRifles_m416"></div>
                                    <input type="hidden" class="form-control" id="WAssaultRifles_m416" name="WAssaultRifles_m416" value="<?= isset($data['WAssaultRifles_m416']) ? $data['WAssaultRifles_m416'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">M16A4</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['WAssaultRifles_m16a4']) ? $data['WAssaultRifles_m16a4'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#WAssaultRifles_m16a4"></div>
                                    <input type="hidden" class="form-control" id="WAssaultRifles_m16a4" name="WAssaultRifles_m16a4" value="<?= isset($data['WAssaultRifles_m16a4']) ? $data['WAssaultRifles_m16a4'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">SCAR</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['WAssaultRifles_scar_l']) ? $data['WAssaultRifles_scar_l'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#WAssaultRifles_scar_l"></div>
                                    <input type="hidden" class="form-control" id="WAssaultRifles_scar_l" name="WAssaultRifles_scar_l" value="<?= isset($data['WAssaultRifles_scar_l']) ? $data['WAssaultRifles_scar_l'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">QBZ95</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['WAssaultRifles_qbz95']) ? $data['WAssaultRifles_qbz95'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#WAssaultRifles_qbz95"></div>
                                    <input type="hidden" class="form-control" id="WAssaultRifles_qbz95" name="WAssaultRifles_qbz95" value="<?= isset($data['WAssaultRifles_qbz95']) ? $data['WAssaultRifles_qbz95'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">猎人步枪</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['WHuntingRifle']) ? $data['WHuntingRifle'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#WHuntingRifle"></div>
                                    <input type="hidden" class="form-control" id="WHuntingRifle" name="WHuntingRifle" value="<?= isset($data['WHuntingRifle']) ? $data['WHuntingRifle'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">win94</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['WHuntingRifles_win94']) ? $data['WHuntingRifles_win94'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#WHuntingRifles_win94"></div>
                                    <input type="hidden" class="form-control" id="WHuntingRifles_win94" name="WHuntingRifles_win94" value="<?= isset($data['WHuntingRifles_win94']) ? $data['WHuntingRifles_win94'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">轻机枪</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['WLMG']) ? $data['WLMG'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#WLMG"></div>
                                    <input type="hidden" class="form-control" id="WLMG" name="WLMG" value="<?= isset($data['WLMG']) ? $data['WLMG'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">dp28</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['WLMG_dp28']) ? $data['WLMG_dp28'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#WLMG_dp28"></div>
                                    <input type="hidden" class="form-control" id="WLMG_dp28" name="WLMG_dp28" value="<?= isset($data['WLMG_dp28']) ? $data['WLMG_dp28'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">冲锋枪</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['WSMG']) ? $data['WSMG'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#WSMG"></div>
                                    <input type="hidden" class="form-control" id="WSMG" name="WSMG" value="<?= isset($data['WSMG']) ? $data['WSMG'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">汤姆枪</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['WSMG_tommygun']) ? $data['WSMG_tommygun'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#WSMG_tommygun"></div>
                                    <input type="hidden" class="form-control" id="WSMG_tommygun" name="WSMG_tommygun" value="<?= isset($data['WSMG_tommygun']) ? $data['WSMG_tommygun'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">ump</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['WSMG_ump']) ? $data['WSMG_ump'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#WSMG_ump"></div>
                                    <input type="hidden" class="form-control" id="WSMG_ump" name="WSMG_ump" value="<?= isset($data['WSMG_ump']) ? $data['WSMG_ump'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">uzi</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['WSMG_uzi']) ? $data['WSMG_uzi'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#WSMG_uzi"></div>
                                    <input type="hidden" class="form-control" id="WSMG_uzi" name="WSMG_uzi" value="<?= isset($data['WSMG_uzi']) ? $data['WSMG_uzi'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">vector</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['WSMG_vector']) ? $data['WSMG_vector'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#WSMG_vector"></div>
                                    <input type="hidden" class="form-control" id="WSMG_vector" name="WSMG_vector" value="<?= isset($data['WSMG_vector']) ? $data['WSMG_vector'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">散弹枪类</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['WshotGun']) ? $data['WshotGun'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#WshotGun"></div>
                                    <input type="hidden" class="form-control" id="WshotGun" name="WshotGun" value="<?= isset($data['WshotGun']) ? $data['WshotGun'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">s686</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Wshotguns_s686']) ? $data['Wshotguns_s686'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Wshotguns_s686"></div>
                                    <input type="hidden" class="form-control" id="Wshotguns_s686" name="Wshotguns_s686" value="<?= isset($data['Wshotguns_s686']) ? $data['Wshotguns_s686'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">s12k</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Wshotguns_s12k']) ? $data['Wshotguns_s12k'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Wshotguns_s12k"></div>
                                    <input type="hidden" class="form-control" id="Wshotguns_s12k" name="Wshotguns_s12k" value="<?= isset($data['Wshotguns_s12k']) ? $data['Wshotguns_s12k'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">s1897</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Wshotguns_s1897']) ? $data['Wshotguns_s1897'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Wshotguns_s1897"></div>
                                    <input type="hidden" class="form-control" id="Wshotguns_s1897" name="Wshotguns_s1897" value="<?= isset($data['Wshotguns_s1897']) ? $data['Wshotguns_s1897'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">手枪类</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Whandguns']) ? $data['Whandguns'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Whandguns"></div>
                                    <input type="hidden" class="form-control" id="Whandguns" name="Whandguns" value="<?= isset($data['Whandguns']) ? $data['Whandguns'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">p18c</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Whandguns_p18c']) ? $data['Whandguns_p18c'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Whandguns_p18c"></div>
                                    <input type="hidden" class="form-control" id="Whandguns_p18c" name="Whandguns_p18c" value="<?= isset($data['Whandguns_p18c']) ? $data['Whandguns_p18c'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">p1911</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Whandguns_p1911']) ? $data['Whandguns_p1911'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Whandguns_p1911"></div>
                                    <input type="hidden" class="form-control" id="Whandguns_p1911" name="Whandguns_p1911" value="<?= isset($data['Whandguns_p1911']) ? $data['Whandguns_p1911'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">p92</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Whandguns_p92']) ? $data['Whandguns_p92'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Whandguns_p92"></div>
                                    <input type="hidden" class="form-control" id="Whandguns_p92" name="Whandguns_p92" value="<?= isset($data['Whandguns_p92']) ? $data['Whandguns_p92'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">p1895</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Whandguns_r1895']) ? $data['Whandguns_r1895'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Whandguns_r1895"></div>
                                    <input type="hidden" class="form-control" id="Whandguns_r1895" name="Whandguns_r1895" value="<?= isset($data['Whandguns_r1895']) ? $data['Whandguns_r1895'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">r45</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Whandguns_r45']) ? $data['Whandguns_r45'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Whandguns_r45"></div>
                                    <input type="hidden" class="form-control" id="Whandguns_r45" name="Whandguns_r45" value="<?= isset($data['Whandguns_r45']) ? $data['Whandguns_r45'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">锯短型散弹枪</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Whandguns_sawedoff']) ? $data['Whandguns_sawedoff'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Whandguns_sawedoff"></div>
                                    <input type="hidden" class="form-control" id="Whandguns_sawedoff" name="Whandguns_sawedoff" value="<?= isset($data['Whandguns_sawedoff']) ? $data['Whandguns_sawedoff'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">投掷物</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Wthrowables']) ? $data['Wthrowables'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Wthrowables"></div>
                                    <input type="hidden" class="form-control" id="Wthrowables" name="Wthrowables" value="<?= isset($data['Wthrowables']) ? $data['Wthrowables'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">震爆弹</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Wthrowables_flashbang']) ? $data['Wthrowables_flashbang'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Wthrowables_flashbang"></div>
                                    <input type="hidden" class="form-control" id="Wthrowables_flashbang" name="Wthrowables_flashbang" value="<?= isset($data['Wthrowables_flashbang']) ? $data['Wthrowables_flashbang'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">破片手榴弹</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Wthrowables_fraggrenade']) ? $data['Wthrowables_fraggrenade'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Wthrowables_fraggrenade"></div>
                                    <input type="hidden" class="form-control" id="Wthrowables_fraggrenade" name="Wthrowables_fraggrenade" value="<?= isset($data['Wthrowables_fraggrenade']) ? $data['Wthrowables_fraggrenade'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">燃烧弹</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Wthrowables_molotov']) ? $data['Wthrowables_molotov'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Wthrowables_molotov"></div>
                                    <input type="hidden" class="form-control" id="Wthrowables_molotov" name="Wthrowables_molotov" value="<?= isset($data['Wthrowables_molotov']) ? $data['Wthrowables_molotov'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">烟雾弹</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Wthrowables_smokebomb']) ? $data['Wthrowables_smokebomb'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Wthrowables_smokebomb"></div>
                                    <input type="hidden" class="form-control" id="Wthrowables_smokebomb" name="Wthrowables_smokebomb" value="<?= isset($data['Wthrowables_smokebomb']) ? $data['Wthrowables_smokebomb'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">近战武器</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['WMelee']) ? $data['WMelee'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#WMelee"></div>
                                    <input type="hidden" class="form-control" id="WMelee" name="WMelee" value="<?= isset($data['WMelee']) ? $data['WMelee'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">撬棍</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Wmelee_crowbar']) ? $data['Wmelee_crowbar'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Wmelee_crowbar"></div>
                                    <input type="hidden" class="form-control" id="Wmelee_crowbar" name="Wmelee_crowbar" value="<?= isset($data['Wmelee_crowbar']) ? $data['Wmelee_crowbar'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">大砍刀</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Wmelee_machete']) ? $data['Wmelee_machete'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Wmelee_machete"></div>
                                    <input type="hidden" class="form-control" id="Wmelee_machete" name="Wmelee_machete" value="<?= isset($data['Wmelee_machete']) ? $data['Wmelee_machete'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">平底锅</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Wmelee_pan']) ? $data['Wmelee_pan'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Wmelee_pan"></div>
                                    <input type="hidden" class="form-control" id="Wmelee_pan" name="Wmelee_pan" value="<?= isset($data['Wmelee_pan']) ? $data['Wmelee_pan'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">镰刀</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Wmelee_sickle']) ? $data['Wmelee_sickle'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Wmelee_sickle"></div>
                                    <input type="hidden" class="form-control" id="Wmelee_sickle" name="Wmelee_sickle" value="<?= isset($data['Wmelee_sickle']) ? $data['Wmelee_sickle'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">弓箭</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Wbow']) ? $data['Wbow'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Wbow"></div>
                                    <input type="hidden" class="form-control" id="Wbow" name="Wbow" value="<?= isset($data['Wbow']) ? $data['Wbow'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">十字弓</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Wbow_crossbow']) ? $data['Wbow_crossbow'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Wbow_crossbow"></div>
                                    <input type="hidden" class="form-control" id="Wbow_crossbow" name="Wbow_crossbow" value="<?= isset($data['Wbow_crossbow']) ? $data['Wbow_crossbow'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">信号枪</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Wflaregun']) ? $data['Wflaregun'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Wflaregun"></div>
                                    <input type="hidden" class="form-control" id="Wflaregun" name="Wflaregun" value="<?= isset($data['Wflaregun']) ? $data['Wflaregun'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">配件</label>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">瞄准镜</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['AScope']) ? $data['AScope'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#AScope"></div>
                                    <input type="hidden" class="form-control" id="AScope" name="AScope" value="<?= isset($data['AScope']) ? $data['AScope'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">红点瞄准镜</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Ascope_dotsight']) ? $data['Ascope_dotsight'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Ascope_dotsight"></div>
                                    <input type="hidden" class="form-control" id="Ascope_dotsight" name="Ascope_dotsight" value="<?= isset($data['Ascope_dotsight']) ? $data['Ascope_dotsight'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">全息瞄准镜</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Ascope_holosight']) ? $data['Ascope_holosight'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Ascope_holosight"></div>
                                    <input type="hidden" class="form-control" id="Ascope_holosight" name="Ascope_holosight" value="<?= isset($data['Ascope_holosight']) ? $data['Ascope_holosight'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">2倍镜</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Ascope_scope2x']) ? $data['Ascope_scope2x'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Ascope_scope2x"></div>
                                    <input type="hidden" class="form-control" id="Ascope_scope2x" name="Ascope_scope2x" value="<?= isset($data['Ascope_scope2x']) ? $data['Ascope_scope2x'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">3倍镜</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Ascope_scope3x']) ? $data['Ascope_scope3x'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Ascope_scope3x"></div>
                                    <input type="hidden" class="form-control" id="Ascope_scope3x" name="Ascope_scope3x" value="<?= isset($data['Ascope_scope3x']) ? $data['Ascope_scope3x'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">4倍镜</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Ascope_scope4x']) ? $data['Ascope_scope4x'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Ascope_scope4x"></div>
                                    <input type="hidden" class="form-control" id="Ascope_scope4x" name="Ascope_scope4x" value="<?= isset($data['Ascope_scope4x']) ? $data['Ascope_scope4x'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">6倍镜</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Ascope_scope6x']) ? $data['Ascope_scope6x'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Ascope_scope6x"></div>
                                    <input type="hidden" class="form-control" id="Ascope_scope6x" name="Ascope_scope6x" value="<?= isset($data['Ascope_scope6x']) ? $data['Ascope_scope6x'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">8倍镜</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Ascope_scope8x']) ? $data['Ascope_scope8x'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Ascope_scope8x"></div>
                                    <input type="hidden" class="form-control" id="Ascope_scope8x" name="Ascope_scope8x" value="<?= isset($data['Ascope_scope8x']) ? $data['Ascope_scope8x'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">弹夹</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['AMagazine']) ? $data['AMagazine'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#AMagazine"></div>
                                    <input type="hidden" class="form-control" id="AMagazine" name="AMagazine" value="<?= isset($data['AMagazine']) ? $data['AMagazine'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">突击步枪弹夹</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Amagazine_ar_mag']) ? $data['Amagazine_ar_mag'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Amagazine_ar_mag"></div>
                                    <input type="hidden" class="form-control" id="Amagazine_ar_mag" name="Amagazine_ar_mag" value="<?= isset($data['Amagazine_ar_mag']) ? $data['Amagazine_ar_mag'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">狙击步枪弹夹</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Amagazine_sr_mag']) ? $data['Amagazine_sr_mag'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Amagazine_sr_mag"></div>
                                    <input type="hidden" class="form-control" id="Amagazine_sr_mag" name="Amagazine_sr_mag" value="<?= isset($data['Amagazine_sr_mag']) ? $data['Amagazine_sr_mag'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">冲锋枪弹夹</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Amagazine_smg_mag']) ? $data['Amagazine_smg_mag'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Amagazine_smg_mag"></div>
                                    <input type="hidden" class="form-control" id="Amagazine_smg_mag" name="Amagazine_smg_mag" value="<?= isset($data['Amagazine_smg_mag']) ? $data['Amagazine_smg_mag'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">手枪弹夹</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Amagazine_pistol_mag']) ? $data['Amagazine_pistol_mag'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Amagazine_pistol_mag"></div>
                                    <input type="hidden" class="form-control" id="Amagazine_pistol_mag" name="Amagazine_pistol_mag" value="<?= isset($data['Amagazine_pistol_mag']) ? $data['Amagazine_pistol_mag'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">枪口</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['AMuzzle']) ? $data['AMuzzle'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#AMuzzle"></div>
                                    <input type="hidden" class="form-control" id="AMuzzle" name="AMuzzle" value="<?= isset($data['AMuzzle']) ? $data['AMuzzle'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">狙击步枪枪口</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Amuzzle_sr_muzzle']) ? $data['Amuzzle_sr_muzzle'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Amuzzle_sr_muzzle"></div>
                                    <input type="hidden" class="form-control" id="Amuzzle_sr_muzzle" name="Amuzzle_sr_muzzle" value="<?= isset($data['Amuzzle_sr_muzzle']) ? $data['Amuzzle_sr_muzzle'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">突击步枪枪口</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Amuzzle_ar_muzzle']) ? $data['Amuzzle_ar_muzzle'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Amuzzle_ar_muzzle"></div>
                                    <input type="hidden" class="form-control" id="Amuzzle_ar_muzzle" name="Amuzzle_ar_muzzle" value="<?= isset($data['Amuzzle_ar_muzzle']) ? $data['Amuzzle_ar_muzzle'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">散弹枪枪口</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Amuzzle_sg_muzzle']) ? $data['Amuzzle_sg_muzzle'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Amuzzle_sg_muzzle"></div>
                                    <input type="hidden" class="form-control" id="Amuzzle_sg_muzzle" name="Amuzzle_sg_muzzle" value="<?= isset($data['Amuzzle_sg_muzzle']) ? $data['Amuzzle_sg_muzzle'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">冲锋枪枪口</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Amuzzle_smg_muzzle']) ? $data['Amuzzle_smg_muzzle'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Amuzzle_smg_muzzle"></div>
                                    <input type="hidden" class="form-control" id="Amuzzle_smg_muzzle" name="Amuzzle_smg_muzzle" value="<?= isset($data['Amuzzle_smg_muzzle']) ? $data['Amuzzle_smg_muzzle'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">手枪枪口</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Amuzzle_pistol_muzzle']) ? $data['Amuzzle_pistol_muzzle'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Amuzzle_pistol_muzzle"></div>
                                    <input type="hidden" class="form-control" id="Amuzzle_pistol_muzzle" name="Amuzzle_pistol_muzzle" value="<?= isset($data['Amuzzle_pistol_muzzle']) ? $data['Amuzzle_pistol_muzzle'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">握把</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Aforegrip']) ? $data['Aforegrip'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Aforegrip"></div>
                                    <input type="hidden" class="form-control" id="Aforegrip" name="Aforegrip" value="<?= isset($data['Aforegrip']) ? $data['Aforegrip'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">枪托配件</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Astock']) ? $data['Astock'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Astock"></div>
                                    <input type="hidden" class="form-control" id="Astock" name="Astock" value="<?= isset($data['Astock']) ? $data['Astock'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">箭袋</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Astock_crossbowquiver']) ? $data['Astock_crossbowquiver'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Astock_crossbowquiver"></div>
                                    <input type="hidden" class="form-control" id="Astock_crossbowquiver" name="Astock_crossbowquiver" value="<?= isset($data['Astock_crossbowquiver']) ? $data['Astock_crossbowquiver'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">战术枪托(M416，Vector)</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Astock_ar_composite']) ? $data['Astock_ar_composite'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Astock_ar_composite"></div>
                                    <input type="hidden" class="form-control" id="Astock_ar_composite" name="Astock_ar_composite" value="<?= isset($data['Astock_ar_composite']) ? $data['Astock_ar_composite'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">枪托(Micro UZI 冲锋枪)</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Astock_uzi_stock']) ? $data['Astock_uzi_stock'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Astock_uzi_stock"></div>
                                    <input type="hidden" class="form-control" id="Astock_uzi_stock" name="Astock_uzi_stock" value="<?= isset($data['Astock_uzi_stock']) ? $data['Astock_uzi_stock'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">子弹袋(霰弹枪)</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Astock_sg_bulletloops']) ? $data['Astock_sg_bulletloops'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Astock_sg_bulletloops"></div>
                                    <input type="hidden" class="form-control" id="Astock_sg_bulletloops" name="Astock_sg_bulletloops" value="<?= isset($data['Astock_sg_bulletloops']) ? $data['Astock_sg_bulletloops'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">子弹袋(Win94,Kar98k)</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Astock_kar98k_bulletloops']) ? $data['Astock_kar98k_bulletloops'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Astock_kar98k_bulletloops"></div>
                                    <input type="hidden" class="form-control" id="Astock_kar98k_bulletloops" name="Astock_kar98k_bulletloops" value="<?= isset($data['Astock_kar98k_bulletloops']) ? $data['Astock_kar98k_bulletloops'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">托腮板(精确射手步枪,狙击步枪)</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Astock_sr_cheekpad']) ? $data['Astock_sr_cheekpad'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Astock_sr_cheekpad"></div>
                                    <input type="hidden" class="form-control" id="Astock_sr_cheekpad" name="Astock_sr_cheekpad" value="<?= isset($data['Astock_sr_cheekpad']) ? $data['Astock_sr_cheekpad'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">消耗品</label>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">治疗物品</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Uheal']) ? $data['Uheal'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Uheal"></div>
                                    <input type="hidden" class="form-control" id="Uheal" name="Uheal" value="<?= isset($data['Uheal']) ? $data['Uheal'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">绷带</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Uheal_bandage']) ? $data['Uheal_bandage'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Uheal_bandage"></div>
                                    <input type="hidden" class="form-control" id="Uheal_bandage" name="Uheal_bandage" value="<?= isset($data['Uheal_bandage']) ? $data['Uheal_bandage'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">急救包</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Uheal_firstaid']) ? $data['Uheal_firstaid'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Uheal_firstaid"></div>
                                    <input type="hidden" class="form-control" id="Uheal_firstaid" name="Uheal_firstaid" value="<?= isset($data['Uheal_firstaid']) ? $data['Uheal_firstaid'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">医疗箱</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Uheal_medkit']) ? $data['Uheal_medkit'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Uheal_medkit"></div>
                                    <input type="hidden" class="form-control" id="Uheal_medkit" name="Uheal_medkit" value="<?= isset($data['Uheal_medkit']) ? $data['Uheal_medkit'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">能量药物</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Uboost']) ? $data['Uboost'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Uboost"></div>
                                    <input type="hidden" class="form-control" id="Uboost" name="Uboost" value="<?= isset($data['Uboost']) ? $data['Uboost'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">能量饮料</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Uboost_energydrink']) ? $data['Uboost_energydrink'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Uboost_energydrink"></div>
                                    <input type="hidden" class="form-control" id="Uboost_energydrink" name="Uboost_energydrink" value="<?= isset($data['Uboost_energydrink']) ? $data['Uboost_energydrink'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">止痛药</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Uboost_painkiller']) ? $data['Uboost_painkiller'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Uboost_painkiller"></div>
                                    <input type="hidden" class="form-control" id="Uboost_painkiller" name="Uboost_painkiller" value="<?= isset($data['Uboost_painkiller']) ? $data['Uboost_painkiller'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">肾上腺素</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Uboost_adrenaline']) ? $data['Uboost_adrenaline'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Uboost_adrenaline"></div>
                                    <input type="hidden" class="form-control" id="Uboost_adrenaline" name="Uboost_adrenaline" value="<?= isset($data['Uboost_adrenaline']) ? $data['Uboost_adrenaline'] : '1x' ?>" />
                                </div>

                                <div class="form-group">
                                    <label class="control-label">燃料</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Ujerrycan']) ? $data['Ujerrycan'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Ujerrycan"></div>
                                    <input type="hidden" class="form-control" id="Ujerrycan" name="Ujerrycan" value="<?= isset($data['Ujerrycan']) ? $data['Ujerrycan'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">装备</label>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">背包</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Ebag']) ? $data['Ebag'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Ebag"></div>
                                    <input type="hidden" class="form-control" id="Ebag" name="Ebag" value="<?= isset($data['Ebag']) ? $data['Ebag'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">一级包</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Bag_Lv1']) ? $data['Ebag_backpack_lv1'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Ebag_backpack_lv1"></div>
                                    <input type="hidden" class="form-control" id="Ebag_backpack_lv1" name="Ebag_backpack_lv1" value="<?= isset($data['Ebag_backpack_lv1']) ? $data['Ebag_backpack_lv1'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">二级包</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Ebag_backpack_lv2']) ? $data['Ebag_backpack_lv2'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Ebag_backpack_lv2"></div>
                                    <input type="hidden" class="form-control" id="Ebag_backpack_lv2" name="Ebag_backpack_lv2" value="<?= isset($data['Ebag_backpack_lv2']) ? $data['Ebag_backpack_lv2'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">三级包</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Ebag_backpack_lv3']) ? $data['Ebag_backpack_lv3'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Ebag_backpack_lv3"></div>
                                    <input type="hidden" class="form-control" id="Ebag_backpack_lv3" name="Ebag_backpack_lv3" value="<?= isset($data['Ebag_backpack_lv3']) ? $data['Ebag_backpack_lv3'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">头盔</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Ehelmet']) ? $data['Ehelmet'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Ehelmet"></div>
                                    <input type="hidden" class="form-control" id="Ehelmet" name="Ehelmet" value="<?= isset($data['Ehelmet']) ? $data['Ehelmet'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">一级头盔</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Ehelmet_helmet_lv1']) ? $data['Ehelmet_helmet_lv1'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Ehelmet_helmet_lv1"></div>
                                    <input type="hidden" class="form-control" id="Ehelmet_helmet_lv1" name="Ehelmet_helmet_lv1" value="<?= isset($data['Ehelmet_helmet_lv1']) ? $data['Ehelmet_helmet_lv1'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">二级头盔</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Ehelmet_helmet_lv2']) ? $data['Ehelmet_helmet_lv2'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Ehelmet_helmet_lv2"></div>
                                    <input type="hidden" class="form-control" id="Ehelmet_helmet_lv2" name="Ehelmet_helmet_lv2" value="<?= isset($data['Ehelmet_helmet_lv2']) ? $data['Ehelmet_helmet_lv2'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">三级头盔</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Ehelmet_helmet_lv3']) ? $data['Ehelmet_helmet_lv3'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Ehelmet_helmet_lv3"></div>
                                    <input type="hidden" class="form-control" id="Ehelmet_helmet_lv3" name="Ehelmet_helmet_lv3" value="<?= isset($data['Ehelmet_helmet_lv3']) ? $data['Ehelmet_helmet_lv3'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">盔甲</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Earmor']) ? $data['Earmor'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Earmor"></div>
                                    <input type="hidden" class="form-control" id="Earmor" name="Earmor" value="<?= isset($data['Earmor']) ? $data['Earmor'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">一级甲</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Earmor_armor_lv1']) ? $data['Earmor_armor_lv1'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Earmor_armor_lv1"></div>
                                    <input type="hidden" class="form-control" id="Earmor_armor_lv1" name="Earmor_armor_lv1" value="<?= isset($data['Earmor_armor_lv1']) ? $data['Earmor_armor_lv1'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">二级甲</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Earmor_armor_lv2']) ? $data['Earmor_armor_lv2'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Earmor_armor_lv2"></div>
                                    <input type="hidden" class="form-control" id="Earmor_armor_lv2" name="Earmor_armor_lv2" value="<?= isset($data['Earmor_armor_lv2']) ? $data['Earmor_armor_lv2'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">三级甲</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Earmor_armor_lv3']) ? $data['Earmor_armor_lv3'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Earmor_armor_lv3"></div>
                                    <input type="hidden" class="form-control" id="Earmor_armor_lv3" name="Earmor_armor_lv3" value="<?= isset($data['Earmor_armor_lv3']) ? $data['Earmor_armor_lv3'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">交通工具</label>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">车</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Car']) ? $data['Car'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Car"></div>
                                    <input type="hidden" class="form-control" id="Car" name="Car" value="<?= isset($data['Car']) ? $data['Car'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">双人越野车</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Buggy']) ? $data['Buggy'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Buggy"></div>
                                    <input type="hidden" class="form-control" id="Buggy" name="Buggy" value="<?= isset($data['Buggy']) ? $data['Buggy'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Dacia轿车</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Dacia']) ? $data['Dacia'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Dacia"></div>
                                    <input type="hidden" class="form-control" id="Dacia" name="Dacia" value="<?= isset($data['Dacia']) ? $data['Dacia'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">面包车</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Minibus']) ? $data['Minibus'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Minibus"></div>
                                    <input type="hidden" class="form-control" id="Minibus" name="Minibus" value="<?= isset($data['Minibus']) ? $data['Minibus'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Mirado小轿车</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Mirado']) ? $data['Mirado'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Mirado"></div>
                                    <input type="hidden" class="form-control" id="Mirado" name="Mirado" value="<?= isset($data['Mirado']) ? $data['Mirado'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">摩托车</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Motorbike']) ? $data['Motorbike'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Motorbike"></div>
                                    <input type="hidden" class="form-control" id="Motorbike" name="Motorbike" value="<?= isset($data['Motorbike']) ? $data['Motorbike'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">三轮摩托车</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Motorbike_Sidecar']) ? $data['Motorbike_Sidecar'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Motorbike_Sidecar"></div>
                                    <input type="hidden" class="form-control" id="Motorbike_Sidecar" name="Motorbike_Sidecar" value="<?= isset($data['Motorbike_Sidecar']) ? $data['Motorbike_Sidecar'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">皮卡车</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['PickupTruck']) ? $data['PickupTruck'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#PickupTruck"></div>
                                    <input type="hidden" class="form-control" id="PickupTruck" name="PickupTruck" value="<?= isset($data['PickupTruck']) ? $data['PickupTruck'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">乌阿斯吉普车</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Uaz']) ? $data['Uaz'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Uaz"></div>
                                    <input type="hidden" class="form-control" id="Uaz" name="Uaz" value="<?= isset($data['Uaz']) ? $data['Uaz'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">快艇</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Boat']) ? $data['Boat'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Boat"></div>
                                    <input type="hidden" class="form-control" id="Boat" name="Boat" value="<?= isset($data['Boat']) ? $data['Boat'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">摩托艇</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Jetski']) ? $data['Jetski'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Jetski"></div>
                                    <input type="hidden" class="form-control" id="Jetski" name="Jetski" value="<?= isset($data['Jetski']) ? $data['Jetski'] : '1x' ?>" />
                                </div>


                                <div class="form-group">
                                    <label class="control-label">其他</label>
                                </div>
<!--                                <div class="form-group">-->
<!--                                    <label class="control-label">装饰物品</label>-->
<!--                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="--><?//= isset($data['Costume']) ? $data['Costume'] : 1 ?><!--" data-postfix=" x" data-step="0.1" data-fill="#Costume"></div>-->
<!--                                    <input type="hidden" class="form-control" id="Costume" name="Costume" value="--><?//= isset($data['Costume']) ? $data['Costume'] : '1x' ?><!--" />-->
<!--                                </div>-->
                                <div class="form-group">
                                    <label class="control-label">子弹</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Ammo']) ? $data['Ammo'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Ammo"></div>
                                    <input type="hidden" class="form-control" id="Ammo" name="Ammo" value="<?= isset($data['Ammo']) ? $data['Ammo'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">12口径</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Ammo_12gauge']) ? $data['Ammo_12gauge'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Ammo_12gauge"></div>
                                    <input type="hidden" class="form-control" id="Ammo_12gauge" name="Ammo_12gauge" value="<?= isset($data['Ammo_12gauge']) ? $data['Ammo_12gauge'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">.45口径</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Ammo_45acp']) ? $data['Ammo_45acp'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Ammo_45acp"></div>
                                    <input type="hidden" class="form-control" id="Ammo_45acp" name="Ammo_45acp" value="<?= isset($data['Ammo_45acp']) ? $data['Ammo_45acp'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">5.56毫米</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Ammo_556mm']) ? $data['Ammo_556mm'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Ammo_556mm"></div>
                                    <input type="hidden" class="form-control" id="Ammo_556mm" name="Ammo_556mm" value="<?= isset($data['Ammo_556mm']) ? $data['Ammo_556mm'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">7.62毫米</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Ammo_762mm']) ? $data['Ammo_762mm'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Ammo_762mm"></div>
                                    <input type="hidden" class="form-control" id="Ammo_762mm" name="Ammo_762mm" value="<?= isset($data['Ammo_762mm']) ? $data['Ammo_762mm'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">9毫米</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Ammo_9mm']) ? $data['Ammo_9mm'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Ammo_9mm"></div>
                                    <input type="hidden" class="form-control" id="Ammo_9mm" name="Ammo_9mm" value="<?= isset($data['Ammo_9mm']) ? $data['Ammo_9mm'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">弩箭</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Ammo_bolt']) ? $data['Ammo_bolt'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Ammo_bolt"></div>
                                    <input type="hidden" class="form-control" id="Ammo_bolt" name="Ammo_bolt" value="<?= isset($data['Ammo_bolt']) ? $data['Ammo_bolt'] : '1x' ?>" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">照明弹</label>
                                    <div class="slider slider-green" data-min="0" data-max="3" data-value="<?= isset($data['Ammo_flare']) ? $data['Ammo_flare'] : 1 ?>" data-postfix=" x" data-step="0.1" data-fill="#Ammo_flare"></div>
                                    <input type="hidden" class="form-control" id="Ammo_flare" name="Ammo_flare" value="<?= isset($data['Ammo_flare']) ? $data['Ammo_flare'] : '1x' ?>" />
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
        getMode();
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

        if(mode == 2){
            $("#display_zombie").show()
        }else{
            $("#display_zombie").hide();
        }

        if(mode == 3){
            if(customId == ''){
                window.location.href='<?= Url::to(['/pubg/create','mode' => 3])?>'
            }else{
                window.location.href='<?= Url::to(['/pubg/update','id' => isset($data['id']) ? $data['id'] : '','mode' => 3])?>'
            }
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

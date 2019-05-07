<?php

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\web\view;

AppAsset::registerCss($this, '@web/plugin/select2/select2.css');
AppAsset::registerCss($this , '@web/plugin/daterangepicker/daterangepicker-bs3.css');
AppAsset::registerJs($this, '@web/plugin/select2/select2.min.js');
AppAsset::registerJs($this, '@web/plugin/fileinput.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/moment.min.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/daterangepicker.js');
Pjax::begin(['id' => 'season']);
?>
<style>

    .col-md-12{ overflow:hidden;}
    .big{}
    .thumbnail{ border:0; max-width:100% }
    .bbig{ bottom:1px;}
    .positionsave{ width:25%; float:right}
    .rowright{ border-left:1px solid #999}
    .red{ color:red}
    .group{ padding-left:9%}
    #page .panel-primary{border-bottom:0px}
    /*#page .panel-primary{ min-height:400px}*/
</style>
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
        <?php if(Yii::$app->session->hasFlash('settlementError')){ ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?=Yii::$app->session->getFlash('settlementError')?>
            </div>
        <?php }?>
    </div>
</div>
<?php
$form = ActiveForm::begin([
    'id' => 'season-form',
    'method' => 'post',
    'options' => [
        'data-pjax' => true,
        'role' => 'form',
        'class' => 'form-horizontal form-groups-bordered validate'
    ]
] );
?>
<?php if($isRecord == 0){ ?>
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-primary" data-collapsed="0">
                <div class="panel-heading">
                    <div class="panel-title">本场游戏是线下进行，需先编辑游戏配置</div>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="sponsor" class="col-sm-3 control-label">服务器</label>
                        <div class="col-sm-5">
                            <select class="form-control" name="server" id="server">
                                <option value="AS Server">亚服</option>
                                <option value="NA Server">北美服</option>
                                <option value="OC Server">澳服</option>
                                <option value="SEA Server">东南亚服</option>
                                <option value="SA Server">南美服</option>
                                <option value="EU Server">欧服</option>
                                <option value="KRJP Server">日服韩服</option>
                                <option value="KAKAO Server">kakao服</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" id="pubg_display">
                        <label for="config" class="col-sm-3 control-label">组队人数</label>
                        <div class="col-sm-5">
                            <select class="form-control" name="queue" id="queue">
                                <option value="1">单排</option>
                                <option value="2">双排</option>
                                <option value="4">四排</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" id="pubg_display">
                        <label for="config" class="col-sm-3 control-label">队伍数量</label>
                        <div class="col-sm-5 ">
                            <input type="text" class="form-control" id="participate" name="participate" value="0" onkeyup="this.value=this.value.replace(/\D/g,'')">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="startTime" class="col-sm-3 control-label">视角模式</label>
                        <div class="col-sm-5">
                            <select class="form-control" name="mode" id="mode">
                                <option value="tpp">第三人称</option>
                                <option value="fpp">第一人称</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="customer" class="col-sm-3 control-label" >地图</label>
                        <div class="col-sm-5">
                            <select class="form-control" name="map" id="map">
                                <option value="Erangel">海岛</option>
                                <option value="Desert">沙漠</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="endTime" class="col-sm-3 control-label">开始时间</label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input type="text" readonly class="form-control" name="startTime" id="startTime" value="<?= (date('Y-m-d H:i') );?>" >
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
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
        <input type="hidden" name="isRecord" id="isRecord" value=" <?=$isRecord;?>">
    </div>
<?php }else{?>
<div class="col-md-15" id="page">
    <div class="panel panel-primary" data-collapsed="0">
        <div class="panel-heading">
            <div class="panel-title">
                比赛编号: <?=$res['gid'];?>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12" >

                    <div class="col-md-7" id="img_area">
                        <h2>游戏截图</h2>
                        <div class="col-md-6 bbig" >
                            <label for="gameyue" class="control-label" data-validate="required" style="display:none">游戏截图</label>
                            <div class="fileinput fileinput-new big" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="min-width: 100%;" data-trigger="fileinput">
                                    <!-- <input type="file" name="file" id="file" class="fileele" />-->
                                    <img  src="<?= isset($data['matchInfo']['images']) && !empty($data['matchInfo']['images']) ? $data['matchInfo']['images'] : Url::to('@web/images/noimg.png')?>" alt="...">
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" style="min-width: 100%; " id="fileiput-preview">
                                </div>
                                <span class="btn btn-white btn-file" style="display: none">
                                            <input type="file" name="file_1" accept="image/*" class="file" >
                                        </span>
                                <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" value="<?= Url::to(['@web/images/noimg.png'])?>" id="img_address">
                    <div class="col-md-5 rowright">
                        <?php for($key=0;$key<$res['teamCount']; $key++){ ?>
                            <div class="col-md-12 bigteam<?=($key+1);?>">
                                <h2>#<?=($key+1);?></h2>
                                <!--<input type="hidden" name="teamid[]"  value="<?=($key+1);?>">-->
                                <div class="col-sm-11">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">队员</label>

                                        <div class="col-sm-9">

                                            <select name="team[]" class="select2" id="te<?=($key+1);?>" onchange="change(this);" data-allow-clear="true" data-placeholder="请选择">
                                                <option></option>
                                                <optgroup label="搜索结果">
                                                    <?php foreach($res['members'] as $k=>$v){ ?>
                                                        <option value="<?=$v['nickname'];?>|<?=$v['seatNo'];?>"><?=$v['nickname'];?></option>
                                                    <?php } ?>

                                                </optgroup>
                                            </select>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 group"></div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="float: left;" id="display_upload">
        <a href="javascript:void(0);"  class="btn btn-success btn-square radius-4 pull-right" onclick="uploadImg()">
            <i class="entypo-plus"></i>
            上传图片
        </a>
    </div>
    <div class="form-group default-padding form-button positionsave">
        <button type="submit" class="btn btn-success save" id="submit_save">保　存</button>
        <input type="hidden" name="matchId" id="matchId" value=" <?=$res['gid'];?>">
        <input type="hidden" name="createTime" id="createTime" value="<?= isset($res['createTime']) ? $res['createTime'] : 0 ;?>">
        <input type="hidden" name="leagueId" id="leagueId" value=" <?=$res['leagueId'];?>">
        <input type="hidden" name="seasonId" id="seasonId" value=" <?=$res['seasonId'];?>">
        <input type="hidden" name="isRecord" id="isRecord" value=" <?=$isRecord;?>">
        <input type="hidden" name="imgNum" id="imgNum" value="0">
        <a href="<?=\Yii::$app->request->getReferrer();?>" class="btn btn-default">返　回</a>
    </div>
</div>
<?php } ?>


    <?php
    ActiveForm::end();
    Pjax::end();
    ?>
    <script>
        function change(obj){
            var nicknames = obj.value;
            var arr=nicknames.split('|');
            var nickname=arr[0];
            var seatNos=arr[1];
            var selectId = obj.id;
            var steamName=$(obj).find("option:selected").text();
            var selectteam=selectId.substr(2,20);

            var html='';
            html+='<div class="col-sm-5 yuan'+nickname+'">';
            html+='<div class="form-group">';
            html+='<a href="javascript:void(0);" data-rel="close"  onclick="del(this)"><i class="entypo-cancel red"></i></a>';
            html+='<label for="field-1" class="col-sm-6 control-label">'+steamName+'</label>';

            html+='<div class="col-md-5">';
            html+='<input type="text" class="form-control" name="killcount['+seatNos+']['+nickname+'][]" value="0"  placeholder="击杀数">';
            html+='<input type="hidden" class="form-control" name="steamId['+seatNos+'][]" value="'+nickname+'" >';
            html+='<input type="hidden" class="form-control" name="teamRank['+seatNos+'][]" value="'+selectteam+'" >';
            html+='<input type="hidden" class="form-control" name="teamid[]" value="'+seatNos+'" >';
            html+='</div>';
            html+=' </div>';
            html+=' </div>';

            if(nickname){
                $('.bigteam'+selectteam+  ' .group').append(html);
            }
        }

        function del(ob){
            var tes=$(ob).parent().parent();
            tes.remove();
        }
        $(document).ready(function(){
            var isRecord = $("#isRecord").val();
            if(isRecord != 0){
                var createTime = $("#createTime").val();
                if(createTime.length != 10){
                    $("#submit_save").attr('disabled',true)
                }
            }else{
                $('#startTime').daterangepicker({
                    startDate: "<?= isset($data['startTime']) ? $data['startTime'] : date('Y-m-d H:i') ?>",
                    showWeekNumbers : false, //是否显示第几周
                    timePicker : true, //是否显示小时和分钟
                    timePickerIncrement : 5, //时间的增量，单位为分钟
                    timePicker12Hour : false, //是否使用12小时制来显示时间
                    singleDatePicker: true,
                    opens : 'right', //日期选择框的弹出位置
                    buttonClasses : [ 'btn btn-default' ],
                    applyClass : 'btn-small btn-success',
                    cancelClass : 'btn-small',
                    format : 'YYYY-MM-DD HH:mm', //控件中from和to 显示的日期格式
                    separator : ' 至 ',
                    locale : {
                        applyLabel : '确定',
                        cancelLabel : '取消',
                        fromLabel : '开始时间',
                        toLabel : '结束时间',
                        daysOfWeek : [ '日', '一', '二', '三', '四', '五', '六' ],
                        monthNames : [ '一月', '二月', '三月', '四月', '五月', '六月',
                            '七月', '八月', '九月', '十月', '十一月', '十二月' ],

                    }
                }, function(start, end, label) {//格式化日期显示框
                    $('#startTime span').html(start.format('YYYY-MM-DD HH:mm'));
                });
            }

            var screenshots=$("input[name='gameyue']");
            $('.save').on('click', function () {
                if(!screenshots){
                    alert('请上传游戏截图');
                    return false;
                }
            });
            $(".file").change( function() {
                var name=$(this).val()
                var imgNum = $("#imgNum").val()

                if(name){
                    if(imgNum == 0){
                        $("#imgNum").val(1)
                    }
                }
            });
        })

        function uploadImg(){
            var imgNum = $(".bbig").length +1 ;
            var fileName = 'file_' + imgNum;
            var url_address = $("#img_address").val()
            $("#img_area").append("<div class='col-md-7 bbig' style='width:90%'> <label for='gameyue' class='control-label' data-validate='required' style='display:none'>游戏截图</label>" +
                "<div class='fileinput fileinput-new big' data-provides='fileinput'><div class='fileinput-new thumbnail' style='min-width: 100%' data-trigger='fileinput'>" +
                "<img src="+url_address+" alt='...'></div><div class='fileinput-preview fileinput-exists thumbnail' style='min-width: 100%; ' id='fileiput-preview'></div>" +
                "<span class='btn btn-white btn-file' style='display: none'><input type='file' name="+fileName+" accept='image/*' class='file' ></span>" +
                "<a href='#' class='btn btn-orange fileinput-exists' data-dismiss='fileinput'>Remove</a>" +
                "</div>" +
                "</div>");
            $("#imgNum").val(imgNum)
        }
    </script>



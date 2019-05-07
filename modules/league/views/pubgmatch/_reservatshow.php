<?php

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\web\View;

AppAsset::registerCss($this, '@web/plugin/select2/select2.css');
AppAsset::registerCss($this , '@web/plugin/daterangepicker/daterangepicker-bs3.css');
AppAsset::registerJs($this, '@web/plugin/select2/select2.min.js');
AppAsset::registerJs($this, '@web/plugin/fileinput.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/moment.min.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/daterangepicker.js');
Pjax::begin(['id' => 'season']);
?>
<style>
    .big{  }
    .thumbnail{ border:0;  }
    .bbig{ bottom:1%;  }
    .col-md-12{ overflow:hidden;}
    /* .fileinput-exists img{ position: fixed; }*/
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
<div class="col-md-12" id="page">
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
                        <?php if(isset($res['images']) && !empty($res['images']) ){ ?>
                            <?php for ($i=0;$i<count($res['images']);$i++){ ?>
                                <div class="col-md-10 bbig">
                                    <label for="image" class="control-label" data-validate="required" style="display:none">游戏截图</label>
                                    <div class="fileinput fileinput-new big"data-provides="fileinput" >
                                        <div class="fileinput-new thumbnail" style="max-width: 100%;" data-trigger="fileinput">
                                            <!-- <input type="file" name="file" id="file" class="fileele" />-->
                                            <img width="90%" class="demo-img" style="display: block;margin-left: auto;margin-right: auto;"
                                                 src="<?= isset($res['images'][$i]) && !empty($res['images'][$i]) ? $res['images'][$i]: Url::to('@web/images/notimg.png')?>"    >
                                        </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="min-width: 100%; "></div>
                                        <span class="btn btn-white btn-file" style="display: none">
                                                <?php $name = 'file_'.($i+1) ?>
                                            <input type="file" name="<?= $name?>" accept="image/*" class="file">
                                            <input type="hidden" name="<?= 'image_'.($i+1) ?>" value="<?= $res['images'][$i]?>" class="file">
                                            </span>
                                        <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
                                    </div>
                                </div>
                            <?php }?>
                        <?php }else{?>
                            <div class="col-md-5 bbig">
                                <label for="gameyue" class="control-label" data-validate="required" style="display:none">游戏截图</label>
                                <div class="fileinput fileinput-new big" data-provides="fileinput" >
                                    <div class="fileinput-new thumbnail" style="max-width: 100%;" data-trigger="fileinput">
                                        <img width="90%" class="demo-img" style="display: block;margin-left: auto;margin-right: auto;"
                                             src="<?= Url::to('@web/images/notimg.png')?>"    >
                                    </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail" style="min-width: 100%; "></div>
                                    <span class="btn btn-white btn-file" style="display: none">
                                                <input type="file" name="file_1" accept="image/*" class="file">
                                            </span>
                                    <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
                                </div>
                            </div>
                        <?php }?>
                    </div>
                    <input type="hidden" value="<?= Url::to(['@web/images/noimg.png'])?>" id="img_address">
                    <div class="col-md-5 rowright">
                        <?php $i=1;?>
                        <?php foreach($res['members'] as $key=>$value){ ?>
                            <div class="col-md-12 bigteam<?=$i;?>">
                                <h2>#<?=$key;?></h2>
                                <!--<input type="hidden" name="teamid[]"  value="<?=($key+1);?>">-->
                                <div class="col-sm-11">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">队员</label>

                                        <div class="col-sm-9">

                                            <select name="team[]" class="select2" id="te<?=$key;?>" onchange="change(this);" data-allow-clear="true" data-placeholder="请选择">
                                                <option></option>
                                                <optgroup label="搜索结果">
                                                    <?php foreach($res['memberinfo'] as $k=>$v){ ?>
                                                        <option value="<?=$v['nickname'];?>|<?=$v['seatNo'];?>"><?=$v['nickname'];?></option>
                                                    <?php } ?>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 group">
                                    <?php foreach($value as $k=>$v){ ?>
                                        <div class="col-sm-5 yuan<?=$v['nickname'];?>">
                                            <div class="form-group">

                                                <a href="javascript:void(0);" data-rel="close" onclick="del(this)"><i class="entypo-cancel red"></i></a>

                                                <label for="field-1" class="col-sm-6 control-label"><?=$v['nickname'];?></label>

                                                <div class="col-md-5">
                                                    <input type="text" class="form-control" name="killcount[<?=$v['seatNo'];?>][<?=$v['nickname'];?>][]" value="<?= isset($v['killNum']) ? $v['killNum'] : 0;?>"  placeholder="击杀数">
                                                    <input type="hidden" class="form-control" name="steamId[<?=$v['seatNo'];?>][]" value="<?=$v['nickname'];?>" >
                                                    <input type="hidden" class="form-control" name="teamRank[<?=$v['seatNo'];?>][]" value="<?=$v['teamRank'];?>" >
                                                    <input type="hidden" class="form-control" name="teamid[]" value="<?=$v['seatNo'];?>" >
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php $i++;} ?>
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
        <button type="submit" class="btn btn-success save">保　存</button>
        <input type="hidden" name="matchId" id="matchId" value=" <?=$res['gid'];?>">
        <input type="hidden" name="leagueId" id="leagueId" value=" <?=$res['leagueId'];?>">
        <input type="hidden" name="seasonId" id="seasonId" value=" <?=$res['seasonId'];?>">
        <input type="hidden" name="createTime" value="<?=$res['createTime'];?>">
        <input type="hidden" name="imgNum" id="imgNum" value="<?= count($res['images'])?>">
        <a href="<?= Url::to(['/league/pubgmatch/reservation']) ?>" class="btn btn-default">返　回</a>
    </div>
    <?php
    ActiveForm::end();
    Pjax::end();
    ?>
    <script>
        function change(obj){
            //console.log(obj);
            var nicknames = obj.value;
            //console.log(steamIds); return false;
            var arr=nicknames.split('|');
            var nickname=arr[0];
            var seatNos=arr[1];
            var selectId = obj.id;
            var steamName=$(obj).find("option:selected").text();
            var selectteam=selectId.substr(2,20);
            // alert(selectteam);
            var html='';
            html+='<div class="col-sm-5 yuan'+nickname+'">';
            html+='<div class="form-group">';
            html+='<a href="javascript:void(0);" data-rel="close"  onclick="del(this)"><i class="entypo-cancel red"></i></a>';
            // html+='<a href="#" data-rel="close" id="remove" data-title='+steamId+' ><i class="entypo-cancel"></i></a>';
            html+='<label for="field-1" class="col-sm-6 control-label">'+steamName+'</label>';

            html+='<div class="col-md-5">';
            html+='<input type="text" class="form-control" name="killcount['+seatNos+']['+nickname+'][]" value="0"  placeholder="击杀数">';
            html+='<input type="hidden" class="form-control" name="steamId['+seatNos+'][]" value="'+nickname+'" >';
            html+='<input type="hidden" class="form-control" name="teamRank['+seatNos+'][]" value="'+selectteam+'" >';
            html+='<input type="hidden" class="form-control" name="teamid[]" value="'+seatNos+'" >';
            html+='</div>';
            html+=' </div>';
            html+=' </div>';
            // alert('.bigteam'+selectteam+  '#group'+selectteam);
            if(nickname){
                $('.bigteam'+selectteam+  ' .group').append(html);
            }
        }

        function del(ob){
            var tes=$(ob).parent().parent();
            tes.remove();
        }

        $(document).ready(function(){
            var screenshots=$("input[name='gameyue']");
            $('.save').on('click', function () {
                if(!screenshots){
                    alert('请上传游戏截图');
                    return false;
                }
            });
            $(".file").on('change',function() {
                var name=$(this).val()
                var imgNum = $("#imgNum").val()

                if(name){
                    if(imgNum == 0){
                        $("#imgNum").val(1)
                    }
                }
                // alert(222);
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



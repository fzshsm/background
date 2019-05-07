<?php

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

AppAsset::registerCss($this, '@web/plugin/select2/select2.css');
AppAsset::registerCss($this , '@web/plugin/daterangepicker/daterangepicker-bs3.css');
AppAsset::registerJs($this, '@web/plugin/select2/select2.min.js');
AppAsset::registerJs($this, '@web/plugin/fileinput.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/moment.min.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/daterangepicker.js');

Pjax::begin(['id' => 'season']);
?>
    <style>
        .col-md-3{ min-height:330px;}
        .teamRankSort{ width:100%;}

        .teamName {
            width: 60%;
            float: left;
            font-size: 15px;
            color: #333;
            font-weight: 400;
        }
        .teamlogo{ border-radius:100px; width:50px; height:50px;vertical-align: middle;}
        .teamsort{ width:40%; float:left;vertical-align: middle;font-size:15px;height:50px; line-height:50px; }
        .teamRank{ width:60%;height:28px;margin-top:11px}
        .teamallsort{ width:35%; float:right; padding-right:3% }
        #killcount{ width:80px}
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
    <div class="col-md-15" id="page">
    <div class="panel panel-primary" data-collapsed="0">
    <div class="panel-heading">
        <div class="panel-title">
            比赛编号: <?=$data['matchInfo']['gid'];?>
        </div>
    </div>
    <div class="panel-body">
<div class="row">

<?php foreach($data['teamInfo'] as $key=>$value){ ?>

    <div class="col-md-3">


        <input type="hidden" name="teamid[]" id="teamid" value="<?=$key;?>">
        <div class="teamRankSort"> <label  class="teamName"><img src="<?=$value['teamLogo'];?>" class="teamlogo"><?=$value['teamName'];?></label>
            <div class="teamallsort">
                <label for="teamRank" class="teamsort">排名：</label>
                <input type="text" placeholder="排名" class="form-control teamRank" name="teamRank[<?=$key;?>][]" id="teamRank" value="<?= isset($value['teamRank']) ? $value['teamRank'] : '';?>" >
            </div>
        </div>
        <table class="table responsive">
            <thead>
            <tr>
               <!-- <th>id</th>-->
                <td width="80%">队员</td>
                <td width="20%">击杀数</td>
            </tr>
            </thead>

            <tbody>

        <?php foreach($value['teamPlayers'] as $k=>$v){ ?>
            <input type="hidden" name="steamId[<?=$key;?>][]"  value="<?=$k;?>">
            <tr>
               <!-- <td>1</td>-->
                <td><?=$v['nickname'];?></td>
                <td>
                    <input type="text" class="form-control" name="killcount[<?=$key;?>][<?=$k;?>][]" placeholder="击杀数" id="killcount" value="<?= isset($v['kill_num']) ? $v['kill_num'] : '';?>" >
                </td>
            </tr>
        <?php } ?>

            </tbody>
        </table>

    </div>
<?php } ?>

    <div class="col-md-3" style="clear:both">
        <div class="form-group">
            <label for="screenshots" class="col-sm-3 control-label" data-validate="required">游戏截图</label>
            <div class="col-sm-5">
                <div class="fileinput fileinput-new" data-provides="fileinput">
                    <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;" data-trigger="fileinput">
                        <img src="<?= isset($data['matchInfo']['images']) && !empty($data['matchInfo']['images']) ? $data['matchInfo']['images'] : Url::to('@web/images/200x200.png')?>" alt="...">
                    </div>
                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px"></div>
                    <span class="btn btn-white btn-file" style="display: none">
                                        <input type="file" name="screenshots" accept="image/*" >
                                    </span>
                    <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
                </div>
            </div>
        </div>
    </div>
</div>


    </div>
    </div>
    </div>
<div class="form-group default-padding form-button">
    <button type="submit" class="btn btn-success save">保　存</button>
    <input type="hidden" name="matchId" id="matchId" value="<?=$data['matchInfo']['gid'];?>">
    <a href="<?= Url::to(['/league/pubgmatch']) ?>" class="btn btn-default">返　回</a>
</div>
<?php
ActiveForm::end();
Pjax::end();
?>



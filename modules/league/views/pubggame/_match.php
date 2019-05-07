<?php
use yii\helpers\Url;
use yii\helpers\Html;
?>
<div class="cbp_tmlabel">
    <p class="league-info"><?= isset($leagueData[$model['leagueId']]) ? $leagueData[$model['leagueId']]['name'] : '' ?> - <?= isset($seasonData[$model['seasonId']]) ? $seasonData[$model['seasonId']]['name'] : '' ?></p>
    <?php
        $text = '';
        $badgeClass = '';
        $borderClass = '';

        switch($model['winner']){
            case 2 :
                $text = '胜';
                $badgeClass = 'badge-success';
                $borderClass = 'border-success';
                break;
            case 3 :
                $text = '负';
                $badgeClass = 'badge-danger';
                $borderClass = 'border-fail';
                break;
            case 4 :
                $text = '平';
                $badgeClass = 'badge-warning';
                $borderClass = 'border-same';
                break;
            default:
                $text = '';
                $badgeClass = 'badge-info';
                $borderClass = '';
                break;
        }
    ?>
    <?php
        $roleId = '';
        if('roleId' == Yii::$app->request->get('searchType')){
            $roleId = Yii::$app->request->get('content');
        }
    ?>
    <div class="col-md-6" style="margin-left: 10px">
        <?php if($model['winner'] > 0){ ?>
            <span class="badge <?=$badgeClass?>"><?=$text?></span>
        <?php } ?>
        <?php foreach($model['mathMembers'] as $member){?>
            <?php
            $voteText = '';
            $voteColor = '';
            switch ($member['vote']){
                case 3:
                    $voteColor = 'red';
                    $voteText = '投票赢';
                    break;
                case 4:
                    $voteColor = 'orange';
                    $voteText = '平局';
                    break;
            }
            $isVote = '';
            $isVoteColor = '';
            switch ($member['hasCommit']){
//                case 0:
//                    $isVote = '未投票';
//                    $isVoteColor = 'red';
//                    break;
                case 1:
                    $isVote = '已投票';
                    $isVoteColor = 'green';
                    break;
            }
            ?>
        <div class="col-xs-2">
            <div class="image">
                <img src="<?= isset($member['headImg']) ? $member['headImg'] : ''?>" width="50" height="50" title="<?= isset($member['roleId']) ? $member['roleId'] : '' ?>">
                <?php if(isset($member['gameScore']) && $member['gameScore'] != 0){ ?>
                <i class="badge <?= $member['gameScore'] > 0 ? 'badge-success' : 'badge-danger' ?> "><?= ($member['gameScore'] > 0 ? '+' : '' ) . $member['gameScore']?></i>
                <?php } ?>
            </div>
            <?php
                $color = '';
                if(!empty($roleId)){
                    if($roleId == (isset($member['roleId']) ? $member['roleId'] : '')){
                        $color = 'color:red';
                    }
                }
            ?>
            <?php if(isset($member['roleId'])){?>
                <?= Html::a("<span title=".$member['roleId']." style=".$color.">".$member['roleId']."</span>",Url::to(['/league/pubgmember','leagueId' => $model['leagueId'],'search[type]' => 'role','search[value]' => $member['roleId']]),['target' => '_blank','data-pjax'=>"0"])?>
            <?php }?>
            <!--
                    段位段位段位段位
            -->
            <?php if($model['status'] == 6){ ?>
                <span title="<?= $isVote?>" style="color: <?=$isVoteColor?>"><?= $isVote?></span>
            <?php }else{?>
                <span title="<?= $voteText?>" style="color: <?=$voteColor?>"><?= $voteText?></span>
            <?php }?>
        </div>
        <?php } ?>
    </div>
    <div class="col-md-1 text-center">
        <?php if($model['status'] <= 6){ ?>
            <a data-game-id="<?= $model['id']?>" data-result="<?= $model['winner']?>" data-datetime="<?= !empty($model['startTime']) ? date('Y-m-d H:i' , $model['startTime'] / 1000) : '' ?>"  class="btn btn-info game-transform" href="javascript:void(0);">更改</a>
        <?php }?>
    </div>
</div>

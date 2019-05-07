<?php

use app\assets\AppAsset;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
$leagueId = Yii::$app->request->get('leagueId' , 0);
$this->params['breadcrumbs'] = [
    [
        'label' => '联赛管理' ,
        'url'   => Url::to( [
            '/league' ,
        ] ) ,
    ] ,
    [
        'label' => $matchName ,
        'url'   => '' ,
    ] ,
    [ 'label' => '赛季管理' ] ,
];
AppAsset::registerCss( $this , '@web/plugin/datatables/datatables.min.css' );
AppAsset::registerJs($this, '@web/js/common.js');
Pjax::begin(['id' => 'season']);
$text = Html::tag('i' , '' , ['class' => 'entypo-plus']) . '创建';
 if ($gameType == 'pubg') {
     $creatUrl = '/league/pubgseason/create';
 }else{
     $creatUrl = '/league/gloryseason/create';
 }
echo Html::a($text , Url::to([$creatUrl , 'leagueId' => $leagueId]) , ['class' => 'btn btn-success btn-square radius-4 pull-right','id' => 'create_url']);

?>
<div class="row">
    <div class="col-md-8">
        <?php if(Yii::$app->session->hasFlash('error')){ ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?=Yii::$app->session->getFlash('error')?>
            </div>
        <?php }?>
    </div>
</div>

<div class="col-md-12">
    <?php if(empty($leagueId)){ ?>
    <ul class="nav nav-tabs bordered" style="margin-bottom: 10px">
        <?php if ($gameType == 'glory') { ?>
            <?php $glory = 'active';$pubg = ''; ?>
        <?php } else { ?>
            <?php $glory = '';
            $pubg = 'active'; ?>
        <?php } ?>
        <li class="<?= $glory ?>">
            <?php
            $gloryUrl = Url::to(['/league/gloryseason']);
            if(!empty($leagueId)){
                $gloryUrl = $gloryUrl.'?leagueId='.$leagueId;
            }
            ?>
            <a data-status="1" href="<?= $gloryUrl ?>" onclick="gloryUrl()">
                <span>王者荣耀</span>
            </a>
        </li>
        <li class="<?= $pubg ?>">
            <?php
                $pubgUrl = Url::to(['/league/pubgseason']);
                if(!empty($leagueId)){
                    $pubgUrl = $pubgUrl.'?leagueId='.$leagueId;
                }
            ?>
            <a data-status="2" href="<?= $pubgUrl?>" onclick="pubgUrl()">
                <span>绝地求生</span>
            </a>
        </li>
    </ul>
    <?php } ?>
<?php
if($gameType == 'glory'){
    echo GridView::widget( [
        'id'               => 'season' ,
        'dataProvider'     => $dataProvider ,
        'emptyText'        => "暂无{$matchName}赛季信息！" ,
        'emptyTextOptions' => [ 'class' => 'text-center' ] ,
        'tableOptions'     => [ 'class' => 'table table-bordered datatable no-footer hover stripe text-center dataTable' ] ,
        'options'          => [ 'class' => 'dataTables_wrapper no-footer no-border' ] ,
        'layout'           => "{errors}{items}{pager}" ,
        "columns"          => [
            'id:raw:ID' ,
            'seasonName:text:名称',
            [
                'attribute' => 'parentId' ,
                'label'     => '所属联赛' ,
                'value'     => function() use ( $matchName ){
                    return $matchName;
                } ,
            ] ,
            [
                'attribute' => 'reward' ,
                'label'     => '奖金' ,
                'format'    => 'html' ,
                'value'     => function( $model ){
//                    if(preg_match('/[\x7f-\xff]/', $model['reward'])){
//                        $reward = $model['reward'];
//                    }else{
//                        $reward = number_format($model['reward']);
//                    }

                    return Html::tag( 'span' ,  $model['reward'] , [ 'class' => 'color-red' ] );
                } ,
            ] ,
            'bonusName:text:奖金方案' ,
            'startTime:date:开始时间' ,
            'endTime:date:结束时间' ,
            [
                'attribute' => 'status' ,
                'label'     => '状态' ,
                'format' => 'html',
                'value' => function($model){
                    $className = "";
                    $text = "";
                    switch ($model['status']){
                        case 1 :
                            $className = ' label-default';
                            $text = '未开始';
                            break;
                        case 2 :
                            $className = ' label-success';
                            $text = '进行中';
                            break;
                        case 3 :
                            $className = ' label-primary';
                            $text = '已关闭';
                            break;
                    }
                    return Html::tag( 'span' , $text , ['class' => 'label ' . $className]);
                }
            ] ,
            [
                'class'    => ActionColumn::className() ,
                'template' => '{update}{bonus}{detail}' ,
                'header'   => '操作' ,
                'contentOptions' => ['class' => 'actions'],
                'buttons'  => [
                    'update' => function( $url )use($leagueId){
                        $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-pencil' ] );
                        return Html::a( $icon . '编辑' , $url.'&leagueId='.$leagueId , [ 'class' => 'btn btn-default btn-sm btn-icon icon-left' ] );
                    } ,
                    'bonus' => function( $url ,$model){
                        if($model['isBonus'] == 0){
                            $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-cny' ] );
                            return Html::a( $icon . '奖金发放' , $url , [ 'class' => 'btn btn-success btn-sm btn-icon icon-left send-bonus','data-name' => $model['seasonName'] ] );
                        }
                    } ,
                    'detail' => function( $url,$model )use($leagueId){
                        if($model['isBonus'] != 0) {
                            $icon = Html::tag('i', '', ['class' => 'fa fa-bars']);
                            return Html::a($icon . '奖金详情', $url.'&seasonName='.$model['seasonName'].'&leagueId='.$leagueId , ['class' => 'btn btn-info btn-sm btn-icon icon-left']);
                        }
                    } ,
                ] ,
            ] ,
        ] ,
        'pager'            => [
            'options'     => [ 'class' => 'pagination dataTables_paginate paging_simple_numbers' ] ,
            'linkOptions' => [ 'class' => 'paginate_button' ] ,
        ] ,
    ] );
}else{
    echo GridView::widget( [
        'id'               => 'season' ,
        'dataProvider'     => $dataProvider ,
        'emptyText'        => "暂无{$matchName}场次信息！" ,
        'emptyTextOptions' => [ 'class' => 'text-center' ] ,
        'tableOptions'     => [ 'class' => 'table table-bordered datatable no-footer hover stripe text-center dataTable' ] ,
        'options'          => [ 'class' => 'dataTables_wrapper no-footer no-border' ] ,
        'layout'           => "{errors}{items}{pager}" ,
        "columns"          => [
            'id:raw:ID' ,
            'seasonName:text:名称' ,
            'leagueName:text:所属联赛',
            [
                'attribute' => 'reward' ,
                'label'     => '奖金' ,
                'format'    => 'html' ,
                'value'     => function( $model ){
//                    if(preg_match('/[\x7f-\xff]/', $model['reward'])){
//                        $reward = $model['reward'];
//                    }else{
//                        $reward = number_format($model['reward']);
//                    }
                    return Html::tag( 'span' ,  $model['reward'], [ 'class' => 'color-red' ] );
                } ,
            ] ,
            'startTime:text:开始时间' ,
            'endTime:text:结束时间' ,
            [
                'attribute' => 'status' ,
                'label'     => '状态' ,
                'format' => 'html',
                'value' => function($model){
                    $className = "";
                    $text = "";
                    switch ($model['status']){
                        case 1 :
                            $className = ' label-default';
                            $text = '未开始';
                            break;
                        case 2 :
                            $className = ' label-success';
                            $text = '进行中';
                            break;
                        case 3 :
                            $className = ' label-primary';
                            $text = '已关闭';
                            break;
                        case 4 :
                            $className = ' label-primary';
                            $text = '关闭且已结算';
                            break;
                    }
                    return Html::tag( 'span' , $text , ['class' => 'label ' . $className]);
                }
            ] ,
            [
                'class'    => ActionColumn::className() ,
                'template' => '{update}{pubgmatch}' ,
                'header'   => '操作' ,
                'contentOptions' => ['class' => 'actions'],
                'buttons'  => [
                    'update' => function( $url ) use($leagueId){
                        $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-pencil' ] );
                        return Html::a( $icon . '编辑' , $url.'&leagueId='.$leagueId, [ 'class' => 'btn btn-default btn-sm btn-icon icon-left' ] );
                    } ,
                    'pubgmatch' => function($url,$model)use($leagueId){
                        $url = Url::to(['/league/pubgmatch','seasonId' => $model['seasonId'],'seasonName' => $model['seasonName']]);
                        $icon = Html::tag('i','',['class' => 'fa fa-bars']);
                        return Html::a($icon . '场次', $url.'&leagueId='.$leagueId, ['class' => 'btn btn-success btn-sm btn-icon icon-left'] );
                    }
                ] ,
            ] ,
        ] ,
        'pager'            => [
            'options'     => [ 'class' => 'pagination dataTables_paginate paging_simple_numbers' ] ,
            'linkOptions' => [ 'class' => 'paginate_button' ] ,
        ] ,
    ] );
}


?>
</div>
<script>
    $(document).ready(function(){
//        $(".nav-tabs a").click(function(){
//            var status = $(this).attr('data-status');console.log(status);
//            if(status == 2){
//                $("#create_url").attr('href','<?//= Url::to(['/league/pubgseason/create'])?>//');
//            }else{
//                $("#create_url").attr('href','<?//= Url::to(['/league/gloryseason/create'])?>//');
//            }
//        });

        $('a.send-bonus').click(function(){
            var seasonName = $(this).attr('data-name')
            var confirmText = $('<span>').addClass('color-orange font-16').html("确定将进行（"+seasonName+"）结算吗?该操作只能进行一次，无法回档，你确定继续吗？");
            var successText = "（"+seasonName+"）结算成功！";
            showConfirmModal(this,confirmText, successText);
            return false;
        });
    })

    function gloryUrl(){
        $("#create_url").attr('href','<?= Url::to(['/league/gloryseason/create','leagueId' => $leagueId])?>');
    }

    function pubgUrl(){
        $("#create_url").attr('href','<?= Url::to(['/league/pubgseason/create','leagueId' => $leagueId])?>');
    }



</script>
<?php
Pjax::end();
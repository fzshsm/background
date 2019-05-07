<?php

use app\assets\AppAsset;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->params['breadcrumbs'] = [
    [
        'label' => '联赛管理' ,
        'url'   => Url::to( [
            '/league/pubg' ,
        ] ) ,
    ] ,
    [
        'label' => $matchName ,
        'url'   => '' ,
    ] ,
    [ 'label' => '绝地求生赛季管理' ] ,
];
AppAsset::registerCss( $this , '@web/plugin/datatables/datatables.min.css' );
$text = Html::tag('i' , '' , ['class' => 'entypo-plus']) . '创建';
echo Html::a($text , Url::to(['/league/pubgseason/create' , 'leagueId' => $leagueId]) , ['class' => 'btn btn-success btn-square radius-4 pull-right']);
Pjax::begin();
?>
    <div class="col-sm-1 padding-left-8">
        <div class="btn-group game-status">
            <?php
            $gameStatus = Yii::$app->request->get('status' , 1);
            $gameStatusList = [0 => ['game' => '王者荣耀','url' => Url::to(['/league/glorysession'])], 1 => ['game' => '绝地求生','url' => Url::to(['/league/pubgsession'])]];
            ?>
            <input type="hidden" id="status" name="status" value="<?=$gameStatus?>">
            <button id="game-status" type="button" class="btn btn-blue dropdown-toggle" data-toggle="dropdown" data-status="<?=$gameStatus?>">
                <?= $gameStatusList[$gameStatus]['game']?>　<span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-darkblue">
                <?php foreach($gameStatusList as $key => $value){ ?>
                    <li><a href="<?= $value['url']?>" data-status="<?= $key?>"><?= $value['game']?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
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
            'name:text:名称' ,
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
                    if(preg_match('/[\x7f-\xff]/', $model['reward'])){
                        $reward = $model['reward'];
                    }else{
                        $reward = number_format($model['reward']);
                    }
                    return Html::tag( 'span' ,  $reward , [ 'class' => 'color-red' ] );
                } ,
            ] ,
            [
                'attribute' => 'signinCount' ,
                'label'     => '报名人数' ,
                'format'    => 'html' ,
                'value' => function($model){
                    return Html::tag( 'span' , $model['signinCount'] , ['class' => 'badge badge-info']);
                }
            ] ,
            'startTime:date:开始时间' ,
            'endTime:date:结束时间' ,
            'createTime:date:创建时间' ,
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
                'template' => '{update}' ,
                'header'   => '操作' ,
                'contentOptions' => ['class' => 'actions'],
                'buttons'  => [
                    'update' => function( $url ){
                        $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-pencil' ] );
                        return Html::a( $icon . '编辑' , $url , [ 'class' => 'btn btn-default btn-sm btn-icon icon-left' ] );
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
//            [
//                'attribute' => 'signinCount' ,
//                'label'     => '报名人数' ,
//                'format'    => 'html' ,
//                'value' => function($model){
//                    return Html::tag( 'span' , $model['signinCount'] , ['class' => 'badge badge-info']);
//                }
//            ] ,
            'startTime:date:开始时间' ,
            'endTime:date:结束时间' ,
            'createTime:date:创建时间' ,
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
                            $text = '已结束';
                            break;
                        case 4:
                            $className = ' label-primary';
                            $text = '结束且已结算';
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
                    'update' => function( $url ){
                        $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-pencil' ] );
                        return Html::a( $icon . '编辑' , $url , [ 'class' => 'btn btn-default btn-sm btn-icon icon-left' ] );
                    } ,
                    'pubgmatch' => function($url){
                        $icon = Html::tag('i','',['class' => 'fa fa-bars']);
                        return Html::a($icon . '场次', $url, ['class' => 'btn btn-info btn-sm btn-icon icon-left'] );
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

Pjax::end();
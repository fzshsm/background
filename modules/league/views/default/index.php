<?php
use app\assets\AppAsset;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

$this->params['breadcrumbs'] = [
    [
        'label' => '联赛管理',
        'url' => Url::to([
            '/league'
        ] )
    ],
    [
        'label' => '联赛列表'
    ]
];

AppAsset::registerCss($this, '@web/plugin/select2/select2.css');
AppAsset::registerJs($this, '@web/plugin/select2/select2.min.js');
AppAsset::registerCss($this, '@web/plugin/datatables/datatables.min.css');
AppAsset::registerCss($this, '@web/plugin/shCircleLoader/jquery.shCircleLoader.css');
AppAsset::registerCss($this, '@web/plugin/viewer/viewer.css');

AppAsset::registerJs($this, '@web/plugin/datatables/datatables.min.js');

AppAsset::registerJs($this, '@web/plugin/shCircleLoader/jquery.shCircleLoader-min.js');
AppAsset::registerJs($this, '@web/plugin/viewer/viewer.js');
AppAsset::registerJs($this , '@web/js/common.js');
Pjax::begin();
?>
<?php if(Yii::$app->session->hasFlash('error')){ ?>
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <?=Yii::$app->session->getFlash('error')?>
    </div>
<?php }?>
<style>
    th{font-weight:bold;font-size:13px}
    table a.btn{margin-top: 4px}
</style>
<?php
$form = ActiveForm::begin([
    'id' => 'news-form',
    'method' => 'get',
    'options' => ['data-pjax' => true , 'league' => 'form']
] );
?>
<?php if($gameType == 'glory'){ ?>
    <?php $url = '/league/create'?>
<?php }else{ ?>
    <?php $url = '/league/pubg/create'?>
<?php } ?>
<a href="<?=Url::to([$url])?>" id="create_url" class="btn btn-success btn-square radius-4 pull-right">
    <i class="entypo-plus"></i>
    创建
</a>

<div class="col-md-12">
    <ul class="nav nav-tabs bordered" style="margin-bottom: 10px">
        <?php if($gameType == 'glory'){?>
            <?php $glory='active';$pubg= '';?>
        <?php }else{?>
            <?php $glory='';$pubg= 'active';?>
        <?php }?>
        <li class="<?= $glory?>">
            <a data-status="1" href="<?= Url::to(['/league'])?>">
                <span>王者荣耀</span>
            </a>
        </li>
        <li class="<?= $pubg?>">
            <a data-status="2" href="<?= Url::to(['/league/pubg'])?>">
                <span>绝地求生</span>
            </a>
        </li>
    </ul>
<div id="league-search" style="margin-bottom: 10px;margin-top: 5px;float: right" >
    <div class="col-sm-1 refresh" style="margin-right: 5%">
        <?php if($gameType == 'glory'){ ?>
            <?php $refreshUrl = '/league'?>
        <?php }else{ ?>
            <?php $refreshUrl = '/league/pubg'?>
        <?php } ?>
        <a href="<?= Url::to([$refreshUrl])?>" class="btn btn-default" title="刷新">
            <i class="fa fa-refresh"></i>
        </a>
    </div>

    <div class="input-group league-search-type" style="width: 400px">
        <div class="input-group-btn search-type" style="padding-right: 10px">
            <?php
                $searchType = Yii::$app->request->get('searchType' , 0);
            ?>
            <input type="hidden" id="searchType" name="searchType" value="<?=$searchType?>">
            <button type="button" class="btn btn-info dropdown-toggle type" style="width: 80px" data-searchtype="<?= $leagueSortList[$searchType]['id']?>"  data-toggle="dropdown">
                <?= $leagueSortList[$searchType]['name'] ?>　<span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-infoblue searchtype">
                <?php foreach($leagueSortList as $sort){ ?>
                    <li>
                        <a data-searchtype="<?= $sort['id'] ?>" href="javascript:void(0);"><?= $sort['name'] ?></a>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <div class="input-group-btn search-type">
            <button type="button" class="btn btn-success  " style="width: 60px" data-searchtype="name"  data-toggle="dropdown">
                联赛名
            </button>
        </div>
        <?php
            $content = \Yii::$app->request->get('content','');
        ?>
        <input type="text" id="content" class="form-control" name="content" placeholder="" value="<?= !empty($content) ? $content : ''?>">
        <div class="input-group-btn">
            <button  type="submit" class="btn btn-success search">
                <i class="entypo-search"></i>
            </button>
        </div>
    </div>
</div>
<?php
ActiveForm::end();
if($gameType == 'glory'){
    echo GridView::widget( [
        'id'               => 'glory_league',
        'dataProvider'     => $responseData ,
        'emptyText'        => "暂无联赛信息！" ,
        'emptyTextOptions' => [ 'class' => 'text-center' ] ,
        'tableOptions'     => [ 'class' => 'table table-bordered datatable no-footer hover stripe text-center','id'=> 'show_glory_image' ] ,
        'options'          => [ 'class' => 'dataTables_wrapper no-footer no-border' ] ,
        'layout'           => "{errors}{items}{pager}" ,
        "columns"          => [
            [
                'label' => 'ID',
                'attribute' => 'id',
                'value' => 'id',
                'headerOptions' =>['width' => '2%']
            ],
            [
                'label' => '名称',
                'attribute' => 'leagueName',
                'value' => 'leagueName',
                'headerOptions' =>['width' => '5%'],
            ],
            [
                'label' => '封面',
                'format' => [
                    'image',
                    [
                        'height'=>'80',
                        'name' => 'show_image'
                    ]
                ],
                'value' => function ($model) {
                    return $model['cover'];
                },
                'headerOptions' =>['width' => '8%']
            ],
            [
                'label' => '分享logo',
                'format' => [
                    'image',
                    [
                        'width'=>'150',
                        'height'=>'80',
                        'name' => 'show_image'
                    ]
                ],
                'value' => function ($model) {
                    return $model['shareIcon'];
                },
                'headerOptions' =>['width' => '8%']
            ],
            [
                'label' => '分享联赛图',
                'format' => [
                    'image',
                    [
                        'width'=>'150',
                        'height'=>'80',
                        'name' => 'show_image'
                    ]
                ],
                'value' => function ($model) {
                    return $model['shareCover'];
                },
                'headerOptions' =>['width' => '8%']
            ],
            [
                'label' => '联赛分类',
                'attribute' => 'flag',
                'value' => 'flag',
                'headerOptions' =>['width' => '5%'],
            ],
            [
                'label' => '联赛模式',
                'attribute' => 'typeName',
                'value' => 'typeName',
                'headerOptions' =>['width' => '5%'],
            ],
            [
                'label' => '奖金',
                'attribute' => 'reward',
                'value' => 'reward',
                'headerOptions' =>['width' => '4%'],
            ],
            [
                'label' => '等级',
                'attribute' => 'level',
                'value' => 'level',
                'headerOptions' =>['width' => '4%'],
            ],
            [
                'label' => '成员人数',
                'attribute' => 'signCount',
                'value' => 'signCount',
                'headerOptions' =>['width' => '5%'],
            ],
            [
                'label' => '举办单位',
                'attribute' => 'sponsor',
                'value' => 'sponsor',
                'headerOptions' =>['width' => '8%'],
            ],
            [
                'label' => '简介',
                'format' => 'raw',
                'headerOptions' =>['width' => '20%'],
                //'contentOptions' => ['class' => 'text-mess msg'],
                'value' => function ($model) {
                    return '<div class="text-intro"  onmousemove="mousemove($(this))" onmouseout="mouseout($(this))">'  .$model['leagueDescribe'] . '</div>';
                }
            ],
            [
                'label' => '创建时间',
                'attribute' => 'createTime',
                'value'=>'createTime',
                'headerOptions' => ['width' => '6%'],
            ],
            [
                'class'    => ActionColumn::className() ,
                'template' => '{status}' ,
                'header'   => '状态' ,
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['width' => '4%'],
                'buttons'  => [
                    'status' => function($url,$model){
                        if($model['status'] == 1) {
                            return Html::tag( 'span' , '未开始' , [ 'class' => 'label label-default'] );
                        }
                        if($model['status'] == '2') {
                            return Html::tag( 'span' , '进行中' , [ 'class' => 'label label-success'] );
                        }
                        if($model['status'] == '3') {
                            return Html::tag( 'span' , '已关闭' , [ 'class' => 'label label-primary'] );
                        }
                    } ,
                ] ,
            ],
            [
                'class'    => ActionColumn::className() ,
                'template' => '{update}{season}{member}{game}{notice}' ,
                'header'   => '操作' ,
                'contentOptions' => ['class' => 'actions'],
                'headerOptions' =>['width' => '20%'],
                'buttons'  => [
                    'update' => function( $url ){
                        $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-pencil' ] );
                        return Html::a( $icon . '编辑' , $url , [ 'class' => 'btn btn-default btn-sm btn-icon icon-left'] );
                    } ,
                    'season' => function ($url,$model) {
                        $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-bars' ] );
                        if($model['status'] == '2') {
                            return Html::a($icon . '赛季', Url::to(['/league/gloryseason', 'leagueId' => $model['id']]), ['class' => 'btn btn-orange btn-sm btn-icon icon-left']);
                        }
                    },
                    'member' => function ($url,$model) {
                        $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-users' ] );
                        if($model['status'] == '2'){
                            return Html::a($icon.'成员',Url::to(['/league/glorymember','leagueId' => $model['id']]),['class' => 'btn btn-info btn-sm btn-icon icon-left','data-name' => $model['name']]);
                        }
                    },
                    'game' => function ($url,$model) {
                        $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-gamepad' ] );
                        if($model['status'] == '2'){
                            return Html::a($icon.'游戏',Url::to(['/league/glorygame','leagueId' => $model['id']]),['class' => 'btn btn-success btn-sm btn-icon icon-left','data-name' => $model['name']]);
                        }
                    },
                    'notice' => function ($url,$model) {
                        $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-sticky-note-o' ] );
                        if($model['status'] == '2'){
                            return Html::a($icon.'公告',Url::to(['/league/glorynotice','id' => $model['id']]),['class' => 'btn btn-primary btn-sm btn-icon icon-left','data-name' => $model['name']]);
                        }
                    }
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
        'id'               => 'pubg_league' ,
        'dataProvider'     => $responseData ,
        'emptyText'        => "暂无联赛信息！" ,
        'emptyTextOptions' => [ 'class' => 'text-center' ] ,
        'tableOptions'     => [ 'class' => 'table table-bordered datatable no-footer hover stripe text-center','id'=> 'show_pubg_image' ] ,
        'options'          => [ 'class' => 'dataTables_wrapper no-footer no-border' ] ,
        'layout'           => "{errors}{items}{pager}" ,
        "columns"          => [
            [
                'label' => 'ID',
                'attribute' => 'id',
                'value' => 'id',
                'headerOptions' =>['width' => '2%']
            ],
            [
                'label' => '名称',
                'attribute' => 'leagueName',
                'value' => 'leagueName',
                'headerOptions' =>['width' => '5%'],
            ],
            [
                'label' => '封面',
                'format' => [
                    'image',
                    [
                        'height'=>'80',
                        'name' => 'show_image'
                    ]
                ],
                'value' => function ($model) {
                    return $model['cover'];
                },
                'headerOptions' =>['width' => '8%']
            ],
            [
                'label' => '分享logo',
                'format' => [
                    'image',
                    [
                        'width'=>'150',
                        'height'=>'80',
                        'name' => 'show_image'
                    ]
                ],
                'value' => function ($model) {
                    return $model['shareIcon'];
                },
                'headerOptions' =>['width' => '8%']
            ],
            [
                'label' => '分享联赛图',
                'format' => [
                    'image',
                    [
                        'height'=>'80',
                        'name' => 'show_image'
                    ]
                ],
                'value' => function ($model) {
                    return $model['shareCover'];
                },
                'headerOptions' =>['width' => '8%']
            ],
            [
                'label' => '联赛分类',
                'attribute' => 'leagueCategory',
                'value' => 'leagueCategory',
                'headerOptions' =>['width' => '5%'],
            ],
            [
                'label' => '奖金',
                'attribute' => 'reward',
                'value' => 'reward',
                'headerOptions' =>['width' => '4%'],
            ],
            [
                'label' => '报名人数',
                'attribute' => 'signinCount',
                'value' => 'signCount',
                'headerOptions' =>['width' => '5%'],
            ],
            [
                'label' => '举办单位',
                'attribute' => 'sponsor',
                'value' => 'sponsor',
                'headerOptions' =>['width' => '4%'],
            ],
            [
                'label' => '简介',
                'format' => 'raw',
                'headerOptions' =>['width' => '20%'],
//                'contentOptions' => ['class' => 'text-mess msg'],
                'value' => function ($model) {
                    return '<div class="text-intro"  onmousemove="mousemove($(this))" onmouseout="mouseout($(this))">' . $model['leagueDescribe'] . '</div>';
                }
            ],
            [
                'label' => '创建时间',
                'attribute' => 'createTime',
                'value'=>'createTime',
                'headerOptions' => ['width' => '6%'],
            ],
            [
                'label' => '机器人',
                'attribute' => 'robotName',
                'value' => 'robotName',
                'headerOptions' =>['width' => '4%'],
            ],
            [
                'class'    => ActionColumn::className() ,
                'template' => '{status}' ,
                'header'   => '状态' ,
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['width' => '4%'],
                'buttons'  => [
                    'status' => function($url,$model){
                        if($model['status'] == 1) {
                            return Html::tag( 'span' , '未开始' , [ 'class' => 'label label-default'] );
                        }
                        if($model['status'] == '2') {
                            return Html::tag( 'span' , '进行中' , [ 'class' => 'label label-success'] );
                        }
                        if($model['status'] == '3') {
                            return Html::tag( 'span' , '结束' , [ 'class' => 'label label-primary'] );
                        }
                        if($model['status'] == '4') {
                            return Html::tag( 'span' , '结束且已结算' , [ 'class' => 'label label-primary'] );
                        }
                    } ,
                ] ,
            ],
            [
                'class'    => ActionColumn::className() ,
                'template' => '{update}{season}{member}{bind}{unbind}' ,
                'header'   => '操作' ,
                'contentOptions' => ['class' => 'actions'],
                'headerOptions' =>['width' => '20%'],
                'buttons'  => [
                    'update' => function( $url ){
                        $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-pencil' ] );
                        return Html::a( $icon . '编辑' , $url , [ 'class' => 'btn btn-default btn-sm btn-icon icon-left'] );
                    } ,
                    'season' => function ($url,$model) {
                        $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-bars' ] );
                        if($model['status'] == '2') {
                            return Html::a($icon . '赛季', Url::to(['/league/pubgseason', 'leagueId' => $model['leagueId']]), ['class' => 'btn btn-orange btn-sm btn-icon icon-left','target' => '_blank']);
                        }
                    },
                    'member' => function ($url,$model) {
                        $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-users' ] );
                        if($model['status'] == '2'){
                            return Html::a($icon.'成员',Url::to(['/league/pubgmember','leagueId' => $model['leagueId']]),['class' => 'btn btn-info btn-sm btn-icon icon-left','data-name' => $model['name']]);
                        }
                    },
                    'bind' => function ($url,$model) {
                        $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-lock' ] );
                        if($model['robotName'] == ''){
                            return Html::a($icon.'绑定机器人',"javascript:void(0);",['class' => 'bind btn btn-success btn-sm btn-icon icon-left','data-id' => $model['leagueId']]);
                        }
                    },
                    'unbind' => function ($url,$model) {
                        $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-unlock' ] );
                        if($model['robotName'] != ''){
                            return Html::a($icon.'解绑机器人',$url.'&name='.$model['robotName'],['class' => 'unbind btn btn-danger btn-sm btn-icon icon-left','data-robot-name' => $model['robotName']]);
                        }
                    },
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
    <div class="modal fade in" id="robot-control" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">绑定机器人</h4>
                </div>
                <div class="modalbody">
                    <div class="row" style="margin-top: 5px">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="version" class="col-sm-2 control-label">机器人</label>
                                <div class="col-sm-5">
                                    <?=Html::dropDownList('robot_id' , 0 , $robotList,['class' => 'form-control','id' => 'robot_id']);?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="league_id" value="0">
                <div class="modal-footer" >
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" id="subRobot">提交更改</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal -->
    </div>
<script>
    $(document).ready(function(){
        gloryTable = $('#show_glory_image');
        if (gloryTable.find('img').length > 0){
            var gloryViewer = new Viewer(gloryTable[0] , {navbar : false, tooltip : false, scalable : false, fullscreen : false,zIndex : 99999});
        }

        pubgTable = $('#show_pubg_image');
        if (pubgTable.find('img').length > 0){
            var pubgViewer = new Viewer(pubgTable[0] , {navbar : false, tooltip : false, scalable : false, fullscreen : false,zIndex : 99999});
        }

        $('.search-type .dropdown-menu a').click(function(){
            $('#searchType').val($(this).attr('data-searchType'));
            $('.search-type button.dropdown-toggle').attr('data-searchType' , $(this).attr('data-seasonid'));
            $('.search-type button.dropdown-toggle').html($(this).text() +　'　<span class="caret"></span>');
            $('button.search').trigger('click');
        });
    })

    $(document).on('pjax:complete',function(){
        gloryTable = $('#show_glory_image');
        if (gloryTable.find('img').length > 0){
            var gloryViewer = new Viewer(gloryTable[0] , {navbar : false, tooltip : false, scalable : false, fullscreen : false,zIndex : 99999});
        }

        pubgTable = $('#show_pubg_image');
        if (pubgTable.find('img').length > 0){
            var pubgViewer = new Viewer(pubgTable[0] , {navbar : false, tooltip : false, scalable : false, fullscreen : false,zIndex : 99999});
        }

    })

    function mousemove(e){
        e.removeClass('text-intro');
        e.addClass('text-intro-show');
    }
    function mouseout(e){
        e.removeClass('text-intro-show');
        e.addClass('text-intro');
    }

    $('a.bind').click(function(){
        var leagueId = $(this).attr('data-id');
        $("#robot-control").modal('show');
        $("#league_id").val(leagueId)

    });
    
    $('a.unbind').click(function () {
        var $that = $(this);
        var robotName = $(this).attr('data-robot-name');
        var confirmText = $('<span>').addClass('color-orange font-16').html("你确定要解绑 （" + robotName + "） 机器人吗？");
        var successText = "已经解绑(" + robotName + ")的机器人！";
        showConfirmModal(this , confirmText , successText );
        return false;
    })

    $("#subRobot").click(function(){
        var $that = $(this);
        var leagueId = $("#league_id").val();
        var robotId = $('#robot_id').val();

        $.ajax({
            url:'<?= Url::to(['/league/pubg/bind'])?>',
            data:{
                leagueId :leagueId,
                name : robotId,
            },
            type : 'get',
            dataType:"json",
            success:function(response){
                if(response.status == 'success'){
                    toastr.success(response.message , '' , $that.toastrOpts);
                    window.location.reload();
                }else{
                    toastr.error(response.message , '' , $that.toastrOpts);
                    $("#robot-control").modal('hide');
                }
            }
        })
    })

</script>
<?php Pjax::end();?>



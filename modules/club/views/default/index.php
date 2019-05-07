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
        'label' => '战队管理' ,
        'url'   => Url::to( [
            '/club' ,
        ] ) ,
    ] ,
    [
        'label' => '战队列表' ,
    ] ,
];
AppAsset::registerCss( $this , '@web/plugin/datatables/datatables.min.css' );
AppAsset::registerCss($this , '@web/plugin/daterangepicker/daterangepicker-bs3.css');
AppAsset::registerCss($this, '@web/plugin/viewer/viewer.css');

AppAsset::registerJs($this, '@web/plugin/viewer/viewer.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/moment.min.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/daterangepicker.js');
AppAsset::registerJs($this , '@web/js/common.js');
Pjax::begin();
?>
<style>
    table a.btn{margin-top: 4px}
</style>

<?php
$form = ActiveForm::begin([
    'id' => 'team-form',
    'method' => 'get',
    'options' => ['data-pjax' => true , 'role' => 'form']
] );
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
<?php if($gameType == 'glory'){ ?>
    <?php $url = '/club/create'?>
<?php }else{ ?>
    <?php $url = '/club/pubg/create'?>
<?php } ?>
<a href="<?=Url::to([$url])?>" id="create_url" class="btn btn-success btn-square radius-4 pull-right">
    <i class="entypo-plus"></i>
    创建
</a>

<div class="col-md-12">
    <ul class="nav nav-tabs bordered " style="margin-bottom: 10px">
        <?php if ($gameType == 'glory') { ?>
            <?php $glory = 'active';
            $pubg = ''; ?>
        <?php } else { ?>
            <?php $glory = '';
            $pubg = 'active'; ?>
        <?php } ?>
        <li class="<?= $glory ?>">
            <a data-status="1" href="<?= Url::to(['/club']) ?>">
                <span>王者荣耀</span>
            </a>
        </li>
        <li class="<?= $pubg ?>">
            <a data-status="2" href="<?= Url::to(['/club/pubg?approvalStatus=0']) ?>">
                <span>绝地求生</span>
            </a>
        </li>
    </ul>
    <?php if($gameType == 'pubg'){ ?>
    <div id="club-search" style="margin-bottom: 10px;margin-top: 5px;float: right" >
        <div class="col-sm-1 refresh" style="margin-right: 5%">
            <a href="<?= \Yii::$app->request->getHostInfo().'/'.\Yii::$app->request->getPathInfo()?>" class="btn btn-default" title="刷新">
                <i class="fa fa-refresh"></i>
            </a>
        </div>

        <div class="input-group club-search-type" style="width: 400px">
            <div class="input-group-btn search-type" style="padding-right: 10px">
                <?php
                $clubStatus = Yii::$app->request->get('approvalStatus' , 0);
                $clubStatusList = (isset($approvalStatusData) && !empty($approvalStatusData)) ? $approvalStatusData : [0 => '待审批', 1 => '审批通过' , 2 => '审批失败' , 3 => '全部'];
                ?>
                <input type="hidden" id="approvalStatus" name="approvalStatus" value="<?=$clubStatus?>">
                <button id="club-status" type="button" class="btn btn-blue dropdown-toggle search" data-toggle="dropdown" data-status="<?=$clubStatus?>">
                    <?= $clubStatusList[$clubStatus]?>　<span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-darkblue">
                    <?php foreach($clubStatusList as $key => $value){ ?>
                        <li><a href="javascript:void(0);" data-status="<?= $key?>"><?= $value?></a></li>
                    <?php } ?>
                </ul>
            </div>
            <div class="input-group-btn search-type">
                <button type="button" class="btn btn-success  " style="width: 60px" data-searchtype="name"  data-toggle="dropdown">
                    战队名
                </button>
            </div>
            <?php
            $content = \Yii::$app->request->get('teamName','');
            ?>
            <input type="text" id="teamName" class="form-control" name="teamName" placeholder="" value="<?= !empty($teamName) ? $teamName : ''?>">
            <div class="input-group-btn">
                <button  type="submit" class="btn btn-success search">
                    <i class="entypo-search"></i>
                </button>
            </div>
        </div>
    </div>
    <?php } ?>
<?php
ActiveForm::end();
if($gameType == 'glory') {
    echo GridView::widget([
        'id' => 'club',
        'dataProvider' => $dataProvider,
        'emptyText' => "暂无战队信息！",
        'emptyTextOptions' => ['class' => 'text-center'],
        'tableOptions' => ['class' => 'table table-bordered datatable no-footer hover stripe text-center dataTable', 'id' => 'show_image'],
        'options' => ['class' => 'dataTables_wrapper no-footer no-border'],
        'layout' => "{errors}{items}{pager}",
        "columns" => [
            [
                'label' => '战队编号',
                'value' => 'id',
                'headerOptions' => ['width' => '2%'],
            ],
            [
                'label' => '战队名',
                'value' => 'name',
                'headerOptions' => ['width' => '1%'],
                'contentOptions' => ['style' => 'text-align:left']
            ],
            [
                'label' => '图标',
                'format' => [
                    'image',
                    [
                        'height' => '35',
                    ]
                ],
                'value' => function ($model) {
                    return $model['icon'];
                },
                'headerOptions' => ['width' => '2%']
            ],
            [
                'label' => '类型',
                'value' => 'typeName',
                'headerOptions' => ['width' => '2%']
            ],
            [
                'attribute' => 'freeExamine',
                'label' => '免审核',
                'format' => 'raw',
                'headerOptions' => ['width' => '2%'],
                'value' => function ($model) {
                    $class = $model['freeExamine'] == 2 ? 'fa fa-check-circle color-green font-18' : 'fa fa-times-circle color-red font-18';
                    return Html::tag('span', '', ['class' => $class]);
                }
            ],
            [
                'label' => '成员数量',
                'value' => 'count',
                'headerOptions' => ['width' => '2%']
            ],
            [
                'attribute' => 'desc',
                'label' => '描述',
                'format' => 'raw',
                'headerOptions' => ['width' => '30%'],
                'value' => function ($model) {
                    return '<div class="text-intro"  onmousemove="mousemove($(this))" onmouseout="mouseout($(this))">' . $model['desc'] . '</div>';
                }
            ],
            [
                'class' => ActionColumn::className(),
                'template' => '{update}{delete}{member}',
                'header' => '操作详细',
                'contentOptions' => ['class' => 'actions'],
                'headerOptions' => ['width' => '20%'],
                'buttons' => [
                    'update' => function ($url) {
                        $icon = Html::tag('i', '', ['class' => 'fa fa-pencil']);
                        return Html::a($icon . '编辑', $url, ['class' => 'btn btn-default btn-sm btn-icon icon-left']);
                    },
                    'member' => function ($url, $model) {
                        $icon = Html::tag('i', '', ['class' => 'fa fa-eye']);
                        return Html::a($icon . '成员详情', Url::to(['/club/member?id=']) . $model['id'], ['class' => 'btn btn-success btn-sm btn-icon icon-left']);
                    },
                    'delete' => function ($url, $model) {
                        $icon = Html::tag('i', '', ['class' => 'fa fa-times']);
                        return Html::a($icon . '删除', $url, ['class' => 'btn btn-danger btn-sm btn-icon icon-left', 'data-name' => $model['name']]);
                    },
                ],
            ],
        ],
        'pager' => [
            'options' => ['class' => 'pagination dataTables_paginate paging_simple_numbers'],
            'linkOptions' => ['class' => 'paginate_button'],
        ],
    ]);
}else{
    echo GridView::widget( [
        'id'               => 'club' ,
        'dataProvider'     => $dataProvider ,
        'emptyText'        => "暂无战队信息！" ,
        'emptyTextOptions' => [ 'class' => 'text-center' ] ,
        'tableOptions'     => [ 'class' => 'table table-bordered datatable no-footer hover stripe text-center dataTable','id'=> 'show_image' ] ,
        'options'          => [ 'class' => 'dataTables_wrapper no-footer no-border' ] ,
        'layout'           => "{errors}{items}{pager}" ,
        "columns"          => [
            [
                'label' => '战队名',
                'value' => 'teamName',
                'headerOptions' => ['width' => '1%'],
            ],
            [
                'attribute' => 'teamLevel',
                'label' => '战队等级',
                'value' => 'teamLevel',
                'headerOptions' => ['width' => '2%'],
            ],
            [
                'label' => '图标',
                'format' => [
                    'image',
                    [
                        'height'=>'80',
                        'width' => '80'
                    ]
                ],
                'value' => function ($model) {
                    return $model['teamLogo'];
                },
                'headerOptions' =>['width' => '2%']
            ],
            [
                'attribute' => 'teamNumber',
                'label' => '成员数量',
                'format' => 'raw',
                'headerOptions' => ['width' => '2%'],
                'value' => function($model){
                    return Html::tag('span',$model['teamNumber'],['class' => 'badge badge-success']);
                }
            ],
            [
                'attribute' => 'waitApprovalNumber',
                'label' => '待审批队员数',
                'format' => 'raw',
                'headerOptions' => ['width' => '1%'],
                'value' => function($model){
                    return Html::tag('span',$model['waitApprovalNumber'],['class' => 'badge badge-orange']);
                }
            ],
            [
                'attribute' => 'teamBrief',
                'label' => '描述',
                'format' => 'raw',
                'headerOptions' => ['width' => '30%'],
                'value' => function($model){
                    return '<div class="text-intro"  onmousemove="mousemove($(this))" onmouseout="mouseout($(this))">'.$model['teamBrief'].'</div>';
                }
            ],
            [
                'attribute' => 'approvalStatus',
                'label' => '状态',
                'format' => 'raw',
                'headerOptions' => ['width' => '2%'],
                'value' => function($model){
                    $el = '';
                    $approvalStatus = intval($model['approvalStatus']);
                    switch ($approvalStatus) {
                        case 0 :
                            $el = Html::tag('i', '', ['class' => 'fa fa-minus-circle color-orange font-18', 'title' => '待审批']);
                            break;
                        case 1 :
                            $el = Html::tag('i', '', ['class' => 'fa fa-check-circle color-green font-18', 'title' => '审批通过']);
                            break;
                        case 2:
                            $el = Html::tag('i', '', ['class' => 'fa fa-times color-red font-18', 'title' => '审批失败']);
                            break;
                    }
                    return $el;
                }
            ],
            [
                'class' => ActionColumn::className(),
                'template' => ' {agree}{reject}{update}{member}{delete}',
                'header' => '操作',
                'contentOptions' => ['class' => 'actions'],
                'buttons' => [
                    'agree' => function ($url,$model){
                        $icon = Html::tag('i', '', ['class' => 'fa fa-check']);
                        if($model['approvalStatus'] == 0 ){
                            $url = Url::to(['/club/pubg/status','userId' => isset($model['createUserId']) ? $model['createUserId'] : 0,'userTeamId' => $model['id'],'isPass' => 1]);
                            return Html::a($icon . '同意', $url,
                                ['class' => 'btn btn-success btn-sm btn-icon icon-left agree','data-name' => $model['teamName']]);
                        }
                    },
                    'reject' => function($url,$model) {
                        $icon = Html::tag('i','',['class' => 'fa fa-times']);
                        if($model['approvalStatus'] == 0 ){
                            //$url = Url::to(['/club/pubg/status','userId' => isset($model['createUserId']) ? $model['createUserId'] : 0,'userTeamId' => $model['id'],'isPass' => 0]);
                            return Html::a($icon . '拒绝', 'javascript:',
                                ['class' => 'btn btn-primary btn-sm btn-icon icon-left reject','data-name' => $model['teamName'],'data-id' => $model['id'],
                                    'data-userId' => isset($model['createUserId']) ? $model['createUserId'] : 0]);
                        }
                    },
                    'update' => function( $url,$model){
                        if($model['approvalStatus'] == 1 ) {
                            $icon = Html::tag('i', '', ['class' => 'fa fa-pencil']);
                            return Html::a($icon . '编辑', $url, ['class' => 'btn btn-default btn-sm btn-icon icon-left']);
                        }
                    } ,
                    'member' => function( $url,$model){
                        if($model['approvalStatus'] == 1 ){
                            $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-eye' ] );
                            return Html::a($icon.'成员详情',Url::to(['/club/pubgmember?id=']).$model['id'], [ 'class' => 'btn btn-success btn-sm btn-icon icon-left'] );
                        }
                    },
                    'delete' => function ($url, $model) {
                        $icon = Html::tag('i', '', ['class' => 'fa fa-times']);
                        return Html::a($icon . '删除', $url, ['class' => 'btn btn-danger btn-sm btn-icon icon-left', 'data-name' => $model['teamName']]);
                    },
                ],
            ],
        ] ,
        'pager'            => [
            'options'     => [ 'class' => 'pagination dataTables_paginate paging_simple_numbers' ] ,
            'linkOptions' => [ 'class' => 'paginate_button' ] ,
        ] ,
    ] );
}
Pjax::end();
?>
</div>
<script>
    $(document).ready(function () {
        table = $("#show_image");
        if (table.find('img').length > 0){
            var viewer = new Viewer(table[0] , {navbar : false, tooltip : false, scalable : false, fullscreen : false,zIndex : 99999});
        }
    })

    $('.search-type .dropdown-menu a').click(function(){
        $('#approvalStatus').val($(this).attr('data-status'));
        $('.search-type button.dropdown-toggle').attr('data-status' , $(this).attr('data-status'));
        $('.search-type button.dropdown-toggle').html($(this).text() +　'　<span class="caret"></span>');
        $('button.search').trigger('click');
    });

    $('a.btn-danger').click(function(){
        var teamName = $(this).attr('data-name');
        var confirmText = $('<span>').addClass('color-orange font-16').html("你确定要删除 （" + teamName + "） 的这个战队吗？");
        var successText = "删除(" + teamName + ")成功！";
        showConfirmModal(this , confirmText , successText );
        return false;
    });


    $('.agree').click(function(){
        var teamName = $(this).attr('data-name');
        var confirmText = $('<span>').addClass('color-orange font-16').html("确定同意战队（“" + teamName + "”）建立吗？");
        var successText = teamName + " 审批成功！";
        showConfirmModal(this,confirmText, successText);
        return false;
    });

    // $('.reject').click(function(){
    //     var teamName = $(this).attr('data-name');
    //     var confirmText = $('<span>').addClass('color-orange font-16').html("确定拒绝“" + teamName + "”加入吗？");
    //     var successText = teamName + " 拒绝成功！";
    //     showConfirmModal(this,confirmText, successText);
    //     return false;
    // });

    $('.reject').click(function () {
        var nickname = $(this).attr('data-name');
        var teamId = $(this).attr('data-id');
        var userId = $(this).attr('data-userId');
        teamReject(teamId,nickname,userId);
        return false;
    });

    $(document).on('pjax:complete',function(){
        table = $("#show_image");
        if (table.find('img').length > 0){
            var viewer = new Viewer(table[0] , {navbar : false, tooltip : false, scalable : false, fullscreen : false,zIndex : 99999});
        }

        $('a.btn-success.agree').click(function(){
            var teamName = $(this).attr('data-name');
            var confirmText = $('<span>').addClass('color-orange font-16').html("确定同意战队（“" + teamName + "”）建立吗？");
            var successText = teamName + " 审批成功！";
            showConfirmModal(this,confirmText, successText);
            return false;
        });

        $('a.btn-danger').click(function(){
            var teamName = $(this).attr('data-name');
            var confirmText = $('<span>').addClass('color-orange font-16').html("确定拒绝战队（“" + teamName + "”）的建立吗？");
            var successText = teamName + " 拒绝成功！";
            showConfirmModal(this,confirmText, successText);
            return false;
        });

        $('.search-type .dropdown-menu a').click(function(){
            $('#approvalStatus').val($(this).attr('data-status'));
            $('.search-type button.dropdown-toggle').attr('data-status' , $(this).attr('data-status'));
            $('.search-type button.dropdown-toggle').html($(this).text() +　'　<span class="caret"></span>');
            $('button.search').trigger('click');
        });

        $('.remove').click(function(){
            var userName = $(this).attr('data-name');
            var teamName = $(this).attr('data-team');
            var confirmText = $('<span>').addClass('color-orange font-16').html("确定将“" + userName + "”从（"+teamName+" ）战队移除出去吗？");
            var successText = "已将 "+userName + " 移除成功！";
            showConfirmModal(this,confirmText, successText);
            return false;
        });

        $('a.update').click(function(){
            var typeName = $(this).attr('data-name');
            var realName = $(this).attr('data-realName');
            var confirmText = $('<span>').addClass('color-orange font-16').html("你确定要将( " + realName + " )的战队角色改为( "+typeName+" )？");
            var successText = "更改成功！";
            showConfirmModal(this , confirmText , successText );
            return false;
        });

        $('a.reject').click(function () {
            var nickname = $(this).attr('data-name');
            var teamId = $(this).attr('data-id');
            var userId = $(this).attr('data-userId');
            teamReject(teamId,nickname,userId);
            return false;
        });
    })

    function mousemove(e){
        e.removeClass('text-intro');
        e.addClass('text-intro-show');
    }
    function mouseout(e){
        e.removeClass('text-intro-show');
        e.addClass('text-intro');
    }

    function teamReject(teamId,teamname,userId){
        var $that = this;
        $("#rejectLabel").html("拒绝"+teamname+"的战队申请加入");
        $("#reject").modal('show');
        var successText = '成功拒绝('+teamname+')的申请';
        $("#reject .confirm").off('click');
        $("#reject .confirm").on('click',function(){
            var remark = $("#remark").val();
            if(remark == ''){
                toastr.error('必须填写拒绝理由' , '' , $that.toastrOpts);
                return false;
            }
            var url = '<?= Url::to(['/club/pubg/status'])?>'
            $.ajax({
                url : url,
                type : 'get',
                dataType : 'json',
                data:"userId="+userId+ "&userTeamId="+teamId+"&isPass=0&remark="+remark,
                success : function(response){
                    if(response.status == 'success'){

                        toastr.success(successText , '' , $that.toastrOpts);
                        window.location.reload();
                    }else{
                        toastr.error(response.message , '' , $that.toastrOpts);
                    }
                }
            });
            $('#reject').modal('hide');
        });
        return false;
    }
</script>
<div class="modal fade in" id="reject" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="rejectLabel"></h4>
            </div>
            <div class="modalbody">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="version" class="col-sm-2 control-label">拒绝理由</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" autocomplete="false" name="remark" required id="remark" value="" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" >
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary confirm">提交</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>
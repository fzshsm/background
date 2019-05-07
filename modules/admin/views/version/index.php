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
        'label' => '版本管理' ,
        'url'   => Url::to( [
            '/admin/version' ,
        ] ) ,
    ] ,
    [
        'label' => '版本列表' ,
    ] ,
];
AppAsset::registerCss( $this , '@web/plugin/datatables/datatables.min.css' );
AppAsset::registerJs($this , '@web/js/common.js');
Pjax::begin();
?>
<style>table a.btn{margin-top: 4px}</style>
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
    <div class="form-group col-md-12">
        <a class="create btn btn-success btn-square radius-4 pull-right" href="javascript:void(0);"><i class="entypo-plus"></i>创建</a>
    </div>
<?php
$form = ActiveForm::begin([
    'id' => 'version-form',
    'method' => 'get',
    'options' => ['data-pjax' => true , 'version' => 'form']
] );
?>
<?php
ActiveForm::end();
echo GridView::widget( [
    'id'               => 'version' ,
    'dataProvider'     => $dataProvider ,
    'emptyText'        => "暂无版本信息！" ,
    'emptyTextOptions' => [ 'class' => 'text-center' ] ,
    'tableOptions'     => [ 'class' => 'table table-bordered datatable no-footer hover stripe text-center dataTable' ] ,
    'options'          => [ 'class' => 'dataTables_wrapper no-footer no-border' ] ,
    'layout'           => "{errors}{items}{pager}" ,
    "columns"          => [
        'id:raw:ID' ,
        [
            'label' => '设备型号',
            'attribute' => 'type',
            'value' => function($model){
                    $typeName = $model['type'] == 3 ? 'anriod' : 'ios';
                    $className = $model['type'] == 3 ? 'badge badge-success' : 'badge badge-default';
                    return Html::tag('span',$typeName,['class' => $className]);
            },
            'format' => 'raw',
            'headerOptions' =>['width' => '5%'],
        ],
        [
            'label' => '升级方式',
            'attribute' => 'forceUpdate',
            'value' => function($model){
                $class = $model['forceUpdate'] == 1 ? 'badge badge-success' : 'badge badge-default';
                $title = $model['forceUpdate'] == 1 ? '强制升级' : '普通升级';
                return Html::tag('span',$title,['class' => $class]);
            },
            'format' => 'raw',
            'headerOptions' =>['width' => '5%'],
        ],
        [
            'label' => '内部版本号',
            'attribute' => 'code',
            'value' => function($model){
                return Html::tag('span',$model['code'],['class' => 'badge badge-info']);
            },
            'format' => 'raw',
            'headerOptions' =>['width' => '5%'],
        ],
        [
            'label' => '对外版本号',
            'attribute' => 'codeDesc',
            'value' => function($model){
                return Html::tag('span',$model['codeDesc'],['class' => 'badge badge-info']);
            },
            'format' => 'raw',
            'headerOptions' =>['width' => '5%'],
        ],
        [
            'attribute' => '下载链接',
            'value' => function ($model) {
                return Html::a($model['downLoadUrl'], $model['downLoadUrl'], ['target' => '_blank']);
            },
            'format' => 'raw',
        ],
        [
            'attribute' => '更新描述',
            'value' => 'remark',
            'headerOptions' => ['width' => '30%'],
            'contentOptions' => ['style' => 'text-align:left']
        ],
        [
            'attribute' => '更改时间',
            'value' => function($model){
                $time = substr($model['time'],0,10);
                return  date("Y-m-d H:i:s",$time);
            }
        ],
        [
            'class'    => ActionColumn::className() ,
            'template' => '{update}{delete}' ,
            'header'   => '操作' ,
            'contentOptions' => ['class' => 'actions'],
            'buttons'  => [
                'update' => function( $url,$model ){
                    $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-pencil' ] );
                    return Html::a($icon.'编辑' , 'javascript:' , [ 'class' => 'update btn btn-default btn-sm btn-icon icon-left','data-code' => $model['code'],'data-id' => $model['id'],
                        'data-downLoadUrl' => $model['downLoadUrl'],'data-remark' => $model['remark'],'data-type' => $model['type'],'data-forceUpdate' => $model['forceUpdate'],'data-codeDesc' => $model['codeDesc'] ] );
                } ,
                'delete' => function( $url,$model ){
                    $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-times' ] );
                    return Html::a( $icon.'删除' , $url , [ 'class' => 'delete btn btn-danger btn-sm btn-icon icon-left' ,'data-id' => $model['id']] );
                } ,
            ] ,
        ] ,
    ] ,
    'pager'            => [
        'options'     => [ 'class' => 'pagination dataTables_paginate paging_simple_numbers' ] ,
        'linkOptions' => [ 'class' => 'paginate_button' ] ,
    ] ,
] );
Pjax::end();
?>
<!--modal start-->
<div class="modal fade in" id="version-control" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="VersionLabel"></h4>
            </div>
            <div class="modalbody">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="medalNum" class="col-sm-2 control-label">设备类型</label>
                            <div class="col-sm-8">
                                <div class="radio radio-replace radio-inline">
                                    <input type="radio"  name="type"  value="3" checked="checked">
                                    <label class="tooltip-default">
                                        anriod
                                    </label>
                                </div>
                                <div class="radio radio-replace radio-inline">
                                    <input type="radio"  name="type"  value="4">
                                    <label class="tooltip-default" >
                                        ios
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="medalNum" class="col-sm-2 control-label">升级方式</label>
                            <div class="col-sm-8">
                                <div class="radio radio-replace radio-inline">
                                    <input type="radio"  name="forceUpdate"  value="1" checked="checked">
                                    <label class="tooltip-default">
                                        强制升级
                                    </label>
                                </div>
                                <div class="radio radio-replace radio-inline" style="margin-left: 8.5%">
                                    <input type="radio"  name="forceUpdate"  value="0">
                                    <label class="tooltip-default" >
                                        普通升级
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 5px">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="version" class="col-sm-2 control-label">内部版本号</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" autocomplete="false" name="code" required id="code" value="" >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 5px">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="version" class="col-sm-2 control-label">对外版本号</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" autocomplete="false" name="codeDesc" required id="codeDesc" value="" >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 5px">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="version" class="col-sm-2 control-label">下载地址</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" autocomplete="false" name="downLoadUrl" required id="downLoadUrl" value="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 5px">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="sortWeight" class="col-sm-2 control-label">更新描述</label>
                            <div class="col-sm-8">
                                <textarea  class="form-control" id="remark" name="remark"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" id="version_id" value="0">
            <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
            <div class="modal-footer" >
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" id="subVersion">提交更改</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>
<!--modal end-->
<script>
    $(document).ready(function(){
        $("#version-control").on('hide.bs.modal',function(){
            $("#version_id").val(0);
            $("#remark").val('');
            $("#downLoadUrl").val('');
            $("#code").val('');
            $("#version-control input[type='radio'][name='type'][value=3]").trigger('click');
            $("#version-control input[type='radio'][name='forceUpdate'][value=1]").trigger('click');
        });
    });
    $('a.create').click(function(){
        $("#version-control").modal('show');
        $("#VersionLabel").html('创建版本');
        $("#version-control").on('shown.bs.modal',function(){
            $("#version_id").val(0);
        })
    });
    $('a.delete').click(function(){
        var $that = $(this);
        var versionId = $(this).attr('data-id');
        var confirmText = $('<span>').addClass('color-orange font-16').html("你确定要删除编号("+versionId+")这个版本吗？");
        var successText = "删除成功！";
        showConfirmModal(this , confirmText , successText );
        return false;
    });
    $('a.update').click(function(){
        var versionId = $(this).attr('data-id');
        var downLoadUrl = $(this).attr('data-downLoadUrl');
        var remark = $(this).attr('data-remark');
        var code = $(this).attr('data-code');
        var type = $(this).attr('data-type');
        var forceUpdate = $(this).attr('data-forceUpdate');
        var codeDesc = $(this).attr('data-codeDesc');

        $("#version-control").modal('show');
        $("#VersionLabel").html('编辑版本');
        $("#downLoadUrl").val(downLoadUrl);
        $("#remark").val(remark);
        $("#code").val(code);
        $("#codeDesc").val(codeDesc)
        $("input[type='radio'][name='type'][value=" + type +"]").trigger('click');
        $("input[type='radio'][name='forceUpdate'][value="+forceUpdate +"]").trigger('click');
        $("#version-control").on('shown.bs.modal',function(){
            $("#version_id").val(versionId)
        })

    });
    $("#subVersion").click(function(){
        var $that = $(this);
        var versionId = $("#version_id").val();
        var downLoadUrl = $("#downLoadUrl").val();
        var remark = $("#remark").val();
        var code = $("#code").val();
        var codeDesc = $("#codeDesc").val();
        var type = $('#version-control input[name=type]:checked').val();
        var forceUpdate = $('#version-control input[name=forceUpdate]:checked').val();
        var csrfToken = $("#_cstf").val();

        var url = "<?= Url::to(['/admin/version/create'])?>";
        if(versionId != 0){
            url = "<?= Url::to(['/admin/version/update?id='])?>"+versionId;
        }

        $.ajax({
            url:url,
            data:{
                downLoadUrl :downLoadUrl,
                remark : remark,
                code : code,
                type : type,
                forceUpdate:forceUpdate,
                _csrf : csrfToken,
                codeDesc:codeDesc
            },
            type : 'post',
            dataType:"json",
            success:function(response){
                if(response.status == 'success'){
                    toastr.success(response.message , '' , $that.toastrOpts);
                    window.location.reload();
                }else{
                    toastr.error(response.message , '' , $that.toastrOpts);
                    $("#version-control").modal('hide');
                }
            }
        })
    })

</script>

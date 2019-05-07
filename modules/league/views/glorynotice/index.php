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
        'label' => '联赛管理' ,
        'url'   => Url::to( [
            '/league' ,
        ] ) ,
    ] ,
    [
        'label' => '王者荣耀公告列表' ,
    ] ,
];
AppAsset::registerCss( $this , '@web/plugin/datatables/datatables.min.css' );
AppAsset::registerJs($this , '@web/js/common.js');
Pjax::begin();
?>
<div class="form-group col-md-12">
    <a class="create btn btn-success btn-square radius-4 pull-right"  href="javascript:"><i class="entypo-plus"></i>创建</a>
</div>
<?php
$form = ActiveForm::begin([
    'id' => 'notice-form',
    'method' => 'get',
    'options' => ['data-pjax' => true , 'notice' => 'form']
] );
?>

<?php
ActiveForm::end();
echo GridView::widget( [
    'id'               => 'season' ,
    'dataProvider'     => $dataProvider ,
    'emptyText'        => "暂无公告信息！" ,
    'emptyTextOptions' => [ 'class' => 'text-center' ] ,
    'tableOptions'     => [ 'class' => 'table table-bordered datatable no-footer hover stripe text-center dataTable' ] ,
    'options'          => [ 'class' => 'dataTables_wrapper no-footer no-border' ] ,
    'layout'           => "{errors}{items}{pager}" ,
    "columns"          => [
        'id:raw:ID' ,
        [
            'attribute' => '公告内容',
            'value' => 'message',
            'headerOptions' =>['width' => '50%'],
            'contentOptions' => ['style' => 'white-space: normal;', 'width' => '25%'],
        ],
        'sortWeight:text:排序权重',
        [
            'class'    => ActionColumn::className() ,
            'template' => '{update} {delete}' ,
            'header'   => '操作' ,
            'contentOptions' => ['class' => 'actions'],
            'buttons'  => [
                'update' => function( $url ,$model){
                    $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-pencil' ] );
                    return Html::a( $icon . '编辑' , 'javascript:' , [ 'class' => 'update btn btn-default btn-sm btn-icon icon-left' ,'data-id' => $model['id'],'data-msg' => $model['message'],'data-sort' => $model['sortWeight']] );
                } ,
                'delete' => function( $url , $model ){
                    $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-times' ] );
                    return Html::a( $icon . '删除' , $url , [ 'class' => 'btn btn-danger btn-sm btn-icon icon-left'  , 'data-id' => $model['id'] ] );
                } ,
            ] ,
        ] ,
    ] ,
    'pager'            => [
        'options'     => [ 'class' => 'pagination dataTables_paginate paging_simple_numbers' ] ,
        'linkOptions' => [ 'class' => 'paginate_button' ] ,
    ] ,
] );

?>
<!--modal start-->
<div class="modal fade in" id="notice" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="NoticeLabel"></h4>
            </div>
            <div class="modalbody">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="message" class="col-sm-2 control-label">公告内容</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" autocomplete="false" name="message" required id="message" value="" >
                            </div>
                        </div>
                    </div>>
                </div>
                <div class="form-group">
                    <label for="sortWeight" class="col-sm-2 control-label">排序权重</label>
                    <div class="col-sm-6">
                        <div class="input-spinner">
                            <button type="button" class="btn btn-default btn-sm">-</button>
                            <input type="text" class="form-control size-1" data-min="0" id="sortWeight" name="sortWeight" value="0">
                            <button type="button" class="btn btn-default btn-sm">+</button>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" id="notice_id" value="0">
            <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
            <div class="modal-footer" >
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" id="subNotice">提交</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>
<!--modal end-->
<script>
    jQuery( document ).ready( function( $ ){
        $('a.btn-danger').click(function(e){
            var id = $(this).attr('data-id');
            var confirmText = $('<span>').addClass('color-orange font-16').html("你确定要删除 编号( " + id + " ) 的这条公告吗？");
            var successText = "删除 公告( " + id + " ) 成功！";
            showConfirmModal(this , confirmText , successText);
            return false;
        });
        $("#notice").on('hide.bs.modal',function () {
            $("#message").val('');
        })
        $('a.create').click(function(e){
            $("#notice").modal('show');
            $("#NoticeLabel").html('创建公告')
            $("#notice").on('shown.bs.modal',function(){
                $("#notice_id").val('')
            })
        });
        $('a.update').click(function(e){
            var $that = $(this);
            $("#notice").modal('show');
            var noticeId = $(this).attr('data-id');
            var oriMessage = $(this).attr('data-msg');
            var oriSort = $(this).attr('data-sort');
            $("#NoticeLabel").html('更新公告');
            $("#message").val(oriMessage);
            $("#sortWeight").val(oriSort);
            $("#notice").on('shown.bs.modal',function(){
                $("#notice_id").val(noticeId);
            })
        });
    });
    $("#subNotice").click(function(){
        var leagueId = '<?=\Yii::$app->request->get('leagueId');?>';
        var noticeId = $("#notice_id").val();
        var message = $("#message").val();
        var sortWeight = $("#sortWeight").val();
        var csrfToken = $("#_csrf").val();
        var $that = this;

        if(message == ''){
            toastr.error('公告内容不能为空' , '' , $that.toastrOpts);
            return false;
        }

        var url = '<?= Url::to(['/league/notice/create'])?>';
        if(noticeId != 0){
            url = "<?= Url::to(['/league/notice/update?id='])?>"+noticeId;
        }

        $.ajax({
            url:url,
            data:{
                _csrf:csrfToken,
                leagueId:leagueId,
                noticeId:noticeId,
                message:message,
                sortWeight:sortWeight
            },
            dataType:'json',
            type:'post',
            success : function(response){
                if(response.status == 'success'){
                    toastr.success(response.message , '' , $that.toastrOpts);
                    window.location.reload();
                }else{
                    toastr.error(response.message , '' , $that.toastrOpts);
                    $("#notice").modal('hide');
                }
            }
        })
    })
</script>
<?php Pjax::end(); ?>
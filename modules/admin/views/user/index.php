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
        'label' => '用户管理' ,
        'url'   => Url::to( [
            '/admin/user' ,
        ] ) ,
    ] ,
    [
        'label' => '用户列表' ,
    ] ,
];
AppAsset::registerCss( $this , '@web/plugin/datatables/datatables.min.css' );
AppAsset::registerJs($this , '@web/js/common.js');
Pjax::begin();
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
    <div class="form-group col-md-12">
        <a class="btn btn-success btn-square radius-4 pull-right" href="<?=Url::to(['/admin/user/create'])?>"><i class="entypo-plus"></i>创建</a>
    </div>
<?php
$form = ActiveForm::begin([
    'id' => 'user-form',
    'method' => 'get',
    'options' => ['data-pjax' => true , 'role' => 'form']
] );
?>
<?php
ActiveForm::end();
echo GridView::widget( [
    'id'               => 'season' ,
    'dataProvider'     => $dataProvider ,
    'emptyText'        => "暂无用户信息！" ,
    'emptyTextOptions' => [ 'class' => 'text-center' ] ,
    'tableOptions'     => [ 'class' => 'table table-bordered datatable no-footer hover stripe text-center dataTable' ] ,
    'options'          => [ 'class' => 'dataTables_wrapper no-footer no-border' ] ,
    'layout'           => "{errors}{items}{pager}" ,
    "columns"          => [
        'id:raw:ID' ,
        'username:text:姓名',
        'role_name:text:角色',
        'create_time:text:创建时间',
        [
            "class" => ActionColumn::className() ,
            "template" => "{update}{reset}{delete}",
            "header" => "操作",
            "buttons" => [
                "update" => function($url) {
                    $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-pencil' ] );
                    return Html::a($icon."编辑",$url,['class' =>'btn btn-default btn-sm btn-icon icon-left']);
                },
                "reset" => function ($url, $model) {
                    $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-lock' ] );
                    $userId = $model['id'];
                    return Html::button($icon."重置密码", [ 'class' => 'btn btn-warning btn-sm btn-icon icon-left','type' => 'button','onclick' =>"showResetPassword($userId)" ] );
                },
                "delete" => function($url,$model) {
                    $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-times' ] );
                    return Html::a($icon."删除",$url,['class' => 'delete btn btn-danger btn-sm btn-icon icon-left',
                        'data-name' => $model['username']]);
                }
            ],
        ]
    ] ,
    'pager'            => [
        'options'     => [ 'class' => 'pagination dataTables_paginate paging_simple_numbers' ] ,
        'linkOptions' => [ 'class' => 'paginate_button' ] ,
    ] ,
] );
Pjax::end();
?>

<script>
    function showResetPassword(id){
        $("#reset-password").modal('show');

        $("#reset-password").on('shown.bs.modal',function(){
            $("#admin_user_id").val(id)
        })
    }
</script>

<!--modal start-->
<div class="modal fade in" id="reset-password" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">重置密码</h4>
            </div>
            <div class="modalbody">
                <div class="form-group">
                    <label for="reward" class="col-sm-3 control-label">密码</label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" autocomplete="false" name="password" required id="password" value="" >
                    </div>
                </div>
                <input type="hidden" id="admin_user_id" value="0">
                <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" id="resetPassword">提交更改</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>
<!--modal end-->
<script>
    $("#resetPassword").click(function(){
        var password = $('#password').val();
        var user_id = $("#admin_user_id").val();
        var csrfToken = $("#_csrf").val();
        var $that = this;

        if(password == ''){
            toastr.error('密码不能为空' , '' , $that.toastrOpts);
            return false;
        }

        $.ajax({
            url:'<?= Url::to(['/admin/user/reset-password?id='])?>'+user_id,
            data:{
                _csrf:csrfToken,
                password:password,
            },
            dataType:'json',
            type:'post',
            success : function(response){
                if(response.status == 'success'){
                    toastr.success('重置成功！' , '' , $that.toastrOpts);
                    $("#reset-password").modal('hide');
                }else{
                    toastr.error(response.message , '' , $that.toastrOpts);
                }
            }
        })
        $("#password").val('');
    })

    $('a.delete').click(function(){
        var $that = $(this);
        var username = $(this).attr('data-name')
        var confirmText = $('<span>').addClass('color-orange font-16').html("你确定要删除"+username+"这名用户吗？");
        var successText = "删除成功！";
        showConfirmModal(this , confirmText , successText );
        return false;
    });
</script>


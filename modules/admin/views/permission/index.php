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
        'label' => '权限管理' ,
        'url'   => Url::to( [
            '/admin/permission' ,
        ] ) ,
    ] ,
    [
        'label' => '权限列表' ,
    ] ,
];
AppAsset::registerCss( $this , '@web/plugin/datatables/datatables.min.css' );
AppAsset::registerJs($this , '@web/js/common.js');
AppAsset::registerJs($this, '@web/plugin/layer/layer.js');
Pjax::begin();
?>
<?php if(Yii::$app->session->hasFlash('error')){ ?>
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <?=Yii::$app->session->getFlash('error')?>
    </div>
<?php }?>
    <div class="form-group col-md-12">
        <a class="btn btn-info btn-square radius-4 pull-right" href="<?=Url::to(['/admin/permission/automatic'])?>"><i class="entypo-retweet"></i>刷新权限</a>
        <a class="btn btn-success btn-square radius-4 pull-right" href="<?=Url::to(['/admin/permission/create'])?>"><i class="entypo-plus"></i>创建</a>
    </div>

<?php
$form = ActiveForm::begin([
    'id' => 'role-form',
    'method' => 'get',
    'options' => ['data-pjax' => true , 'role' => 'form']
] );
?>
<?php
ActiveForm::end();
echo GridView::widget( [
    'id'               => 'season' ,
    'dataProvider'     => $dataProvider ,
    'emptyText'        => "暂无权限信息！" ,
    'emptyTextOptions' => [ 'class' => 'text-center' ] ,
    'tableOptions'     => [ 'class' => 'table table-bordered datatable no-footer hover stripe text-center dataTable' ] ,
    'options'          => [ 'class' => 'dataTables_wrapper no-footer no-border' ] ,
    'layout'           => "{errors}{items}{pager}" ,
    "columns"          => [
        'id:raw:ID' ,
        'name:text:权限名',
        'module:text:模块',
        'controller:text:控制器',
        'action:text:动作',
        'menu_name:text:菜单名',
        [
            'class'    => ActionColumn::className() ,
            'template' => '{update}' ,
            'header'   => '操作' ,
            'contentOptions' => ['class' => 'actions'],
            'buttons'  => [
                'update' => function( $url ){
                    $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-pencil' ] );
                    return Html::a( $icon . '编辑' ,$url, [ 'class' => 'btn btn-default btn-sm btn-icon icon-left' ] );
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
<script>
    $('a.btn-info').on('click' , function(){
        var confirmText = $('<span>').addClass('color-orange font-16').html("你确定要刷新权限吗？");
        var successText = "刷新权限成功！";
        showConfirmModal(this , confirmText , successText);
        var $that = this;
        var index;
        jQuery('#confirm-modal .modal-body').html(confirmText);
        jQuery('#confirm-modal .confirm').off('click');
        jQuery('#confirm-modal .confirm').on('click' , function(){
            $.ajax({
                url : '<?= Url::to(['/admin/permission/automatic'])?>',
                type : 'get',
                dataType : 'json',
                beforeSend:function(){
                    index = layer.load(2,{
                        shade:[0.4,'#fff']
                    })
                },
                success : function(response){
                    if(response.status == 'success'){
                        layer.close(index);
                        toastr.success(successText , '' , $that.toastrOpts);
                        window.location.reload();
                    }else{
                        toastr.error(response.message , '' , $that.toastrOpts);
                    }
                }
            });
            jQuery('#confirm-modal').modal('hide');
        });
        jQuery('#confirm-modal').modal('show');
        return false;
    });
</script>

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
        'label' => '战队成员' ,
    ] ,
];

AppAsset::registerCss($this, '@web/plugin/select2/select2.css');
AppAsset::registerJs($this, '@web/plugin/select2/select2.min.js');
AppAsset::registerCss( $this , '@web/plugin/datatables/datatables.min.css' );
AppAsset::registerCss($this , '@web/plugin/daterangepicker/daterangepicker-bs3.css');
AppAsset::registerCss($this, '@web/plugin/viewer/viewer.css');

AppAsset::registerJs($this, '@web/plugin/viewer/viewer.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/moment.min.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/daterangepicker.js');
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
<?php
$form = ActiveForm::begin([
    'id' => 'member-form',
    'method' => 'get',
    'options' => ['data-pjax' => true , 'role' => 'form']
] );
?>
<?php
ActiveForm::end();
echo GridView::widget( [
    'id'               => 'member' ,
    'dataProvider'     => $dataProvider ,
    'emptyText'        => "暂无战队成员信息！" ,
    'emptyTextOptions' => [ 'class' => 'text-center' ] ,
    'tableOptions'     => [ 'class' => 'table table-bordered datatable no-footer hover stripe text-center dataTable','style' => 'margin-left:10px;margin-right:10px'] ,
    'options'          => [ 'class' => 'dataTables_wrapper no-footer no-border' ] ,
    'layout'           => "{errors}{items}{pager}" ,
    "columns"          => [
        'realName:text:认证名',
        'rolerId:text:游戏角色名',
        'leagueName:text:当前联赛名',
        'qq:text:QQ',
        'userTypeName:text:战队角色',
        [
            'class'    => ActionColumn::className() ,
            'template' => '{update}' ,
            'header'   => '操作' ,
            'contentOptions' => ['class' => 'actions'],
            'buttons'  => [
                'update' => function( $url,$model ){
                    if($model['userType'] == 0){
                        $name = '战队管理员';
                        $modelType = 2;
                        $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-user-plus' ] );
                        $class = 'update btn btn-orange btn-sm btn-icon icon-left';
                    }else{
                        $name = '队员';
                        $modelType = 0;
                        $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-user' ] );
                        $class = 'update btn btn-default btn-sm btn-icon icon-left';
                    }
                    return Html::a($icon.$name ,Url::to(['/club/member/update?id=']).$model['userId']."&type=$modelType" ,
                        [ 'class' => $class,'data-realName' => $model['realName'],'data-name' => $name] );
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
<script>
    $(document).ready(function(){
        $('a.update').click(function(){
            var typeName = $(this).attr('data-name');
            var realName = $(this).attr('data-realName');
            var confirmText = $('<span>').addClass('color-orange font-16').html("你确定要将( " + realName + " )的战队角色改为( "+typeName+" )？");
            var successText = "更改成功！";
            showConfirmModal(this , confirmText , successText );
            return false;
        });
    })
</script>
<?php
Pjax::end();
?>
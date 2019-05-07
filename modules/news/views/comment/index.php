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
        'label' => '新闻管理' ,
        'url'   => \Yii::$app->request->getReferrer(),
    ] ,
    [
        'label' => '评论列表' ,
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
<?php if(Yii::$app->session->hasFlash('error')){ ?>
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <?=Yii::$app->session->getFlash('error')?>
    </div>
<?php }?>

<?php
$form = ActiveForm::begin([
    'id' => 'comments-form',
    'method' => 'get',
    'options' => ['data-pjax' => true , 'news' => 'form']
] );
?>


<div class="form-group pull-right col-md-5">
    <div class="input-group col-sm-6 pull-right">
        <div class="col-sm-1">
            <a href="<?= Url::to(['/news/comment?id=']).\Yii::$app->request->get('id')?>" class="btn btn-default" title="刷新">
                <i class="fa fa-refresh"></i>
            </a>
        </div>
        <div class="input-group-btn">
            <button type="button" class="btn btn-success dropdown-toggle btn-width-100" data-searchtype="nickname">
                用户昵称　
            </button>
        </div>
        <input type="text" id="nickname" class="form-control" name="nickname" placeholder="" value="<?= Yii::$app->request->get('nickname'); ?>">
        <div class="input-group-btn">
            <button  type="submit" class="btn btn-success search">
                <i class="entypo-search"></i>
            </button>
        </div>
        <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
    </div>
</div>
<style>th{font-weight:bold;font-size:13px}</style>
<?php
ActiveForm::end();
echo GridView::widget( [
    'caption' => '新闻标题：'.$newsDetail->title,
    'captionOptions' => ['style' => 'font-size: 16px; font-weight: bold; color: #000; text-align: center;'],
    'id'               => 'season' ,
    'dataProvider'     => $dataProvider ,
    'emptyText'        => "暂无评论信息！" ,
    'emptyTextOptions' => [ 'class' => 'text-center' ] ,
    'tableOptions'     => [ 'class' => 'table table-bordered datatable no-footer hover stripe text-center','id' => 'show_image'] ,
    'options'          => [ 'class' => 'dataTables_wrapper no-footer no-border' ] ,
    'layout'           => "{summary}{items}{pager}" ,
    'summary' => '<span style="color: red;font-size: 15px">总共有'.$newsDetail->comment.'条评论</span>',
    "columns"          => [
        [
            'label' => 'ID',
            'attribute' => 'id',
            'value' => 'id',
            'headerOptions' =>['width' => '1%'],
            'enableSorting'=>true,
            'format' => 'raw'
        ],
        [
            'attribute' =>  '昵称',
            'value' => 'nickname',
            'headerOptions' =>['width' => '3%'],
            'enableSorting' => true,
            'contentOptions' => ['style' => 'text-align:left']
        ],
        [
            'label' => '头像',
            'format' => [
                'image',
                [
                    'height'=>'35'
                ]
            ],
            'value' => function ($model) {
                return $model['avatar'];
            },
            'headerOptions' =>['width' => '6%'],
        ],
        [
            'attribute' => '内容',
            'value' => 'content',
            'headerOptions' =>['width' => '25%'],
            'contentOptions' => ['style' => 'white-space: normal;text-align:left', 'width' => '25%'],
        ],
        [
            'label' => '回复数量',
            'attribute' => 'reply',
            'value' => function($model){
                return Html::tag('span',$model['reply'],['class' => 'badge badge-info']);
            },
            'format' => 'raw',
            'headerOptions' =>['width' => '5%'],
        ],
        [
            'label' => '点攒数',
            'attribute' => 'praise',
            'value' => function($model){
                        return Html::tag('span',$model['praise'],['class' => 'badge badge-danger']);
            },
            'format' => 'raw',
            'headerOptions' =>['width' => '5%']
        ],
        [
            'label' => '回复时间',
            'attribute' => 'create_time',
            'value'=>
                function($model){
                    return  date("Y.m.d H:i:s",$model['create_time']);
                },
            'headerOptions' =>['width' => '8%']
        ],
        [
            'class'    => ActionColumn::className() ,
            'template' => '{status}' ,
            'header'   => '回复状态' ,
            'contentOptions' => ['class' => 'text-center'],
            'headerOptions' => ['width' => '6%'],
            'buttons'  => [
                'status' => function( $url,$model ){
                    switch ($model['status']){
                        case 'normal':
                            return Html::tag( 'span' , '正常' , [ 'class' => 'label label-success'] );
                            break;
                        case 'delete':
                            return Html::tag( 'span' , '删除' , [ 'class' => 'label label-danger'] );
                            break;
                    }
                } ,
            ] ,
        ],
        [
            'class'    => ActionColumn::className() ,
            'template' => '{reply}{comment}{delete}{normal}' ,
            'header'   => '操作' ,
            'contentOptions' => ['class' => 'actions'],
            'headerOptions' => ['width' => '7%'],
            'buttons'  => [
                'reply' => function ($url,$model) {
                    $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-comment' ] );
                    $commentId = $model['id'];
                    $newsId = $model['news_id'];
                    return Html::button($icon."作者回复", [ 'class' => 'btn btn-blue btn-sm btn-icon icon-left','type' => 'button','onclick' =>"showReply($commentId,$newsId)"] );
                },
                'comment' => function ($url,$model) use ($newsDetail){
                    $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-comments' ] );
                    $commentId = $model['id'];
                    $newsTitle = $newsDetail->title;
                    return Html::a($icon.'查看回复',Url::to(['/news/comment/reply','id' => $commentId,'newsTitle' => $newsTitle]),['class' => 'btn btn-orange btn-sm btn-icon icon-left','target' => '_blank']);
                },
                'delete' => function ($url,$model) {
                    $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-times' ] );
                    if($model['status'] == 'normal'){
                        return Html::a($icon.'删除',Url::to(['/news/comment/delete','id' => $model['id']]),['class' => 'delete btn btn-danger btn-sm btn-icon icon-left']);
                    }
                },
                'normal' => function ($url,$model) {
                    $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-check' ] );
                    if($model['status'] == 'delete'){
                        return Html::a($icon.'恢复',Url::to(['/news/comment/normal','id' => $model['id']]),['class' => 'normal btn btn-success btn-sm btn-icon icon-left']);
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

?>

<!--modal start-->
<div class="modal fade in" id="author-reply" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">作者回复</h4>
            </div>
            <div class="modalbody">
<!--                <div class="form-group">-->
<!--                    <label for="title" class="col-sm-3 control-label">标题</label>-->
<!--                    <div class="input-group col-sm-8">-->
<!--                        <input type="text" class="form-control" autocomplete="false" name="reply_title"  id="reply_title" value="" >-->
<!--                    </div>-->
<!--                </div>-->
                <div class="form-group">
                    <label for="reply" class="col-sm-3 control-label">回复内容：</label>
                    <div class="input-group col-sm-8">
                        <input type="text" class="form-control" autocomplete="false" name="reply_content"  id="reply_content" value="" >
                    </div>
                </div>
            </div>
            <input type="hidden" id="comment_id" value="0">
            <input type="hidden" id="news_id" value="0">
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" id="replyComment">提交更改</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>
<!--modal end-->

<script>
    $(document).ready(function(){
        table = $("#show_image");
        if (table.find('img').length > 0){
            var viewer = new Viewer(table[0] , {navbar : false, tooltip : false, scalable : false, fullscreen : false,zIndex : 99999});
        }
    })

    //更改评论的状态
    $('a.normal').click(function(){
        var $that = $(this);
        var confirmText = $('<span>').addClass('color-orange font-16').html("你确定要恢复这条评论吗？");
        var successText = "发布成功！";
        showConfirmModal(this , confirmText , successText );
        return false;
    });

    $('a.delete').click(function(){
        var $that = $(this);
        var confirmText = $('<span>').addClass('color-orange font-16').html("你确定要删除这条评论吗？");
        var successText = "删除成功！";
        showConfirmModal(this , confirmText , successText );
        return false;
    });

    //编辑作者回复
    function showReply(id,news_id){
        $("#author-reply").modal('show');

        $("#author-reply").on('shown.bs.modal',function(){
            $("#comment_id").val(id)
            $("#news_id").val(news_id);
        })
        $("#author-reply").on('hide.bs.modal',function () {
            $("#reply_content").val('');
        })
    }

    $("#replyComment").click(function(){
        var comment_id = $('#comment_id').val();
        var news_id = $("#news_id").val();
        var reply_content = $("#reply_content").val();
        var csrfToken = $("#_csrf").val();
        var reply_title = $("#reply_title").val();
        var $that = this;

        if(reply_content == ''){
            toastr.error('回复不能为空！' , '' , $that.toastrOpts);
            return false;
        }

        $.ajax({
            url:"<?= Url::to(['/news/comment/author?id='])?>"+comment_id,
            data:{
                _csrf:csrfToken,
                reply_content:reply_content,
                news_id:news_id,
                reply_title:reply_title
            },
            dataType:'json',
            type:'post',
            success : function(response){
                if(response.status == 'success'){
                    toastr.success('回复成功！' , '' , $that.toastrOpts);
                }else{
                    toastr.error(response.message , '' , $that.toastrOpts);
                }
            }
        })
        $("#author-reply").modal('hide');
    })
</script>

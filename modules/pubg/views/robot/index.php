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
        'label' => '机器人列表' ,
    ] ,
];
AppAsset::registerCss( $this , '@web/plugin/datatables/datatables.min.css' );
AppAsset::registerCss($this , '@web/plugin/daterangepicker/daterangepicker-bs3.css');
AppAsset::registerCss($this, '@web/plugin/viewer/viewer.css');
AppAsset::registerJs($this, '@web/plugin/layer/layer.js');
AppAsset::registerJs($this, '@web/plugin/viewer/viewer.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/moment.min.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/daterangepicker.js');
AppAsset::registerJs($this , '@web/js/common.js');
AppAsset::registerJs($this, '@web/plugin/layer/layer.js');
Pjax::begin();
$form = ActiveForm::begin([
    'id' => 'recruit-form',
    'method' => 'get',
    'options' => ['data-pjax' => true , 'role' => 'form']
] );
?>
<style>
    th{font-weight:bold;font-size:13px}
    table a.btn{margin-top: 4px}
</style>

<div class="row">
    <div class="col-md-8">
        <?php if(Yii::$app->session->hasFlash('error')){ ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?=Yii::$app->session->getFlash('error')?>
            </div>
        <?php }?>
        <?php if(Yii::$app->session->hasFlash('dataError')){ ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?=Yii::$app->session->getFlash('dataError')?>
            </div>
        <?php }?>
    </div>
</div>
    <div class="form-group col-md-12">
        <a href="javascript:void(0);" class="btn btn-info pull-right  update-version">更新版本号</a>
    </div>
<?php
ActiveForm::end();
echo GridView::widget( [
    'id'               => 'robot' ,
    'dataProvider'     => $dataProvider ,
    'emptyText'        => "暂无机器人信息！" ,
    'emptyTextOptions' => [ 'class' => 'text-center' ] ,
    'tableOptions'     => [ 'class' => 'table table-bordered datatable no-footer hover stripe text-center','id' => 'show_image' ] ,
    'options'          => [ 'class' => 'dataTables_wrapper no-footer no-border' ] ,
    'layout'           => "{errors}{items}{pager}" ,
    "columns"          => [
        [
            'label' => '名称',
            'attribute' => 'name',
            'value' => 'name',
            'headerOptions' =>['width' => '22%']
        ],
        [
            'label' => '联赛名',
            'attribute' => 'leagueName',
            'value' => 'leagueName',
            'headerOptions' =>['width' => '22%']
        ],
        [
            'attribute' => 'status',
            'label' => '机器人在线状态',
            'format' => 'html',
            'headerOptions' =>['width' => '22%'],
            'value' => function ($model) {
                $el = '';
                $data = intval($model['status']);
                switch ($data) {
                    case 1 :
                        $el = Html::tag('i', '', ['class' => 'fa fa-minus-circle color-orange font-18', 'title' => '检测中']);
                        break;
                    case 2 :
                        $el = Html::tag('i', '', ['class' => 'fa fa-check-circle color-green font-18', 'title' => '在线']);
                        break;
                    case 3 :
                        $el = Html::tag('i', '', ['class' => 'fa fa-times-circle color-red font-18', 'title' => '已掉线']);
                        break;
                }
                return $el;
            }
        ],
        [
            'attribute' => 'isLive',
            'label' => '使用状态',
            'format' => 'html',
            'headerOptions' =>['width' => '22%'],
            'value' => function ($model) {
                $el = '';
                $data = intval($model['isLive']);
                switch ($data) {
                    case 3 :
                        $el = Html::tag('i', '', ['class' => 'fa fa-times-circle color-red font-18', 'title' => '否']);
                        break;
                    case 4 :
                        $el = Html::tag('i', '', ['class' => 'fa fa-check-circle color-green font-18', 'title' => '是']);
                        break;
                }
                return $el;
            }
        ],
        [
            'class'    => ActionColumn::className(),
            'template' => '{close}{start}' ,
            'header'   => '操作详细' ,
            'contentOptions' => ['class' => 'actions'],
            'buttons'  => [
                'close' => function( $url,$model){
                    if($model['isLive'] == 4){
                        $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-times' ] );
                        return Html::a($icon.'停止使用',Url::to(['/pubg/robot/close','name' => $model['name']]), [ 'class' => 'btn btn-danger btn-sm btn-icon icon-left','data-name' => $model['name'],'data-leagueName' => $model['leagueName']] );
                    }
                } ,
                'start' => function($url,$model){
                    if($model['isLive'] == 3){
                        $icon = Html::tag('i','',['class' => 'fa fa-check']);
                        return Html::a($icon.'启用',Url::to(['/pubg/robot/start','name' => $model['name']]),['class' => 'btn btn-success btn-sm btn-icon icon-left','data-name' => $model['name'],'data-leagueName' => $model['leagueName']]);
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

?>
<script>
    $(document).ready(function(){

        $.ajax(getting)

        $('a.update-version').click(function(){
            $("#version-control").modal('show');
            $("#VersionLabel").html('更新版本号');
            $("#version-control").on('shown.bs.modal',function(){
                $("#version_id").val(0);
            })
        });
        $('a.btn-danger').click(function(){
            var $that = $(this);
            var robotName = $(this).attr('data-name');
            var leagueName = $(this).attr('data-leagueName');
            var confirmText = $('<span>').addClass('color-orange font-16').html("如果停止使用（" + robotName + "） 机器人，联赛（"+leagueName+"）下所有的比赛将要人工操作，确定停止吗？");
            var successText = "已停止使用(" + robotName + ")机器人！";
            showConfirmModal(this , confirmText , successText );
            return false;
        });

        $('a.btn-success').click(function(){
            var $that = $(this);
            var robotName = $(this).attr('data-name');
            var leagueName = $(this).attr('data-leagueName');
            var confirmText = $('<span>').addClass('color-orange font-16').html("如果启用（" + robotName + "） 机器人，联赛（"+leagueName+"）下所有的比赛将要自动操作，确定启用吗？");
            var successText = "已启用(" + robotName + ") 机器人！";
            showConfirmModal(this , confirmText , successText );
            return false;
        });

        $("#subVersion").click(function(){
            var $that = $(this);
            var version = $("#version").val();
            var csrfToken = $("#_cstf").val();

            var reg = /^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/;

            var url = "<?= Url::to(['/pubg/robot/version'])?>";

            if(!reg.test(version)){
                layer.alert("对不起，您输入的版本号格式不正确!");
                return false;
            }
            $.ajax({
                url:url,
                data:{
                    version : version,
                    _csrf : csrfToken
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
    })

    var getting = {
        url:'<?= Url::to(['/pubg/robot/status'])?>',
        dataType:'json',
        success:function(res) {
            if(res.status == 'success'){
                var result = res.result;
                for(var i=0; i<result.length;i++){
                    var robotStatus = result[i]['result'];
                    var num = i+1;
                    if(robotStatus == 1){
                        $("table").find("tr").eq(num).find("td").eq(2).html("<i class='fa fa-minus-circle color-orange font-18' title='检测中'></i>");
                    }else if(robotStatus == 2){
                        $("table").find("tr").eq(num).find("td").eq(2).html("<i class='fa fa-check-circle color-green font-18' title='在线'></i>");
                    }else if(robotStatus == 3){
                        $("table").find("tr").eq(num).find("td").eq(2).html("<i class='fa fa-times-circle color-red font-18' title='已掉线'></i>");
                    }
                }
            }else{
                console.log(res.result)
            }
        }
    };

     window.setInterval(function(){$.ajax(getting)},60000);


</script>

<!--modal start-->
<div class="modal fade in" id="version-control" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="VersionLabel"></h4>
            </div>
            <div class="modalbody">

                <div class="row" style="margin-top: 5px">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="version" class="col-sm-2 control-label">版本号</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" autocomplete="false" name="version" required id="version" value="<?= isset($version) ? $version : ''?>" >
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

<?php

Pjax::end();
?>

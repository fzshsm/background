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
        'label' => '财务管理' ,
        'url'   => Url::to( [
            '/finance' ,
        ] ) ,
    ] ,
    [
        'label' => '豆豆赠送审核' ,
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

<?php
$form = ActiveForm::begin([
    'id' => 'consumption-form',
    'method' => 'get',
    'options' => ['data-pjax' => true , 'consumption' => 'form']
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

<div id="roomcard-search col-sm-12" style="margin-bottom: 10px;margin-top: 5px;float: right" >
    <div class="col-sm-1 refresh" style="margin-right: 5%">
        <a href="<?= Url::to(['/finance/consumption/send'])?>" class="btn btn-default" title="刷新">
            <i class="fa fa-refresh"></i>
        </a>
    </div>
    <div class="input-group " style="width: 400px">
        <div class="input-group-btn search-consumption-type" style="padding-right: 10px">
            <?php
            $consumptionType = Yii::$app->request->get('type' , 3);
            $consumptionTypeList = [3 => '全部' ,0 => '待审核', 1 => '审核成功', 2 => '审核失败'];
            ?>
            <input type="hidden" id="type" name="type" value="<?=$consumptionType?>">
            <button type="button" class="btn btn-info dropdown-toggle status" style="width: 80px" data-searchConsumptionType="<?= $consumptionType?>"  data-toggle="dropdown">
                <?= $consumptionTypeList[$consumptionType] ?>　<span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-infoblue">
                <?php foreach($consumptionTypeList as $key => $value){ ?>
                    <li>
                        <a data-searchConsumptionType="<?= $key ?>" href="javascript:void(0);"><?= $value ?></a>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <div class="input-group-btn searchType">
            <?php
                $searchType = Yii::$app->request->get('searchType' , 'userNo');
                $searchTypeList = ['userNo' => '用户ID'];
            ?>
            <input type="hidden" id="searchType" name="searchType" value="<?=$searchType?>">
            <button type="button" class="btn btn-success dropdown-toggle type" style="width: 100px" data-searchtype="<?= $searchType?>" >
                <?= $searchTypeList[$searchType] ?>
            </button>
        </div>
        <?php
        $content = \Yii::$app->request->get('content','');
        ?>
        <input type="text" id="content" class="form-control" name="content" placeholder="" value="<?= isset($content) ? $content : ''?>">
        <div class="input-group-btn">
            <button  type="submit" class="btn btn-success search">
                <i class="entypo-search"></i>
            </button>
        </div>
    </div>
</div>
<?php
ActiveForm::end();
echo GridView::widget( [
    'id'               => 'finance' ,
    'dataProvider'     => $dataProvider ,
    'emptyText'        => "暂无豆豆日志信息！" ,
    'emptyTextOptions' => [ 'class' => 'text-center' ] ,
    'tableOptions'     => [ 'class' => 'table table-bordered datatable no-footer hover stripe text-center dataTable'] ,
    'options'          => [ 'class' => 'dataTables_wrapper no-footer no-border' ] ,
    'layout'           => "{errors}{items}{pager}" ,
    "columns"          => [
        [
            'label' => '用户编号',
            'attribute' => 'userNo',
            'value' => 'userNo',
        ],
        [
            'label' => '用户昵称',
            'attribute' => 'nickName',
            'value' => 'nickName',
        ],
        [
            'label' => '赠送数量',
            'attribute' => 'coinB',
            'value' => 'coinB',
        ],
        [
            'label' => '赠送理由',
            'attribute' => 'remark',
            'value'=>'remark',
        ],
        [
            'label' => '赠送时间',
            'attribute' => 'createTime',
            'value'=>'createTime',
        ],
        [
            'label' => '操作人',
            'attribute' => 'adminName',
            'value'=>'adminName',
        ],
        [
            'attribute' => 'status' ,
            'label'     => '状态' ,
            'format' => 'html',
            'value' => function($model){
                $className = "";
                $text = "";
                switch ($model['status']){
                    case 0 :
                        $className = 'fa fa-minus-circle color-orange font-18';
                        $text = '待审核';
                        break;
                    case 1 :
                        $className = 'fa fa-check-circle color-green font-18';
                        $text = '审核通过';
                        break;
                    case 2 :
                        $className = 'fa fa-times-circle color-red font-18';
                        $text = '审核失败';
                        break;

                }
                return  Html::tag('i', '', ['class' => $className, 'title' => $text]);
            }
        ] ,
        [
            'label' => '审核人',
            'attribute' => 'auditName',
            'value'=>'auditName',
        ],
        [
            'label' => '拒绝理由',
            'attribute' => 'auditRemark',
            'value'=>'auditRemark',
        ],
        [
            'label' => '审核时间',
            'attribute' => 'auditTime',
            'value'=>'auditTime',
        ],
        [
            'class' => ActionColumn::className(),
            'template' => ' {agree}{reject}',
            'header' => '操作',
            'contentOptions' => ['class' => 'actions'],
            'buttons' => [
                'agree' => function ($url,$model)  {
                    $icon = Html::tag('i', '', ['class' => 'fa fa-check']);
                    if($model['status'] == 0 ){
                        return Html::a($icon . '同意', $url,
                            ['class' => 'btn btn-success btn-sm btn-icon icon-left', 'data-user' => $model['nickName'],'data-coinB' => $model['coinB']]);
                    }
                },
                'reject' => function($url,$model) {
                    $icon = Html::tag('i','',['class' => 'fa fa-times']);
                    if($model['status'] == 0 ){
                        return Html::a($icon . '拒绝', 'javascript;',
                            ['class' => 'btn btn-danger btn-sm btn-icon icon-left reject', 'data-user' => $model['nickName'],'data-coinB' => $model['coinB'],'data-id' => $model['id']]);
                    }
                }
            ],
        ],
    ],
    'pager'            => [
        'options'     => [ 'class' => 'pagination dataTables_paginate paging_simple_numbers' ] ,
        'linkOptions' => [ 'class' => 'paginate_button' ] ,
        ] ,
] );

?>
<script>
    jQuery( document ).ready( function( $ ){
        $('.search-consumption-type .dropdown-menu a').click(function () {
            $('#type').val($(this).attr('data-searchConsumptionType'));
            $('button.btn-info.dropdown-toggle.status').attr('data-searchConsumptionType', $(this).attr('data-searchConsumptionType'));
            $('button.btn-info.dropdown-toggle.status').html($(this).text() + '　<span class="caret"></span>');
            $('button.search').trigger('click');
        });

        $('a.btn-success').click(function(){
            var nickname = $(this).attr('data-user');
            var coinB = $(this).attr('data-coinB');
            var confirmText = $('<span>').addClass('color-orange font-16').html("确定同意赠送（" + nickname+'） ' +  coinB +" 豆豆吗？");
            var successText = " 赠送成功！";
            showConfirmModal(this,confirmText, successText);
            return false;
        });

        // $('a.reject').click(function(){
        //     var nickname = $(this).attr('data-user');
        //     var coinB = $(this).attr('data-coinB');
        //     var confirmText = $('<span>').addClass('color-orange font-16').html("确定拒绝赠送（" + nickname+'） ' +  coinB +" 豆豆吗？");
        //     var successText = "拒绝成功！";
        //     showConfirmModal(this,confirmText, successText);
        //     return false;
        // });

        $('a.reject').click(function () {
            var nickname = $(this).attr('data-user');
            var coinB = $(this).attr('data-coinB');
            var rejectId = $(this).attr('data-id');
            $("#remark").val('');
            currencyReject(rejectId,nickname,coinB);
            return false;
        });

    })

    function currencyReject(rejectId,nickname,coinB){
        var $that = this;
        $("#rejectLabel").html("拒绝赠送给<span class='color-orange'>("+nickname+")</span> "+coinB+"个豆豆 的申请");
        $("#reject").modal('show');
        var successText = '成功拒绝赠送<span class=\'color-orange\'>('+nickname+')</span> '+coinB+'个豆豆 的申请';
        $("#reject .confirm").off('click');
        $("#reject .confirm").on('click',function(){
            var remark = $("#remark").val();
            var url = '<?= Url::to(['/finance/consumption/reject'])?>'
            $.ajax({
                url : url,
                type : 'get',
                dataType : 'json',
                data:"id="+rejectId+ "&remark="+remark,
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

<!--modal start-->
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
                                <textarea  class="form-control" autocomplete="false" name="remark" required id="remark" ></textarea>
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
<?php
Pjax::end();
?>

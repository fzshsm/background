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
        'label' => '用户管理',
        'url' => Url::to([
            '/user'
        ] )
    ],
    [ 'label' => '个人豆豆增送列表' ] ,
];
AppAsset::registerCss( $this , '@web/plugin/datatables/datatables.min.css' );
AppAsset::registerCss($this , '@web/plugin/daterangepicker/daterangepicker-bs3.css');
AppAsset::registerCss($this, '@web/plugin/viewer/viewer.css');

AppAsset::registerJs($this, '@web/plugin/viewer/viewer.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/moment.min.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/daterangepicker.js');
AppAsset::registerJs($this , '@web/js/common.js');
Pjax::begin(['id' => 'complaint-filter']);
$form = ActiveForm::begin([
    'id' => 'complaint-filter-form',
    'method' => 'get',
    'options' => ['data-pjax' => true , 'role' => 'form']
] );
?>
<?php if(Yii::$app->session->hasFlash('error')){ ?>
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <?=Yii::$app->session->getFlash('error')?>
    </div>
<?php }?>

    <div class="col-sm-12">
        <?php
        ActiveForm::end();
        echo GridView::widget( [
            'id'               => 'complaint' ,
            'dataProvider'     => $dataProvider ,
            'emptyText'        => "暂无豆豆详情信息！",
            'emptyCell' => '',
            'emptyTextOptions' => [ 'class' => 'text-center' ] ,
            'tableOptions'     => [ 'class' => 'table table-bordered datatable no-footer hover stripe text-center dataTable','id'=> 'show_image' ] ,
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
                                $text = '已发放';
                                break;
                            case 2 :
                                $className = 'fa fa-times-circle color-red font-18';
                                $text = '拒绝';
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
                    'label' => '审核时间',
                    'attribute' => 'auditTime',
                    'value'=>'auditTime',
                ],
            ] ,
            'pager'            => [
                'options'     => [ 'class' => 'pagination dataTables_paginate paging_simple_numbers' ] ,
                'linkOptions' => [ 'class' => 'paginate_button' ] ,
            ] ,
        ] );


        ?>
    </div>
    <script>
        jQuery( document ).ready( function( $ ){
            table = $('#show_image');
            if (table.find('img').length > 0){
                var viewer = new Viewer(table[0] , {navbar : false, tooltip : false, scalable : false, fullscreen : false,zIndex : 99999});
            }

            $('#date').daterangepicker({
                startDate: "<?= date('Y-m-d H:i' , strtotime( "-3 days")) ?>",
                endDate: "<?= date('Y-m-d H:i') ?>",
                showWeekNumbers : false, //是否显示第几周
                timePicker : false, //是否显示小时和分钟
                opens : 'right', //日期选择框的弹出位置
                buttonClasses : [ 'btn btn-default' ],
                applyClass : 'btn-small btn-success',
                cancelClass : 'btn-small',
                format : 'YYYY-MM-DD', //控件中from和to 显示的日期格式
                separator : ' 至 ',
                locale : {
                    applyLabel : '确定',
                    cancelLabel : '取消',
                    fromLabel : '开始时间',
                    toLabel : '结束时间',
                    daysOfWeek : [ '日', '一', '二', '三', '四', '五', '六' ],
                    monthNames : [ '一月', '二月', '三月', '四月', '五月', '六月',
                        '七月', '八月', '九月', '十月', '十一月', '十二月' ],

                }
            }, function(start, end, label) {//格式化日期显示框
                $('#date span').html(start.format('YYYY-MM-DD') + ' 至 ' + end.format('YYYY-MM-DD'));
            }).on('apply.daterangepicker' , function(){
                $('button.search').trigger('click');
            });
        });
        $('a.mark').click(function(){
            var objNo = $(this).attr('data-name');
            var confirmText = $('<span>').addClass('color-orange font-16').html("您确定使用编号（" + objNo + "） 这个物品吗？该操作不可逆转，您确定继续吗？");
            var successText = "已将编号(" + objNo + ") 的物品标记为使用！";

            var markUrl = $(this).attr('href');
            var operateUser = $("#operate-user").val();
            var userId = $("#user-id").val();
            var nickName = $(this).attr('data-user');
            var mark = operateUser+'将用户'+nickName+'（'+userId+'）背包里'+'编号为（'+objNo+'）'+'的物品标记为已使用';

            markUrl = markUrl+'&mark='+mark;
            jQuery('#confirm-modal .modal-body').html(confirmText);
            jQuery('#confirm-modal .confirm').off('click');
            jQuery('#confirm-modal .confirm').on('click' , function(){

                $.ajax({
                    url : markUrl,
                    type : 'get',
                    dataType : 'json',
                    success : function(response){
                        if(response.status == 'success'){
                            toastr.success(successText , '' , toastrOpts);
                            window.location.reload();
                        }else{
                            toastr.error(response.message , '' , toastrOpts);
                        }
                    }
                });
                jQuery('#confirm-modal').modal('hide');
            });
            jQuery('#confirm-modal').modal('show');
            return false;
        });
        $('a.search-objName').click(function(){
            $('#content').val($(this).attr('data-name'));
            $('button.search').trigger('click');
        });
    </script>


<?php
Pjax::end();
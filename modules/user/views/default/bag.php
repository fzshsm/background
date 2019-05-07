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
    [
        'label' => '用户列表'
    ] ,
    [ 'label' => '背包管理' ] ,
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
    <h2 class="col-sm-12" style="text-align: left;margin-bottom: 10px">
        <?php if(isset($data['nickName']) && !empty($data['nickName'])){
            echo $data['nickName'].' 的';
        } ?>
        背包概览：</h2>
    <style>
        b{font-size: 1.3rem}
    </style>


    <div class="col-sm-12">
        <?php
        $num = (isset($data['overview']) && !empty($data['overview'])) ? count($data['overview']) : 0;
        for($i=0;$i<$num;$i++){
            ?>
            <div class="col-md-1">
                <b><a href="#" class="search-objName" data-name="<?= $data['overview'][$i]['objName']?>"><?= $data['overview'][$i]['objName'] ?></a></b>
                <span style="color: red">(<?= $data['overview'][$i]['objNum']?>)</span>
            </div>
        <?php }?>
        <div id="roomcard-search col-sm-12" style="margin-bottom: 10px;margin-top: 5px;float: right" >
            <div class="col-sm-1 refresh" style="">
                <a href="<?= Url::to(['/user/bag','id' => \Yii::$app->request->get('id')])?>" class="btn btn-default" title="刷新">
                    <i class="fa fa-refresh"></i>
                </a>
            </div>
            <div class="col-sm-4 padding-left-8">
                <div class="input-group ">
                    <span class="input-group-addon"><i class="entypo-calendar"></i></span>
                    <input type="text" class="form-control cursor-pointer" name="date" id="date" readonly placeholder="选择使用日期" value="<?= Yii::$app->request->get('date'); ?>" >
                </div>
            </div>
            <div class="input-group " style="width: 400px">
                <div class="input-group-btn search-status" style="padding-right: 10px">
                    <?php
                    $searchStatus = Yii::$app->request->get('searchStatus' , 0);
                    $searchStatusList = [0 => '全部', 1 => '待使用', 2 => '已使用', 3 => '已过期'];
                    ?>
                    <input type="hidden" id="searchStatus" name="searchStatus" value="<?=$searchStatus?>">
                    <button type="button" class="btn btn-info dropdown-toggle status" style="width: 80px" data-searchstatus="<?= $searchStatus?>"  data-toggle="dropdown">
                        <?= $searchStatusList[$searchStatus] ?>　<span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-infoblue searchstatus">
                        <?php foreach($searchStatusList as $key => $value){ ?>
                            <li>
                                <a data-searchstatus="<?= $key ?>" href="javascript:void(0);"><?= $value ?></a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="input-group " style="width: 300px">
                    <div class="input-group-btn searchType">
                        <input type="hidden" id="searchType" name="searchType" value="objName">
                        <button type="button" class="btn btn-success dropdown-toggle type" style="width: 80px" data-searchtype="objName" data-toggle="dropdown">
                            物品名
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
        </div>
        <input type="hidden" id="operate-user" value="<?= Yii::$app->user->getIdentity()->username ?>">
        <input type="hidden" id="user-id" value="<?= Yii::$app->request->get('id') ?>">
        <?php
        ActiveForm::end();
        echo GridView::widget( [
            'id'               => 'complaint' ,
            'dataProvider'     => $dataProvider ,
            'emptyText'        => "暂无背包详情信息！",
            'emptyCell' => '',
            'emptyTextOptions' => [ 'class' => 'text-center' ] ,
            'tableOptions'     => [ 'class' => 'table table-bordered datatable no-footer hover stripe text-center dataTable','id'=> 'show_image' ] ,
            'options'          => [ 'class' => 'dataTables_wrapper no-footer no-border' ] ,
            'layout'           => "{errors}{items}{pager}" ,
            "columns"          => [
                'objNo:text:物品编号',
                'objName:text:物品名',
                [
                    'label' => '物品图标',
                    'format' => [
                        'image',
                        [
                            'height'=>'80',
                            'name' => 'show_image'
                        ]
                    ],
                    'value' => function ($model) {
                        return $model['objIcon'];
                    },
                    'headerOptions' =>['width' => '8%']
                ],
                'getDate:text:发放时间',
                'useDate:text:使用时间',
               // 'status:text:状态',
                [
                    'label' => '状态',
                    'attribute' => 'status',
                    'value' => function($model){
                        switch ($model['status']){
                            case '未使用':
                                return Html::tag( 'span' , '未使用' , [ 'class' => 'label label-success'] );
                                break;
                            case '已使用':
                                return Html::tag( 'span' , '已使用' , [ 'class' => 'label label-default'] );
                                break;
                            case '已经过期':
                                return Html::tag( 'span' , '已过期' , [ 'class' => 'label label-primary'] );
                                break;
                        }
                    },
                    'format' => 'raw'
                ],
                [
                    'class'    => ActionColumn::className() ,
                    'template' => '{mark}',
                    'header'   => '操作' ,
                    'contentOptions' => ['class' => 'actions'],
                    'buttons'  => [
                        'mark' => function($url,$model){
                            if($model['status'] == '未使用'){
                                $icon = Html::tag('i','',['class' => 'fa fa-check']);
                                return Html::a($icon.'使用',$url,['class' => 'mark btn btn-success btn-sm btn-icon icon-left','data-name' => $model['objNo'],'data-user' => $model['nickName']]);
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

            $('.search-status .dropdown-menu a').click(function(){
                $('#searchStatus').val($(this).attr('data-searchstatus'));
                $('.search-status button.dropdown-toggle').attr('data-searchstatus' , $(this).attr('data-searchstatus'));
                $('.search-status button.dropdown-toggle').html($(this).text() +　'　<span class="caret"></span>');
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
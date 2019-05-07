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
        'label' => '商城管理',
        'url' => Url::to([
            '/mall'
        ] )
    ],
    [
        'label' => '商品所属'
    ]
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
        <div class="col-sm-5" style="margin-bottom: 10px;margin-top: 5px;padding-right: 0px;float: right" >
            <div class="refresh pull-left" style="margin-right: 15px;">
                <a href="<?= Url::to(['/mall/goods/owner'])?>" class="btn btn-default" title="刷新">
                    <i class="fa fa-refresh"></i>
                </a>
            </div>
            <div class="col-sm-1 input-group pull-left searchStatus" style="margin-right: 18px;">
                <?php
                $status = Yii::$app->request->get('status' , 0);
                $statusList = [0 => '全部状态', 1 => '未使用', 2 => '已使用', 3 => '已过期'];
                ?>
                <input type="hidden" id="status" name="status" value="<?=$status?>">
                <button type="button" class="btn btn-blue dropdown-toggle status" style="width: 120px" data-searchStatus="<?= $status?>"  data-toggle="dropdown">
                    <?= $statusList[$status] ?>　<span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-darkblue">
                    <?php foreach($statusList as $key => $value){ ?>
                        <li>
                            <a data-searchStatus="<?= $key ?>" href="javascript:void(0);"><?= $value ?></a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <div class="input-group col-sm-8 pull-left searchType">
                <?php
                $searchType = Yii::$app->request->get('searchType', 'goodsName');
                $searchTypeList = [ 'goodsName' => '物品名称','goodsCode' => '物品编号'];
                ?>
                <div class="input-group-btn">
                    <input type="hidden" id="searchType" name="searchType" value="goodsName">
                    <button type="button" class="btn btn-success dropdown-toggle btn-width-100 type"
                            data-searchtype="<?= $searchType ?>" data-toggle="dropdown">
                        <?= $searchTypeList[$searchType] ?>　<span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-green">
                        <?php foreach($searchTypeList as $key => $value){ ?>
                            <li><a href="javascript:void(0);" data-searchtype="<?= $key?>"><?= $value?></a></li>
                        <?php } ?>
                    </ul>
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
        <input type="hidden" id="operate-user" value="<?= Yii::$app->user->getIdentity()->username ?>">
    </div>
    
    
    <div class="col-sm-12">
        <?php
        ActiveForm::end();
        echo GridView::widget( [
            'id'               => 'complaint' ,
            'dataProvider'     => $dataProvider ,
            'emptyText'        => "暂无商品详情信息！",
            'emptyCell' => '',
            'emptyTextOptions' => [ 'class' => 'text-center' ] ,
            'tableOptions'     => [ 'class' => 'table table-bordered datatable no-footer hover stripe text-center dataTable','id'=> 'show_image' ] ,
            'options'          => [ 'class' => 'dataTables_wrapper no-footer no-border' ] ,
            'layout'           => "{errors}{items}{pager}" ,
            "columns"          => [
                'goodsName:text:物品名称',
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
                        return $model['goodsImg'];
                    },
                    'headerOptions' =>['width' => '8%']
                ],
                'goodsNo:text:物品编号',
                'price:text:购买价格',
                'nickName:text:用户昵称',
                'userNo:text:用户编号',
                'contactsName:text:收货人',
                'contactsMobile:text:收货人电话',
                'idCard:text:收货人身份证',
                'getTime:text:获取时间',
                [
                    'label' => '状态',
                    'attribute' => 'status',
                    'value' => function($model){
                        switch ($model['status']){
                            case 1:
                                return Html::tag( 'span' , '未使用' , [ 'class' => 'label label-default'] );
                                break;
                            case 2:
                                return Html::tag( 'span' , '已使用' , [ 'class' => 'label label-success'] );
                                break;
                            case 3:
                                return Html::tag( 'span' , '已过期' , [ 'class' => 'label label-danger'] );
                                break;
                        }
                    },
                    'format' => 'raw'
                ],
                'useTime:text:使用时间',
                'useNickName:text:使用人'
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
        });
        $('.searchStatus .dropdown-menu a').click(function () {
            console.log('xxxxxxxxxxxxxxxxxxxxx');
            $('#status').val($(this).attr('data-searchStatus'));
            $('button.btn-blue.dropdown-toggle.status').attr('data-searchStatus', $(this).attr('data-searchStatus'));
            $('button.btn-blue.dropdown-toggle.status').html($(this).text() + '　<span class="caret"></span>');
            $('button.search').trigger('click');
        });
        $('.searchType .dropdown-menu a').click(function () {
            $('#searchType').val($(this).attr('data-searchtype'));
            $('button.btn-success.dropdown-toggle.type').attr('data-searchtype', $(this).attr('data-searchtype'));
            $('button.btn-success.dropdown-toggle.type').html($(this).text() + '　<span class="caret"></span>');
        });
    </script>


<?php
Pjax::end();
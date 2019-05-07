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
        'label' => '日志管理' ,
        'url'   => Url::to( [
            '/admin/log' ,
        ] ) ,
    ] ,
    [
        'label' => '日志列表' ,
    ] ,
];
AppAsset::registerCss( $this , '@web/plugin/datatables/datatables.min.css' );
AppAsset::registerCss($this , '@web/plugin/daterangepicker/daterangepicker-bs3.css');
AppAsset::registerCss($this, '@web/plugin/viewer/viewer.css');

AppAsset::registerJs($this, '@web/plugin/viewer/viewer.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/moment.min.js');
AppAsset::registerJs($this , '@web/plugin/daterangepicker/daterangepicker.js');

Pjax::begin();
?>

<!--展示log详情的modal-->
    <div class="modal fade in" id="show-log" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">操作详情</h4>
                </div>
                <div class="modalbody">
                    <div class="form-group">
                        <div class="col-sm-10">
                            <pre id="log_detail">
                            </pre>
                        </div>
                    </div>
                    <input type="hidden" id="log_id" value="0">
                    <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal -->
    </div>
<!--展示json格式的样式-->
    <style>
        pre {outline: 1px solid #ccc; padding: 5px; margin: 5px; }
        .string { color: green; }
        .number { color: darkorange; }
        .boolean { color: blue; }
        .null { color: magenta; }
        .key { color: red; }
    </style>
<?php
$form = ActiveForm::begin([
    'id' => 'user-form',
    'method' => 'get',
    'options' => ['data-pjax' => true , 'role' => 'form']
] );
?>

<div class="form-group pull-right col-md-6">
    <div class="col-sm-1">
        <a href="<?= Url::to(['/admin/operate-log'])?>" class="btn btn-default" title="刷新">
            <i class="fa fa-refresh"></i>
        </a>
    </div>
    <div class="col-sm-6">
        <div class="input-group">
            <span class="input-group-addon"><i class="entypo-calendar"></i></span>
            <input type="text" class="form-control cursor-pointer" name="date" id="date" readonly placeholder="选择日期" value="<?= Yii::$app->request->get('date'); ?>" >
        </div>
    </div>
    <div class="input-group col-sm-5 pull-right search-type">
        <?php
        $searchType = Yii::$app->request->get('searchType' , 'action');
        $searchTypeList = ['action' => '操作名称' , 'username' => '用户名' ];
        ?>
        <div class="input-group-btn">
            <input type="hidden" id="searchType" name="searchType" value="<?=$searchType?>">
            <button type="button" class="btn btn-success dropdown-toggle btn-width-100" data-searchtype="<?= $searchType?>" data-toggle="dropdown">
             <?= $searchTypeList[$searchType]?>　<span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-green">
                <?php foreach($searchTypeList as $key => $value){ ?>
                    <li><a href="javascript:void(0);" data-searchtype="<?= $key?>"><?= $value?></a></li>
                <?php } ?>
            </ul>
        </div>
        <input type="text" id="content" class="form-control" name="content" placeholder=""  value="<?= Yii::$app->request->get('content'); ?>">
        <div class="input-group-btn">
            <button  type="submit" class="btn btn-success search">
                <i class="entypo-search"></i>
            </button>
        </div>

    </div>
</div>
    <script>
        $(document).ready(function(){
            $('.search-type .dropdown-menu a').click(function(){
                $('#searchType').val($(this).attr('data-searchtype'));
                $('.search-type button.dropdown-toggle').attr('data-searchtype' , $(this).attr('data-searchtype'));
                $('.search-type button.dropdown-toggle').html($(this).text() +　'　<span class="caret"></span>');
            });
        })
        function showLogDetail(id){
            $("#show-log").modal('show');

            var csrfToken = $("#_csrf").val();
            $.ajax({
                data:{
                    _csrf:csrfToken,
                },
                dataType:"json",
                type:"post",
                url:'<?= Url::to(['/admin/operate-log/note?id='])?>'+id,
                success:function(response){
                    if(response.status == 'success'){
                        $('#log_detail').html(syntaxHighlight(response.data));
                    }else{
                        alert(response.message)
                    }
                }
            })
            $("#show-log").on('shown.bs.modal',function(){
                $("#log_id").val(id)
            })
        }
        function syntaxHighlight(json) {
            if (typeof json != 'string') {
                json = JSON.stringify(json, undefined, 2);
            }
            json = json.replace(/&/g, '&').replace(/</g, '<').replace(/>/g, '>');
            return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function(match) {
                var cls = 'number';
                if (/^"/.test(match)) {
                    if (/:$/.test(match)) {
                        cls = 'key';
                    } else {
                        cls = 'string';
                    }
                } else if (/true|false/.test(match)) {
                    cls = 'boolean';
                } else if (/null/.test(match)) {
                    cls = 'null';
                }
                return '<span class="' + cls + '">' + match + '</span>';
            });
        }
    </script>
<?php
ActiveForm::end();
echo GridView::widget( [
    'id'               => 'season' ,
    'dataProvider'     => $dataProvider ,
    'emptyText'        => "暂无日志信息！" ,
    'emptyTextOptions' => [ 'class' => 'text-center' ] ,
    'tableOptions'     => [ 'class' => 'table table-bordered datatable no-footer hover stripe text-center dataTable' ] ,
    'options'          => [ 'class' => 'dataTables_wrapper no-footer no-border' ] ,
    'layout'           => "{errors}{items}{pager}" ,
    "columns"          => [
        'id:raw:ID' ,
        'username:text:用户名',
        'target_url:text:访问路径',
        'action:text:操作',
        'ip:text:IP',
        'create_time:text:操作时间',
        [
            'class'    => ActionColumn::className() ,
            'template' => '{show}' ,
            'header'   => '操作详细' ,
            'contentOptions' => ['class' => 'actions'],
            'buttons'  => [
                'show' => function( $url,$model,$key){

                    $icon = Html::tag( 'i' , '' , [ 'class' => 'fa fa-eye' ] );
                    $logId = $model['id'];
                    return Html::button($icon."查看", [ 'class' => 'btn btn-info btn-sm btn-icon icon-left','type' => 'button','onclick' =>"showLogDetail($logId)" ] );
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
    jQuery( document ).ready( function( $ ){
        getDate();
    })

    function getDate(){
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
    }

    $(document).on('pjax:complete',function(){
        getDate()
    })
</script>

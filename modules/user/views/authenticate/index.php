<?php
use yii\helpers\Url;
use app\assets\AppAsset;

$this->params['breadcrumbs'] = [
    [
        'label' => '用户管理',
        'url' => Url::to([
            '/user'
        ] )
    ],
    [
        'label' => '认证审核'
    ]
];

AppAsset::registerCss($this, '@web/plugin/select2/select2-bootstrap.css');
AppAsset::registerCss($this, '@web/plugin/select2/select2.css');
AppAsset::registerCss($this, '@web/plugin/datatables/datatables.min.css');
AppAsset::registerCss($this, '@web/plugin/shCircleLoader/jquery.shCircleLoader.css');
AppAsset::registerCss($this, '@web/plugin/viewer/viewer.css');

AppAsset::registerJs($this, '@web/plugin/datatables/datatables.min.js');
AppAsset::registerJs($this, '@web/plugin/select2/select2.min.js');
AppAsset::registerJs($this, '@web/plugin/shCircleLoader/jquery.shCircleLoader-min.js');
AppAsset::registerJs($this, '@web/plugin/viewer/viewer.js');
?>
<script type="text/javascript" src="<?= Url::to('@web/js/user/authenticateDataFormat.js')?>"></script>
<script type="text/javascript" src="<?= Url::to('@web/js/datatable.custom.js')?>"></script>
<script type="text/javascript">
    jQuery( document ).ready( function( $ ) {
        jQuery('.loadding').shCircleLoader();
        var format = AuthenticateDataFormat;
        format.toastrOpts = toastrOpts;
        format.setActionsUrl([
            '<?= Url::to(['/user/authenticate/agree'])?>',
            '<?= Url::to(['/user/authenticate/reject'])?>',
        ]);
        format.ajaxUrl = '<?= Url::to(['/user/authenticate']);?>';
        format.datatable = customDatatable(jQuery( '#authenticate-data') , format);
    } );
</script>
<div id="authenticate-search" class="hide">
    <div class="col-sm-1 refresh" style="margin-right: 10%">
        <a href="javascript:void(0);" class="btn btn-default" title="刷新">
            <i class="fa fa-refresh"></i>
        </a>
    </div>
    <div class="input-group">
        <div class="input-group-btn">
            <button type="button" class="btn btn-success dropdown-toggle btn-width-75" data-searchtype="nickname"  data-toggle="dropdown">
                昵称　<span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-green">
                <li><a href="javascript:void(0);" data-searchtype="2" >昵称 </a></li>
                <li><a href="javascript:void(0);" data-searchtype="1">QQ</a></li>
            </ul>
        </div>
        <input type="text" id="content" class="form-control" name="search" placeholder="">
        <div class="input-group-btn">
            <button  type="button" class="btn btn-success search">
                <i class="entypo-search"></i>
            </button>
        </div>
    </div>
</div>
<div class="col-md-12">
    <ul class="nav nav-tabs bordered"><!-- available classes "bordered", "right-aligned" -->
        <li>
            <a data-status="-1" href="javascript:void(0);">
                <span class="visible-xs"><i class="entypo-home"></i></span>
                <span class="hidden-xs">全　部</span>
            </a>
        </li>
        <li class="active">
            <a data-status="1" href="javascript:void(0);">
                <span class="visible-xs"><i class="entypo-user"></i></span>
                <span class="hidden-xs">审核中</span>
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <table class="table table-bordered datatable no-footer hover stripe text-center no-border" id="authenticate-data" cellspacing="0" >
            <thead>
            <tr>
                <td width="8%">昵称</td>
                <td width="6%">QQ</td>
                <td width="6%">战队</td>
                <td width="6%">真实姓名</td>
                <td width="10%">身份证</td>
                <td width="15%">正面照</td>
                <td width="15%">反面照</td>
                <td width="15%">半身照</td>
                <td width="5%">状态</td>
                <td width="15%">操作</td>
            </tr>
            </thead>
        </table>
    </div>
</div>

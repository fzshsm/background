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
                'label' => '用户列表' 
        ] 
];

AppAsset::registerCss($this, '@web/plugin/select2/select2-bootstrap.css');
AppAsset::registerCss($this, '@web/plugin/select2/select2.css');
AppAsset::registerCss($this, '@web/plugin/datatables/datatables.min.css');
AppAsset::registerCss($this, '@web/plugin/shCircleLoader/jquery.shCircleLoader.css');
AppAsset::registerJs($this, '@web/plugin/datatables/datatables.min.js');
AppAsset::registerJs($this, '@web/plugin/select2/select2.min.js');
AppAsset::registerJs($this, '@web/plugin/shCircleLoader/jquery.shCircleLoader-min.js');
?>
<style>table a.btn{margin-top: 4px}</style>
<script type="text/javascript" src="<?= Url::to('@web/js/user/userDatatableFormat.js')?>"></script>
<script type="text/javascript" src="<?= Url::to('@web/js/datatable.custom.js')?>"></script>
<script type="text/javascript">
jQuery( document ).ready( function( $ ) {
	jQuery('.loadding').shCircleLoader();
	var format = UserDatatableFormat;
	format.toastrOpts = toastrOpts;
	format.setActionsUrl([
		'<?= Url::to(['/user/update'])?>',
		'<?= Url::to(['/user/authorize'])?>',
		'<?= Url::to(['/user/lock'])?>',
		'<?= Url::to(['/user/unlock'])?>',
        '<?= Url::to(['/user/unbind'])?>',
        '<?= Url::to(['/user/bindrole'])?>',
        '<?= Url::to(['/user/role'])?>',
        '<?= Url::to(['/user/bag'])?>',
        '<?= Url::to(['/user/currency'])?>',
        '<?= Url::to(['/user/currencylist'])?>',
	]);

	format.ajaxUrl = '<?= Url::to(['/user']); ?>';
	format.datatable = customDatatable(jQuery( '#user-data') , format);

} );
</script>
<div id="user-search" class="hide">
    <div class="col-sm-1 refresh" style="margin-right: 10%">
        <a href="javascript:void(0);" class="btn btn-default" title="刷新">
            <i class="fa fa-refresh"></i>
        </a>
    </div>
	<div class="input-group">
    	<div class="input-group-btn">
    		<button type="button" class="btn btn-success dropdown-toggle btn-width-100" data-searchtype="id"  data-toggle="dropdown">
    			I D　<span class="caret"></span>
    		</button>
    		<ul class="dropdown-menu dropdown-green">
                <li><a href="javascript:void(0);" data-searchtype="id">I D</a></li>
    			<li><a href="javascript:void(0);" data-searchtype="nickname" >昵称 </a></li>
<!--    			<li><a href="javascript:void(0);" data-searchtype="qq">QQ</a></li>-->
    			<li><a href="javascript:void(0);" data-searchtype="mobile">手机</a></li>
<!--                <li><a href="javascript:void(0);" data-searchtype="roleId">游戏昵称</a></li>-->
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

<table class="table table-bordered datatable no-footer hover stripe" id="user-data" cellspacing="0" >
	<thead>
		<tr>
			<td width="2%">ID</td>
			<td width="8%">昵称</td>
<!--            <td width="8%">游戏昵称</td>-->
			<td width="4%">性别</td>
			<td width="8%">QQ</td>
			<!-- <td width="8%">手机</td> -->
			<td width="6%">所属联赛</td>
			<!-- <td width="4%">胜率</td>
			<td width="4%">勋章</td> -->
			<td width="4%">实名</td>
			<td width="6%">认证身份</td>
			<td width="6%">战队</td>
			<td width="1%">状态</td>
			<td width="20%">操作</td>
		</tr>
	</thead>
</table>


<div class="modal fade in" id="bind-steam-role" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">绑定steam角色</h4>
            </div>
            <div class="modalbody">
                <div class="row">
                    <div class="col-sm-11">
                        <div class="form-group">
                            <label for="steamRole" class="col-sm-3 control-label">steam角色名：</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" autocomplete="false" name="steamRole" required id="steamRole" value="" >
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

<div class="modal fade in" id="send-currency" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="sendTitle"></h4>
            </div>
            <div class="modalbody">
                <div class="row">
                    <div class="col-sm-11">
                        <div class="form-group">
                            <label for="steamRole" class="col-sm-3 control-label">豆豆数量：</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" autocomplete="false" name="coinB" required id="coinB" value=""
                                       onkeyup="this.value=this.value.replace(/\D/g,'')"  onafterpaste="this.value=this.value.replace(/[^\d\.]/g,'')" maxlength=9>
                            </div>
                        </div>
                        <div class="form-group" style="margin-top: 10%">
                            <label for="steamRole" class="col-sm-3 control-label">赠送理由：</label>
                            <div class="col-sm-7">
                                <textarea  class="form-control" autocomplete="false" name="remark" required id="remark"  ></textarea>
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
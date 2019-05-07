<?php

use app\assets\AppAsset;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
$leagueId = Yii::$app->request->get('leagueId' , 0);
$this->params['breadcrumbs'] = [
    [
        'label' => '联赛管理',
        'url' => Url::to([
            '/league',
        ]),
    ],
];
if(!empty($leagueId)){
    array_push($this->params['breadcrumbs'],['label' => $leagueName]);
}
array_push($this->params['breadcrumbs'],['label' => '成员管理']);

AppAsset::registerCss($this, '@web/plugin/datatables/datatables.min.css');
AppAsset::registerCss($this, '@web/plugin/daterangepicker/daterangepicker-bs3.css');
AppAsset::registerCss($this, '@web/plugin/viewer/viewer.css');

AppAsset::registerJs($this, '@web/plugin/viewer/viewer.js');
AppAsset::registerJs($this, '@web/plugin/daterangepicker/moment.min.js');
AppAsset::registerJs($this, '@web/plugin/daterangepicker/daterangepicker.js');
AppAsset::registerJs($this, '@web/js/common.js');

Pjax::begin(['id' => 'complaint-filter']);
$form = ActiveForm::begin([
    'id' => 'complaint-filter-form',
    'method' => 'get',
    'options' => ['data-pjax' => true, 'role' => 'form']
]);
?>
<?php if(Yii::$app->session->hasFlash('error')){ ?>
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <?=Yii::$app->session->getFlash('error')?>
    </div>
<?php }?>
<style>
    th{font-weight:bold;font-size:13px}
    table a.btn{margin-top: 4px}
</style>

    <div class="col-sm-12">
        <?php if(!empty($leagueId)){?>
            <div class="form-group col-sm-1 pull-left member-status">
                <?php
                $memberStatus = Yii::$app->request->get('status' , 0);
                $memberStatusList = [0 => '全部', 3 => '参赛成员' , 2 => '等待审核'];
                ?>
                <button type="button" id="member-status" class="btn btn-blue dropdown-toggle" data-member-status="<?= $memberStatus ?>"  data-toggle="dropdown">
                    <?= $memberStatusList[$memberStatus]?>　<span class="caret"></span>
                </button>
                <input type="hidden" id="status" name="status" value="<?= $memberStatus?>">
                <ul class="dropdown-menu dropdown-blue">
                    <?php foreach ($memberStatusList as $key => $value){?>
                        <li>
                            <a data-member-status="<?= $key?>" href="javascript:void(0);"><?= $value?></a>
                        </li>
                    <?php }?>
                </ul>
            </div>
            <?php if($gameType == 'glory'){?>
                <div class="form-group col-sm-1 pull-left season-list">
                    <?php
                    if(!empty($currentSeason) && !empty($seasonList)){
                        ?>
                        <input type="hidden" id="seasonId" name="seasonId" value="<?=$currentSeason['id']?>">
                        <button id="season" type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" data-seasonid="<?=$currentSeason['id']?>">
                            <?=$currentSeason['name']?>　<span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-infoblue" role="menu">
                            <?php foreach($seasonList as $season){ ?>
                                <li>
                                    <a data-seasonid="<?= $season['id']?>" href="javascript:void(0);"><?= $season['name']?></a>
                                </li>
                            <?php } ?>
                        </ul>
                    <?php } ?>
                </div>
            <?php }?>
        <?php } ?>
        <?php if(empty($leagueId)){ ?>
        <ul class="nav nav-tabs bordered " style="margin-bottom: 10px">
            <?php if ($gameType == 'glory') { ?>
                <?php $glory = 'active';
                $pubg = ''; ?>
            <?php } else { ?>
                <?php $glory = '';
                $pubg = 'active'; ?>
            <?php } ?>
            <li class="<?= $glory ?>">
                <?php
                    $gloryUrl = Url::to(['/league/glorymember']);
                    if(!empty($leagueId)){
                        $gloryUrl = $gloryUrl.'?leagueId='.$leagueId;
                    }
                ?>
                <a data-status="1" href="<?= $gloryUrl ?>">
                    <span>王者荣耀</span>
                </a>
            </li>
            <li class="<?= $pubg ?>">
                <?php
                    $pubgUrl = Url::to(['/league/pubgmember']);
                    if(!empty($leagueId)){
                        $pubgUrl = $pubgUrl.'?leagueId='.$leagueId;
                    }
                ?>
                <a data-status="2" href="<?= $pubgUrl ?>">
                    <span>绝地求生</span>
                </a>
            </li>
        </ul>
        <?php }?>
        <div class="form-group pull-right col-sm-6">
            <?php if(!empty($leagueId)){?>
                <?php $url = \Yii::$app->request->getHostInfo().'/'.\Yii::$app->request->getPathInfo().'?leagueId='.$leagueId;?>
            <?php }else{?>
                <?php $url = \Yii::$app->request->getHostInfo().'/'.\Yii::$app->request->getPathInfo();?>
            <?php }?>
                <div class="col-sm-1" style="margin-left: 48%">
                    <a href="<?= $url?>" class="btn btn-default" title="刷新">
                        <i class="fa fa-refresh"></i>
                    </a>
                </div>

            <div class="input-group league-search-type col-sm-5 pull-right">
                <div class="input-group-btn searchType">
                    <?php

                    if($gameType == 'glory'){
                        if(!empty($leagueId)){
                            $searchType = Yii::$app->request->get('searchType', 'qq');
                            $searchTypeList = [ 'qq' => 'QQ','role' => '游戏角色', 'mobile' => '手机'];
                        }else{
                            $searchType = Yii::$app->request->get('searchType', '4');
                            $searchTypeList = [ '4' => 'QQ','1' => '游戏角色', '2' => '手机'];
                        }
                    }else{
                        $searchType = Yii::$app->request->get('searchType', '1');
                        $searchTypeList = ['1' => '昵称', '2' => '手机', '3' => 'steamId'];
                    }
                    ?>
                    <input type="hidden" id="searchType" name="searchType" value="<?= $searchType ?>">
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
                <input type="text" id="content" class="form-control" name="content" placeholder="" value="<?= Yii::$app->request->get('content'); ?>">
                <div class="input-group-btn">
                    <button  type="submit" class="btn btn-success search">
                        <i class="entypo-search"></i>
                    </button>
                </div>
            </div>
        </div>
<?php
ActiveForm::end();
echo $this->render('_gridView' , ['dataProvider' => $dataProvider,'leagueId' => isset($leagueId) ? $leagueId : '','gameType' => $gameType]);
?>
    </div>
    <script>
        jQuery(document).ready(function ($) {
            if($("#status").val() == 2){
                $(".season-list").hide()
            }

            $('.searchType .dropdown-menu a').click(function () {
                $('#searchType').val($(this).attr('data-searchtype'));
                $('button.btn-success.dropdown-toggle').attr('data-searchtype', $(this).attr('data-searchtype'));
                $('button.btn-success.dropdown-toggle').html($(this).text() + '　<span class="caret"></span>');
            });

            $('a.btn-success').click(function(){
                var nickname = $(this).attr('data-user');
                var league = $(this).attr('data-league');
                var confirmText = $('<span>').addClass('color-orange font-16').html("确定同意“" + nickname + "”加入" + league + "吗？");
                var successText = nickname + " 认证成功！";
                showConfirmModal(this,confirmText, successText);
                return false;
            });

            $('a.ban').click(function(){
                var nickname = $(this).attr('data-user');
                var league = $(this).attr('data-league');
                var confirmText = $('<span>').addClass('color-orange font-16').html("确定要将" + league + "中的 “" + nickname + "” 禁赛吗？");
                var successText = "禁止  " + nickname + "  比赛成功！";
                showConfirmModal(this,confirmText, successText);
                return false;
            });

            $('a.unban').click(function(){
                var nickname = $(this).attr('data-user');
                var league = $(this).attr('data-league');
                var confirmText = $('<span>').addClass('color-orange font-16').html("确定要解除" + league + "中的 “" + nickname + "” 的禁赛处罚吗？");
                var successText = "解除  " + nickname + "  的禁赛处罚成功！";
                showConfirmModal(this,confirmText, successText);
                return false;
            });

            $('a.pubg-reject').click(function(){
                var nickname = $(this).attr('data-user');
                var league = $(this).attr('data-league');
                var confirmText = $('<span>').addClass('color-orange font-16').html("确定拒绝“" + nickname + "”加入" + league + "吗？");
                var successText = "拒绝成功！";
                showConfirmModal(this,confirmText, successText);
                return false;
            });

            $('a.reject').click(function () {
                var nickname = $(this).attr('data-user');
                var rejectId = $(this).attr('data-rejectId');
                gloryReject(rejectId,nickname);
                return false;
            });

            $('.season-list .dropdown-menu a').click(function(){
                $('#seasonId').val($(this).attr('data-seasonid'));
                $('.season-list button.dropdown-toggle').attr('data-seasonid' , $(this).attr('data-seasonid'));
                $('.season-list button.dropdown-toggle').html($(this).text() +　'　<span class="caret"></span>');
                $('button.search').trigger('click');
            });

            $('.member-status .dropdown-menu a').click(function(){
                var status = $(this).attr('data-member-status');
                if(status == 2){
                    $(".season-list").hide()
                }else{
                    $('.season-list').show();
                }
                $('#status').val(status);
                $('.member-status button.dropdown-toggle').attr('data-member-status' , $(this).attr('data-member-status'));
                $('.member-status button.dropdown-toggle').html($(this).text() +　'　<span class="caret"></span>');
                $('button.search').trigger('click');
            });

            $('a.chat').click(function(){
                var userId = $(this).attr('data-user');
                var forbidEndTime = $(this).attr('data-forbidEndTime');
                forbidUserEndTime(userId,forbidEndTime);
                return false;
            });

            table = $('#complaint');
            if (table.find('img').length > 0) {
                var viewer = new Viewer(table[0], {
                    navbar: false,
                    tooltip: false,
                    scalable: false,
                    fullscreen: false,
                    zIndex: 99999
                });
            }

            $('.nav-tabs a').off('click');
            $('.nav-tabs a').on('click', function () {
                var $that = $(this);
                var dataStatus = $that.attr('data-status');
                $('.nav-tabs li').removeClass('active');
                $that.closest('li').addClass('active');
            })
        });

        function gloryReject(rejectId,nickname){
            var $that = this;
            $("#rejectLabel").html("拒绝"+nickname+"的认证申请");
            $("#reject").modal('show');
            var successText = '成功拒绝('+nickname+')的认证申请';
            $("#reject .confirm").off('click');
            $("#reject .confirm").on('click',function(){
                var remark = $("#remark").val();
                var url = '<?= Url::to(['/league/glorymember/reject'])?>'
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

        function forbidUserEndTime(userId,forbidEndTime){
            var $that = this;
            if(forbidEndTime == null){
                $('#forbid-end-time input[value=1]').trigger('click');
            }else{
                $('#forbid-end-time input[value=0]').trigger('click');
            }
            $("#time").val(0)
            $("#forbid-end-time").modal('show');
            $("#forbid-end-time .confirm").off('click');
            $("#forbid-end-time .confirm").on('click' , function(){
                var controlType = $("input[name='controlType']:checked").val();
                var time = $("#time").val();
                var url = '<?= Url::to(['/user/chat'])?>';
                $.ajax({
                    url : url,
                    type : 'get',
                    dataType : 'json',
                    data:"controlType="+controlType+ "&userId="+userId+"&time="+time,
                    success : function(response){
                        if(response.status == 'success'){
                            toastr.success(response.message , '' , $that.toastrOpts);
                            window.location.reload();
                        }else{
                            toastr.error(response.message , '' , $that.toastrOpts);
                        }
                    }
                });
                $('#forbid-end-time').modal('hide');
            });
            return false;
        }
    </script>
    <div class="modal fade" id="forbid-end-time">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modalbody">
                    <div class="form-group">
                        <label for="medalNum" class="col-sm-3 control-label">是否禁言</label>
                        <div class="radio radio-replace radio-inline">
                            <input type="radio"  name="controlType"  value="0" checked="checked">
                            <label class="tooltip-default">
                                解禁
                            </label>
                        </div>
                        <div class="radio radio-replace radio-inline">
                            <input type="radio"  name="controlType"  value="1">
                            <label class="tooltip-default" >
                                禁言
                            </label>
                        </div>
                    </div>
                    <label for="medalNum" class="col-sm-3 control-label">禁言时间（分钟）</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                        <input type="text" class="form-control input-sm"  name="time" id="time" value="0" >
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default cancel">取消</button>
                    <button type="button" class="btn btn-info confirm">确定</button>
                </div>
            </div>
        </div>
    </div>

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
                                    <input type="text" class="form-control" autocomplete="false" name="remark" required id="remark" value="" >
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
    <!--modal end-->
<?php
Pjax::end();
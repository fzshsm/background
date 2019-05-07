<?php
use yii\helpers\Url;
use app\assets\AppAsset;

$leagueId = Yii::$app->request->get('leagueId' , 0);

$this->params['breadcrumbs'] = [
    [
        'label' => '联赛管理',
        'url' => Url::to([
            '/league'
        ] )
    ]
];

if(!empty($leagueId) && !empty($leagueName)){
    $breadcrumbs = [
        [
            'label' => $leagueName,
            'url' => Url::to(['/league/member' , 'leagueId' => $leagueId])
        ],
        ['label' => '绝地求生成员管理']
    ];
    $this->params['breadcrumbs'] = array_merge($this->params['breadcrumbs'] , $breadcrumbs);
}else{
    $this->params['breadcrumbs'][] = ['label' => '绝地求生成员审核'];
}

AppAsset::registerCss($this, '@web/plugin/select2/select2-bootstrap.css');
AppAsset::registerCss($this, '@web/plugin/select2/select2.css');
AppAsset::registerCss($this, '@web/plugin/datatables/datatables.min.css');
AppAsset::registerCss($this, '@web/plugin/shCircleLoader/jquery.shCircleLoader.css');
AppAsset::registerCss($this, '@web/plugin/viewer/viewer.css');

AppAsset::registerJs($this, '@web/plugin/datatables/datatables.min.js');
AppAsset::registerJs($this, '@web/plugin/select2/select2.min.js');
AppAsset::registerJs($this, '@web/plugin/shCircleLoader/jquery.shCircleLoader-min.js');
AppAsset::registerJs($this, '@web/plugin/viewer/viewer.js');
AppAsset::registerJs($this, '@web/plugin/layer/layer.js');
?>
<style>table a.btn{margin-top: 4px}</style>
<script type="text/javascript" src="<?= Url::to('@web/js/league/pubgMemberFormat.js')?>"></script>
<script type="text/javascript" src="<?= Url::to('@web/js/datatable.custom.js')?>"></script>
<script type="text/javascript">
    jQuery( document ).ready( function( $ ) {
        jQuery('.loadding').shCircleLoader();
        var format = pubgMemberFormat;
        <?php if(empty($leagueId)){ ?>
        format.columns = [
            {"data" : 'rolerId'},
            {"data" : 'level'},
            {"data" : 'league'},
            {"data" : 'season'},
            {"data" : 'qq'},
            {"data" : 'mobile'},
            {"data" : 'screenshot'},
            {"data" : 'time'},
            {"data" : 'status'},
            {"data" : 'id'}
        ];
        <?php } ?>
        <?php
        $url =  \Yii::$app->request->getUrl();
        $search = \Yii::$app->request->get('search');
        $searchType = '';
        $searchValue = '';
        if(!empty($search)){
            $searchType = $search['type'];
            $searchValue = $search['value'];
        }
        ?>
        if('<?= $searchType?>' == 'role'){
            $('button.dropdown-toggle').attr('data-searchtype','role');
            $('button.dropdown-toggle').html('游戏角色　<span class="caret"></span>');
            format.searchValue = '<?= $searchValue?>';
        }
        format.toastrOpts = toastrOpts;
        format.setActionsUrl([
            '<?= Url::to(['/league/pubgmember/agree'])?>',
            '<?= Url::to(['/league/pubgmember/reject'])?>',
            '<?= Url::to(['/league/pubgmember/update'])?>',
            '<?= Url::to( ['/league/pubgmember/ban'])?>',
            '<?= Url::to( ['/league/pubgmember/unban'])?>',
            '<?= Url::to(['/user/medal'])?>',
            '<?= Url::to(['/user/chat'])?>',
            '<?= Url::to(['/user/chat'])?>'
        ]);
        format.ajaxUrl = '<?= Url::to([$url]);?>';
        format.ajaxData = {'leagueId' : '<?= !empty($leagueId) ? $leagueId : 0?>'};
        format.datatable = customDatatable(jQuery( '#member-data') , format);
    } );
</script>
<div class="col-sm-1 padding-left-8" style="margin-left: 1%">
    <div class="btn-group game-status">
        <?php
        $gameStatus = Yii::$app->request->get('status' , 1);
        $gameStatusList = [0 => ['game' => '王者荣耀','url' => Url::to(['/league/glorymember'])], 1 => ['game' => '绝地求生','url' => Url::to(['/league/pubgmember'])]];
        ?>
        <input type="hidden" id="status" name="status" value="<?=$gameStatus?>">
        <button id="game-status" type="button" class="btn btn-blue dropdown-toggle" data-toggle="dropdown" data-status="<?=$gameStatus?>">
            <?= $gameStatusList[$gameStatus]['game']?>　<span class="caret"></span>
        </button>
        <ul class="dropdown-menu dropdown-darkblue">
            <?php foreach($gameStatusList as $key => $value){ ?>
                <li><a href="<?= $value['url']?>" data-status="<?= $key?>"><?= $value['game']?></a></li>
            <?php } ?>
        </ul>
    </div>
</div>
<div id="member-search" class="hide">
    <?php if(!empty($leagueId)){ ?>
    <div class="col-sm-1 refresh" style="margin-right: 10%">
        <a href="javascript:void(0);" class="btn btn-default" title="刷新">
            <i class="fa fa-refresh"></i>
        </a>
    </div>
    <?php }?>
    <div class="input-group">
        <div class="input-group-btn">
            <button type="button" class="btn btn-success dropdown-toggle btn-width-100" data-searchtype="role"  data-toggle="dropdown">
                游戏角色　<span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-green">
                <li><a href="javascript:void(0);" data-searchtype="role" >游戏角色 </a></li>
                <?php if($gameType == 'glory'){?>
                <li><a href="javascript:void(0);" data-searchtype="qq">QQ</a></li>
                <?php }?>
                <li><a href="javascript:void(0);" data-searchtype="mobile">手机</a></li>
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
<div id="season-filter" class="hide">
    <div class="btn-group season-filter pull-left">
        <?php
            if(!empty($currentSeason) && !empty($seasonList)){
        ?>
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
</div>
<div class="col-md-12">
    <?php if(!empty($leagueId)){ ?>
    <ul class="nav nav-tabs bordered"><!-- available classes "bordered", "right-aligned" -->
        <li class="active">
            <a data-status="0" href="javascript:void(0);">
                <span class="visible-xs"><i class="entypo-home"></i></span>
                <span class="hidden-xs">全　部</span>
            </a>
        </li>
        <li>
            <a data-status="3" href="javascript:void(0);">
                <span class="visible-xs"><i class="entypo-home"></i></span>
                <span class="hidden-xs">参赛成员</span>
            </a>
        </li>
        <li>
            <a data-status="2" href="javascript:void(0);">
                <span class="visible-xs"><i class="entypo-user"></i></span>
                <span class="hidden-xs">等待审核</span>
            </a>
        </li>
    </ul>
    <?php } ?>
    <div class="tab-content">
        <?php
            if(!empty($leagueId)){
        ?>
        <table class="table table-bordered datatable no-footer hover stripe text-center no-border" id="member-data" cellspacing="0" >
            <thead>
            <tr>
                <td width="8%">游戏角色</td>
                <td width="8%">段位</td>
                <td width="5%">联赛</td>
                <td width="5%">赛季</td>
                <td width="4%">名次</td>
                <td width="4%">排名变化</td>
                <td width="3%">积分</td>
                <td width="3%">总场次</td>
                <td width="3%">胜场</td>
                <td width="3%">败场</td>
                <td width="3%">胜率</td>
                <td width="9%">加入时间</td>
                <td width="5%">QQ</td>
                <td width="6%">手机</td>
                <td width="5%">游戏截图</td>
                <td width="4%">参赛状态</td>
                <td width="4%">发言状态</td>
                <td width="15%">操作</td>
            </tr>
            </thead>
        </table>
        <?php }else{ ?>
            <table class="table table-bordered datatable no-footer hover stripe text-center " id="member-data" cellspacing="0" >
                <thead>
                <tr>
                    <td width="8%">游戏角色</td>
                    <td width="8%">段位</td>
                    <td width="5%">联赛</td>
                    <td width="5%">赛季</td>
                    <td width="7%">QQ</td>
                    <td width="7%">手机</td>
                    <td width="10%">游戏截图</td>
                    <td width="10%">加入时间</td>
                    <td width="4%">状态</td>
                    <td width="10%">操作</td>
                </tr>
                </thead>
            </table>
        <?php } ?>
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

<script>
    $("#subReject").click(function(){
        var rejectUserId = $("#rejectUserId").val();
        var remark = $("#remark").val();
        var csrfToken = $("#_csrf").val()

        if(remark == ''){
            layer.msg('拒绝理由不能为空',{time:1000});
            return false;
        }
        $.ajax({
            data:{
                remark:remark,
                _csrf : csrfToken
            },
            url:'<?= Url::to(['/league/member/reject?id='])?>'+rejectUserId,
            dataType:'json',
            type:"post",
            success:function (response){
                if(response.status == 'success'){
                    layer.msg('拒绝成功',{time:1000});
                    window.location.reload()
                }else{
                    layer.msg(response.message,{time:1000});
                }
            }
        })
        $("#reject").modal('hide');
    })
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
<?php
use yii\helpers\Url;
use app\assets\AppAsset;

$this->params['breadcrumbs'] = [
    [
        'label' => '联赛管理',
        'url' => Url::to([
            '/league'
        ] )
    ],
    [
        'label' => '绝地求生联赛列表'
    ]
];

AppAsset::registerCss($this, '@web/plugin/datatables/datatables.min.css');
AppAsset::registerCss($this, '@web/plugin/shCircleLoader/jquery.shCircleLoader.css');
AppAsset::registerCss($this, '@web/plugin/viewer/viewer.css');

AppAsset::registerJs($this, '@web/plugin/datatables/datatables.min.js');
AppAsset::registerJs($this, '@web/plugin/shCircleLoader/jquery.shCircleLoader-min.js');
AppAsset::registerJs($this, '@web/plugin/viewer/viewer.js');
?>
<style>table a.btn{margin-top: 4px}</style>
<script type="text/javascript" src="<?= Url::to('@web/js/league/pubgFormat.js')?>"></script>
<script type="text/javascript" src="<?= Url::to('@web/js/datatable.custom.js')?>"></script>
<script type="text/javascript">
    jQuery( document ).ready( function( $ ) {
        jQuery('.loadding').shCircleLoader();
        var format = pubgLeagueDatatableFormat;
        format.toastrOpts = toastrOpts;
        format.setActionsUrl([
            '<?= Url::to(['/league/pubg/update'])?>',
            '<?= Url::to(['/league/pubgseason'])?>',
            '<?= Url::to(['/league/pubgmember'])?>',
            '<?= Url::to(['/league/pubggame'])?>',
            '<?= Url::to(['/league/pubgnotice'])?>'
        ]);
        format.ajaxUrl = '<?= Url::to(['/league/pubg']);?>';
        format.datatable = customDatatable(jQuery( '#league-data') , format);

    } );
</script>
<div class="col-sm-1 padding-left-8">
    <div class="btn-group game-status">
        <?php
        $gameStatus = Yii::$app->request->get('status' , 1);
        $gameStatusList = [0 => ['game' => '王者荣耀','url' => Url::to(['/league'])], 1 => ['game' => '绝地求生','url' => Url::to(['/league/pubg'])]];
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

<a href="<?=Url::to(['/league/pubg/create'])?>" class="btn btn-success btn-square radius-4 pull-right">
    <i class="entypo-plus"></i>
    创建
</a>
<div id="league-search" class="hide">
    <div class="col-sm-1 refresh" style="margin-right: 5%">
        <a href="javascript:void(0);" class="btn btn-default" title="刷新">
            <i class="fa fa-refresh"></i>
        </a>
    </div>

    <div class="input-group league-search-type">
        <div class="input-group-btn search-type" style="padding-right: 10px">
            <button type="button" class="btn btn-info dropdown-toggle" style="width: 70px" data-searchtype="<?= $leagueSortList[0]['id']?>"  data-toggle="dropdown">
                <?= $leagueSortList[0]['name'] ?>　<span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-infoblue">
                <?php foreach($leagueSortList as $sort){ ?>
                    <li>
                        <a data-searchtype="<?= $sort['id'] ?>" href="javascript:void(0);"><?= $sort['name'] ?></a>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <div class="input-group-btn search-type">
            <button type="button" class="btn btn-success dropdown-toggle " style="width: 80px" data-searchtype="name"  data-toggle="dropdown">
                联赛名
            </button>
        </div>
        <input type="text" id="content" class="form-control" name="search" placeholder="">
        <div class="input-group-btn">
            <button  type="button" class="btn btn-success search">
                <i class="entypo-search"></i>
            </button>
        </div>
    </div>
</div>
<table class="table table-bordered datatable no-footer hover stripe text-center" id="league-data" cellspacing="0" >
    <thead>
    <tr>
        <td width="2%">ID</td>
        <td width="5%">名称</td>
        <td width="8%">封面</td>
        <td width="8%">分享logo</td>
        <td width="8%">分享联赛图</td>
        <td width="5%">联赛分类</td>
        <td width="5%">联赛模式</td>
        <td width="4%">奖金</td>
        <td width="4%">等级</td>
        <td width="5%">成员人数</td>
        <td width="8%">举办单位</td>
        <td width="20%">简介</td>
        <td width="6%">创建时间</td>
        <td width="4%">状态</td>
        <td width="20%">操作</td>
    </tr>
    </thead>
</table>

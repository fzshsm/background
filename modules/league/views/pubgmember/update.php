<?php
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use app\assets\AppAsset;
use yii\widgets\Pjax;
$this->params['breadcrumbs'] = [
    [
        'label' => '联赛管理',
        'url' => Url::to([
            '/league'
        ] )
    ]
];

if(!empty($data['gameLeagueId']) && !empty($leagueName)){
    $breadcrumbs = [
        [
            'label' => $leagueName,
            'url' => Url::to(['/league/member' , 'leagueId' => $data['gameLeagueId']])
        ],
        ['label' => '编辑绝地求生成员信息']
    ];
    $this->params['breadcrumbs'] = array_merge($this->params['breadcrumbs'] , $breadcrumbs);
}

AppAsset::registerCss($this, '@web/plugin/select2/select2.css');
AppAsset::registerJs($this, '@web/plugin/select2/select2.min.js');
if(!empty($data)){
Pjax::begin(["id" => 'member-info']);
    $form = ActiveForm::begin([
        'id' => 'member',
        'method' => 'post',
        'options' => [
            'data-pjax' => true,
            'role' => 'form',
            'class' => 'form-horizontal form-groups-bordered validate'
        ]
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
            <?php if(Yii::$app->session->hasFlash('success')){?>
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?=Yii::$app->session->getFlash('success')?>
                </div>
            <?php }?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-primary" data-collapsed="0">
                <div class="panel-heading">
                    <div class="panel-title">
                        成员信息
                    </div>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">联赛</label>
                        <div class="col-sm-5 ">
                            <p><?=$data['league'];?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">赛季</label>
                        <div class="col-sm-5 ">
                            <p><?=$data['season'];?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">游戏角色</label>
                        <div class="col-sm-5 ">
                            <p><?=$data['rolerId'];?><a target="_blank" href="<?=Url::to(['/user/update' , 'id' => $data['userId']])?>" class="btn-lg"><i class="fa fa-pencil-square-o"></i></a></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="score" class="col-sm-3 control-label">积分</label>
                        <div class="col-sm-5">
                            <div class="input-spinner">
                                <button type="button" class="btn btn-default btn-sm">-</button>
                                <input type="text" class="form-control size-1" data-min="0" name="score" value="<?=$data['score']?>">
                                <button type="button" class="btn btn-default btn-sm">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="winCount" class="col-sm-3 control-label">胜</label>
                        <div class="col-sm-5">
                            <div class="input-spinner">
                                <button type="button" class="btn btn-default btn-sm">-</button>
                                <input type="text" class="form-control size-1" data-min="0" name="winCount" value="<?=$data['winCount']?>">
                                <button type="button" class="btn btn-default btn-sm">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="loseCount" class="col-sm-3 control-label">败</label>
                        <div class="col-sm-5">
                            <div class="input-spinner">
                                <button type="button" class="btn btn-default btn-sm">-</button>
                                <input type="text" class="form-control size-1" data-min="0" name="loseCount" value="<?=$data['loseCount']?>">
                                <button type="button" class="btn btn-default btn-sm">+</button>
                            </div>
                        </div>
                    </div>
                    
                    <?=Html::hiddenInput("leagueId" , $data['leagueKeyId'])?>
                </div>
            </div>
        
        </div>
    </div>
    <div class="form-group default-padding form-button">
        <button type="submit" class="btn btn-success">保　存</button>
        <a href="<?= \Yii::$app->request->getReferrer() ?>" class="btn btn-default">返　回</a>
    </div>
    <script language="JavaScript">
        jQuery( document ).ready( function( $ ){
            $('form#user select').select2( {
                minimumResultsForSearch: -1
            });
            $(".input-spinner").each(function(i, el){
                var $this = $(el),
                    $minus = $this.find('button:first'),
                    $plus = $this.find('button:last'),
                    $input = $this.find('input'),

                    minus_step = attrDefault($minus, 'step', -1),
                    plus_step = attrDefault($minus, 'step', 1),

                    min = attrDefault($input, 'min', null),
                    max = attrDefault($input, 'max', null);


                $this.find('button').on('click', function(ev)
                {
                    ev.preventDefault();

                    var $this = $(this),
                        val = $input.val(),
                        step = attrDefault($this, 'step', $this[0] == $minus[0] ? -1 : 1);

                    if( ! step.toString().match(/^[0-9-\.]+$/))
                    {
                        step = $this[0] == $minus[0] ? -1 : 1;
                    }

                    if( ! val.toString().match(/^[0-9-\.]+$/))
                    {
                        val = 0;
                    }

                    $input.val( parseFloat(val) + step ).trigger('keyup');
                });

                $input.keyup(function()
                {
                    if(min != null && parseFloat($input.val()) < min)
                    {
                        $input.val(min);
                    }
                    else

                    if(max != null && parseFloat($input.val()) > max)
                    {
                        $input.val(max);
                    }
                });

            });
        });
    </script>
<?php
    ActiveForm::end();
Pjax::end();
}
?>
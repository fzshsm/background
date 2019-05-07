<?php
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use app\assets\AppAsset;
use yii\widgets\Pjax;
$uid = Yii::$app->request->get('id' , 0);
$this->params['breadcrumbs'] = [
        [
                'label' => '用户管理',
                'url' => Url::to([
                        '/user'
                ] )
        ],
        [
                'label' => '用户编辑'
        ]
];

AppAsset::registerCss($this, '@web/plugin/select2/select2.css');
AppAsset::registerJs($this, '@web/plugin/select2/select2.min.js');
if(!empty($data)){
Pjax::begin(["id" => 'user-info']);
    $form = ActiveForm::begin([
            'id' => 'user',
            'method' => 'post',
            'options' => [
                    'data-pjax' => true,
                    'role' => 'form',
                    'class' => 'form-horizontal form-groups-bordered validate' 
            ] 
    ] );
?>
    <style>
        input{vertical-align:middle;}
        .runjoingame{ width:33%; float:right}
        .joingame{ padding-left:25%}
        .form-horizontal .checkbox{ width:33%; float:left}
        /* .positionright{ padding-left:10%}*/
        .selectall span{ color:#666}
        .game2{ display:none}
    </style>
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
					用户信息
				</div>
			</div>
			<div class="panel-body">
                <div class="form-group">
                    <label for="personName" class="col-sm-2 control-label"></label>
                <ul class="nav nav-tabs bordered col-sm-6" style="margin-bottom: 10px">
                    <?php if($gameType == 1){?>
                        <?php $glory='active';$pubg= '';?>
                    <?php }else{?>
                        <?php $glory='';$pubg= 'active';?>
                    <?php }?>
                    <li class="<?= $glory?>">
                        <a data-status="1" href="<?= Url::to(['/user/update','id' => $uid,'gameType' => 1])?>">
                            <span>王者荣耀</span>
                        </a>
                    </li>
                    <li class="<?= $pubg?>">
                        <a data-status="2" href="<?= Url::to(['/user/update','id' => $uid,'gameType' => 2])?>">
                            <span>绝地求生</span>
                        </a>
                    </li>
                </ul>
                </div>
				<div class="form-group">
					<label for="personName" class="col-sm-3 control-label">真实姓名</label>
					<div class="col-sm-5 ">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-user"></i></span>
							<input type="text" class="form-control" id="realName" name="realName" value="<?= isset($data['realName']) ? $data['realName'] : '' ;?>">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="nickName" class="col-sm-3 control-label" data-validate="required">昵称</label>
					<div class="col-sm-5">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-info-circle"></i></span>
							<input type="text" class="form-control" id="nickName" name="nickName" value="<?= isset($data['nickName']) ? $data['nickName'] : '' ;?>">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="male" class="col-sm-3 control-label">性别</label>
					<div class="col-sm-5">
						<div class="radio radio-replace radio-inline">
							<input type="radio" id="male" name="gender" <?= $data['gender'] == 0 ? 'checked' : '' ?> value="0">
							<label class="tooltip-default" data-toggle="tooltip" data-placement="top" title="" data-original-title="男">
								<i class="fa fa-mars-stroke color-blue font-18"></i>
							</label>
						</div>
						<div class="radio radio-replace radio-inline">
							<input type="radio" id="female" name="gender" <?= $data['gender'] == 1 ? 'checked' : '' ?> value="1">
							<label class="tooltip-default" data-toggle="tooltip" data-placement="top" title="" data-original-title="女">
								<i class="fa fa-venus color-nacarat font-18"></i> 
							</label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="qq" class="col-sm-3 control-label">QQ</label>
					<div class="col-sm-5">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-qq"></i></span>
							<input type="text" class="form-control" name="qq" id="qq" value="<?= isset($data['qq']) ? $data['qq'] : '' ?>" >
						</div>	
					</div>
				</div>
				<div class="form-group">
					<label for="mobile" class="col-sm-3 control-label">手机</label>
					<div class="col-sm-5">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-phone"></i></span>
							<input type="text" class="form-control" name="mobile" id="mobile" value="<?= isset($data['mobile']) ? $data['mobile'] : '' ?>">
						</div>
					</div>
				</div>
				
				<div class="form-group" style="display: <?= $gameType == 1 ? 'block':'none' ?>">
					<label for="medalNum" class="col-sm-3 control-label">勋章</label>
					<div class="col-sm-5">
                        <div class="input-group">
                            <a class="font-14" href="<?=Url::to(['/user/medal' , 'id' => $uid ,'name' => isset($data['nickName']) ? $data['nickName'] : ''])?>">查看</a>
                        </div>
					</div>
				</div>
				<div class="form-group">
					<label for="clubName" class="col-sm-3 control-label">战队</label>
					<div class="col-sm-5">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-flag-checkered"></i></span>
							<input type="text" class="form-control" name="clubName" id="clubName" value="<?= isset($data['clubName']) ? $data['clubName'] : '' ?>" >
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="gameLeagueId" class="col-sm-3 control-label">所属联赛</label>
                    <div class="col-sm-5">
                        <?=Html::checkboxList('gameLeagueId',isset($data['leagueOwns']) ? $data['leagueOwns'] : [],$matchTypes,['class'=>'form-control col-sm-5' ,'style' => 'height:20em', 'itemOptions' =>['labelOptions' =>['style' =>'margin-left:2%','class' => 'checkbox-inline checkbox-replace']]]);?>
                    </div>
				</div>
<!--				<div class="form-group">-->
<!--					<label for="videoURL" class="col-sm-3 control-label">直播</label>-->
<!--					<div class="col-sm-5">-->
<!--						<div class="input-group">-->
<!--							<span class="input-group-addon"><i class="fa fa-video-camera"></i></span>-->
<!--							<input type="text" class="form-control" name="videoURL" id="videoURL" value="--><?//= isset($data['videoURL']) ? $data['videoURL'] : '' ?><!--" >-->
<!--						</div>-->
<!--					</div>-->
<!--				</div>-->
				
				<?=Html::hiddenInput("userId" , $data['id'])?>
			</div>
		</div>
	
	</div>
</div>
<div class="form-group default-padding form-button">
	<button type="submit" class="btn btn-success">保　存</button>
	<a href="<?=\Yii::$app->request->getReferrer()?>" class="btn btn-default">返　回</a>
</div>
<script language="JavaScript">
    jQuery( document ).ready( function( $ ){
        $('.positionright').on('click',function(){
            $('.game2').css('display','block');
            $('.game1').css('display','none');
            $('.positionright').css('color','#21a9e1');
            $(".positionleft").removeAttr("style");
        })
        $('.positionleft').on('click',function(){
            $('.game1').css('display','block');
            $('.game2').css('display','none');
            $(".positionright").removeAttr("style");
            $('.positionleft').css('color','#21a9e1');
        })
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
        replaceCheckboxes();


    });
</script>
<?php 
        ActiveForm::end();
Pjax::end();
}
?>

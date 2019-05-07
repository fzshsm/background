<?php
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use app\assets\AppAsset;
use yii\helpers\ArrayHelper;

$this->params['breadcrumbs'] = [
        [
                'label' => '角色管理',
                'url' => Url::to([
                        '/admin/role'
                ] )
        ],
        [
                'label' => '分配权限'
        ]
];
AppAsset::registerJs($this, '@web/plugin/fileinput.js');
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
<?php
if(!empty($data)){
    $form = ActiveForm::begin([ 
            'id' => 'role-permission',
            'method' => 'post',
            'options' => [ 
                    'role' => 'form',
                    'class' => 'form-horizontal form-groups-bordered validate',
                    'enctype' => 'multipart/form-data',
                     'data-pjax' => true
            ] 
    ] );
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
			<div class="panel-heading">
				<div class="panel-title">
                    为<span class="text-danger"><?= isset($data['role_name'])?$data['role_name']:'' ?></span>分配权限
				</div>
			</div>
			<div class="panel-body">
				<div class="form-group">
					<div class="col-sm-12">
                        <?php foreach($per_data as $key=> $value){?>
                            <?=Html::label($key.":",'test',['style'=>'color:#ff0000','class' => 'control-label']);?>
                            <?=Html::checkboxList('permission_ids',$permission_ids,$value,['class'=>'form-control checkbox',
                                'style' =>'height:5%','itemOptions' =>['labelOptions' =>['style' =>'margin-left:0.5%','class' => 'checkbox-inline']]]);?><br/>
                        <?php } ?>
					</div>
				</div>

				<?=Html::hiddenInput("id" , $data['role_id'])?>
			</div>
		</div>
	
	</div>
</div>
<div class="form-group default-padding form-button">
	<button type="submit" class="btn btn-success">保　存</button>
    <a href="<?= \Yii::$app->request->getReferrer()?>" class="btn btn-default">返　回</a>
</div>
<?php 
        ActiveForm::end();
    }
?>
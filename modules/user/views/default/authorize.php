<?php
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use app\assets\AppAsset;

$this->params['breadcrumbs'] = [
        [
                'label' => '用户管理',
                'url' => Url::to([
                        '/user'
                ] )
        ],
        [
                'label' => '认证用户'
        ]
];
AppAsset::registerCss($this, '@web/plugin/select2/select2.css');
AppAsset::registerJs($this, '@web/plugin/select2/select2.min.js');
AppAsset::registerJs($this, '@web/plugin/fileinput.js');
AppAsset::registerJs($this , '@web/js/common.js');
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
            'id' => 'user-authenticate',
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
	<div class="col-md-8">
		<div class="panel panel-primary" data-collapsed="0">
			<div class="panel-heading">
				<div class="panel-title">
					认证用户
				</div>
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label for="nickName" class="col-sm-3 control-label" data-validate="required">游戏昵称</label>
					<div class="col-sm-5">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-info-circle"></i></span>
							<input type="text" class="form-control" id="nickName" name="nickName" value="<?=$data['nickName'];?>">
						</div>
					</div>
				</div>
                <div class="form-group">
                    <label for="gameType" class="col-sm-3 control-label">所属游戏</label>
                    <div class="col-sm-5">
                        <?=Html::dropDownList('gameType' , isset($data['gameType']) ? $data['gameType'] : 0 , [ 1 => '王者荣耀' , 2 => '绝地求生'],['id' => 'gameType', 'onchange' => "changeTeam()"]);?>
                    </div>
                </div>
                <div class="form-group" id="display_userType">
                    <label for="status" class="col-sm-3 control-label">认证类型</label>
                    <div class="col-sm-5">
                        <?=Html::dropDownList('userType' , isset($data['userType']) ? $data['userType'] : 0 , [ 0 => '普通用户' , 1 => '客服' , 2 => '战队管理员'],['id' => 'userType']);?>
                    </div>
                </div>
                <div class="form-group" id="display_teamIdentity">
                    <label for="teamIdentity" class="col-sm-3 control-label">认证类型</label>
                    <div class="col-sm-5">
                        <?=Html::dropDownList('teamIdentity' , isset($data['teamIdentity']) ? $data['teamIdentity'] : 0 , [ 0 => '队员' , 1 => '队长'],['id' => 'teamIdentity']);?>
                    </div>
                </div>
				<div class="form-group" id="display_personName">
					<label for="medalNum" class="col-sm-3 control-label">认证名称</label>
					<div class="col-sm-5 ">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-user"></i></span>
							<input type="text" class="form-control" id="personName" name="personName" value="<?=$data['personName'];?>">
						</div>
					</div>
				</div>
				<div class="form-group" id="glory_display">
					<label for="clubId" class="col-sm-3 control-label">王者荣耀战队</label>
					<div class="col-sm-5">
						<div class="input-group" id="teamList">
							<span class="input-group-addon"><i class="fa fa-flag-checkered"></i></span>
                            <?= Html::dropDownList('clubId',isset($data['clubId'])?$data['clubId']:1,$clubData,['id' => 'clubId'])?>
						</div>
					</div>
				</div>
                <div class="form-group" id="pubg_display">
                    <label for="teamId" class="col-sm-3 control-label">绝地求生战队</label>
                    <div class="col-sm-5">
                        <div class="input-group" id="teamList">
                            <span class="input-group-addon"><i class="fa fa-flag-checkered"></i></span>
                            <?= Html::dropDownList('teamId',isset($data['teamId'])?$data['teamId']:1, $pubgTeam,['id' => 'teamId'])?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="headImage" class="col-sm-3 control-label" data-validate="required">头像</label>
                    <div class="col-sm-5">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;" data-trigger="fileinput">
                                <img id="head_image" src="<?= isset($data['headImg']) && !empty($data['headImg']) ? $data['headImg'] : Url::to('@web/images/noimg.png')?>" alt="...">
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px"></div>
                            <span class="btn btn-white btn-file" style="display: none">
                                    <input type="file" name="headImage" accept="image/*" onchange="checkUploadImage(this)">
                            </span>
                            <input type="hidden" id="image" name="image" value="<?= $data['headImg'] ? $data['headImg'] : ''?>">
                            <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
                        </div>
                    </div>
                </div>
				<?=Html::hiddenInput("userId" , $data['userId'])?>
                <input type="hidden" id="teamMemberId" name="teamMemberId" value="<?= isset($data['teamMemberId']) ? $data['teamMemberId'] :'' ?>">
			</div>
		</div>
	
	</div>
</div>
<script>
    jQuery( document ).ready( function( $ ) {
        $('#userType').select2({
            minimumResultsForSearch: -1
        });
        $('#clubId').select2();
        $('#teamId').select2();
        $("#gameType").select2({
            minimumResultsForSearch: -1
        })
        $('#teamIdentity').select2({
            minimumResultsForSearch: -1
        });

        var gameType = $("#gameType").val();

        if(gameType == 1){
            $("#pubg_display").hide()
            $("#display_teamIdentity").hide()
        }else{
            $("#glory_display").hide()
            $("#display_personName").hide()
            $("#display_userType").hide()
        }
    });

    function changeTeam(){
        var gameType = $("#gameType").val();

        if(gameType == 1){
            $("#glory_display").show()
            $("#pubg_display").hide()
            $("#display_personName").show()
            $("#display_teamIdentity").hide()
            $("#display_userType").show()
        }else{
            $("#glory_display").hide()
            $("#pubg_display").show()
            $("#display_personName").hide()
            $("#display_teamIdentity").show()
            $("#display_userType").hide()
        }
        getUserInfo();
    }

    function getUserInfo(){
        var gameType = $("#gameType").val();
        var userId = $("input[name='userId']").val()

        $.ajax({
            url:'<?= Url::to(['/user/detail'])?>',
            data:{user_id:userId, gameType:gameType},
            type:'get',
            dataType:'json',
            success:function(response){
                if(response.status == 'success'){
                    var data = response.data;
                    $("#nickName").val(data['nickName']);
                    $("#gameType").val(data['gameType']);
                    $("#submit_save").attr("disabled", false);
                    if(gameType == 2){
                        $("#teamId").val(data['teamId']).trigger("change");
                        $("#display_personName").hide()
                        $("#teamMemberId").val(data['teamMemberId'])
                        if(data['teamIdentity'] == 1){
                            $("#teamIdentity").val(data['teamIdentity']).trigger("change");
                        }
                    }else{
                        $("#clubId").val(data['clubId'])
                        $("#personName").val(data['personName'])
                        $("#display_personName").show()
                    }
                    if(data['headImg'] == null){
                        $("#head_image").attr('src','<?= Url::to('@web/images/noimg.png')?>')
                    }else{
                        $("#head_image").attr('src',data['headImg'])
                        $("#image").val(data['headImg']);
                    }
                }else{
                    if(response.status == '201'){
                        $("#submit_save").attr("disabled", true);
                    }
                    console.log(toastrOpts)
                    toastr.error(response.message , '' , {
                        "closeButton": false,
                        "debug": false,
                        "progressBar": true,
                        "positionClass": "toast-center",
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "2000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    });
                }
            }
        })
    }

</script>
<div class="form-group default-padding form-button">
	<button type="submit" class="btn btn-success" id="submit_save">保　存</button>
	<a href="<?= \Yii::$app->request->getReferrer()?>" class="btn btn-default">返　回</a>
</div>
<?php 
        ActiveForm::end();
    }
?>
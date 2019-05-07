<?php 
use app\assets\AppAsset;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

AppAsset::register($this);
$this->beginPage();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="icon" href="images/favicon.ico">
	<title><?= Yii::$app->name;?></title>
	<?php $this->head() ?>
	<script src="plugin/jquery-1.11.3.min.js"></script>
	<!--[if lt IE 9]><script src="plugin/ie8-responsive-file-warning.js"></script><![endif]-->
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="plugin/html5shiv.min.js"></script>
		<script src="plugin/respond.min.js"></script>
	<![endif]-->


</head>
<body class="page-body login-page login-form-fall">
<?php $this->beginBody() ?>
<!-- This is needed when you send requests via Ajax -->
<script type="text/javascript">
var loginUrl = '<?= urldecode(Url::to(['/login']));?>';
</script>
<div class="login-container">
	<div class="login-header login-caret">
		
		<div class="login-content">
			
			<a href="<?= Url::to(['/'])?>" class="logo">
				<img src="images/logo@2x.png" width="120" alt="" />
			</a>
			
			<p class="title"><?= Yii::$app->name;?></p>
			
			<!-- progress bar indicator -->
			<div class="login-progressbar-indicator">
				<h3>43%</h3>
				<span>logging in...</span>
			</div>
		</div>
		
	</div>
	
	<div class="login-progressbar">
		<div></div>
	</div>
	
	<div class="login-form">
		
		<div class="login-content">
			
			<div class="form-login-error">
				<p>Invalid login</p>
			</div>
			
			<?php
    			$form = ActiveForm::begin([
                    'id' => 'form_login',
    			    'method' => 'post',
                    'options' => ['role'=>'form'],
    			    'action' => "javascript:return false;"
                ]); 
			?>
				<div class="form-group">
					
					<div class="input-group">
						<div class="input-group-addon">
							<i class="entypo-user"></i>
						</div>
						
						<input type="text" class="form-control" name="username" id="username" placeholder="账  号" autocomplete="off" />
					</div>
					
				</div>
				
				<div class="form-group">
					
					<div class="input-group">
						<div class="input-group-addon">
							<i class="entypo-key"></i>
						</div>
						
						<input type="password" class="form-control" name="password" id="password" placeholder="密  码" autocomplete="off" />
					</div>
				
				</div>
				
				<div class="form-group">
					<button type="submit" class="btn btn-primary btn-block btn-login">
						<i class="entypo-login"></i>
						登　　录
					</button>
				</div>
			<?php ActiveForm::end();?>
			<div class="login-bottom-links">
				<a href="extra-forgot-password.html" class="link">忘记密码？</a>
			</div>
			
		</div>
		
	</div>
	
</div>
	<!-- Bottom scripts (common) -->
	<script src="plugin/gsap/TweenMax.min.js"></script>
	<script src="plugin/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
	<script src="plugin/bootstrap.js"></script>
	<script src="plugin/joinable.js"></script>
	<script src="plugin/resizeable.js"></script>
	<script src="plugin/neon-api.js"></script>
	<script src="plugin/jquery.validate.min.js"></script>
	<script src="js/login.js"></script>
	<!-- JavaScripts initializations and stuff -->
	<script src="plugin/neon-custom.js"></script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
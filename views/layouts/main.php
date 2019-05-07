<?php 
use yii\helpers\Html;
use app\assets\AppAsset;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use yii\web\View;
AppAsset::register($this);
$this->beginPage();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="description" content="<?= $this->title?>" />
	<?= Html::csrfMetaTags() ?>
	<link rel="icon" href="images/favicon.ico">

	<title><?= Yii::$app->name;?></title>
	<?php 
	   $this->head();
	?>
	<script src="<?=Url::to('@web/plugin/jquery-1.11.3.min.js')?>"></script>
	<!--[if lt IE 9]><script src="<?=Url::to('@web/plugin/ie8-responsive-file-warning.js')?>"></script><![endif]-->
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
    	<script src="<?=Url::to('@web/plugin/html5shiv.min.js')?>"></script>
    	<script src="<?=Url::to('@web/plugin/respond.min.js')?>"></script>
	<![endif]-->
</head>
<body class="page-body page-fade-only" data-url="<?= \Yii::$app->urlManager->createUrl('/');?>">
<?php $this->beginBody() ?>
<div class="loading-shade" style="display:none">
	<div class="loadding pull-left"></div>
	<span class="pull-left">数据正在加载中...</span>
</div>
<div class="page-container horizontal-menu with-sidebar fit-logo-with-sidebar">
	<?=$this->render('_navbar');?>
    <div class="main-content">
		<?php 
		$links = [
	        ['label' => '系统首页' ,  'url' => Yii::$app->urlManager->createUrl('/')],
		];
		if(isset($this->params['breadcrumbs']) && !empty($this->params['breadcrumbs'])){
		    $links = array_merge($links , $this->params['breadcrumbs']);
		}
		if(count($links) > 1 ){
		    echo Breadcrumbs::widget([
	            'tag' => 'ol',
	            'options' => [ 'class' => 'breadcrumb bc-3'],
	            'itemTemplate' => '<li>{link}</li>',
	            'activeItemTemplate' => '<li class="active"><strong>{link}</strong></li>',
	            'homeLink' => false,
	            'links' => $links,
		    ]);
		}
		 ?>
		<?= $content;?>
    </div>
</div>
<div class="modal fade" id="confirm-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default cancel" data-dismiss="modal">取消</button>
				<button type="button" class="btn btn-info confirm">确定</button>
			</div>
		</div>
	</div>
</div>

<!-- Bottom scripts (common) -->
<?php
AppAsset::registerJs($this, '@web/plugin/gsap/TweenMax.min.js');
AppAsset::registerJs($this, '@web/plugin/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js');
AppAsset::registerJs($this, '@web/plugin/bootstrap.js');
AppAsset::registerJs($this, '@web/plugin/joinable.js');
AppAsset::registerJs($this, '@web/plugin/resizeable.js');
AppAsset::registerJs($this, '@web/plugin/toastr.js');
AppAsset::registerJs($this, '@web/plugin/neon-api.js');
AppAsset::registerJs($this, '@web/plugin/neon-custom.js');
?>	
<script type="text/javascript">
var toastrOpts = {
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
};
function modalInit(){
    jQuery('.modal').on('show.bs.modal', function(){
        var $this = $(this);
        var $modal_dialog = $this.find('.modal-dialog');
        // 关键代码，如没将modal设置为 block，则$modala_dialog.height() 为零
        $(this).css('display', 'block');
        $modal_dialog.css({'margin-top': Math.max(0, ($(window).height() - $modal_dialog.height()) / 3) });
    });
    jQuery('.modal').on('hidden.bs.modal' , function(){
        $(this).find('.modal-body').html('');
    });
}
jQuery(function(){
    modalInit();
});
</script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
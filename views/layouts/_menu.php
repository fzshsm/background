<?php
use yii\helpers\Url;
?>
<li class="<?= Yii::$app->id == $this->context->module->id && $this->context->id == 'default' ? 'active' : '';?>">
	<a href="<?= Url::to(['/'] , false);?>"> <i class="fa fa-dashboard"></i>
		<span class="title">系统首页</span>
	</a>
</li>
<?php foreach($this->params['menus'] as $menuData) { ?>
<li class="has-sub <?= $this->context->module->id == $menuData['module'] ? 'active' : '';?>">
    <?php if(in_array($menuData['id'],\Yii::$app->params['betMenuIds'])){?>
        <a href="<?= \Yii::$app->params['betDomain'].$menuData['url']?>" target="_blank">
    <?php }elseif($menuData['id'] == 18){?>
            <a href="<?= \Yii::$app->params['betDomain'].'/news/newslist'?>" target="_blank">
<!--    --><?php //}elseif($menuData['id'] == 1){?>
<!--            <a href="--><?//= \Yii::$app->params['betDomain'].'/league/leaguelist'?><!--" target="_blank">-->
    <?php }elseif($menuData['id'] == 34){?>
            <a href="<?= \Yii::$app->params['betDomain'].'/mall/goods'?>" target="_blank">
    <?php }elseif($menuData['id'] == 25){?>
            <a href="<?= \Yii::$app->params['betDomain'].'/recruit/recruitlist'?>" target="_blank">
    <?php }elseif($menuData['id'] == \Yii::$app->params['financeMenuIds']){?>
            <a href="<?= \Yii::$app->params['betDomain'].'/finance/rechargelist'?>" target="_blank">
    <?php }elseif($menuData['id'] == 27){?>
            <a href="<?= \Yii::$app->params['betDomain'].'/club/clublist'?>" target="_blank">
    <?php }else{?>
        <a href="<?=Url::to([$menuData['url']])?>">
    <?php }?>

        <i class="<?=$menuData['class']?>"></i>
        <span class="title"><?=$menuData['name']?></span>
    </a>
    <ul class="visible">
        <?php
            foreach($menuData['childNode'] as $childNode){
         ?>
        <li class="<?= $this->context->module->id == $childNode['module'] && $this->context->id == $childNode['controller'] ? 'active' : '';?>">
            <?php if(in_array($menuData['id'],\Yii::$app->params['betMenuIds'])){?>
                <a href="<?= \Yii::$app->params['betDomain'].$childNode['url']?>" target="_blank">
            <?php }elseif($menuData['id'] == 18){?>
                <a href="<?= \Yii::$app->params['betDomain'].'/news/newslist'?>" target="_blank">
<!--            --><?php //}elseif($menuData['id'] == 1){?>
<!--                <a href="--><?//= \Yii::$app->params['betDomain'].'/league/leaguelist'?><!--" target="_blank">-->
            <?php }elseif($menuData['id'] == 34){?>
                <a href="<?= \Yii::$app->params['betDomain'].'/mall/goods'?>" target="_blank">
            <?php }elseif($menuData['id'] == 25){?>
                <a href="<?= \Yii::$app->params['betDomain'].'/recruit/recruitlist'?>" target="_blank">
            <?php }elseif($menuData['id'] == \Yii::$app->params['financeMenuIds']){?>
                <a href="<?= \Yii::$app->params['betDomain'].'/finance/rechargelist'?>" target="_blank">
            <?php }elseif($menuData['id'] == 27){?>
                <a href="<?= \Yii::$app->params['betDomain'].'/club/clublist'?>" target="_blank">
            <?php }else{?>
                <a href="<?=Url::to([$childNode['url']])?>">
            <?php }?>

            <span class="title"><?=$childNode['name']?></span></a>
        </li>
        <?php }?>
    </ul>
</li>
<?php } ?>

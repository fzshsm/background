<?php

use yii\helpers\VarDumper;

?>
<div class="page-error-404">
    <div class="error-symbol"><i class="entypo-attention"></i></div>
    <div class="error-text"><h2><?=$error->statusCode;?></h2>
        <p><?=$error->getMessage();?></p>
    </div>
    
    <?php
        if(YII_DEBUG){
    ?>
    <hr>
    <div class="error-text">
        <p class="text-left">file : <?= $error->getFile();?></p>
        <p class="text-left">line : <?= $error->getLine();?></p>
    </div>
    <div class="error-text">
        <?php foreach($error->getTrace() as $value){ ?>
            <p class="text-left"><?php VarDumper::dump( $error , 20 , true );?></p>
        <?php } ?>
    </div>
    <?php } ?>
</div>

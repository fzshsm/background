<?php

use yii\helpers\Url;

?>
<div class="sidebar-menu">
    <div class="sidebar-menu-inner">
        <header class="navbar navbar-fixed-top logo-env">
            <!-- set fixed position by adding class "navbar-fixed-top" -->


                <!-- logo -->
                <div class="navbar-brand">
                    <a href="<?= \Yii::$app->urlManager->createUrl('/'); ?>"> <img
                                src="<?= Url::to('@web/images/logo@2x.png') ?>" width="120" alt=""/></a>
                </div>

                <!-- main menu -->

                <div class="sidebar-mobile-menu visible-xs">
                    <a href="#" class="with-animation"><!-- add class "with-animation" to support animation -->
                        <i class="entypo-menu"></i>
                    </a>
                </div>

                <!-- notifications and other links -->
                <ul class="nav navbar-right pull-right">
                    <li class="profile-info dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="<?= Url::to('@web/images/member.jpg') ?>" alt="" class="img-circle"
                                 width="25"> <?= Yii::$app->user->getIdentity()->username; ?>
                        </a>
                        <ul class="dropdown-menu">

                            <!-- Reverse Caret -->
                            <li class="caret"></li>

                            <!-- Profile sub-links -->
                            <li>
                                <a href="<?= Url::to(['/login/logout']); ?>">
                                    <i class="entypo-user"></i>
                                    退出
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="sep"></li>
                    <li>
                        <a href="<?= Url::to(['/login/logout']); ?>"> 退出 <i class="entypo-logout right"></i></a>
                    </li>
                </ul>
        </header>



        <ul id="main-menu" class="main-menu">
            <!-- add class "multiple-expanded" to allow multiple submenus to open -->
            <!-- class "auto-inherit-active-class" will automatically add "active" class for parent elements who are marked already with class "active" -->
            <?= $this->render('_menu'); ?>
        </ul>
    </div>
</div>
<?php

use yii\helpers\Url;

$this->params['breadcrumbs'] = [
    [
        'label' => '菜单管理',
        'url' => Url::to([
            '/admin/menu'
        ] )
    ],
    [
        'label' => '创建菜单'
    ]
];
echo $this->render('_form',['menuList' => $menuList]);
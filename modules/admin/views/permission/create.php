<?php

use yii\helpers\Url;

$this->params['breadcrumbs'] = [
    [
        'label' => '权限管理',
        'url' => Url::to([
            '/admin/permission'
        ] )
    ],
    [
        'label' => '创建权限'
    ]
];
echo $this->render('_form',['menuList' => $menuList]);
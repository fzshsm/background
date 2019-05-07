<?php

use yii\helpers\Url;

$this->params['breadcrumbs'] = [
    [
        'label' => '角色管理',
        'url' => Url::to([
            '/admin/role'
        ] )
    ],
    [
        'label' => '创建角色'
    ]
];
echo $this->render('_form');
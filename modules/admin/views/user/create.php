<?php

use yii\helpers\Url;

$this->params['breadcrumbs'] = [
    [
        'label' => '用户管理',
        'url' => Url::to([
            '/admin/user'
        ] )
    ],
    [
        'label' => '创建用户'
    ]
];
echo $this->render('_form',['roleList' => $roleList]);
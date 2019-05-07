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
        'label' => '编辑角色'
    ]
];
echo $this->render('_form',['data' => $data]);
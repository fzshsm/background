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
        'label' => '编辑权限'
    ]
];
echo $this->render('_form',['data' => $data,'menuList' => $menuList]);
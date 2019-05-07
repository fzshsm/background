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
        'label' => '编辑菜单'
    ]
];
echo $this->render('_form',['data' => $data,'menuList' => $menuList]);
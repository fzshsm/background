<?php

use yii\helpers\Url;

$this->params['breadcrumbs'] = [
    [
        'label' => '绝地求生管理',
        'url' => Url::to([
            '/pubg'
        ] )
    ],
    [
        'label' => '积分规则',
        'url' => Url::to([
            "/pubg/rule"
        ] )
    ],
    [
        'label' => '创建规则'
    ]
];
echo $this->render('_form');
<?php

use yii\helpers\Url;

$this->params['breadcrumbs'] = [
    [
        'label' => '消息管理',
        'url' => Url::to([
            '/message'
        ] )
    ],
    [
        'label' => '创建消息'
    ]
];
echo $this->render('_form');
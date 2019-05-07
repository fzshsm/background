<?php

use yii\helpers\Url;

$this->params['breadcrumbs'] = [
    [
        'label' => '商城管理',
        'url' => Url::to([
            '/mall'
        ] )
    ],
    [
        'label' => '奖金列表',
        'url' => Url::to([
            '/mall/bonus'
        ] )
    ],
    [
        'label' => '更改奖金配置'
    ]
];
echo $this->render('_form',['data' => $data]);
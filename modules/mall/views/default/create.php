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
        'label' => '创建商品'
    ]
];
echo $this->render('_form',['roomCardList' => $roomCardList, 'roomCardDesc' => $roomCardDesc]);
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
        'label' => '更改商品'
    ]
];
echo $this->render('_form',['data' => $data,'roomCardList' => $roomCardList, 'roomCardDesc' => $roomCardDesc]);
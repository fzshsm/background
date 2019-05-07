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
        'label' => '房卡列表',
        'url' => Url::to([
            '/mall/roomcard'
        ] )
    ],
    [
        'label' => '更改商品'
    ]
];
echo $this->render('_form',['data' => $data, 'leagueList' => $leagueList]);
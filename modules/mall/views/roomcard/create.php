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
        'label' => '创建房卡'
    ]
];
echo $this->render('_form', ['leagueList' => $leagueList]);
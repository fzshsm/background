<?php

use yii\helpers\Url;

$this->params['breadcrumbs'] = [
    [
        'label' => '积分评级',
        'url' => Url::to([
            '/rating'
        ] )
    ],
    [
        'label' => '赛事规则',
        'url' => Url::to([
            "/rating/rule?id={$pid}&gameName={$gameName}"
        ] )
    ],
    [
        'label' => '创建规则'
    ]
];
echo $this->render('_form',['pid' => $pid]);
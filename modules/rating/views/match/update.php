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
        'label' => '赛事列表',
        'url' => Url::to(['/rating/match'])
    ],
    [
        'label' => '编辑赛事'
    ]
];
echo $this->render('_form',['data' => $data]);
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
        'url' => \Yii::$app->request->getReferrer()
    ],
    [
        'label' => '编辑规则'
    ]
];
echo $this->render('_form',['data' => $data]);
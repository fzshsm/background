<?php

use yii\helpers\Url;

$this->params['breadcrumbs'] = [
    [
        'label' => '战队管理',
        'url' => Url::to([
            '/club'
        ] )
    ],
    [
        'label' => '创建战队'
    ]
];
echo $this->render('_form',['clubType' => $clubType]);
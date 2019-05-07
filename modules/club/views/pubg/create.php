<?php

use yii\helpers\Url;

$this->params['breadcrumbs'] = [
    [
        'label' => '战队管理',
        'url' => Url::to([
            '/club/pubg'
        ] )
    ],
    [
        'label' => '创建绝地求生战队'
    ]
];
echo $this->render('_form');
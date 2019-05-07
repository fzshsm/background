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
        'label' => '编辑绝地求生战队'
    ]
];
echo $this->render('_form',['data' => $data]);
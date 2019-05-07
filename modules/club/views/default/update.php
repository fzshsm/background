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
        'label' => '编辑战队'
    ]
];
echo $this->render('_form',['data' => $data ,'clubType' => $clubType]);
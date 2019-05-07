<?php

use yii\helpers\Url;

$this->params['breadcrumbs'] = [
    [
        'label' => '联赛管理',
        'url' => Url::to([
            '/league'
        ] )
    ],
    [
        'label' => '编辑联赛'
    ]
];
echo $this->render('_reservation' , ['data' => $data]);
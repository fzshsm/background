<?php

use yii\helpers\Url;

$this->params['breadcrumbs'] = [
    [
        'label' => '招聘管理',
        'url' => Url::to([
            '/recruit'
        ] )
    ],
    [
        'label' => '编辑招聘'
    ]
];
echo $this->render('_form',['data' => $data,'medals' => $medals,'medalsIds' => $medalsIds,'matchTypes' => $matchTypes,'medalsIdsJs' => $medalsIdsJs]);
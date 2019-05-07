<?php

use yii\helpers\Url;

$this->params['breadcrumbs'] = [
    [
        'label' => '新闻管理',
        'url' => Url::to([
            '/news'
        ] )
    ],
    [
        'label' => '创建新闻'
    ]
];
echo $this->render('_form');
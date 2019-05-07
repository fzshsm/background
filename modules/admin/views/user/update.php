<?php

use yii\helpers\Url;

$this->params['breadcrumbs'] = [
    [
        'label' => '用户管理',
        'url' => Url::to([
            '/moba/team'
        ] )
    ],
    [
        'label' => '编辑用户'
    ]
];
echo $this->render('_form',['data' => $data,'type' => $type,'roleList' => $roleList]);
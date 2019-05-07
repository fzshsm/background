<?php

use yii\helpers\Url;

$this->params['breadcrumbs'] = [
    [
        'label' => '绝地求生管理',
        'url' => Url::to([
            '/pubg'
        ] )
    ],
    [
        'label' => '自定义房间配置',
        'url' => Url::to(['/pubg' ])
    ],
    [
        'label' => '创建配置'
    ]
];
if($mode == 3){
    echo $this->render('_war' , ['data' => $data, 'regionList' => $regionList]);
}else{
    echo $this->render('_form' , ['data' => $data, 'regionList' => $regionList]);
}

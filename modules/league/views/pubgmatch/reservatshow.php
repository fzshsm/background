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
if($leagueModel == 2){
    echo $this->render('_reservatshowone' , ['res'=>$res, 'isRecord' => $isRecord]);
}else{
    echo $this->render('_reservatshow' , ['res'=>$res, 'isRecord' => $isRecord]);
}

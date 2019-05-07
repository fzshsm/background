<?php

use yii\helpers\Url;

$this->params['breadcrumbs'] = [
    [
        'label' => '绝地求生联赛管理',
        'url' => Url::to([
            '/league/pubg'
        ] )
    ],
    [
        'label' => $seasonName,
        'url' => Url::to(['/league/pubgmatch' , 'seasonId' => $seasonId])
    ],
    [
        'label' => '创建场次'
    ]
];
echo $this->render('_form' , ['seasonId' => $seasonId,'mapList' => $mapList, 'seasonName' => $seasonName,
    'obList' => $obList,'obIds' => '', 'configList' => $configList, 'leagueId' => $leagueId, 'leagueDetail' => $leagueDetail]);
<?php

use yii\helpers\Url;

$this->params['breadcrumbs'] = [
    [
        'label' => '联赛管理',
        'url' => Url::to([
            '/league/pubg'
        ] )
    ],
    [
        'label' => $seasonName.'场次管理',
        'url' => Url::to(['/league/pubgmatch' , 'seasonId' => $seasonId])
    ],
];
echo $this->render('_form' , ['data' => $data , 'seasonId' => $seasonId , 'mapList' => $mapList, 'seasonName' => $seasonName,
    'obList' => $obList,'obIds' => $obIds, 'configList' => $configList, 'leagueId' => $leagueId, 'leagueDetail' => $leagueDetail]);
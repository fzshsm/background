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
        'label' => $matchName,
        'url' => Url::to(['/league/gloryseason' , 'leagueId' => $leagueId])
    ],
    [
        'label' => '创建赛季'
    ]
];
echo $this->render('_form' , ['leagueId' => $leagueId, 'bonusConfigList' => $bonusConfigList]);
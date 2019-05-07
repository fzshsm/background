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
        'label' => $matchName,
        'url' => Url::to(['/league/pubgseason' , 'leagueId' => $leagueId])
    ],
    [
        'label' => '创建场次'
    ]
];
echo $this->render('_form' , ['leagueId' => $leagueId, 'ruleConfigList' => $ruleConfigList]);
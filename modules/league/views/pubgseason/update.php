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
        'url' => Url::to(['/league/pubgseason' , 'leagueId' => isset($data['leagueId']) ? $data['leagueId'] : 0])
    ],
];
echo $this->render('_form' , ['data' => $data , 'leagueId' => $leagueId , 'ruleConfigList' => $ruleConfigList]);
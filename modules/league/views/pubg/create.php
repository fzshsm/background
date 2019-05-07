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
        'label' => '添加绝地求生联赛'
    ]
];
echo $this->render('_form' ,['leagueSorts' => $leagueSorts, 'teamAllowCount' => $teamAllowCount, 'leagueTypes' => $leagueTypes ]);
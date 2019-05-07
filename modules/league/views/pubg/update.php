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
        'label' => '编辑联赛'
    ]
];
echo $this->render('_form' , ['data' => $data,'leagueSorts' => $leagueSorts, 'teamAllowCount' => $teamAllowCount, 'leagueTypes' => $leagueTypes ]);
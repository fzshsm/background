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
        'url' => Url::to(['/league/gloryseason' , 'leagueId' => isset($data['parentId']) ? $data['parentId'] : 0])
    ]
];
echo $this->render('_form' , ['data' => $data , 'leagueId' => isset($data['parentId']) ? $data['parentId'] : $leagueId, 'bonusConfigList' => $bonusConfigList]);
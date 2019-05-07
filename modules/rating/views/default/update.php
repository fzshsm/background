<?php
/**
 * Created by IntelliJ IDEA.
 * User: Zhangxinlong
 * Date: 2017/11/3
 * Time: 10:34
 */
use yii\helpers\Url;

$this->params['breadcrumbs'] = [
    [
        'label' => '积分评级',
        'url' => Url::to([
            '/rating'
        ] )
    ],
    [
        'label' => '编辑积分'
    ]
];
echo $this->render('_form'  , ['data' => $data , 'authlist' => $authlist ,'gameType' => $gameType,'gameRule' => $gameRule,'scoreList' => $scoreList ]);
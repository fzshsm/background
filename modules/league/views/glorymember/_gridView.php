<?php
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

if (empty($leagueId)) {
//    if (in_array(\Yii::$app->request->getPathInfo(),['league/glorymember','league/glorymember/index'])) {
    if ($gameType == 'glory') {
        echo GridView::widget([
            'id' => 'complaint',
            'dataProvider' => $dataProvider,
            'emptyText' => "暂无成员信息！",
            'emptyCell' => '',
            'emptyTextOptions' => ['class' => 'text-center'],
            'tableOptions' => ['class' => 'table table-bordered datatable no-footer hover stripe text-center dataTable'],
            'options' => ['class' => 'dataTables_wrapper no-footer no-border'],
            'layout' => "{errors}{items}{pager}",
            "columns" => [
                'nickname:text: 游戏昵称',
                'level:text:段位',
                'star:text:星级',
                'league:text:联赛',
                'qq:text:QQ',
                'mobile:text:手机',
                [
                    'attribute' => 'screenshot',
                    'label' => '游戏截图',
                    'format' => 'html',
                    'value' => function ($model) {
                        $value = '';
                        if (!empty($model['screenshot'])) {
                            $value = Html::img($model['screenshot'],
                                [
                                    'width' => 50,
                                    'height' => 50,
                                ]
                            );
                        }
                        return $value;
                    }
                ],
                'time:text:加入时间',
                [
                    'class' => ActionColumn::className(),
                    'template' => ' {agree}{reject}',
                    'header' => '操作',
                    'contentOptions' => ['class' => 'actions'],
                    'buttons' => [
                        'agree' => function ($url,$model)  {
                            $icon = Html::tag('i', '', ['class' => 'fa fa-check']);
                            if($model['status'] == 2 || $model['status'] == 4){
                                return Html::a($icon . '同意', $url,
                                    ['class' => 'btn btn-success btn-sm btn-icon icon-left','data-league' => $model['league'], 'data-user' => $model['nickname']]);
                            }
                        },
                        'reject' => function($url,$model) {
                            $icon = Html::tag('i','',['class' => 'fa fa-times']);
                            if($model['status'] < 3){
                                return Html::a($icon . '拒绝', 'javascript:',
                                    ['class' => 'btn btn-danger btn-sm btn-icon icon-left reject','data-rejectId' => $model['leagueSignId'], 'data-user' => $model['nickname']]);
                            }
                        }
                    ],
                ],
            ],
            'pager' => [
                'options' => ['class' => 'pagination dataTables_paginate paging_simple_numbers'],
                'linkOptions' => ['class' => 'paginate_button'],
            ],
        ]);
    }else{
        echo GridView::widget([
            'id' => 'complaint',
            'dataProvider' => $dataProvider,
            'emptyText' => "暂无成员信息！",
            'emptyCell' => '',
            'emptyTextOptions' => ['class' => 'text-center'],
            'tableOptions' => ['class' => 'table table-bordered datatable no-footer hover stripe text-center dataTable'],
            'options' => ['class' => 'dataTables_wrapper no-footer no-border'],
            'layout' => "{errors}{items}{pager}",
            "columns" => [
                'steamId:text:steamID',
                'nickname:text: 游戏昵称',
                'leagueName:text:联赛',
                'teamName:text:所属战队',
                'createTime:text:申请时间',
                'qq:text:QQ',
                'mobile:text:手机',
                [
                    'class' => ActionColumn::className(),
                    'template' => ' {agree}{reject}',
                    'header' => '操作',
                    'contentOptions' => ['class' => 'actions'],
                    'buttons' => [
                        'agree' => function ($url,$model)  {
                            $icon = Html::tag('i', '', ['class' => 'fa fa-check']);
                            if($model['status'] != 1){
                                return Html::a($icon . '同意', $url,
                                    ['class' => 'btn btn-success btn-sm btn-icon icon-left','data-league' => $model['leagueName'], 'data-user' => $model['nickname']]);
                            }
                        },
                        'reject' => function($url,$model) {
                            $icon = Html::tag('i','',['class' => 'fa fa-times']);
                            if($model['status'] != 0){
                                return Html::a($icon . '拒绝', $url,
                                    ['class' => 'btn btn-danger btn-sm btn-icon icon-left pubg-reject','data-league' => $model['leagueName'], 'data-user' => $model['nickname']]);
                            }
                        }
                    ],
                ],
            ],
            'pager' => [
                'options' => ['class' => 'pagination dataTables_paginate paging_simple_numbers'],
                'linkOptions' => ['class' => 'paginate_button'],
            ],
        ]);
    }
}else{
    if ($gameType == 'glory') {
        echo GridView::widget([
            'id' => 'complaint',
            'dataProvider' => $dataProvider,
            'emptyText' => "暂无成员信息！",
            'emptyCell' => '',
            'emptyTextOptions' => ['class' => 'text-center'],
            'tableOptions' => ['class' => 'table table-bordered datatable no-footer hover stripe text-center'],
            'options' => ['class' => 'dataTables_wrapper no-footer no-border'],
            'layout' => "{errors}{items}{pager}",
            "columns" => [
                'rolerId:text: 游戏昵称',
                [
                    'label' => '段位',
                    'attribute' => 'level',
                    'value' => 'level',
                    'headerOptions' => ['width' => '1%'],
                ],
                'league:text:联赛',
                [
                    'label' => '赛季',
                    'attribute' => 'season',
                    'value' => 'season',
                    'headerOptions' => ['width' => '1%'],
                ],
                [
                    'label' => '名次',
                    'attribute' => 'nowRank',
                    'value' => 'nowRank',
                    'headerOptions' => ['width' => '1%'],
                ],
                [
                    'attribute' => 'lastRank',
                    'label' => '排名变化',
                    'format' => 'html',
                    'headerOptions' => ['width' => '1%'],
                    'value' => function ($model) {
                        $iconClass = 'fa-minus color-gray';
                        $text = '';
                        if ($model['lastRank'] > 0) {
                            $rankDiff = $model['nowRank'] - $model['lastRank'];
                            if ($rankDiff < 0) {
                                $iconClass = 'fa-long-arrow-up color-red';
                            }
                            if ($rankDiff > 0) {
                                $iconClass = 'fa-long-arrow-down color-green';
                            }
                            if ($rankDiff != 0) {
                                $text = abs($rankDiff);
                            }
                        }
                        $rankChange = Html::tag('i', $text, ['class' => "fa $iconClass"]);
                        return $rankChange;
                    }
                ],
                'score:text:积分',
                'totalCount:text:总场次',
                'winCount:text:胜场',
                'loseCount:text:败场',
                'winRatio:text:胜率',
                'time:text:加入时间',
                'qq:text:QQ',
                'mobile:text:手机',
                [
                    'attribute' => 'screenshot',
                    'label' => '游戏截图',
                    'format' => 'html',
                    'value' => function ($model) {
                        $value = '';
                        if (!empty($model['screenshot'])) {
                            $value = Html::img($model['screenshot'],
                                [
                                    'width' => 50,
                                    'height' => 50,
                                ]
                            );
                        }
                        return $value;
                    }
                ],
                [
                    'attribute' => 'status',
                    'label' => '参赛状态',
                    'format' => 'html',
                    'value' => function ($model) {
                        $el = '';
                        $data = intval($model['status']);
                        switch ($data) {
                            case 2 :
                                $el = Html::tag('i', '', ['class' => 'fa fa-minus-circle color-orange font-18', 'title' => '等待审核']);
                                break;
                            case 3 :
                                $el = Html::tag('i', '', ['class' => 'fa fa-check-circle color-green font-18', 'title' => '正式成员']);
                                break;
                            case 4 :
                                $el = Html::tag('i', '', ['class' => 'fa fa-times-circle color-red font-18', 'title' => '拒绝加入']);
                                break;
                            case 5 :
                                $el = Html::tag('i', '', ['class' => 'fa fa-ban color-black font-18', 'title' => '禁赛中']);
                                break;
                        }
                        return $el;
                    }
                ],
                [
                    'attribute' => 'forbidEndTime',
                    'label' => '发言状态',
                    'format' => 'html',
                    'value' => function ($model) {
                        if($model['forbidEndTime'] == null){
                            $el = Html::tag('i','',['class' => 'fa fa-check-circle color-green font-18','title' => '正常']);
                        }else{
                            $el = Html::tag('span',$model['forbidEndTime'],['title' => '禁言中']);
                        }
                        return $el;
                    }
                ],
                [
                    'class' => ActionColumn::className(),
                    'template' => '{update}{ban}{unban}{medal}{chat}{authorize}',
                    'header' => '操作',
                    'contentOptions' => ['class' => 'actions'],
                    'buttons' => [
                        'update' => function ($url, $model) {
                            if ($model['status'] == 3 || $model['status'] == 5) {
                                $icon = Html::tag('i', '', ['class' => 'fa fa-pencil']);
                                $url = Url::to(['/league/glorymember/update', 'id' => $model['leagueKeyId'],'seasonId' => $model['seasonId'],'userId' => $model['userId'],'leagueId' => $model['leagueId']]);
                                return Html::a($icon . '编辑', $url, ['class' => 'btn btn-default btn-sm btn-icon icon-left']);
                            }
                        },
                        'ban' => function ($url, $model) {
                            if ($model['status'] > 2) {
                                $icon = Html::tag('i', '', ['class' => 'fa fa-ban']);
                                return Html::a($icon . '禁赛', $url,
                                    ['class' => 'btn btn-primary btn-sm btn-icon icon-left ban', 'data-rejectId' => $model['id'], 'data-user' => $model['rolerId'],'data-league' => $model['league']]);
                            }
                        },
                        'unban' => function ($url, $model) {
                            if ($model['status']  > 2) {
                                $icon = Html::tag('i', '', ['class' => 'fa fa-check']);
                                return Html::a($icon . '解禁', $url.'&userId='.$model['userId'].'&leagueId='.$model['leagueId'],
                                    ['class' => 'btn btn-success btn-sm btn-icon icon-left unban', 'data-rejectId' => $model['id'], 'data-user' => $model['rolerId'],'data-league' => $model['league']]);
                            }
                        },
                        'medal' => function ($url, $model) {
                            if ($model['status'] == 3 || $model['status'] == 5) {
                                $url = Url::to(['/user/medal','id' => $model['userId'],'name' => $model['rolerId']]);
                                $icon = Html::tag('i', '', ['class' => 'fa fa-life-ring']);
                                return Html::a($icon . '勋章', $url, ['class' => 'btn btn-gold btn-sm btn-icon icon-left']);
                            }
                        },
                        'chat' => function ($url, $model) {
                            if ($model['status'] > 2) {
                                $icon = Html::tag('i', '', ['class' => 'fa fa-volume-off']);
                                return Html::a($icon . '禁言', 'javascript:', ['class' => 'btn btn-danger btn-sm btn-icon icon-left chat','data-user' => $model['userId'],'data-forbidEndTime' => $model['forbidEndTime']]);
                            }
                        },
                        'authorize' => function($url, $model) {
                            if ($model['status'] == 3 || $model['status'] == 5){
                                $url = Url::to(['/user/authorize', 'id' => $model['userId']]);
                                $icon = Html::tag('i', '',['class' => 'fa fa-check']);
                                return Html::a($icon.'认证', $url, ['class' => 'btn btn-orange btn-sm btn-icon icon-left']);
                            }
                        }
                    ],
                ],
            ],
            'pager' => [
                'options' => ['class' => 'pagination dataTables_paginate paging_simple_numbers'],
                'linkOptions' => ['class' => 'paginate_button'],
            ],
        ]);
    }else{
        echo GridView::widget([
            'id' => 'complaint',
            'dataProvider' => $dataProvider,
            'emptyText' => "暂无成员信息！",
            'emptyCell' => '',
            'emptyTextOptions' => ['class' => 'text-center'],
            'tableOptions' => ['class' => 'table table-bordered datatable no-footer hover stripe text-center'],
            'options' => ['class' => 'dataTables_wrapper no-footer no-border'],
            'layout' => "{errors}{items}{pager}",
            "columns" => [
                'steamId:text: steamID',
                'nickname:text: 游戏昵称',
                'leagueName:text:联赛',
                'teamName:text:所属战队',
                'qq:text:QQ',
                'mobile:text:手机',
                [
                    'attribute' => 'status',
                    'label' => '参赛状态',
                    'format' => 'html',
                    'value' => function ($model) {
                        $el = '';
                        $data = intval($model['status']);
                        switch ($data) {
                            case 2 :
                                $el = Html::tag('i', '', ['class' => 'fa fa-minus-circle color-orange font-18', 'title' => '等待审核']);
                                break;
                            case 3 :
                                $el = Html::tag('i', '', ['class' => 'fa fa-check-circle color-green font-18', 'title' => '正式成员']);
                                break;
                            case 4 :
                                $el = Html::tag('i', '', ['class' => 'fa fa-times-circle color-red font-18', 'title' => '拒绝加入']);
                                break;
                            case 5 :
                                $el = Html::tag('i', '', ['class' => 'fa fa-ban color-black font-18', 'title' => '禁赛中']);
                                break;
                        }
                        return $el;
                    }
                ],
//                [
//                    'class' => ActionColumn::className(),
//                    'template' => '{ban}{unban}',
//                    'header' => '操作',
//                    'contentOptions' => ['class' => 'actions'],
//                    'buttons' => [
//                        'update' => function ($url,$model)  {
//                            if($model['status'] == 3){
//                                $icon = Html::tag('i', '', ['class' => 'fa fa-pencil']);
//                                $url = Url::to(['/league/pubgmember/update','id' => $model['leagueId']]);
//                                return Html::a($icon . '编辑', $url, ['class' => 'btn btn-default btn-sm btn-icon icon-left']);
//                            }
//                        },
//                        'ban' => function($url,$model) {
//                            if($model['status'] == 3){
//                                $icon = Html::tag('i','',['class' => 'fa fa-ban']);
//                                return Html::a($icon . '禁赛', $url,
//                                    ['class' => 'btn btn-primary btn-sm btn-icon icon-left ban','data-rejectId' => $model['leagueSignId'], 'data-user' => $model['nickname'],'data-league' => $model['leagueName']]);
//                            }
//                        },
//                        'unban' => function($url,$model) {
//                            if($model['status'] == 5){
//                                $icon = Html::tag('i','',['class' => 'fa fa-check']);
//                                return Html::a($icon . '解禁', $url,
//                                    ['class' => 'btn btn-default btn-sm btn-icon icon-left unban','data-rejectId' => $model['leagueSignId'], 'data-user' => $model['nickname'],'data-league' => $model['leagueName']]);
//                            }
//                        },
//                    ],
//                ],
            ],
            'pager' => [
                'options' => ['class' => 'pagination dataTables_paginate paging_simple_numbers'],
                'linkOptions' => ['class' => 'paginate_button'],
            ],
        ]);
    }
}
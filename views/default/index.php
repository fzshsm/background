<?php

use yii\helpers\Url;

$this->params['breadcrumbs'] = [
    [
        'label' => '概览',
    ]
];
?>
<div class="col-sm-3">
    <div class="tile-stats tile-aqua">
        <a href="<?= Url::to(['/league/glorymember'])?>">
            <div class="icon"><i class="fa fa-users"></i></div>
            <div class="num" data-format="1000" data-start="0" data-end="<?= isset($data['matchCount']) ? $data['matchCount'] : 0 ?>" data-prefix="" data-postfix="" data-duration="1000"
                 data-delay="0"><?= isset($data['matchCount']) ? $data['matchCount'] : 0 ?>
            </div>
            <h3>申请加入联赛</h3>
            <p>&nbsp;</p>
        </a>
    </div>
</div>
<div class="col-sm-3">
    <div class="tile-stats tile-orange">
        <a href="<?= Url::to(['/user/authenticate'])?>">
            <div class="icon"><i class="fa fa-check-square-o"></i></div>
            <div class="num" data-format="1000" data-start="0" data-end="<?= isset($data['certificationCount']) ? $data['certificationCount'] : 0 ?>" data-prefix="" data-postfix="" data-duration="1000"
                 data-delay="0"><?= isset($data['certificationCount']) ? $data['certificationCount'] : 0 ?>
            </div>
            <h3>等待认证</h3>
            <p>&nbsp;</p>
        </a>
    </div>
</div>
<div class="col-sm-3">
    <div class="tile-stats tile-red">
        <a href="<?= Url::to(['/league/glorycomplaint'])?>">
            <div class="icon"><i class="fa fa-warning"></i></div>
            <div class="num" data-format="1000" data-start="0" data-end="<?= isset($data['todayComplaintsCount']) ? $data['todayComplaintsCount'] : 0 ?>" data-prefix="" data-postfix="" data-duration="1000"
                 data-delay="0"><?= isset($data['todayComplaintsCount']) ? $data['todayComplaintsCount'] : 0 ?>
            </div>
            <h3>新增投诉</h3>
            <p>&nbsp;</p>
        </a>
    </div>
</div>
<div class="col-sm-3">
    <div class="tile-stats tile-green">
        <a href="<?= Url::to(['/user'])?>">
            <div class="icon"><i class="fa fa-user"></i></div>
            <div class="num" data-format="1000" data-start="0" data-end="<?= isset($data['todayAddUserCount']) ? $data['todayAddUserCount'] : 0 ?>" data-prefix="" data-postfix="" data-duration="1000"
                 data-delay="0"><?= isset($data['todayAddUserCount']) ? $data['todayAddUserCount'] : 0 ?>
            </div>
            <h3>新用户</h3>
            <p>&nbsp;</p>
        </a>
    </div>
</div>
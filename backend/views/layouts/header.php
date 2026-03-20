<?php

use yii\helpers\Html;
use yii\helpers\Url;

$currentBd = Yii::$app->request->cookies->get('bd') ? Yii::$app->request->cookies->get('bd')->value : '';
if (!in_array((string)$currentBd, ['', '4'], true)) {
    $currentBd = '';
}

?>
<header class="header clearfix">
    <ul class="top-nav fright">
        <li><a href="<?= Url::to('/admin/site/reset-password') ?>"><span><i class="flaticon-profile"></i></span></a></li>
        <li>
            <a href="#" onclick="document.getElementById('logout').submit();">
                <span><i class="flaticon-power-button"></i></span>
            </a>
            <?= Html::beginForm(['/site/logout'], 'post', ['id' => 'logout']) . Html::endForm() ?>
        </li>
    </ul>
    <div class="fright top-buttons">
        <a href="/" target="_blank" class="btn btn--border btn--white">Просмотр сайта</a>
    </div>
    <div class="fleft top-buttons" style="padding-left: 30px;">
        <?= Html::beginForm(['/site/change-club']) ?>
        <?= Html::dropDownList('bd', $currentBd, [
            '' => 'EXTRASPORT ТК «Питер», с бассейном',
            4 => 'EXTRASPORT Матроса Железняка'
        ], ['onchange' => 'this.form.submit()']) ?>
        <?= Html::endForm() ?>
        <br>
    </div>
</header>
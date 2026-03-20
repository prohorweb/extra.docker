<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;

/* @var $model \common\models\History */

?>
<div class="team-blog__item col-4 col-sm-6 col-xs-12">
    <div class="team-item team-item--doposle">
        <div class="team-item__img"><a href="<?= Url::to(['/es/history/' . $model->alias]) ?>"><img src="<?= $model->img ? '/uploads/image/article/' . $model->img : '//placehold.it/451x320' ?>" alt="" /></a></div>
        <div class="team-item__title"><a href="<?= Url::to(['/es/history/' . $model->alias]) ?>"><?= $model->title ?></a></div>
        <div class="team-item__text"><a href="<?= Url::to(['/es/history/' . $model->alias]) ?>"><?= $model->intro ?></a></div>
    </div>
</div>

<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;

/* @var $model \common\models\News2 */

//Yii::$app->formatter->locale = 'ru-RU';
?>
<div class="news-blog__item">
    <div class="news-block">
        <div class="news-block__date">
            <strong><?= (new DateTime($model['date']))->format('j') ?></strong> <?= IntlDateFormatter::formatObject(new DateTime($model['date']), 'MMMM', 'ru-RU') ?><br> <span><?= (new DateTime($model['date']))->format('Y') ?> года</span>
        </div>
        <div class="news-block__wrap">
            <div class="news-block__title title-h3"><?= Html::encode($model->title) ?></div>
            <div class="news-block__text"><?= Html::encode($model->intro) ?></div>
            <div class="news-block__button"><a href="<?= Url::to(['/news/' . $model->alias]) ?>" class="btn btn--product"><span>Подробнее</span></a></div>
        </div>
    </div>
</div>

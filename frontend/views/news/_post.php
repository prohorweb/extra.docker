<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;

/* @var $model \common\models\News */

//Yii::$app->formatter->locale = 'ru-RU';
?>
<div class="news-blog__item col-md-6 mb-3">
    <div class="news-block">
        <div class="news-block__date d-flex align-items-center mb-3">
            <h3 class="m-0"><?= (new DateTime($model['date']))->format('j') ?></h3>
            <span class="ms-2"><?= IntlDateFormatter::formatObject(new DateTime($model['date']), 'MMMM', 'ru-RU') ?></span>
            <span class="ms-2"><?= (new DateTime($model['date']))->format('Y') ?> года</span>
        </div>
        <div class="news-block__wrap">
            <h3 class="news-block__title title-h3"><a href="<?= Url::to(['/es/news/' . $model->alias]) ?>"><?= Html::encode($model->title) ?></a></h3>
            <div class="news-block__text"><?= Html::encode($model->intro) ?></div>
            <div class="news-block__button my-3"><a href="<?= Url::to(['/es/news/' . $model->alias]) ?>" class="btn btn-primary btn-lg "><span>Подробнее <i class="fa-thin fa-angles-right"></i></span></a></div>
        </div>
    </div>
</div>
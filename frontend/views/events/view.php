<?php

use richardfan\widget\JSRegister;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\HtmlPurifier;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model \common\models\Events */

$this->title = $model->meta_title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $model->meta_keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $model->meta_description]);

//Yii::$app->formatter->locale = 'ru-RU';
?>


<section class="page-item">
    <div class="container">

        <nav class="d-md-block d-none" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= Url::to(['/']) ?>"><?= $this->params['club']->title ?></a></li>
                <li class="breadcrumb-item"><a href="<?= Url::to(['/es/events/']) ?>">Мероприятия</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= Html::encode($model->title) ?></li>
            </ol>
        </nav>

        <h2 class="section-heading"><?= Html::encode($model->title) ?></h2>

        <div class="news-block__wrap">
            <div class="news-block__text">
            <div class="event-block__date d-flex align-items-center mb-3">
                    <h3 class="m-0"><?= (new DateTime($model['date']))->format('j') ?></h3>
                    <span class="ms-2"><?= IntlDateFormatter::formatObject(new DateTime($model['date']), 'MMMM', 'ru-RU') ?></span>
                    <span class="ms-2"><?= (new DateTime($model['date']))->format('Y') ?> года</span>
                </div>
                <?= HtmlPurifier::process($model->content, function ($config) {
                    /** @var \HTMLPurifier_Config $config */
                    $config->set('HTML.SafeIframe', true);
                    $config->set('URI.SafeIframeRegexp', '%^(http?:)?//(www\.youtube(?:-nocookie)?\.com/embed/)%'); //allow YouTube
                }) ?>
            </div>
        </div>
    </div>
</section>


<?= $this->render('/club/_subscribe') ?>


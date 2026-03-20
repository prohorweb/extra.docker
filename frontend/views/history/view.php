<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */
/* @var $model \common\models\History */

$this->title = $model->meta_title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $model->meta_keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $model->meta_description]);

setlocale(LC_TIME, "ru-RU");

?>
<section class="breadcramb-section breadcramb-section--pb0">
    <div class="container">
        <div class="breadcramb">
            <a href="/">Главная страница</a>
            <a href="<?= Url::to(['/']) ?>"><?= $this->params['club']->title ?></a>
            <a href="<?= Url::to(['/es/club/']) ?>">О клубе</a>
            <a href="<?= Url::to(['/es/history/']) ?>">Истории успеха</a>
            <span><?= Html::encode($model->title) ?>1111</span>
        </div>
    </div>
</section>

<main class="main-section main-section--team-page team-page team-page--doposle">
    <div class="container">

        <a href="<?= Url::to(['/es/history/']) ?>" class="team-page__back btn btn--back"><span>НАЗАД</span></a>

        <div class="team-page__wrap">
            <h1 class="team-page__title title-h1"><?= Html::encode($model->title) ?></h1>
            <div class="team-page__date"><strong><?= (new DateTime($model['date']))->format('d') ?></strong> <?= IntlDateFormatter::formatObject(new DateTime($model['date']), 'MMMM', 'ru-RU') ?> <span><?= (new DateTime($model['date']))->format('Y') ?> года</span></div>

            <div class="team-page__row row">
                <div class="team-page__info col-6 col-sm-12">
                    <div class="item-page">
                        <?= HtmlPurifier::process($model->content, function ($config) {
                            /** @var \HTMLPurifier_Config $config */
                            $config->set('HTML.SafeIframe', true);
                            $config->set('URI.SafeIframeRegexp', '%^(http?:)?//(www\.youtube(?:-nocookie)?\.com/embed/)%'); //allow YouTube
                        }) ?>
                    </div>
                </div>
                <div class="team-page__photo col-6 col-sm-12">

                    <div class="slideshow-wrapper slideshow-wrapper--normal slideshow-wrapper--rb">
                        <div class="slick" data-slick='{"autoplay": true, "adaptiveHeight": false, "centerPadding": "0px", "infinite": true, "slidesToShow": 1, "dots": false, "arrows": true}'>
                            <?php foreach ($banners as $banner) { ?>
                                <div class="slick-slide">
                                    <img src="<?= $banner->img1440 ? '/uploads/image/banners/1440/' . $banner->img1440 : 'http://placehold.it/645x460' ?>" alt="" />
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>
</main>

<?= $this->render('_subscribe') ?>

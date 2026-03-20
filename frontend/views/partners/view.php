<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\HtmlPurifier;
use lajax\translatemanager\helpers\Language;

/* @var $this yii\web\View */
/* @var $model \common\models\Partners */
/* @var $metros \common\models\MetroPartners */

$this->title = $model->meta_title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $model->meta_keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $model->meta_description]);

?>

<main class="main-section main-section--partner-page partner-page">
    <div class="container">
        <div class="breadcrumb">
            <a href="/" class="breadcrumb__item"><?= Language::t('breadcrumb', 'Главная страница') ?></a>
            <a href="<?= Url::to('/ff/club/') ?>" class="breadcrumb__item"><?= Language::t('breadcrumb', 'Клуб') ?></a>
            <a href="<?= Url::to('/ff/partners/') ?>" class="breadcrumb__item"><?= Language::t('breadcrumb', 'Партнеры') ?></a>
            <span class="breadcrumb__item"><?= Html::encode($model->title) ?></span>
        </div>

        <div class="partner-page__wrapper">

            <h1 class="partner-page__title title-h1"><?= Html::encode($model->title) ?></h1>
            <?php if(!empty($model->discount)) { ?>
            <div class="partner-page__sale"><?= Language::t('partners', 'Скидка') ?> <?= $model->discount ?></div>
            <?php } elseif($model->is_gift == 1) { ?>
                <div class="partner-page__sale"><?= Language::t('partners', 'Подарок') ?></div>
            <?php } ?>
            <div class="row">
                <div class="col-8 col-sm-12">
                    <div class="partner-page__text item-page">
                        <?= HtmlPurifier::process($model->content, function ($config) {
                            /** @var \HTMLPurifier_Config $config */
                            $config->set('HTML.SafeIframe', true);
                            $config->set('URI.SafeIframeRegexp', '%^(http?:)?//(www\.youtube(?:-nocookie)?\.com/embed/)%'); //allow YouTube
                        }) ?>
                    </div>

                </div>
                <div class="col-4 hidden-sm">
                    <img src="<?= $model->img ? '/uploads/image/partners/'.$model->img : '//placehold.it/330x240' ?>" alt=""/>
                </div>
            </div>

            <div class="partner-page__contacts partner-contacts">
                <div class="row row--stretch">
                    <div class="col-auto col-sm-6 col-xs-12 xs-mb30">

                        <div class="partner-contacts__title title-h3"><?= Language::t('partners', 'Контакты партнера') ?></div>
                        <div class="partner-contacts__row">
                            <p class="icon-marker"><?= $model->address ?></p>
                            <a href="tel:<?= $model->tel ?>"><p class="icon-phone"><?= $model->tel ?></p></a>
                        </div>
                        <div class="partner-contacts__row">
                            <div class="title-h4 color-black"><?= Language::t('main', 'Ближайшие станции метро') ?></div>
                            <p><?php foreach ((array)$metros as $metro) { ?>
                                    <?= $metro->name ?><br>
                                <?php } ?></p>
                        </div>
                        <div class="partner-contacts__row">
                            <div class="title-h4 color-black"><?= Language::t('partners', 'Сайт партнёра') ?>:</div>
                            <p><a href="<?= $model->site ?>"><?= $model->site ?></a></p>
                        </div>

                    </div>
                    <div class="col-5 col-lg-7 col-sm-6 col-xs-12 xs-mb50">
                        <div id="map" class="partner-contacts__map"></div>
                    </div>
                </div>
            </div>

            <div class="partner-page__back"><a href="<?= Url::to('/ff/partners/') ?>" class="btn btn--sm"><i class="btn-back">←</i><?= Language::t('partners', 'к списку') ?></a></div>

        </div>

    </div>
</main>

<?php
$this->registerJsFile('http://api-maps.yandex.ru/2.0/?load=package.full&lang=' . Yii::$app->language);
$js = <<< JS
ymaps.ready(init);
function init () {
    // Создание экземпляра карты и его привязка к контейнеру с
    // заданным id ("map")
    var myMap = new ymaps.Map('map', {
            // При инициализации карты, обязательно нужно указать
            // ее центр и коэффициент масштабирования
            //center: [68.956833,33.067446],
            center: [$model->coordinates],
            zoom: 16
        });
        
        //myMap.controls.add('zoomControl', { top: 75, left: 5 });
        myMap.behaviors.enable('scrollZoom');

        // Создание метки 
        var myPlacemark = new ymaps.Placemark(
        // Координаты метки
        [$model->coordinates], {
            // Свойства
            // Текст метки
            hintContent: 'FITFASHION'
        }, {
            iconImageHref: '/images/marker.svg', // картинка иконки
            iconImageSize: [53, 101], // размеры картинки
            iconImageOffset: [-37, -79] // смещение картинки
            });     

        // Добавление метки на карту
        myMap.geoObjects.add(myPlacemark);
}
JS;
$this->registerJs($js);


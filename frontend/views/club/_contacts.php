<?php

use lajax\translatemanager\helpers\Language;

/* @var $this yii\web\View */
/* @var $model \common\models\Club */

?>
    <section class="contact-section">

        <div class="container">
            <div class="contact-section__wrap contact-block">
                <div class="contact-block__title title-h2"><?= Language::t('main', 'Контакты') ?></div>
                <div class="row row--center">
                    <div class="col-6 col-sm-12 mb20 sm-mb15">
                        <a href="tel:<?= $model->tel ?>"><p class="icon-phone-2"><?= $model->tel ?></p></a>
                    </div>
                    <div class="col-6 col-sm-12 mb20 sm-mb35">
                        <a href="#zvon-popup" class="btn btn--sm popup-to"><?= Language::t('main', 'обратный звонок') ?></a>
                    </div>
                </div>
                <p class="icon-marker-3 mb50 xs-mb35"><?= $model->address ?></p>
                <div class="row mb50 sm-mb0">
                    <div class="col-6 col-sm-12 mb20 sm-mb50 xs-mb35">
                        <div class="title-h4"><?= Language::t('main', 'Режим работы') ?></div>
                        <p><?= Language::t('main', 'пн - пт') ?>: <?= $model->start_work ?><br><?= Language::t('main', 'сб - вс') ?>: <?= $model->start_work_weekend ?></p>
                    </div>
                    <div class="col-6 col-sm-12 mb20 sm-mb30">
                        <div class="title-h4"><?= Language::t('main', 'Ближайшие станции метро') ?></div>
                        <p><?php foreach ((array)$metros as $metro) { ?>
                                <?= $metro->name ?><br>
                            <?php } ?></p>
                    </div>
                </div>
                <p class="mb50 xs-mb35"><?= Language::t('main', 'Директор клуба') ?>: <a href="mailto:director@fitfashion.ru">director@fitfashion.ru</a><br>
                    <?= Language::t('main', 'Реклама и партнерские программы') ?>: <a href="mailto:clubmanager@fitfashion.ru">clubmanager@fitfashion.ru</a><br>
                    <?= Language::t('main', 'Сервис в клубе') ?>: <a href="mailto:service@fitfashion.ru">service@fitfashion.ru</a><br>
                    <?= Language::t('main', 'Хотите стать частью нашей команды?') ?> <a href="mailto:hr@fitfashion.ru">hr@fitfashion.ru</a></p>
                <div class="soc-list">
                    <?php if(!empty($this->params['club']->url_facebook)) { ?>
                    <div class="soc-list__item"><a href="<?= $this->params['club']->url_facebook ?>"><img src="/images/soc-white-1.png" class="hidden-sm" alt=""/><img src="/images/soc-brown-1.png" class="none visible-sm" alt=""/></a></div>
                    <?php }
                    if(!empty($this->params['club']->url_vk)) { ?>
                    <div class="soc-list__item"><a href="<?= $this->params['club']->url_vk ?>"><img src="/images/soc-white-2.png" class="hidden-sm" alt=""/><img src="/images/soc-brown-2.png" class="none visible-sm" alt=""/></a></div>
                    <?php }
                    if(!empty($this->params['club']->url_instagram)) { ?>
                    <div class="soc-list__item"><a href="<?= $this->params['club']->url_instagram ?>"><img src="/images/soc-white-3.png" class="hidden-sm" alt=""/><img src="/images/soc-brown-3.png" class="none visible-sm" alt=""/></a></div>
                    <?php }
                    if(!empty($this->params['club']->url_ok)) { ?>
                    <div class="soc-list__item"><a href="<?= $this->params['club']->url_ok ?>"><img src="/images/soc-white-4.png" class="hidden-sm" alt=""/><img src="/images/soc-brown-4.png" class="none visible-sm" alt=""/></a></div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div id="map" class="contact-section__map"></div>
    </section>


<?php
$this->registerJsFile('http://api-maps.yandex.ru/2.0/?load=package.full&lang=' . Yii::$app->language);
$var = Yii::$app->devicedetect->isMobile() ? 0 : 200;
$js = <<< JS
ymaps.ready(init);
    function init () {
        // Создание экземпляра карты и его привязка к контейнеру с
        // заданным id ("map")
        var myMap = new ymaps.Map('map', {
            // При инициализации карты, обязательно нужно указать
            // ее центр и коэффициент масштабирования
            center: [$model->coordinates],
            zoom: 16
        });
    
        var pixelCenter = myMap.getGlobalPixelCenter();
        pixelCenter = [
            pixelCenter[0] - $var, // Смещение на 200 пикселей вправо
            pixelCenter[1]
        ];
        var geoCenter = myMap.options.get('projection').fromGlobalPixels(pixelCenter, myMap.getZoom());
        myMap.setCenter(geoCenter);
        
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

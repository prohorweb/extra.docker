<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model \common\models\Club */
/* @var $metros \common\models\Metros */

$this->title = $model->contacts_title;

?>
<section class="breadcramb-section breadcramb-section--pb0 breadcramb-section--grey ">
    <div class="container">
        <div class="breadcramb">
            <a href="/">Главная страница</a>
            <a href="<?= Url::to(['/']) ?>"><?= $this->params['club']->title ?></a>
            <span>Контакты</span>
        </div>
    </div>
</section>

<main class="main-section main-section--contacts contacts-page">
    <div class="container">

        <h1 class="contacts-page__title title-h1">Контакты клуба <?= $this->params['club']->title ?></h1>

        <div class="contacts-page__row row row--stretch">
            <div class="contacts-page__info col-6 col-xs-12">

                <div class="map-section__phone"><a href="tel:<?= $model->tel ?>"><?= $model->tel ?></a><a href="#zvon-popup" class="popup-to map-section__zvon">Заказать звонок</a></div>
                <div class="map-section__adres"><?= $model->address ?><br><?= explode('.', $_SERVER['HTTP_HOST'])[0] == 'matros' ? 'Клуб имеет бесплатную парковку' : 'Клуб имеет собственную охраняемую парковку' ?></div>
                <div class="map-section__metro">Ближайшее метро<br><?= implode(", ", ArrayHelper::getColumn($metros, 'name')); ?></div>
                <div class="map-section__time">Время работы<br> пн–пт: <?= $model->start_work ?><br> сб–вс: <?= $model->start_work_weekend ?></div>
                <div class="map-section__prodag">Отдел продаж<br> пн-вс: <?= explode('.', $_SERVER['HTTP_HOST'])[0] == "matros" ? '14:00 до 21:00' : '10:00 до 22:00' ?></div>
                <div class="map-section__mail mb0"><a href="mailto:<?= $this->params['club']->email ?>"><?= $this->params['club']->email ?></a></div>

            </div>
            <div id="map" class="contacts-page__map col-6 col-xs-12"></div>
        </div>

    </div>
</main>

<?php
$this->registerJsFile('http://api-maps.yandex.ru/2.1/?lang=ru_RU');
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
                zoom: 16,
                controls: []
            },{suppressMapOpenBlock: true});
            
            //myMap.controls.add('zoomControl', { top: 75, left: 5 });
            //myMap.behaviors.enable('scrollZoom');
    
            // Создание метки 
            var myPlacemark = new ymaps.Placemark(
            // Координаты метки
            [$model->coordinates], {
                // Свойства
                // Текст метки
                hintContent: 'ExtraSport'
            }, {
                iconLayout: 'default#image',
                iconImageHref: '/images/marker.png', // картинка иконки
                iconImageSize: [57, 80], // размеры картинки
                iconImageOffset: [-28, -80] // смещение картинки
                });     
    
            // Добавление метки на карту
            myMap.geoObjects.add(myPlacemark);
    }
JS;
$this->registerJs($js);

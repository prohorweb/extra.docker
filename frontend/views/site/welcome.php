<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'EХTRASPORT сеть фитнес-клубов Санкт-Петербург';
$this->params['breadcrumbs'][] = $this->title;

$model = common\models\Club::find()->where(['id' => 1])->one(Yii::$app->db);
$model2 = $model;
$model3 = $model;
$model4 = $model;

$http = (Yii::$app->request->isSecureConnection ? 'https' : 'http'). '://' ;
$host =  $_SERVER['HTTP_HOST'];
$url = $http.$host;

$this->registerJsFile('@web/js/front.js', ['depends' => [\frontend\assets\AppAsset::class]]);
$this->registerCssFile('@web/css/clubs.css', ['depends' => [\frontend\assets\AppAsset::class]]);
   

?>
<header class="main" style="
    overflow: hidden;
    position: relative;">
    <video muted loop autoplay class="w-100">
        <source src="video/bg_moution.mp4" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
        <source src="video/bg_moution.webm" type='video/webm; codecs="vp8, vorbis"'>
    </video>

    <div class="masthead d-flex align-items-center mt-0">
        <div class="container fade-in hero-content">
            <div class="d-flex justify-content-center">
                <div class="col-md-6"><img class="w-100" src="/img/logo.svg" alt="extrasport logo"></div>
            </div>
            <div class="masthead-heading text-uppercase">Сеть фитнес клубов на результат!</div>
            <div class=" d-none d-md-block">
                   <a class="btn btn-primary btn-xl text-uppercase bg-black" href="#clubs">Выберите клуб</a>
            </div>
            <div class=" d-block d-md-none">
                <a class="btn btn-primary btn-xl text-uppercase bg-black" href="#clubs-mobile">Выберите клуб</a>
            </div>
            
        </div>
    </div>
</header>

<section class="d-none d-md-block">
    <div class="clubs_container hieght-100">
        <div class="carousel_clubs-container" id="clubs">
            <div class="carousel_clubs">
                <div class="carousel_clubs-item">
                    <a class="card" href="<?= $http."piter.".$host?>"><img
                            src="<?= $url ?>/img/clubs/welcom-block-img-2.jpg" alt="...">
                        <div class="card-body p-0">
                            <div class="d-flex">
                                <div class="w-100 py-2">
                                    <h5 class="card-title">ТРЦ «Питер»</h5>
                                    <div class="card-text">Санкт-Петербург, ул. Типанова, 21</div>
                                </div>
                                <div class="btn-arrow d-flex align-items-center"><i
                                        class="fa-sharp fa-solid fa-arrow-right"> </i></div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="carousel_clubs-item">
                    <a class="card" href="<?= $http."matros.".$host?>"><img
                            src="<?= $url ?>/img/clubs/welcom-block-img-4.jpg" alt="...">
                        <div class="card-body p-0">
                            <div class="d-flex">
                                <div class="w-100 py-2">
                                    <h5 class="card-title">«Матроса железняка»</h5>
                                    <div class="card-text">Санкт-Петербург, ул. Матроса Железняка, 57А</div>
                                </div>
                                <div class="btn-arrow d-flex align-items-center"><i
                                        class="fa-sharp fa-solid fa-arrow-right"> </i></div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="carousel_clubs-item">
                    <a class="card" href="http://de-vision.ru" target="_blank">
                        <img src="<?= $url ?>/img/clubs/welcom-block-img-5.jpg" alt="...">
                        <div class="card-body p-0">
                            <div class="d-flex">
                                <div class="w-100 py-2">
                                    <h5 class="card-title">De-vision</h5>
                                    <div class="card-text">Санкт-Петербург, пр. Культуры, 1</div>
                                </div>
                                <div class="btn-arrow d-flex align-items-center"><i
                                        class="fa-sharp fa-solid fa-arrow-right"> </i></div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <button class="prev">❮</button>
            <button class="next">❯</button>
        </div>
    </div>
</section>

<section class="clubs_container py-5 d-block d-md-none" id="clubs-mobile">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-4 col-md-6">
                <a class="card" href="<?= $http."piter.".$host?>"><img class="card-img-top"
                        src="/img/clubs/welcom-block-img-2.jpg" alt="...">
                    <div class="card-body p-0">
                        <div class="d-flex">
                            <div class="w-100 py-2">
                                <h5 class="card-title">ТРЦ «Питер»</h5>
                                <div class="card-text">Санкт-Петербург, ул. Типанова, 21</div>
                            </div>
                            <div class="btn-arrow d-flex align-items-center"><i
                                    class="fa-sharp fa-solid fa-arrow-right"> </i></div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-6 col-md-6">
                <a class="card" href="<?= $http."matros.".$host?>"><img class="card-img-top"
                        src="/img/clubs/welcom-block-img-4.jpg" alt="...">
                    <div class="card-body p-0">
                        <div class="d-flex">
                            <div class="w-100 py-2">
                                <h5 class="card-title">«Матроса железняка»</h5>
                                <div class="card-text">Санкт-Петербург, ул. Матроса Железняка, 57А</div>
                            </div>
                            <div class="btn-arrow d-flex align-items-center"><i
                                    class="fa-sharp fa-solid fa-arrow-right"> </i></div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-6 col-md-12">
                <a class="card" href="http://de-vision.ru"><img class="card-img-top"
                        src="/img/clubs/welcom-block-img-5.jpg" alt="...">
                    <div class="card-body p-0">
                        <div class="d-flex">
                            <div class="w-100 py-2">
                                <h5 class="card-title">De-vision</h5>
                                <div class="card-text">Санкт-Петербург, пр. Культуры, 1</div>
                            </div>
                            <div class="btn-arrow d-flex align-items-center"><i
                                    class="fa-sharp fa-solid fa-arrow-right"> </i></div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<!--Map-->
<section class="map-section welcome hieght-100">
    <div class="map-section__map" id="map"> </div>
</section>


<!-- Footer-->
<footer>
    <div class="footer-top py-5">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-md-4">
                    <div class="row">
                        <div class="col-lg-6 text-center">
                            <ul class="map-section__list p-0">
                                <li><a href="http://piter.<?=$url?>">ТРЦ «Питер»</a></li>
                                <li><a href="http://matros.<?=$url?>">«Матроса железняка»</a></li>
                                <li><a href="http://de-vision.ru">De-Vision</a></li>
                            </ul>
                        </div>
                        <div class="col-lg-6 text-center">
                            <div class="row align-items-center">
                                <div class="col-lg-6">
                                    <div class="d-flex justify-content-center mt-3">Мы в:<a
                                            class="fa-brands fa-vk fs-2 ps-3" href="http://vk.com/extrasport_ru"
                                            target="_blunk"></a><a class="fa-brands fa-youtube fs-2 ps-3"
                                            href="http://www.youtube.com/channel/UCCUUiy9ZROCNHBmDvPF-dxw/featured"
                                            target="_blunk"></a></div>
                                </div>
                                <div class="col-lg-6">

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-8">
                    <div class="footer__api d-flex justify-content-end"><a
                            class="d-flex align-items-center justify-content-end my-3 ps-3"
                            href="http://play.google.com/store/apps/details?id=air.com.extrasport" target="_blunk"
                            onclick="ym(21525628, 'reachGoal', 'download_app');">
                            <p class="m-0 text-end">Доступно в <br><b>GOOGLE PLAY</b></p><i
                                class="fa-brands fa-google-play fs-1 ps-3"></i>
                        </a><a class="d-flex align-items-center justify-content-end my-3 ps-3"
                            href="http://itunes.apple.com/ru/app/extra-sport/id1462883244?mt=8" target="_blunk"
                            onclick="ym(21525628, 'reachGoal', 'download_app');">
                            <p class="m-0 text-end">Загрузите в <br><b>APP STORE</b></p><i
                                class="fa-brands fa-app-store-ios fs-1 ps-3"> </i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom col-lg-12">
        <div class="container">
            <div class="row">
                <div class="col-md-4 text-md-start">&copy; 2023 EХTRASPORT, LLC</div>
                <div class="col-md-8 text-md-end">
                    <!-- <a class="text-decoration-none me-2" href="#rules"  data-bs-toggle="modal">Правила поведения в клубе &nbsp; |</a> -->
                    <a class="text-decoration-none" href="http://piter.<?= $url ?>/legal/" target="_blunk">Правовая
                        информация</a>
                </div>
            </div>
        </div>
    </div>
</footer>
<script>
let currentIndex = 0;
</script>

<?php
$this->registerJsFile('http://api-maps.yandex.ru/2.1/?lang=ru_RU');
$js = <<< JS
ymaps.ready(init);
    function init () {
        var host = window.location.hostname; 
        var myMap = new ymaps.Map('map', {
                // При инициализации карты, обязательно нужно указать
                // ее центр и коэффициент масштабирования
                //center: [68.956833,33.067446],
                center: [$model->coordinates],
                zoom: 11,
                controls: []
            },{suppressMapOpenBlock: true});
            
            //myMap.controls.add('zoomControl', { top: 75, left: 5 });
            myMap.behaviors.disable('scrollZoom');
    
            var myPlacemark = new ymaps.Placemark(
            [$model->coordinates], {
                hintContent: 'EХTRASPORT Питер'
            }, {
                iconLayout: 'default#image',
                iconImageHref: '/images/marker.png', // картинка иконки
                iconImageSize: [57, 80], // размеры картинки
                iconImageOffset: [-28, -80] // смещение картинки
                });
            
            var myPlacemark2 = new ymaps.Placemark(
            [$model2->coordinates], {
                hintContent: 'EХTRASPORT Июнь'
            }, {
                iconLayout: 'default#image',
                iconImageHref: '/images/marker.png', // картинка иконки
                iconImageSize: [57, 80], // размеры картинки
                iconImageOffset: [-28, -80] // смещение картинки
                });
            
            var myPlacemark3 = new ymaps.Placemark(
            [$model3->coordinates], {
                hintContent: 'EХTRASPORT Полюс'
            }, {
                iconLayout: 'default#image',
                iconImageHref: '/images/marker.png', // картинка иконки
                iconImageSize: [57, 80], // размеры картинки
                iconImageOffset: [-28, -80] // смещение картинки
                });
            
            var myPlacemark4 = new ymaps.Placemark(
            [$model4->coordinates], {
                hintContent: 'EХTRASPORT Матроса Железняка'
            }, {
                iconLayout: 'default#image',
                iconImageHref: '/images/marker.png', // картинка иконки
                iconImageSize: [57, 80], // размеры картинки
                iconImageOffset: [-28, -80] // смещение картинки
                });
            
            var myPlacemark5 = new ymaps.Placemark(
            [60.033517089690086,30.36834735319519], {
                hintContent: 'De-vision'
            }, {
                iconLayout: 'default#image',
                iconImageHref: '/images/marker2.png', // картинка иконки
                iconImageSize: [57, 80], // размеры картинки
                iconImageOffset: [-28, -80] // смещение картинки
                });
            
            myMap.geoObjects.add(myPlacemark);
            // myMap.geoObjects.add(myPlacemark2);
            // myMap.geoObjects.add(myPlacemark3);
            myMap.geoObjects.add(myPlacemark4);
            myMap.geoObjects.add(myPlacemark5);
            
            myMap.setBounds(myMap.geoObjects.getBounds());
            
            myPlacemark.events.add('click', function (e) { window.location.href = 'http://piter.' + host});
            myPlacemark2.events.add('click', function (e) { window.location.href = 'http://piter.'  + host });
            myPlacemark3.events.add('click', function (e) { window.location.href = 'http://matros.'  + host });
            myPlacemark4.events.add('click', function (e) { window.location.href = 'http://matros.'  + host });
            myPlacemark5.events.add('click', function (e) { window.location.href = 'http://de-vision.ru' });
    }

    
JS;
$this->registerJs($js);
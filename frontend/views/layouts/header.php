<?php

use yii\helpers\Html;
use yii\helpers\Url;

$controller = Yii::$app->controller;
$default_controller = Yii::$app->defaultRoute;
$isHome = (($controller->id === $default_controller) && ($controller->action->id === $controller->defaultAction)) ? true : false;

if  ($isHome == true) {
    $nav_position = 'position-absolute';
} else {
     $nav_position = '';
}
?>

<!-- Navigation -->
<div class="navbar navbar-expand-lg navbar-dark <?= $nav_position ?>  w-100" id="mainNav">
    <div class="container">
        <div class="logo">
            <a class="navbar-brand d-flex align-items-center justify-content-center" href="<?= Url::to(['/']) ?>">
                <img src="/img/logo.svg" alt="extrasport logo">
            </a>
        </div>
        <div class="call d-lg-none d-md-block d-none">
            <a href="#callModal" data-bs-toggle="modal">
                <div class="btn btn-primary btn-lg"><i
                        class="fa-sharp fa-solid fa-phone-arrow-down-left me-1"></i>Обратный звонок</div>
            </a>
        </div>
        <div class="nav-burger d-block d-lg-none d-flex justify-content-between">
            <div class="call d-block d-md-none"><a href="#callModal" data-bs-toggle="modal">
                    <div class="btn btn-primary btn-lg"><i
                            class="fa-sharp fa-solid fa-phone-arrow-down-left me-1"></i>Обратный звонок</div>
                </a></div>
            <button class="navbar-toggler d-flex align-items-center justify-content-end" type="button"
                data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar">
                <span>МЕНЮ</span><span class="navbar-toggler-icon"></span></button>
        </div>
        <div class="offcanvas offcanvas-end text-bg-dark offcanvas-size-sm" id="offcanvasDarkNavbar" tabindex="-1"
            aria-labelledby="offcanvasDarkNavbarLabel">
            <div class="d-none d-lg-block">
                <div class="navbar__adress d-flex align-items-center justify-content-center">
                    <div class="aderss-section d-flex justify-content-center"><i class="fa-solid fa-location-dot pe-1">
                        </i><span class="me-1 d-xl-block d-lg-none">Ваш клуб:</span><a href="#clubModal"
                            data-bs-toggle="modal"><?= $this->params['club']->address ?: '- - -' ?><i
                                class="fa-solid fa-chevron-down"> </i></a></div>
                    <div class="aderss-section"><a class="ms-3"
                            href="tel:<?= $this->params['club']->tel ?>"><?= $this->params['club']->tel ?></a></div>
                </div>
            </div>
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel"><a href="#clubModal"
                        data-bs-toggle="modal"><?= $this->params['club']->title ?: '- - -' ?></h5></a>
                <button class="btn-close btn-close-white" type="button" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <div class="aderss-section px-3 d-block d-sm-none">
                <a class="d-flex justify-content-start" href="#clubModal" data-bs-toggle="modal"><?= $this->params['club']->address ?: '- - -' ?><i
                        class="fa-solid fa-chevron-down"> </i></a>
                <a class="d-flex justify-content-start pt-3" class="ms-3" href="tel:<?= $this->params['club']->tel ?>"><?= $this->params['club']->tel ?></a>
            </div>
            <div class="offcanvas-body justify-content-center">
                <ul class="navbar-nav text-uppercase">
                    <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">О клубе</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= Url::to(['/es/club/']) ?>">Обзор клуба</a></li>
                            <li><a class="dropdown-item" href="<?= Url::to(['/es/command/']) ?>">Тренеры</a></li>
                            <li><a class="dropdown-item" href="<?= Url::to(['/es/news/']) ?>">Новости</a></li>
                            <li><a class="dropdown-item" href="<?= Url::to(['/es/events/']) ?>">Мероприятия</a></li>
                            <li><a class="dropdown-item" href="<?= Url::to(['/es/job/']) ?>">Вакансии</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item"
                                    href="http://www.youtube.com/channel/UCCUUiy9ZROCNHBmDvPF-dxw/featured"
                                    target="_blank">Истории успеха</a></li>
                            <li><a class="dropdown-item"
                                    href="http://www.youtube.com/channel/UCCUUiy9ZROCNHBmDvPF-dxw/featured"
                                    target="_blank">Советы тренеров</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="<?= Url::to(['/card/shares/']) ?>">Акции</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= Url::to(['/services/']) ?>">Услуги</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= Url::to(['/card/type/']) ?>">Абонементы и
                            цены</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= Url::to(['/']) ?>#contacts">Контакты</a></li>
                    <li class="nav-item d-block d-sm-none"><a class="nav-link" href="#callModal" data-bs-toggle="modal">
                            <div class="btn btn-primary btn-lg"><i
                                    class="fa-sharp fa-solid fa-phone-arrow-down-left me-1"></i>Обратный звонок</div>
                        </a></li>
                </ul>
            </div>
        </div>
        <div class="call d-lg-block d-none"><a href="#callModal" data-bs-toggle="modal">
                <div class="btn btn-primary btn-lg"><i
                        class="fa-sharp fa-solid fa-phone-arrow-down-left me-1"></i>Обратный звонок</div>
            </a></div>
    </div>
</div>
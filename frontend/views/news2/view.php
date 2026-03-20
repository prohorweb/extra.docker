<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */
/* @var $model \common\models\News2 */

$this->title = $model->meta_title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $model->meta_keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $model->meta_description]);

?>
<div class="header__body">
    <div class="container header__row">
        <div class="header__logo"><a href="<?= Url::to(['/']) ?>"><img src="/images/logo.svg" alt="ExtraSport" /></a></div>

        <div class="header__show-menu"><a href="#" class="show-menu"><i></i><i></i><i></i></a></div>
    </div>
</div>

<section class="breadcramb-section breadcramb-section--pb0">
    <div class="container">
        <div class="breadcramb">
            <a href="/">Главная страница</a>
            <a href="<?= Url::to(['/news/']) ?>">Новости</a>
            <span><?= Html::encode($model->title) ?></span>
        </div>
    </div>
</section>

<main class="main-section main-section--news-page team-page team-page--doposle team-page--news">
    <div class="container">

        <a href="<?= Url::to(['/news/']) ?>" class="team-page__back btn btn--back"><span>НАЗАД</span></a>

        <div class="team-page__wrap">
            <h1 class="team-page__title title-h1"><?= Html::encode($model->title) ?></h1>
            <div class="team-page__date"><strong><?= (new DateTime($model['date']))->format('j') ?></strong> <?= IntlDateFormatter::formatObject(new DateTime($model['date']), 'MMMM', 'ru-RU') ?> <span><?= (new DateTime($model['date']))->format('Y') ?> года</span></div>

            <div class="team-page__row row">
                <div class="team-page__info col-12 col-sm-12">
                    <div class="item-page">
                        <?= HtmlPurifier::process($model->content, function ($config) {
                            /** @var \HTMLPurifier_Config $config */
                            $config->set('HTML.SafeIframe', true);
                            $config->set('URI.SafeIframeRegexp', '%^(http?:)?//(www\.youtube(?:-nocookie)?\.com/embed/)%'); //allow YouTube
                        }) ?>
                    </div>
                </div>
            </div>

        </div>

    </div>
</main>

<footer class="footer footer--welcome">
    <div class="footer__body">
        <div class="container">
            <div class="footer__row row">
                <div class="footer__left col-3 col-xl-4 col-sm-6 col-xs-12">
                    <div class="footer__logo"><img src="/images/logo-white.svg" alt=""></div>
                    <div class="footer__desc">Самый доступный фитнес в Санкт-Петербурге</div>
                </div>
                <div class="footer__right col-3 col-xl-4 col-sm-6 col-xs-12">
                    <div class="footer__menu">
                        <div class="footer__menu-item"><a href="http://piter.extra.local">Extrasport ТК «Питер»</a></div>
                        <div class="footer__menu-item"><a href="http://piter.extra.local">Extrasport ТРЦ «Июнь»</a></div>
                        <div class="footer__menu-item"><a href="http://matros.extra.local">Extrasport ТРК «Южный полюс»</a></div>
                        <div class="footer__menu-item"><a href="http://matros.extra.local">Extrasport «Матроса Железняка»</a></div>
                        <div class="footer__menu-item"><a href="http://de-vision.ru">De-Vision</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer__bottom">
        <div class="container">
            <div class="footer__bottom__row">
                <div class="footer__copy">© <?= (new DateTime)->format('Y') ?> ExtraSport, LLC</div>

                <div class="footer__design">
                    Разработка сайта: <a href="http://ra-vozduh.ru" target="_blank">РА VOZDUH</a>
                </div>
            </div>
        </div>
    </div>
</footer>
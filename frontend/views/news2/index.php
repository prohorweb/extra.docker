<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $seo \common\models\Seo */

$this->title = $seo->title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $seo->keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $seo->description]);

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
            <span>Новости</span>
        </div>
    </div>
</section>

<main class="main-section main-section--news-blog news-blog">
    <div class="container container--min">

        <h1 class="news-blog__title title-h1">новости</h1>

        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'options' => ['class' => 'news-blog__list'],
            'layout' => '{items}',
            'emptyText' => 'Записей не найдено',
            'itemView' => '_post',
        ]); ?>


        <?php if ($dataProvider->pagination->getPageCount() > 1): ?>
            <div class="article-blog__pagination pagination">
                <?= \yii\widgets\LinkPager::widget([
                    'pagination' => $dataProvider->pagination,
                    'pageCssClass' => 'pagination__item',
                    'prevPageCssClass' => 'pagination__prev',
                    'nextPageCssClass' => 'pagination__next',
                    'nextPageLabel' => '',
                    'prevPageLabel' => ''
                ]) ?>
            </div>
        <?php endif; ?>

    </div>
</main>

<?= $this->render('/site/_callback') ?>

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
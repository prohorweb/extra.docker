<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use lajax\translatemanager\helpers\Language;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $seo \common\models\Seo */

$this->title = $seo->title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $seo->keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $seo->description]);

?>
<main class="main-section main-section--club-page">
    <div class="container">
        <div class="breadcrumb">
            <a href="/" class="breadcrumb__item"><?= Language::t('breadcrumb', 'Главная страница') ?></a>
            <a href="<?= Url::to('/ff/club/') ?>" class="breadcrumb__item"><?= Language::t('breadcrumb', 'Клуб') ?></a>
            <span class="breadcrumb__item"><?= Language::t('breadcrumb', 'Партнеры') ?></span>
        </div>

        <h1 class="main-section__title"><?= Language::t('club', 'Партнеры') ?></h1>

        <div class="tab-nav hidden-xs">
            <div class="tab-nav__item"><a href="<?= Url::to('/ff/club/') ?>"><?= Language::t('club', 'Обзор клуба') ?></a></div>
            <div class="tab-nav__item"><a href="<?= Url::to('/ff/command/') ?>"><?= Language::t('club', 'Команда') ?></a></div>
            <div class="tab-nav__item"><a href="<?= Url::to('/ff/news/') ?>"><?= Language::t('club', 'Новости') ?></a></div>
            <div class="tab-nav__item active"><a href="#"><?= Language::t('club', 'Партнеры') ?></a></div>
            <div class="tab-nav__type">
                <a href="#"><img src="/images/tab-block.png" alt=""/></a>
                <a href="<?= Url::to('/partners/list/') ?>"><img src="/images/tab-list.png" alt=""/></a>
            </div>
        </div>

        <div class="tab-nav-select tab-nav-select--group">
            <select name="" id="" onchange="if (this.value) window.location.href=this.value">
                <option value="<?= Url::to('/ff/club/') ?>"><?= Language::t('club', 'Обзор клуба') ?></option>
                <option value="<?= Url::to('/ff/command/') ?>"><?= Language::t('club', 'Команда') ?></option>
                <option value="<?= Url::to('/ff/news/') ?>"><?= Language::t('club', 'Новости') ?></option>
                <option value="" selected><?= Language::t('club', 'Партнеры') ?></option>
            </select>
            <a href="#"><img src="/images/tab-block.png" alt=""/></a>
            <a href="<?= Url::to('/partners/list/') ?>"><img src="/images/tab-list.png" alt=""/></a>
        </div>

        <div class="tab-wrapper">
            <?php Pjax::begin() ?>
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'options' => ['class' => 'partners-list row'],
                'itemOptions' => ['class' => 'col-3 col-sm-4 col-xs-12 partners-list__item'],
                'layout' => '{items}',
                'emptyText' => Language::t('main', 'Записей не найдено'),
                'itemView' => function ($model, $key, $index, $widget) {
                    return $this->render('_post', ['model' => $model, 'index' => $index]);
                },
            ]); ?>

            <?php if ($dataProvider->pagination->getPageCount() > 1): ?>
                <div class="pagination">
                    <div class="pagination__label"><?= Language::t('main','Страницы') ?></div>
                    <?= \yii\widgets\LinkPager::widget([
                        'pagination' => $dataProvider->pagination,
                        'prevPageLabel' => false,
                        'nextPageLabel' => false,
                    ]) ?>
                </div>
            <?php endif; ?>

            <?php Pjax::end() ?>
        </div>

    </div>
</main>

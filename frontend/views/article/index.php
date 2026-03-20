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
<section class="breadcramb-section">
    <div class="container">
        <div class="breadcramb">
            <a href="">Главная страница</a>
            <a href="<?= Url::to(['/']) ?>"><?= $this->params['club']->title ?></a>
            <a href="<?= Url::to(['/es/club/']) ?>">О клубе</a>
            <span>Советы тренеров</span>
        </div>
    </div>
</section>

<section class="nav-section">
    <div class="container">
        <div class="nav-section__row">
            <div class="nav-section__item"><a href="<?= Url::to(['/es/club/']) ?>">Обзор клуба</a></div>
            <div class="nav-section__item"><a href="<?= Url::to(['/es/command/']) ?>">тренеры</a></div>
            <div class="nav-section__item"><a href="<?= Url::to(['/es/history/']) ?>">истории успеха</a></div>
            <div class="nav-section__item"><a href="<?= Url::to(['/es/job/']) ?>">Вакансии</a></div>
            <div class="nav-section__item active"><a href="#">Советы тренеров</a></div>
        </div>
        <select name="" id="" class="nav-section__select select-noborder" onchange="if (this.value) window.location.href=this.value">
            <option value="<?= Url::to(['/es/club/']) ?>">Обзор клуба</option>
            <option value="<?= Url::to(['/es/command/']) ?>">тренеры</option>
            <option value="<?= Url::to(['/es/history/']) ?>">истории успеха</option>
            <option value="<?= Url::to(['/es/job/']) ?>">Вакансии</option>
            <option value="#" selected>Советы тренеров</option>
        </select>
    </div>
</section>

<main class="main-section main-section--article-blog article-blog">
    <div class="container container--min">

        <h1 class="article-blog__title title-h1">Советы тренеров клуба <?= $this->params['club']->title ?></h1>
        <div class="article-blog__list row">
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'layout' => '{items}',
                'emptyText' => 'Записей не найдено',
                'itemView' => function ($model, $key, $index, $widget) {
                    echo $this->render('_post', ['model' => $model]);
                }
            ]); ?>
        </div>

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

<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\HtmlPurifier;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model \common\models\News */

$this->title = $model->meta_title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $model->meta_keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $model->meta_description]);

?>
<section class="breadcramb-section breadcramb-section--pb0">
    <div class="container">
        <div class="breadcramb">
            <a href="/">Главная страница</a>
            <a href="<?= Url::to(['/']) ?>"><?= $this->params['club']->title ?></a>
            <a href="<?= Url::to(['/es/club/']) ?>">О клубе</a>
            <a href="<?= Url::to(['/es/article/']) ?>">Советы тренеров</a>
            <span><?= Html::encode($model->title) ?></span>
        </div>
    </div>
</section>

<main class="main-section main-section--article-blog article-page">
    <div class="container">

        <a href="<?= Url::to(['/es/article/']) ?>" class="article-page__back btn btn--back"><span>НАЗАД</span></a>

        <div class="article-page__wrap">
            <h1 class="article-page__title title-h1"><?= Html::encode($model->title) ?></h1>
            <div class="item-page">
                <?= HtmlPurifier::process($model->content, function ($config) {
                    /** @var \HTMLPurifier_Config $config */
                    $config->set('HTML.SafeIframe', true);
                    $config->set('URI.SafeIframeRegexp', '%^(http?:)?//(www\.youtube(?:-nocookie)?\.com/embed/)%'); //allow YouTube
                }) ?>
            </div>
        </div>

    </div>
</main>

<?= $this->render('/site/_callback') ?>

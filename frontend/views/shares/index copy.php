<?php

use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
use yii\widgets\ListView;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $params \common\models\ClubCardsParams */

$this->title = $params->meta_title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $params->meta_keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $params->meta_description]);

?>

<section class="breadcramb-section breadcramb-section--grey breadcramb-section--pb0">
    <div class="container">
        <div class="breadcramb">
            <a href="/">Главная страница</a>
            <a href="<?= Url::to(['/']) ?>"><?= $this->params['club']->title ?></a>
            <span>Акции</span>
        </div>
    </div>
</section>

<main class="main-section main-section--share share-blog">
    <div class="container">

        <h1 class="share-blog__title title-h1">Акции клуба EXTRASPORT<br> <?= $this->params['club']->title ?></h1>
        <div class="share-blog__desc"><?= HtmlPurifier::process($params->text) ?></div>


        <div class="share-blog__wrap">
            <?= ListView::widget([
                'id' => 'shares-list',
                'dataProvider' => $dataProvider,
                'itemOptions' => ['class' => 'share-blog__item col-3 col-lg-4 col-sm-6 col-xs-12'],
                'layout' => '<div class="share-blog__row row">{items}</div><div class="other-services__buttons">{pager}</div>',
                'emptyText' => 'Записей не найдено',
                'itemView' => '_post',
                'pager' => [
                    'class' => 'mranger\load_more_pager\LoadMorePager',
                    'id' => 'shares-list-pagination',
                    'buttonText' => 'Показать еще',
                    'contentSelector' => '#shares-list',
                    'contentItemSelector' => '.share-blog__item:not(.even)',
                ],
            ]); ?>
        </div>

    </div>
</main>

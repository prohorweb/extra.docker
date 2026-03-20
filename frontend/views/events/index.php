<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $seo \common\models\Seo */

$this->title = $seo->title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $seo->keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $seo->description]);

?>

<section class="page-item">
    <div class="container">

        <nav class="d-md-block d-none" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= Url::to(['/']) ?>"><?= $this->params['club']->title ?></a></li>
                <li class="breadcrumb-item active" aria-current="page">Мероприятия</li>
            </ol>
        </nav>

        <h2 class="section-heading">Мероприятия клуба <?= $this->params['club']->title ?></h2>

        <div class="events-blog__wrap mb-5">
            <?= ListView::widget([
                'id' => 'events-list',
                'dataProvider' => $dataProvider,
                'itemOptions' => ['tag' => null],
                'layout' => '<div class="events-blog__row row  border-bottom">{items}</div>',
                'emptyText' => '',
                'itemView' => '_post'
            ]); ?>
        </div>

        <div class="events-blog__wrap">
            <?= ListView::widget([
                'id' => 'events_past-list',
                'dataProvider' => $dataProviderPast,                
                'itemOptions' => ['tag' => null],
                'layout' => '<div class="other-services__row row">{items}</div>',
                'emptyText' => 'Записей не найдено',
                'itemView' => '_post2'
            ]); ?>
        </div>

        <nav class="my-5" aria-label="Page navigation">
            <?php if ($dataProviderPast->pagination->getPageCount() > 1) : ?>
                    <?= \yii\widgets\LinkPager::widget([
                        'pagination' => $dataProviderPast->pagination,
                        'linkOptions' => ['class' => 'page-link'],
                        'maxButtonCount' => 5,
                        'options' => [
                            'class' => 'pagination justify-content-center',
                        ],
                        'disabledPageCssClass' => 'disabled page-link',
                        'firstPageLabel' => '<i class="fa-thin fa-angles-left"></i>',
                        'lastPageLabel'  => '<i class="fa-thin fa-angles-right"></i>',
                        'nextPageLabel' => '<i class="fa-sharp fa-regular fa-arrow-right-long"></i>',
                        'prevPageLabel' => '<i class="fa-sharp fa-regular fa-arrow-left-long"></i>'
                    ]) ?>
            <?php endif; ?>
        </nav>

    </div>
</section>

<?= $this->render('/club/_subscribe') ?>
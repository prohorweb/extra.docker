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

<section class="page-item">
    <div class="container">

        <nav class="d-md-block d-none" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= Url::to(['/']) ?>"><?= $this->params['club']->title ?></a></li>
                <li class="breadcrumb-item active" aria-current="page">Новости</li>
            </ol>
        </nav>

        <h2 class="section-heading">Новости клуба <?= $this->params['club']->title ?></h2>


        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemOptions' => ['tag' => null],
            'options' => ['class' => 'news-blog__list row'],
            'layout' => '{items}',
            'itemOptions' => ['tag' => null],
            'emptyText' => 'Записей не найдено',
            'itemView' => '_post',
        ]); ?>



        <nav class="my-5" aria-label="Page navigation">
            <?php if ($dataProvider->pagination->getPageCount() > 1) : ?>
                    <?= \yii\widgets\LinkPager::widget([
                        'pagination' => $dataProvider->pagination,
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
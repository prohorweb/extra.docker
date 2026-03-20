<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $params \common\models\GroupProgramsParams */
/* @var $seo \common\models\Seo */

$this->title = $seo->title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $seo->keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $seo->description]);

$sub = explode('.', $_SERVER['HTTP_HOST'])[0];
$prefix = '';

if ($sub != 'piter') {
    $prefix = '_clubs';
} 

?>

<section id="about" class="position-relative">
     <video muted loop autoplay class="w-100 d-none d-md-block">
        <source src="<?= Url::home(); ?>video/service<?= $prefix ?>.mp4" class="service-video" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
        <source src="<?= Url::home(); ?>video/service<?= $prefix ?>.webm" class="service-video" type='video/webm; codecs="vp8, vorbis"'>
    </video>
    <video muted loop autoplay class="w-100 d-block d-md-none">
        <source src="<?= Url::home(); ?>video/service<?= $prefix ?>_mobile.mp4" class="service-video" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
        <source src="<?= Url::home(); ?>video/service<?= $prefix ?>_mobile.webm" class="service-video" type='video/webm; codecs="vp8, vorbis"'>
    </video>
</section>

<section class="page-item actions" id="actions">
    <div class="container">
        <nav class="d-md-block d-none" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= Url::to(['/']) ?>"><?= $this->params['club']->title ?></a></li>
                <li class="breadcrumb-item active" aria-current="page">Услуги</li>
            </ol>
        </nav>
        <h2 class="section-heading">Услуги клуба <?= $this->params['club']->title ?></h2>
        <div class="d-none"><?= $seo->text ?></div>
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'options' => ['class' => 'row'],
            'itemOptions' => ['tag' => null],
            'layout' => '{items}',
            'emptyText' => 'Записей не найдено',
            'itemView' => function ($model, $key, $index, $widget) {
                return $this->render('_post', ['model' => $model, 'index' => $index]);
            },
        ]) ?>
    </div>
</section>

<!-- Contact-->
<?= $this->render('/club/_subscribe') ?>
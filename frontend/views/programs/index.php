<?php

use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
use yii\widgets\ListView;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $params \common\models\GroupProgramsParams */

$this->title = $params->meta_title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $params->meta_keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $params->meta_description]);

?>

<div class="service-header">
    <div class="d-flex align-items-center justify-content-center title">групповые программы</div>
    <div class="overlay"></div>
    <img class="w-100" src="/uploads/image/banners/1440/Fitness_Dumbbells_479959_1920x1200-1649859427.jpg" alt="">
</div>

<section class="page-item actions" id="actions">
     <div class="container">
        <nav class="d-md-block d-none" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= Url::to(['/']) ?>"><?= $this->params['club']->title ?></a></li>
                <li class="breadcrumb-item"><a href="<?= Url::to(['/services/']) ?>">Услуги</a></li>
                <li class="breadcrumb-item active" aria-current="page">Групповые программы</li>
            </ol>
        </nav>
    </div>
    <div class="container">
        <h2 class="section-heading text-uppercase py-5 m-0 text-center">Направления групповых программ</h2>
        <p> <?= HtmlPurifier::process($params->text) ?></p>
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'options' => ['class' => 'row text-center'],
            'itemOptions' => ['tag' => null],
            'layout' => '{items}',
            'emptyText' => 'Записей не найдено',
            'itemView' => '_post',
        ]); ?>
    </div>

</section>

<?= $this->render('/club/_subscribe') ?>

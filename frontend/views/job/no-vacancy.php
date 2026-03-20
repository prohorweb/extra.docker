<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */
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
                <li class="breadcrumb-item active" aria-current="page">Вакансии</li>
            </ol>
        </nav>

        <h2 class="section-heading">Вакансии клуба <?= $this->params['club']->title ?></h2>

        <div class="careers-page__desc"><?= $seo->text ?></div>
        <div class="careers-page__subtitle title-h3">Вакансий нет</div>
    </div>
</section>

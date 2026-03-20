<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
use yii\widgets\ListView;

$this->title = $this->params['club']->privacy_title;
?>

<section class="page-item">
    <div class="container">
        <nav class="d-md-block d-none" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= Url::to(['/']) ?>"><?= $this->params['club']->title ?></a></li>
                <li class="breadcrumb-item active" aria-current="page">Политика конфиденциальности</li>
            </ol>
        </nav>
        <h2 class="section-heading">Политика конфиденциальности</h2>
        <?= HtmlPurifier::process($this->params['club']->legal) ?>
    </div>
</section>
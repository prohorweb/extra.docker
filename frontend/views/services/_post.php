<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;

/* @var $model \common\models\Services */

?>
<div class="col-md-6">
    <a class="card" href="<?= Url::to(['/services/' . $model->alias]) ?>">
        <img class="card-img-top" src="<?= $model->img ? '/uploads/image/services/' . $model->img : '//placehold.it/644x400' ?>" alt="...">
        <div class="card-body">
            <div class="d-flex">
                <div class="card-body_wrapper">
                    <h5 class="card-title"><?= $model->title ?></h5>
                </div>
                <div class="btn-arrow d-flex align-items-center"><i class="fa-sharp fa-solid fa-arrow-right"> </i></div>
            </div>
        </div>
    </a>
</div>
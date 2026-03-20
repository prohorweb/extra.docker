<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;

/* @var $model \common\models\Shares */

?>


    <a class="card" href="<?= Url::to(['/card/shares/' . $model->alias]) ?>">
        <div class="date-action"><?= $model->title2 ?></div>
        <img class="card-img-top" src="<?= $model->img ? '/uploads/image/share/' . $model->img : '//placehold.it/876x680' ?>" alt="...">
        <div class="card-body">
            <div class="d-flex">
                <div class="card-body_wrapper">
                    <h5 class="card-title"><?= $model->title ?></h5>
                    <div class="card-text"><?= $model->intro ?></div>
                </div>
                <div class="btn-arrow d-flex align-items-center"><i class="fa-sharp fa-solid fa-arrow-right"> </i></div>
            </div>
        </div>
    </a>

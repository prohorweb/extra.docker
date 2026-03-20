<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;

/* @var $model \common\models\GroupPrograms */

?>
<div class="col-lg-4 col-md-6">
    <a class="card" href="<?= Url::to($model->alias) ?>/">
        <img class="card-img-top" src="<?= $model->img ? '/uploads/image/group_programs/'.$model->img : '//placehold.it/646x400' ?>" alt="...">
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
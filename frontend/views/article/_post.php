<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;

/* @var $model \common\models\Articles */

?>
<div class="article-blog__item col-3 col-sm-6 col-xs-12">
    <div class="blog-item">
        <div class="blog-item__img"><a href="<?= Url::to(['/es/article/' . $model->alias]) ?>"><img src="<?= $model->img ? '/uploads/image/article/' . $model->img : '//placehold.it/330x330' ?>" alt="" /></a></div>
        <div class="blog-item__title"><a href="<?= Url::to(['/es/article/' . $model->alias]) ?>"><?= $model->title ?></a></div>
    </div>
</div>

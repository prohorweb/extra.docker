<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use lajax\translatemanager\helpers\Language;

/* @var $model \common\models\Partners */

?>
<div class="partner-box">
    <div class="partner-box__img"><img src="<?= $model->img ? '/uploads/image/partners/'.$model->img : '//placehold.it/330x330' ?>" alt=""/></div>
    <?php if(!empty($model->discount)) { ?>
        <div class="partner-box__stiker stiker"><?= $model->discount ?></div>
    <?php } elseif($model->is_gift == 1) { ?>
        <div class="partner-box__stiker stiker"><img src="/images/gift_icon_mini.png"></div>
    <?php } ?>
    <div class="partner-box__more"><a href="/ff/partners/<?= $model->alias ?>/" class="btn btn--sm"><?= Language::t('main', 'Подробнее') ?></a></div>
</div>

<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;

/* @var $model \common\models\Rasp */

?>
<div class="day-col">
    <div class="time-box" <?= $model->programClasses->color ? 'style="border-color: ' . $model->programClasses->color . '"' : '' ?>>
		<a href="#" class="time-box__link" data-id="#time-box-hint-<?= $model->id ?>"></a>
        <?php if($model->is_new) { ?>
            <img src="/images/label-new.png" alt="" class="time-box__label"/>
        <?php } ?>
        <div class="time-box__head">
            <div class="time-box__time"><span><?= substr($model->time, 0, 5) ?></span>
            </div>
            <ul class="time-box__buttons">
                <?php if ($model->is_preliminary) { ?>
                    <li><img src="<?= Url::to('/images/time-box-phone.png') ?>" alt=""/></li>
                <?php }if ($model->is_pay) { ?>
                    <li><img src="<?= Url::to('/images/time-box-rub.png') ?>" alt=""/></li>
                <?php } ?>
            </ul>
        </div>
        <div class="time-box__zal"><?= $model->rooms->title ?></div>
        <div class="time-box__name"><?= $model->programClasses->title ?></div>
        <div class="time-box__traner">
        <?php if($model->trainer) { ?>
                <a href="<?= Url::to(['/es/command/' . $model->trainer->alias]) ?>" target="_blank"><?= $model->trainer->title ?></a>
        <?php } ?>
        </div>

        <?php if($model->status == 0) { ?>
            <div class="time-box__trener" style="color: red; font-weight: bold; margin-top: 10px;">Занятие отменено</div>
        <?php } elseif($model->status == 20) { ?>
            <div class="time-box__trener" style="color: red; font-weight: bold; margin-top: 10px;">Замена тренера</div>
        <?php } ?>

    </div>
</div>

<div class="time-box-hint" id="time-box-hint-<?= $model->id ?>">
	<div class="time-box-hint__overflow"></div>
	<div class="time-box-hint__wrap">
		<a href="#" class="time-box-hint__close"></a>
		<div class="time-box-hint__head">
			<div class="time-box-hint__title"><?= $model->programClasses->title ?></div>
            <?php if($model->is_new) { ?>
			<div class="time-box-hint__label">New!</div>
            <?php } ?>
		</div>
		<div class="time-box-hint__text item-page">
			<p><?= $model->comment ?></p>
		</div>
		<div class="time-box-hint__time"><?= $model->programClasses->duration ?></div>
        <?php if($model->trainer) { ?>
		<div class="time-box-hint__person"><a href="/ff/command/<?= $model->trainer->alias ?>/" target="_blank"><?= $model->trainer ? $model->trainer->title : '' ?></a></div>
        <?php } ?>
	</div>
</div>

<?php
use yii\helpers\Url;

?>
<main class="main-section main-section--tv schedule-page-tv">
    <div class="container2">

		<div class="schedule-list__line">
			<div class="schedule-list__row">
                <?php foreach ($rasp as $model) { ?>
                    <div class="day-col">
                        <div class="time-box" style="background-color: <?= $model->programClasses->color ?>">
                            <?php if($model->is_new) { ?>
                                <img src="/images/label-new.png" alt="" class="time-box__label"/>
                            <?php } ?>
                            <div class="time-box__head">
                                <div class="time-box__time"><span><?= substr($model->time, 0, 5) ?></span></div>
                                <ul class="time-box__buttons">
                                    <?php if ($model->is_preliminary) { ?>
                                        <li><img src="<?= Url::to('/images/icon-phone-brown.svg') ?>" alt=""></li>
                                    <?php }if ($model->is_pay) { ?>
                                        <li>₽</li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <div class="time-box__zal"><?= $model->rooms->title ?></div>
                            <div class="time-box__name"><?= $model->programClasses->title ?></div>
                            <div class="time-box__traner">
                                <?php if($model->trainer) { ?>
                                    <a href="/es/command/<?= $model->trainer->alias ?>/" target="_blank">
                                        <?= $model->trainer->title ?>
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
			</div>
		</div>


    </div>
</main>
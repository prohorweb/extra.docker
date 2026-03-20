<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;

/* @var $model \common\models\Events */
Yii::$app->formatter->locale = 'ru-RU';

?>

<div class="event col-md-6 mb-5">
    <div class="row">
        <div class="event__img col-lg-6 mb-3">
            <a href="<?= Url::to(['/es/events/' . $model['alias']]) ?>">
                <img class="w-100" src="<?= $model['img'] ? '/uploads/image/event/' . $model['img'] : '//placehold.it/450x300' ?>" alt="" />
            </a>
        </div>
        <div class="event-blog__item col-lg-6">
            <div class="event-block">
                <div class="event-block__date d-flex align-items-center mb-3">
                    <h3 class="m-0"><?= (new DateTime($model['date']))->format('j') ?></h3>
                    <span class="ms-2"><?= IntlDateFormatter::formatObject(new DateTime($model['date']), 'MMMM', 'ru-RU') ?></span>
                    <span class="ms-2"><?= (new DateTime($model['date']))->format('Y') ?> года</span>
                </div>
                <!-- <div class="product-block__labels">
                <?php if ($model['is_open']) { ?>
                    <span>По записи</span>
                <?php }
                if ($model['is_pay']) { ?>
                    <span>Платное</span>
                <?php } ?>
            </div> -->
                <div class="event-block__wrap">
                    <h3 class="event-block__title title-h3"><a href="<?= Url::to(['/es/events/' . $model['alias']]) ?>"><?= Html::encode($model['title']) ?></a></h3>
                    <div class="event-block__text text-break"><?= Html::encode($model['intro']) ?></div>
                    <div class="event-block__button my-3"><a href="<?= Url::to(['/es/events/' . $model['alias']]) ?>" class="btn btn-primary btn-lg "><span>Подробнее <i class="fa-thin fa-angles-right"></i></span></a></div>
                </div>
            </div>
        </div>
    </div>
</div>



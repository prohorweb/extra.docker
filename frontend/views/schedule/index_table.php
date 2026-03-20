<?php

use common\models\Rasp;
use kartik\depdrop\DepDrop;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use Codeception\Lib\Console\Message;


/* @var $this yii\web\View */
/* @var $id_typeRasp integer */
/* @var $model array */
/* @var $arr_time array */
/* @var $seo \common\models\Seo */

$this->title = $seo->title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $seo->keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $seo->description]);

Yii::$app->formatter->locale = 'ru-RU';

$firstDayWeek = (new DateTime())->setISODate($year, $week);
$lastDayWeek = (new DateTime())->setISODate($year, $week, 7);
$fixFirstDayWeek = (new DateTime())->setISODate((new DateTime())->format('o'), (new DateTime())->format('W'));
$fixLastDayWeek = (new DateTime())->setISODate((new DateTime())->format('o'), (new DateTime())->format('W'), 7);
$prevWeek = (new DateTime())->setISODate($year, $week, -6);
$nextWeek = (new DateTime())->setISODate($year, $week, +8);

$rasp = new \common\models\Rasp();
$rasp->group_programs_id = Yii::$app->session->get('group_programs_id');
$rasp->program_classes_id = Yii::$app->session->get('program_classes_id');
$rasp->trainer_id = Yii::$app->session->get('trainer_id');

$this->registerJs(<<<JS
    checkPosition();

    function checkPosition(){
        if (window.matchMedia('(max-width: 1200px)').matches) {
            window.location.replace('list/' + window.location.search);
        }
    }
JS
, $this::POS_HEAD);
?>
<section class="breadcramb-section breadcramb-section--pb0">
    <div class="container">
        <div class="breadcramb">
            <a href="/">Главная страница</a>
            <a href="<?= Url::to(['/']) ?>"><?= $this->params['club']->title ?></a>
            <span>Расписание</span>
        </div>
    </div>
</section>

<main class="main-section main-section--schedule schedule-page">
    <div class="container container--min">
        <h1 class="schedule-page__title title-h1">Расписание клуба <?= $this->params['club']->title ?></h1>

        <div class="schedule-page__dates row row--center">
            <div class="col-auto"><a href="<?= Url::to('?id=' . $id_typeRasp . '&week=' . (new DateTime())->format('W') . '&year=' . (new DateTime())->format('o')) ?>#schedule" class="<?= (new DateTime())->format('W') == $firstDayWeek->format('W')  ? 'active' : '' ?>"><?= Yii::$app->formatter->asDate($fixFirstDayWeek, 'php:j F') ?> — <?= Yii::$app->formatter->asDate($fixLastDayWeek, 'php:j F') ?></a></div>
            <div class="col-auto"><a href="<?= Url::to('?id=' . $id_typeRasp . '&week=' . (new DateTime())->modify('+7 day')->format('W') . '&year=' . (new DateTime())->modify('+7 day')->format('o')) ?>#schedule" class="<?= (new DateTime())->modify('+7 day')->format('W') == $firstDayWeek->format('W')  ? 'active' : '' ?>"><?= Yii::$app->formatter->asDate($fixFirstDayWeek->modify('+7 day'), 'php:j F') ?> — <?= Yii::$app->formatter->asDate($fixLastDayWeek->modify('+7 day'), 'php:j F') ?></a></div>
            <div class="col-auto"><a href="<?= Url::to('?id=' . $id_typeRasp . '&week=' . (new DateTime())->modify('+14 day')->format('W') . '&year=' . (new DateTime())->modify('+14 day')->format('o')) ?>#schedule" class="<?= (new DateTime())->modify('+14 day')->format('W') == $firstDayWeek->format('W')  ? 'active' : '' ?>"><?= Yii::$app->formatter->asDate($fixFirstDayWeek->modify('+7 day'), 'php:j F') ?> — <?= Yii::$app->formatter->asDate($fixLastDayWeek->modify('+7 day'), 'php:j F') ?></a></div>
        </div>
    </div>

    <div class="schedule-page__filter">
        <div class="container container--min">
            <?php $form = ActiveForm::begin(['options' => ['class' => 'schedule-page__filter__row row row--between row--middle']]) ?>
            <?= $form->field($rasp, 'group_programs_id', ['options' => ['class' => 'schedule-page__filter__item col col-xs-12 xs-mb25']])->dropDownList(ArrayHelper::map($groupPrograms, 'id', 'title'), ['enableCientValidation' => false, 'id' => 'group-id', 'prompt' => ['text' => 'Все направления', 'options' => ['value' => '0']], 'class' => 'select-border select--small'])->label(false)->error(false) ?>

            <?= $form->field($rasp, 'program_classes_id', ['options' => ['class' => 'schedule-page__filter__item col col-xs-12 xs-mb25']])->widget(DepDrop::classname(), [
                'options' => ['enableCientValidation' => false, 'id' => 'program-id', 'class' => 'select-border select--small', 'placeholder' => $rasp->program_classes_id ? \common\models\ProgramClasses::findOne($rasp->program_classes_id)['title'] : 'Все занятия'],
                'pluginOptions' => [
                    'depends' => ['group-id'],
                    'placeholder' => 'Все занятия',
                    'url' => Url::to('/schedule/subcat/')
                ]
            ])->label(false)->error(false) ?>

            <?= $form->field($rasp, 'trainer_id', ['options' => ['class' => 'schedule-page__filter__item col col-xs-12']])->dropDownList(ArrayHelper::map(Rasp::getAllTrainers(), 'id', 'title'), ['enableCientValidation' => false, 'id' => 'trainer_id', 'prompt' => ['text' => 'Все тренеры', 'options' => ['value' => '0']], 'class' => 'select-border select--small'])->label(false)->error(false) ?>

            <div class="schedule-page__filter__buttons col-auto">
                <button type="submit" class="btn btn--xs btn--black-full">Принять</button>
                <button type="submit" name="reset" class="btn btn--xs">Сбросить</button>
            </div>
            <div class="schedule-page__filter__type col">
                <a href="#" class="btn-type btn-type--block active"></a>
                <a href="<?= Url::to('/schedule/list/?id=' . $id_typeRasp . '&week=' . $week . '&year=' . $year) ?>" class="btn-type btn-type--list"></a>
            </div>
            <?php ActiveForm::end() ?>
        </div>
    </div>

    <div class="schedule-page__body">
        <div class="container container--min">
            <div class="schedule-table">

                <div class="schedule-table__head">
                    <div class="schedule-table__time"></div>
                    <div class="schedule-table__rows">
                        <?php while ($firstDayWeek <= $lastDayWeek) { ?>
                            <div class="day-col"><?= Message::ucfirst(Yii::$app->formatter->asDate($firstDayWeek, 'php:l')) ?>
                                <br> <?= Yii::$app->formatter->asDate($firstDayWeek, 'php:j') ?> <span><?= Yii::$app->formatter->asDate($firstDayWeek, 'php:F') ?></span></div>
                            <?php
                            $firstDayWeek->modify('+1 day');
                        } ?>
                    </div>
                </div>

                <?php $firstDayWeek->modify('-7 day');
                foreach ($arr_time as $key => $time) { ?>
                    <div class="schedule-table__line">
                        <div class="schedule-table__time"><span><?= substr($time, 0, 5) ?></span></div>
                        <div class="schedule-table__rows">
                            <?php
                            while ($firstDayWeek <= $lastDayWeek) {
                                $arr = ArrayHelper::map($model[$firstDayWeek->format('N') - 1], 'id', 'time', 'time'); ?>
                                <div class="day-col">
                                    <?php if (in_array($time, array_keys($arr))) {
                                        foreach ($arr[$time] as $key2 => $mod) {
                                            $lesson = ArrayHelper::index($model[$firstDayWeek->format('N') - 1], null, 'id')[$key2][0]; ?>
                                            <div class="time-box" <?= $lesson->programClasses->color ? 'style="border-color: ' . $lesson->programClasses->color . '"' : '' ?>>
												<a href="#" class="time-box__link" data-id="#time-box-hint-<?= $lesson->id ?>"></a>
                                                <?php if($lesson->is_new) { ?>
                                                <img src="/images/label-new.png" alt="" class="time-box__label"/>
                                                <?php } ?>
                                                <div class="time-box__head">
                                                    <div class="time-box__time"><?= substr($lesson->time, 0, 5) ?></div>
                                                    <div class="time-box__zal"><?= $lesson->rooms->title ?></div>
                                                    <ul class="time-box__buttons">
                                                        <?php if ($lesson->is_preliminary) { ?>
                                                            <li><img src="<?= Url::to('/images/time-box-phone.png') ?>" alt=""/></li>
                                                        <?php }if ($lesson->is_pay) { ?>
                                                            <li><img src="<?= Url::to('/images/time-box-rub.png') ?>" alt=""/></li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>

                                                <div class="time-box__name"><?= $lesson->programClasses->title ?></div>
                                                <div class="time-box__trener">
                                                    <?php if($lesson->trainer) { ?>
                                                        <a href="<?= Url::to(['/es/command/' . $lesson->trainer->alias]) ?>" target="_blank"><?= $lesson->trainer->title ?></a>
                                                    <?php } ?>
                                                </div>

                                                <?php if($lesson->status == 0) { ?>
                                                    <div class="time-box__trener" style="color: red; font-weight: bold; margin-top: 5px;">Занятие отменено</div>
                                                <?php } elseif($lesson->status == 20) { ?>
                                                    <div class="time-box__trener" style="color: red; font-weight: bold; margin-top: 5px;">Замена тренера</div>
                                                <?php } ?>
                                            </div>

											<div class="time-box-hint" id="time-box-hint-<?= $lesson->id ?>">
												<div class="time-box-hint__overflow"></div>
												<div class="time-box-hint__wrap">
													<a href="#" class="time-box-hint__close"></a>
													<div class="time-box-hint__head">
                                                        <div class="time-box-hint__title"><?= $lesson->programClasses->title ?></div>
                                                        <?php if($lesson->is_new) { ?>
                                                        <div class="time-box-hint__label">New!</div>
                                                        <?php } ?>
													</div>
													<div class="time-box-hint__text item-page">
														<p><?= $lesson->comment ?></p>
													</div>
													<div class="time-box-hint__time"><?= $lesson->programClasses->duration ?></div>
													<div class="time-box-hint__person"><?= $lesson->trainer ? $lesson->trainer->title : '' ?></div>
												</div>
											</div>
                                        <?php }
                                    } else { ?>
                                        <div class="time-box"></div>
                                    <?php }
                                    ?>
                                </div>
                                <?php
                                $firstDayWeek->modify('+1 day');
                            } ?>
                        </div>
                    </div>
                    <?php
                    $firstDayWeek->modify('-7 day');
                } ?>
            </div>
        </div>
    </div>
</main>

<?php
$this->registerJs(<<<JS
    $(window).resize(function(){
        checkPosition();
    });
JS
);

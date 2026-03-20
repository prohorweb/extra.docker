<?php

use common\models\Rasp;
use kartik\depdrop\DepDrop;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $id_typeRasp integer */
/* @var $model array */
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
?>
<main class="main-section main-section--schedule schedule-page">
    <div class="container container--min">

        <h1 class="schedule-page__title title-h1">Расписание клуба <?= $this->params['club']->title ?></h1>

        <div class="schedule-page__dates row row--center">
            <div class="col-auto"><a href="<?= Url::to('?id=' . $id_typeRasp . '&week=' . (new DateTime())->format('W') . '&year=' . (new DateTime())->format('o')) ?>#schedule" class="<?= (new DateTime())->format('W') == $firstDayWeek->format('W')  ? 'active' : '' ?>"><?= Yii::$app->formatter->asDate($fixFirstDayWeek, 'php:j F') ?> — <?= Yii::$app->formatter->asDate($fixLastDayWeek, 'php:j F') ?></a></div>
            <div class="col-auto"><a href="<?= Url::to('?id=' . $id_typeRasp . '&week=' . (new DateTime())->modify('+7 day')->format('W') . '&year=' . (new DateTime())->modify('+7 day')->format('o')) ?>#schedule" class="<?= (new DateTime())->modify('+7 day')->format('W') == $firstDayWeek->format('W')  ? 'active' : '' ?>"><?= Yii::$app->formatter->asDate($fixFirstDayWeek->modify('+7 day'), 'php:j F') ?> — <?= Yii::$app->formatter->asDate($fixLastDayWeek->modify('+7 day'), 'php:j F') ?></a></div>
            <div class="col-auto"><a href="<?= Url::to('?id=' . $id_typeRasp . '&week=' . (new DateTime())->modify('+14 day')->format('W') . '&year=' . (new DateTime())->modify('+14 day')->format('o')) ?>#schedule" class="<?= (new DateTime())->modify('+14 day')->format('W') == $firstDayWeek->format('W')  ? 'active' : '' ?>"><?= Yii::$app->formatter->asDate($fixFirstDayWeek->modify('+7 day'), 'php:j F') ?> — <?= Yii::$app->formatter->asDate($fixLastDayWeek->modify('+7 day'), 'php:j F') ?></a></div>
            <?php
            $fixFirstDayWeek->modify('-14 day');
            $fixLastDayWeek->modify('-14 day');
            ?>
            <select name="" id="" class="none visible-xs select-noborder-white" onchange="if (this.value) window.location.href=this.value">
                <option value="<?= Url::to('?id=' . $id_typeRasp . '&week=' . (new DateTime())->format('W') . '&year=' . (new DateTime())->format('o')) ?>#schedule" <?= (new DateTime())->format('W') == $firstDayWeek->format('W')  ? 'selected' : '' ?>><?= Yii::$app->formatter->asDate($fixFirstDayWeek, 'php:j F') ?> — <?= Yii::$app->formatter->asDate($fixLastDayWeek, 'php:j F') ?></option>
                <option value="<?= Url::to('?id=' . $id_typeRasp . '&week=' . (new DateTime())->modify('+7 day')->format('W') . '&year=' . (new DateTime())->modify('+7 day')->format('o')) ?>#schedule" <?= (new DateTime())->modify('+7 day')->format('W') == $firstDayWeek->format('W')  ? 'selected' : '' ?>><?= Yii::$app->formatter->asDate($fixFirstDayWeek->modify('+7 day'), 'php:j F') ?> — <?= Yii::$app->formatter->asDate($fixLastDayWeek->modify('+7 day'), 'php:j F') ?></option>
                <option value="<?= Url::to('?id=' . $id_typeRasp . '&week=' . (new DateTime())->modify('+14 day')->format('W') . '&year=' . (new DateTime())->modify('+14 day')->format('o')) ?>#schedule" <?= (new DateTime())->modify('+14 day')->format('W') == $firstDayWeek->format('W')  ? 'selected' : '' ?>><?= Yii::$app->formatter->asDate($fixFirstDayWeek->modify('+7 day'), 'php:j F') ?> — <?= Yii::$app->formatter->asDate($fixLastDayWeek->modify('+7 day'), 'php:j F') ?></option>
            </select>
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

                <?= $form->field($rasp, 'trainer_id', ['options' => ['class' => 'schedule-page__filter__item col col-xs-12']])->dropDownList(ArrayHelper::map(Rasp::getAllTrainers(), 'id', 'title'), ['enableCientValidation' => false, 'id' => 'group-id', 'prompt' => ['text' => 'Все тренеры', 'options' => ['value' => '0']], 'class' => 'select-border select--small'])->label(false)->error(false) ?>

                <div class="schedule-page__filter__buttons col-auto">
                    <button type="submit" class="btn btn--xs btn--black-full">Принять</button>
                    <button type="submit" name="reset" class="btn btn--xs">Сбросить</button>
                </div>
                <div class="schedule-page__filter__type col">
                    <a href="<?= Url::to('/schedule/?id=' . $id_typeRasp . '&week=' . $week . '&year=' . $year) ?>" class="btn-type btn-type--block"></a>
                    <a href="#" class="btn-type btn-type--list  active"></a>
                </div>
                <?php ActiveForm::end() ?>
            </div>
        </div>

        <div class="schedule-page__body">
            <div class="container container--min">

                <div class="schedule-list-days">
                    <?php while ($firstDayWeek <= $lastDayWeek) { ?>
                    <div class="schedule-list-days__item">
                        <div class="list-day">
                            <a href="#day-<?= $firstDayWeek->format('N') ?>" class="list-day__link"></a>
                            <div class="list-day__day"><?= Yii::$app->formatter->asDate($firstDayWeek, 'php:D') ?></div>
                            <div class="list-day__date"><strong><?= Yii::$app->formatter->asDate($firstDayWeek, 'php:j') ?></strong> <span class="list-day__month"><?= Yii::$app->formatter->asDate($firstDayWeek, 'php:F') ?></span></div>
                        </div>
                    </div>
                        <?php
                        $firstDayWeek->modify('+1 day');
                    } ?>
                </div>
                <hr>

                <div class="schedule-list">
                <?php
                foreach ($model as $key => $day) {
                    if ($day) { ?>
                        <div class="schedule-list__line" id="day-<?= $key + 1 ?>">
                            <div class="schedule-list__title title-h3"><?= Yii::$app->formatter->asDate($day[0]->date, 'php:l, j F') ?></div>
                            <div class="schedule-list__row">
                                <?php foreach ($day as $model) {
                                    echo $this->render('_timebox', ['model' => $model]);
                                } ?>
                            </div>
                        </div>
                    <?php }
                } ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php if (Yii::$app->session->hasFlash('mailerFormSubmitted')) : ?>
    <div class="popup_wrap" id="finish-popup" style="display: block;">
        <div class="popup">
            <a href="#" class="popup__close close icon-close"></a>
            <div class="popup__content">
                <div class="popup__title"><?= Yii::$app->session->getFlash('mailerFormSubmitted') ?></div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php $this->registerJs(<<<JS
    $(document).ready(function () {
        $(".list-day__link").click(function () {
            var href = $(this).attr("href");
            $(".schedule-list__line").hide();
            $(href).show();
            return false;
        });
    });
JS
);
<?php

/* @var $this yii\web\View */
/* @var $firstDayMonth DateTime */
/* @var $lastDayMonth DateTime */
/* @var $yearMonth string */
/* @var $typeRasp \common\models\TypeRasp */

use common\models\Weeks;
use yii\helpers\Html;
use yii\helpers\Url;
use common\widgets\Alert;

$this->title = 'Расписание клуба';
//$this->params['breadcrumbs'][] = $this->title;

if(Yii::$app->session->getAllFlashes()) {
    $this->registerJs(<<<JS
	window.setTimeout(function() { jQuery(".message-box").alert('close'); }, 2000);
JS
);
}
?>
<div class="content-box clearfix">
    <?= Alert::widget(['closeButton' => false, 'options' => ['class' => 'message-box']]) ?>
    <div class="title-box clearfix">
        <h1 class="page-title fleft"><?= Html::encode($this->title) ?></h1>
        <a href="<?= Url::to(['params']) ?>" class="btn btn--grey fright">Параметры</a>
    </div>
    <div class="wrapper">
        <div class="calendar-wrapper__month">
            <a href="?yearMonth=<?= date('Y-m', strtotime("-1 month", strtotime($yearMonth . '-01'))) ?>" class="month-prev flaticon-prev-arrow"></a>
            <span><?= Yii::$app->formatter->asDate($yearMonth. '-01', 'LLLL Y') ?></span>
            <a href="?yearMonth=<?= date('Y-m', strtotime("+1 month", strtotime($yearMonth . '-01'))) ?>" class="month-next flaticon-next-arrow"></a>
        </div>
        <div class="calendar-wrapper__days">
            <div class="row">
                <div class="col span5">
                    <div class="calendar-days">
                        <div class="calendar-days__day">ПН</div>
                        <div class="calendar-days__day">ВТ</div>
                        <div class="calendar-days__day">СР</div>
                        <div class="calendar-days__day">ЧТ</div>
                        <div class="calendar-days__day">ПТ</div>
                        <div class="calendar-days__day">СБ</div>
                        <div class="calendar-days__day">ВС</div>
                    </div>
                </div>
            </div>
        </div>

        <?php
        $i = 0;
        while ($firstDayMonth <= $lastDayMonth) { ?>
            <?= $i % 7 == 0 ? '<div class="calendar-wrapper__calendar"><div class="row"><div class="calendar-box col span5">' : '' ?>
                <div class="calendar-box__day <?= (new DateTime())->format('j n y') == $firstDayMonth->format('j n y') && (new DateTime())->format('n') == DateTime::createFromFormat('Y-m-d', $yearMonth. '-01')->format('n') ? 'curday' : '' ?> <?= $firstDayMonth->format('n') != DateTime::createFromFormat('Y-m-d', $yearMonth. '-01')->format('n') ? 'noday' : '' ?>"><a href="#"><?= $firstDayMonth->format('j') ?></a></div>
            <?php if ($i % 7 == 6) { ?>
                </div>
                <div class="col span7 calendar-wrapper__info">
                    <?php foreach ($typeRasp as $type) {
                        $week = intval($firstDayMonth->format('W'));
                        $year = $firstDayMonth->format('o');
                        $weeks = Weeks::findOne(['week' => $week, 'year' => $year, 'type_rasp_id' => $type->id]); ?>
                        <p><a href="<?= Url::to(['view', 'id' => $type->id, 'week' => $week, 'year' => $year]) ?>" class="<?= $weeks && !$weeks->is_empty ? '' : 'color_red' ?>"><?= $type->title ?></a></p>
                    <?php } ?>
                </div>
            <?php } ?>
            <?= $i % 7 == 6 ? '</div></div>' : '' ?>
            <?php $firstDayMonth->modify('+1 day');
            $i++;
        } ?>
    </div>
</div>
<?php
$this->registerJs(<<<JS

JS
);
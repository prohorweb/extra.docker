<?php

$interval = new DateInterval('P1D');
$daterange = new DatePeriod(new DateTime('monday this week'), $interval, new DateTime('monday next week'));

?>
<main class="main-section main-section--tv schedule-page-tv">
    <div class="container">

		<div class="tv-select-day">
			<div class="tv-select-day__title">Выберите день недели</div>
			<div class="schedule-list-days tv-select-day__list">
                <?php foreach($daterange as $date) { ?>
				<div class="schedule-list-days__item">
					<div class="list-day"><a href="day/?id=<?= $date->format('N') ?>" class="list-day__link"></a><div class="list-day__day"><?= Yii::$app->formatter->asDate($date, 'php:D') ?></div><div class="list-day__date"><strong><?= $date->format("j") ?></strong> <span class="list-day__month"><?= Yii::$app->formatter->asDate($date, 'php:F') ?></span></div></div>
				</div>
                <?php } ?>
			</div>
		</div>

    </div>
</main>
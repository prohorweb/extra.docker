<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $subject string */
/* @var $event \common\models\Events */

$post = Yii::$app->request->post();
?>
<div>
    <p><?= $subject ?></p>
    <hr>

    <?php if (isset($event)) { ?>
        <p><?= $event->title ?></p>

        <p><?= Yii::$app->formatter->asDate($event->date) ?></p>
    <?php } ?>

    <?php if (isset($post['title'])) { ?>
    <p><?= Html::encode($post['title']) ?></p>
    <?php } ?>

    <p>Имя: <?= Html::encode($post['name']) ?></p>

    <p>Телефон: <?= Html::encode($post['tel']) ?></p>

    <p>Цена: <?= Html::encode($post['price']) ?></p>

    <p><?= Html::encode($post['month']) ?></p>

    <hr>
    <p>Отправлено: <?= Yii::$app->formatter->asDatetime('now') ?></p>

    <p>Со страницы: <?= $post['url'] ?></p>

</div>

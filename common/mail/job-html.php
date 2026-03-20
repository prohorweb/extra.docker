<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $subject string */

$post = Yii::$app->request->post();
?>
<div>
    <p><?= Yii::$app->view->params['club']->title ?></p>
    <p>Отклик на вакансию <?= $post['title'] ?></p>
    <hr>

    <p>Имя: <?= Html::encode($post['name']) ?></p>

    <p>Мобильный телефон: <?= Html::encode($post['tel']) ?></p>

    <hr>
    <p>Отправлено: <?= Yii::$app->formatter->asDatetime('now') ?></p>
</div>

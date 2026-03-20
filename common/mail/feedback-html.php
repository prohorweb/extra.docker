<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $subject string */

$post = Yii::$app->request->post();
?>
<div>
    <p><?= $subject ?></p>
    <hr>

    <p>Имя: <?= Html::encode($post['name']) ?></p>

    <p>Мобильный телефон: <?= Html::encode($post['tel']) ?></p>

    <p>E-mail: <?= Html::encode($post['email']) ?></p>

    <p>Текст сообщения: <?= $post['text'] ?></p>

    <hr>
    <p>Отправлено: <?= Yii::$app->formatter->asDatetime('now') ?></p>

    <p>Со страницы: <?= $post['url'] ?></p>
</div>

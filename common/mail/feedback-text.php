<?php

/* @var $this yii\web\View */
/* @var $subject string */

$post = Yii::$app->request->post();
?>

<?= $subject ?>
- - - - - - - - - - - - - -

Имя: <?= $post['name'] ?>

Мобильный телефон: <?= $post['tel'] ?>,

E-mail: <?= $post['email'] ?>

Текст сообщения: <?= $post['text'] ?>


- - - - - - - - - - - - - -
Отправлено: <?= Yii::$app->formatter->asDatetime('now') ?>

Со страницы: <?= $post['url'] ?>
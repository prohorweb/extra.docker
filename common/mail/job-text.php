<?php

/* @var $this yii\web\View */
/* @var $subject string */

$post = Yii::$app->request->post();
?>

<?= Yii::$app->view->params['club']->title ?>

Отклик на вакансию <?= $post['title'] ?>
- - - - - - - - - - - - - -

Имя: <?= $post['name'] ?>

Мобильный телефон: <?= $post['tel'] ?>,

- - - - - - - - - - - - - -
Отправлено: <?= Yii::$app->formatter->asDatetime('now') ?>

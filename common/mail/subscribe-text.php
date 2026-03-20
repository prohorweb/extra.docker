<?php

/* @var $this yii\web\View */
use yii\helpers\Url;

/* @var $subject string */
/* @var $event \common\models\Events */

$post = Yii::$app->request->post();
?>

<?= $subject ?>
- - - - - - - - - - - - - -

<?php if (isset($event)) { ?>
    <?= $event->title ?>

    <?= Yii::$app->formatter->asDate($event->date) ?>
<?php } ?>

<?php if (isset($post['title'])) { ?>
    <?= $post['title'] ?>
<?php } ?>

Имя: <?= $post['name'] ?>,

Телефон: <?= $post['tel'] ?>,

<?php if (isset($post['email'])) { ?>
E-mail: <?= $post['email'] ?>
<?php } ?>

- - - - - - - - - - - - - -
Отправлено: <?= Yii::$app->formatter->asDatetime('now') ?>

Со страницы: <?= $post['url'] ?>

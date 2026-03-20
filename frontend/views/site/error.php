<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
if ($exception->statusCode == '404') {
    $this->title = "К сожалению, страница не найдена!";
    $message = "Возможно страница не существует или не верно указана ссылка.";
}
?>

<section class="content-line">
    <div class="container clearfix">
        <div class="white-wrap box-404">
            <h1 class="box-404__title"><?= Html::encode($this->title) ?></h1>
            <p class="box-404__text"><?= nl2br(Html::encode($message)) ?></p>
            <?php if($exception->statusCode == '404') { ?>
            <p class="box-404__img"><img src="/images/404-img.png" alt=""></p>
            <p class="box-404__bottom">Перейдите на <a href="/">главную страницу</a> или проверьте ссылку.</p>

            <script type="text/javascript">
                var yaParams = {URL: document.location.href};
                window.onload = function() {yaCounter21525628.reachGoal('error404', yaParams)}
            </script>
            <?php } ?>
        </div>
    </div>
</section>

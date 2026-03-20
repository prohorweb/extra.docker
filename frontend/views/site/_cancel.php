<?php

/* @var $this yii\web\View */

use common\widgets\Alert;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Отмена оплаты банковской картой';
?>

<main class='main-section article-blog__title'>
    <div class="container">
        <img src='/images/smile-neg.png'/>
        <h1 class="title-h1">Оплата не прошла!</h1>
        <p>Попробуйте, пожалуйста, снова.</p>
        </br></br>
        <p>Вернуться к выбору <a class='text_d_under' href='<?= Url::to(['/card/type/']) ?>'>Абонемента</a></p>
        <p>Вернуться к выбору <a class='text_d_under' href='<?= Url::to(['/card/shares/']) ?>'>Акций</a></p>
    </div>
</main>

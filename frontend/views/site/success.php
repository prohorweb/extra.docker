<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Подтверждения списания с личного счета';
?>
<main class='main-section article-blog__title'>
    <div class="container">
        <img src='/images/smile-pos.png'/>
        <h1 class="title-h1">Оплата прошла успешно!</h1>
        <p>В ближайщее время с Вами свяжется менеджер и расскажет все подробности.</p>
        <p>Также на Вашу почту отправлено письмо с информацией о покупке</p>
        </br></br>
        <p>Перейти на <a class='text_d_under' href='/'>главную страницу</a>
            </br> или в раздел <a class='text_d_under' href='<?= Url::to(['/contacts/']) ?>'>контакты</a>
        </p>
    </div>
</main>

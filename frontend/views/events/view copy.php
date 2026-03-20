<?php

use richardfan\widget\JSRegister;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\HtmlPurifier;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model \common\models\Events */

$this->title = $model->meta_title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $model->meta_keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $model->meta_description]);

//Yii::$app->formatter->locale = 'ru-RU';
?>


<main class="main-section main-section--article-blog article-page">
    <div class="container">

        <a href="<?= Url::to(['/es/events/']) ?>" class="article-page__back btn btn--back"><span>НАЗАД</span></a>

        <div class="article-page__wrap">
            <h1 class="article-page__title title-h1"><?= Html::encode($model->title) ?></h1>
            <div class="article-page__date">
                <strong><?= (new DateTime($model['date']))->format('j') ?></strong> <?= IntlDateFormatter::formatObject(new DateTime($model['date']), 'MMMM', 'ru-RU') ?> <span><?= (new DateTime($model['date']))->format('Y') ?> года</span>
            </div>
            <?php if ($model['is_open'] || $model['is_pay']) { ?>
            <div class="article-page__labels">
                <?php if ($model['is_open']) { ?>
                <span>По записи</span>
                <?php }
                if ($model['is_pay']) { ?>
                <span>Платное</span>
                <?php } ?>
            </div>
            <?php } ?>
            <div class="item-page">
                <?= HtmlPurifier::process($model->content) ?>
            </div>
        </div>

    </div>
</main>

<?php if ($model->is_pay || $model->is_open) { ?>
    <section class="form-section form-section--zvon" id="zvon-section">
        <div class="form-section__wrap">
            <div class="form-section__title">Зарегистрироваться на мероприятие</div>
            <?php $form = ActiveForm::begin(['id' => 'register', 'action' => Url::to(['/events/subscribe?id=' . $model->id]), 'options' => ['onsubmit' => "dataLayer.push({'event': 'zayavka'});", 'class' => 'form-section__form']]) ?>
            <div class="form-section__row row">
                <div class="form-section__item col-6 col-xs-12 input-row">
                    <?= Html::textInput('name', null, ['class' => 'inputbox inputbox--border', 'placeholder' => 'Ваше имя *', 'required' => true]) ?>
                </div>
                <div class="form-section__item col-6 col-xs-12 input-row">
                    <?= MaskedInput::widget([
                        'options' => ['class' => 'inputbox inputbox--border', 'placeholder' => 'Ваш телефон *'],
                        'name' => 'tel',
                        'mask' => '+7 999 999 99 99',
                    ]) ?>
                </div>
            </div>
            <div class="form-section__soglas">
                <div class="checkbox">
                    <?= Html::checkbox('accept', false, ['id' => 'soglas-2']) ?>
                    <label for="soglas-2">Ознакомлен с <a href="/privacy/" target="_blank">политикой конфиденциальности</a></label>
                </div>
            </div>
            <div class="form-section__button">
                <button type="submit" class="btn btn--lg">Регистрация</button>
            </div>
            <?= Html::hiddenInput('url', Yii::$app->getRequest()->getAbsoluteUrl()) ?>
            <?php ActiveForm::end() ?>
        </div>
    </section>
<?php } ?>

<?php
JSRegister::begin(); ?>
<script>
    $('#register').on('beforeValidate', function (e) {
        $('.help-block').remove();
        var ret = true;

        if($(this)[0][1].value.length == 0){
            $(this).find('div.input-row:first').append('<div class="help-block" style="color: #ff0000;">Поле имя не может быть пустым</div>');
            ret = false;
        } else if($(this)[0][1].value.match(/[^а-яё ]+/gi)){
            $(this).find('div.input-row:first').append('<div class="help-block" style="color: #ff0000;">Поле имя должно содержать только буквы кириллицы</div>');
            ret = false;
        }
        if($(this)[0][2].value.length == 0){
            $(this).find('div.input-row:last').append('<div class="help-block" style="color: #ff0000;">Поле телефон не может быть пустым</div>');
            ret = false;
        } else if($(this)[0][2].value.match(/[_]+/ig)) {
            $(this).find('div.input-row:last').append('<div class="help-block" style="color: #ff0000;">Поле телефон заполнено неправильно</div>');
            ret = false;
        }
        if(!$(this)[0][3].checked){
            $(this).find('div.checkbox').append('<div class="help-block" style="color: #ff0000;">Для продолжения, пожалуйста, установите флажок "Ознакомлен"</div>');
            ret = false;
        }

        return ret;
    });
</script>
<?php JSRegister::end();

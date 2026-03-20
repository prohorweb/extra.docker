<?php

use richardfan\widget\JSRegister;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use lajax\translatemanager\helpers\Language;

/* @var $this yii\web\View */

?>

<section class="form-section">
    <img src="/images/fitnes-test-section-bg.jpg" alt="" class="form-section__bg" />
    <div class="form-section__wrap">
        <div class="form-section__title title-h1">Персональный тренинг</div>
        <div class="form-section__text">Хотите эффективно достичь свою цель? Запишитесь на персональную тренировку в ExtraSport!</div>
        <?php $form = ActiveForm::begin(['id' => 'zapis', 'action' => Url::to(['/services/subscribe/']), 'options' => ['onsubmit' => (explode('.', $_SERVER['HTTP_HOST'])[0] == "piter" ? "gtag('event', 'zayavka', {'event_category': 'form'});" : "") . "ym(21525628, 'reachGoal', 'zayavka'); dataLayer.push({'event': 'zayavka'});", 'class' => 'form-section__form']]) ?>
        <div class="form-section__row row">
            <div class="form-section__item col-6 col-xs-12 input-row">
                <?= Html::textInput('name', null, ['class' => 'inputbox inputbox--border inputbox--white', 'placeholder' => 'Ваше имя *', 'required' => true]) ?>
            </div>
            <div class="form-section__item col-6 col-xs-12 input-row">
                <?= MaskedInput::widget([
                    'options' => ['class' => 'inputbox inputbox--border inputbox--white', 'placeholder' => 'Ваш телефон *'],
                    'name' => 'tel',
                    'mask' => '+7 999 999 99 99',
                ]) ?>
            </div>
        </div>
        <div class="form-section__soglas">
            <div class="checkbox checkbox--white">
                <?= Html::checkbox('accept', false, ['id' => 'soglas-1']) ?>
                <label for="soglas-1">Ознакомлен с <a href="<?= Url::to(['/privacy/']) ?>" target="_blank">политикой конфиденциальности</a></label>
            </div>
        </div>
        <div class="form-section__button"><button type="submit" class="btn btn--white btn--lg">Записаться</button></div>
        <?= Html::hiddenInput('url', Yii::$app->getRequest()->getAbsoluteUrl()) ?>
        <?php ActiveForm::end() ?>
    </div>
</section>

<?php
JSRegister::begin(); ?>
<script>
    jQuery('#zapis').on('beforeValidate', function (e) {
        jQuery('.help-block').remove();
        var ret = true;

        if(jQuery(this)[0][1].value.length == 0){
            jQuery('#zapis div.input-row:first').append('<div class="help-block" style="color: #ff0000;">Поле имя не может быть пустым</div>');
            ret = false;
        } else if(jQuery(this)[0][1].value.match(/[^а-яё ]+/gi)){
            jQuery('#zapis div.input-row:first').append('<div class="help-block" style="color: #ff0000;">Поле имя должно содержать только буквы кириллицы</div>');
            ret = false;
        }
        if(jQuery(this)[0][2].value.length == 0){
            jQuery('#zapis div.input-row:last').append('<div class="help-block" style="color: #ff0000;">Поле телефон не может быть пустым</div>');
            ret = false;
        } else if(jQuery(this)[0][2].value.match(/[_]+/ig)) {
            jQuery('#zapis div.input-row:last').append('<div class="help-block" style="color: #ff0000;">Поле телефон заполнено неправильно</div>');
            ret = false;
        }
        if(!jQuery(this)[0][3].checked){
            jQuery('#zapis div.checkbox').append('<div class="help-block" style="color: #ff0000;">Для продолжения, пожалуйста, установите флажок "Ознакомлен"</div>');
            ret = false;
        }

        return ret;
    });
</script>
<?php JSRegister::end();

<?php

use himiklab\yii2\recaptcha\ReCaptcha3;
use richardfan\widget\JSRegister;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use lajax\translatemanager\helpers\Language;

?>

<section class="d-flex justify-content-center align-items-center hight-100 test-drive" id="contact">
    <video muted loop autoplay class="w-100 d-none d-md-block  position-absolute">
        <source src="/video/test-drive.mp4" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
        <!-- <source src="video/bg_moution.webm" type='video/webm; codecs="vp8, vorbis"'> -->
    </video>
    <video muted loop autoplay class="w-100 d-block d-md-none  position-absolute">
        <source src="/video/test-drive_mobile.mp4" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
        <!-- <source src="video/bg_moution.webm" type='video/webm; codecs="vp8, vorbis"'> -->
    </video>
    <div class="container py-5">
        <h2 class="section-heading text-center text-uppercase">Фитнес тест-драйв</h2>
        <div class="row justify-content-center">
            <div class="col-lg-5 fs-5">
                <p>Хотите больше узнать о нашем клубе? Оставьте заявку, и наши менеджеры проведут для вас подробную
                    экскурсию. </p>
                <p>Для тех, кому экскурсии мало, мы предлагаем услугу «фитнес тест-драйв» — безлимитную неделю фитнеса!
                </p>
            </div>
            <div class="col-lg-3">
                <?php $form = ActiveForm::begin(['id' => 'subscribe', 'action' => Url::to(['/club/subscribe/']), 'options' => ['onsubmit' => (isset($_SESSION["_language"]) && $_SESSION["_language"] == "db" ? "gtag('event', 'zayavka', {'event_category': 'form'});" : "") . "ym(21525628, 'reachGoal', 'zayavka'); ym(21525628, 'reachGoal', 'test_drive'); dataLayer.push({'event': 'zayavka'}); return true;", 'class' => 'form-section__form']]) ?>
                <div class="form-group input-row">
                    <!-- Name input-->
                    <?= Html::textInput('name', null, ['class' => 'form-control', 'placeholder' => 'Ваше имя *'/*, 'required' => true*/]) ?>
                </div>
                <div class="form-group input-row">
                    <!-- Phone number input-->
                    <?= MaskedInput::widget([
                        'options' => ['class' => 'form-control', 'placeholder' => 'Ваш телефон *'/*, 'required' => true*/],
                        'name' => 'tel',
                        'mask' => '+7 999 999 99 99',
                    ]) ?>
                </div>
                <div class="checkbox">
                    <?= Html::checkbox('accept', false, ['id' => 'soglas']) ?>
                    <label class="d-inline" for="soglas">Ознакомлен с <a href="<?= Url::to(['/privacy/']) ?>"
                            target="_blank">политикой конфиденциальности</a></label>
                </div>
            </div>
        </div>
        <!-- Submit Button-->
        <div class="text-center">
            <button type="submit" class="btn btn-primary btn-xl text-uppercase mt-5">Записаться</button>
        </div>
        <?= ReCaptcha3::widget([
            'name' => 'reCaptcha',
            'siteKey' => '6Ld2_VopAAAAALCXizlp0XRDUqiFZuQdbrUmCBHl',
            'action' => '/club/subscribe/'
        ]) ?>
        <?= Html::hiddenInput('url', Yii::$app->getRequest()->getAbsoluteUrl()) ?>
        <?php ActiveForm::end() ?>
    </div>

    </div>
</section>

<?php
$this->registerJs(<<<JS
    jQuery('#subscribe').on('beforeValidate', function (e) {
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
JS
);
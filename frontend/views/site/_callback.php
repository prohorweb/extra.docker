<?php

use himiklab\yii2\recaptcha\ReCaptcha3;
use richardfan\widget\JSRegister;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

?>


    <!-- Modals-->
    <!-- Call modal popup-->
    <div class="modal fade popup-wrap" id="callModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="close-modal" data-bs-dismiss="modal"><button class="btn-close btn-close-white" type="button" aria-label="Close"></div>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="modal-body p-4">
                        <?php $form = ActiveForm::begin(['id' => 'callback', 'action' => Url::to(['/club/subscribe3/']), 'options' => ['onsubmit' => (isset($_SESSION["_language"]) && $_SESSION["_language"] == "db" ? "gtag('event', 'zayavka', {'event_category': 'form'});" : "") . "ym(21525628, 'reachGoal', 'zayavka'); ym(21525628, 'reachGoal', 'Order_call'); dataLayer.push({'event': 'zayavka'});return true;", 'class' => 'popup__form']]) ?>
                            <div class="row">
                                <div class="col-12 input-row form-group mb-4">
                                    <?= Html::textInput('name', null, ['class' => 'inputbox inputbox--border form-control', 'placeholder' => 'Ваше имя *'/*, 'required' => true*/]) ?>
                                </div>
                                <div class="col-12 input-row form-group mb-4">
                                    <?= MaskedInput::widget([
                                        'options' => ['class' => 'form-control', 'placeholder' => 'Ваш телефон *'/*, 'required' => true*/],
                                        'name' => 'tel',
                                        'mask' => '+7 999 999 99 99',
                                    ]) ?>
                                </div>
                            </div>
                            <div class="form-section__soglas mb-3">
                                <div class="checkbox">
                                    <?= Html::checkbox('accept', false, ['id' => 'soglas-22']) ?>
                                    <label class="d-inline" for="soglas-22">Ознакомлен с <a href="http://piter.extrasport.ru/privacy/" target="_blank">политикой конфиденциальности</a></label>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class=" btn btn-primary btn-lg">Заказать звонок</button>
                            </div>
                            <?= ReCaptcha3::widget([
                                'name' => 'reCaptcha',
                                'siteKey' => '6Ld2_VopAAAAALCXizlp0XRDUqiFZuQdbrUmCBHl',
                                'action' => '/club/subscribe3/'
                            ]) ?>
                            <?= Html::hiddenInput('url', Yii::$app->getRequest()->getAbsoluteUrl()) ?>
                            <?php ActiveForm::end() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
JSRegister::begin(); ?>
<script>
    jQuery('#callback').on('beforeValidate', function (e) {
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

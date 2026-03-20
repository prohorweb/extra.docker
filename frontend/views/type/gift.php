<?php

use himiklab\yii2\recaptcha\ReCaptcha3;
use richardfan\widget\JSRegister;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\HtmlPurifier;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */

$this->title = 'Подарочный сертификат';
//$this->registerMetaTag(['name' => 'keywords', 'content' => $model->meta_keywords]);
//$this->registerMetaTag(['name' => 'description', 'content' => $model->meta_description]);

?>
<section class="breadcramb-section">
    <div class="container">
        <div class="breadcramb">
            <a href="/">Главная страница</a>
            <a href="<?= Url::to(['/']) ?>"><?= $this->params['club']->title ?></a>
            <a href="<?= Url::to(['/card/type/']) ?>">Абонементы и цены</a>
            <span>Фитнес тест-драйв</span>
        </div>
    </div>
</section>

<section class="nav-section">
    <div class="container">
        <div class="nav-section__row">
            <div class="nav-section__item"><a href="<?= Url::to(['/card/type/']) ?>">Выбор абонемента</a></div>
            <div class="nav-section__item active"><a href="#">Фитнес тест-драйв</a></div>
        </div>
        <select name="" id="" class="nav-section__select select-noborder" onchange="if (this.value) window.location.href=this.value">
            <option value="<?= Url::to(['/card/type/']) ?>">Выбор абонемента</option>
            <option value="#" selected>Фитнес тест-драйв</option>
        </select>
    </div>
</section>

<section class="form-section form-section--white">
    <div class="form-section__wrap">
        <div class="form-section__title title-h1">Фитнес тест-драйв</div>
        <div class="form-section__text"><?= HtmlPurifier::process($gift_text) ?></div>
        <div class="form-section__subtitle title-h3">заявка на визит в <?= $this->params['club']->title ?></div>
        <?php $form = ActiveForm::begin(['id' => 'vizit', 'action' => Url::to(['/club/subscribe/']), 'options' => ['onsubmit' => "dataLayer.push({'event': 'zayavka'});", 'class' => 'form-section__form']]) ?>
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
                    <?= Html::checkbox('accept', false, ['id' => 'soglas']) ?>
                    <label for="soglas">Ознакомлен с <a href="/privacy/" target="_blank">политикой конфиденциальности</a></label>
                </div>
            </div>
            <div class="form-section__button">
                <button type="submit" class="btn btn--lg">Записаться</button>
            </div>
        <?= ReCaptcha3::widget([
            'name' => 'reCaptcha',
            'siteKey' => '6Ld2_VopAAAAALCXizlp0XRDUqiFZuQdbrUmCBHl',
            'action' => '/club/subscribe/'
        ]) ?>
        <?= Html::hiddenInput('url', Yii::$app->getRequest()->getAbsoluteUrl()) ?>
        <?php ActiveForm::end() ?>
    </div>
</section>

<?php
JSRegister::begin(); ?>
<script>
    $('#vizit').on('beforeValidate', function (e) {
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

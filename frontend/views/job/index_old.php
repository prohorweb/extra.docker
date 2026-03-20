<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\HtmlPurifier;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $seo \common\models\Seo */

$this->title = $seo->title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $seo->keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $seo->description]);

?>
<section class="breadcramb-section">
    <div class="container">
        <div class="breadcramb">
            <a href="/">Главная страница</a>
            <a href="<?= Url::to(['/']) ?>"><?= $this->params['club']->title ?></a>
            <a href="<?= Url::to(['/es/club/']) ?>">О клубе</a>
            <span>Вакансии</span>
        </div>
    </div>
</section>

<section class="nav-section">
    <div class="container">
        <div class="nav-section__row">
            <div class="nav-section__item"><a href="<?= Url::to(['/es/club/']) ?>">Обзор клуба</a></div>
            <div class="nav-section__item"><a href="<?= Url::to(['/es/command/']) ?>">тренеры</a></div>
            <div class="nav-section__item"><a href="<?= Url::to(['/es/history/']) ?>">истории успеха</a></div>
            <div class="nav-section__item active"><a href="#">Вакансии</a></div>
            <div class="nav-section__item"><a href="<?= Url::to(['/es/article/']) ?>">Советы тренеров</a></div>
        </div>
        <select name="" id="" class="nav-section__select select-noborder" onchange="if (this.value) window.location.href=this.value">
            <option value="<?= Url::to(['/es/club/']) ?>">Обзор клуба</option>
            <option value="<?= Url::to(['/es/command/']) ?>">тренеры</option>
            <option value="<?= Url::to(['/es/history/']) ?>">истории успеха</option>
            <option value="#" selected>Вакансии</option>
            <option value="<?= Url::to(['/es/article/']) ?>">Советы тренеров</option>
        </select>
    </div>
</section>

<main class="main-section main-section--careers-page careers-page">
    <div class="container container--min">

        <h1 class="careers-page__title title-h1">Вакансии клуба <?= $this->params['club']->title ?></h1>

        <div class="careers-page__desc"><?= $seo->text ?></div>
        <div class="careers-page__subtitle title-h3">Открытые вакансии:</div>

        <div class="careers-page__row row row--stretch">
            <?php foreach ($models as $elem) { ?>
            <div class="careers-page__item col-6 col-xs-12">
                <div class="career-item"><a href="#career-popup-<?= $elem->id ?>" class="career-item__link popup-to"></a>
                    <div class="career-item__title"><?= $elem->title ?></div>
                    <div class="career-item__sep">→</div>
                </div>
            </div>

            <div class="popup-wrap" id="career-popup-<?= $elem->id ?>">
                <div class="popup-wrap__overflow"></div>
                <div class="popup popup--career">
                    <a href="#" class="popup__close close"></a>
                    <div class="popup__img"><img src="/images/career-img.jpg" alt="" /></div>

                    <div class="popup__body">
                        <div class="popup__title title-h1"><?= $elem->title ?></div>
                        <div class="popup__subtitle title-h3">Клуб <?= $this->params['club']->title ?></div>
                        <div class="item-page"><?= HtmlPurifier::process($elem->content) ?></div>
                        <div class="popup__button"><a href="#form-popup" class="btn btn--lg popup-to">Откликнуться</a></div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>

    </div>
</main>

<div class="popup-wrap" id="form-popup">
    <div class="popup-wrap__overflow"></div>
    <div class="popup popup--form">
        <a href="#" class="popup__close close"></a>

        <div class="popup__title title-h3">Откликнуться на вакансию</div>
        <div class="popup__desc">Пожалуйста, заполните форму, наш менеджер свяжется с вами.</div>
        <?php $form = ActiveForm::begin(['id' => 'job', 'action' => Url::to(['/job/subscribe']), 'options' => ['onsubmit' => "dataLayer.push({'event': 'zayavka'});", 'enctype' => 'multipart/form-data', 'class' => 'popup__form']]) ?>
            <div class="input-row">
                <?= Html::textInput('name', null, ['class' => 'inputbox inputbox--border', 'placeholder' => 'Ваше имя *', 'required' => true]) ?>
            </div>
            <div class="input-row">
                <?= MaskedInput::widget([
                    'options' => ['class' => 'inputbox inputbox--border', 'placeholder' => 'Ваш телефон *'],
                    'name' => 'tel',
                    'mask' => '+7(999)999-9999',
                ]) ?>
            </div>
            <div class="upload-box input-row">
                <div class="upload-box__wrap">
                    <?= Html::fileInput('rezume', null, ['id' => 'rezume-img', 'accept' => '.pdf,.docx']) ?>
                    <label class="btn btn--black btn--sm" for="rezume-img">Прикрепить резюме *</label>
                </div>
                <div class="upload-box__desc">Формат *.pdf или *.docx. Вес файла не более 100 Кб</div>
                <div class="upload-box__result"></div>
            </div>
            <div class="popup__soglas">
                <div class="checkbox">
                    <?= Html::checkbox('accept', false, ['id' => 'soglas3']) ?>
                    <label for="soglas3">Ознакомлен с <a href="/privacy/" target="_blank">политикой конфиденциальности</a></label>
                </div>
            </div>
            <div class="popup__button"><button type="submit" class="btn btn--lg">Отправить</button></div>
            <?= Html::hiddenInput('title', '', ['id' => 'form-title']) ?>
        <?php ActiveForm::end() ?>

    </div>
</div>

<?php
$this->registerJs(<<<JS
    var uploadField = document.getElementById("rezume-img");
    uploadField.onchange = function() {
        $('.help-block').remove();
        if(this.files[0].size / 1024 > 100){
           $('#job').find('div.input-row:nth-child(4)').append('<div class="help-block" style="color: #ff0000;">Вес файла более 100 Кб</div>');
           this.value = "";
            ret = false;
        }
    };
    
    $('.popup__button .popup-to').click(function (e) {
        var text = $(this).closest('div.popup__body').find('.popup__title').text();
        $('#form-title').val(text);
    });
    
    jQuery('#job').on('beforeValidate', function (e) {
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
            $(this).find('div.input-row:nth-child(3)').append('<div class="help-block" style="color: #ff0000;">Поле телефон не может быть пустым</div>');
            ret = false;
        } else if($(this)[0][2].value.match(/[_]+/ig)) {
            $(this).find('div.input-row:nth-child(3)').append('<div class="help-block" style="color: #ff0000;">Поле телефон заполнено неправильно</div>');
            ret = false;
        }
        if(!$(this)[0][3].files.length > 0){
            $(this).find('div.input-row:nth-child(4)').append('<div class="help-block" style="color: #ff0000;">Для продолжения, пожалуйста, прикрепите резюме');
            ret = false;
        }
        if(!$(this)[0][4].checked){
            $(this).find('div.checkbox').append('<div class="help-block" style="color: #ff0000;">Для продолжения, пожалуйста, установите флажок "Ознакомлен"</div>');
            ret = false;
        }

        return ret;
    });
JS
);

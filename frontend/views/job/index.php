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

<section class="page-item">
    <div class="container">

        <nav class="d-md-block d-none" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= Url::to(['/']) ?>"><?= $this->params['club']->title ?></a></li>
                <li class="breadcrumb-item active" aria-current="page">Вакансии</li>
            </ol>
        </nav>

        <h2 class="section-heading">Вакансии клуба <?= $this->params['club']->title ?></h2>


        <h3 class="careers-page__subtitle title-h3 mb-3">Открытые вакансии:</h3>

        <div class="careers-page__row row row--stretch">
            <?php foreach ($models as $elem) { ?>
            <div class="careers-page__item col-md-6">
                <div class="career-item mb-5">
                    <h5 class="career-item__title mb-3"><?= $elem->title ?></h5>
                    <a href="#career-popup-<?= $elem->id ?>" data-bs-toggle="modal">
                        <div class="btn btn-primary btn-lg">Информация о вакансии<i class="fa-thin fa-angles-right"></i>
                        </div>
                    </a>
                </div>
            </div>

            <div class="modal fade popup-wrap" id="career-popup-<?= $elem->id ?>" tabindex="-1" role="dialog"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="close-modal" data-bs-dismiss="modal"><button class="btn-close btn-close-white"
                                type="button" aria-label="Close"></div>

                        <div class="modal-body p-lg-5">
                            <h3 class="modal-title"><?= $elem->title ?>
                                <?= $this->params['club']->title ?></h3>
                            <div class="item-page my-3"><?= HtmlPurifier::process($elem->content) ?></div>
                            <a href="#form-popup" data-bs-toggle="modal">
                                <div class="btn btn-primary btn-lg">Откликнуться<i class="fa-thin fa-angles-right"></i>
                                </div>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</section>


<div class="modal fade popup-wrap" id="form-popup" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="close-modal" data-bs-dismiss="modal"><button class="btn-close btn-close-white" type="button"
                    aria-label="Close"></div>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="modal-body p-4">
                        <h3 class="popup__title title-h3">Откликнуться на вакансию</h3>
                        <div class="popup__desc">Пожалуйста, заполните форму, наш менеджер свяжется с вами.</div>

                        <?php $form = ActiveForm::begin(['id' => 'job', 'action' => Url::to(['/job/subscribe']), 'options' => ['onsubmit' => "dataLayer.push({'event': 'zayavka'});", 'enctype' => 'multipart/form-data', 'class' => 'popup__form']]) ?>
                        <div class="input-row mb-4">
                            <?= Html::textInput('name', null, ['class' => 'inputbox  form-control inputbox--border', 'placeholder' => 'Ваше имя *']) ?>
                        </div>
                        <div class="input-row mb-4">
                            <?= MaskedInput::widget([
                                'options' => ['class' => 'inputbox  form-control inputbox--border ', 'placeholder' => 'Ваш телефон *'],
                                'name' => 'tel',
                                'mask' => '+7(999)999-9999',
                            ]) ?>
                        </div>
                        <div class="upload-box input-row">
                            <div class="upload-box__wrap">
                                <?= Html::fileInput('rezume', null, ['id' => 'rezume-img', 'accept' => '.pdf,.docx']) ?>
                                <label class="btn btn-secondary btn-s mt-3" for="rezume-img">Прикрепить резюме *</label>
                            </div>
                            <div class="upload-box__desc">Формат *.pdf или *.docx. Вес файла не более 100 Кб</div>
                            <div class="upload-box__result"></div>
                        </div>
                        <div class="popup__soglas my-3">
                            <div class="checkbox">
                                <?= Html::checkbox('accept', false, ['id' => 'soglas3']) ?>
                                <label class="d-inline" for="soglas3">Ознакомлен с <a href="/privacy/" target="_blank">политикой
                                        конфиденциальности</a></label>
                            </div>
                        </div>
                        <div class="popup__button"><button type="submit" class="btn btn-primary btn-lg">Отправить</button></div>
                        <?= Html::hiddenInput('title', '', ['id' => 'form-title']) ?>
                        <?php ActiveForm::end() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
$this->registerJs(
    <<<JS
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
<?php

use himiklab\yii2\recaptcha\ReCaptcha3;
use richardfan\widget\JSRegister;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\HtmlPurifier;
use yii\widgets\MaskedInput;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $models \common\models\ClubCards */
/* @var $params \common\models\ClubCardsParams */


$this->title = $params->meta_title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $params->meta_keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $params->meta_description]);

?>

<section class="page-item actions" id="actions">
    <div class="container">
        <nav class="d-md-block d-none" aria-label="breadcrumb">
            <ol class="breadcrumb m-0 pt-3">
                <li class="breadcrumb-item"><a href="<?= Url::to(['/']) ?>"><?= $this->params['club']->title ?></a></li>
                <li class="breadcrumb-item"><a href="<?= Url::to(['/card/type/']) ?>">Абонементы и цены</a></li>
                <li class="breadcrumb-item active" aria-current="page">Выбор абонемента</li>
            </ol>
        </nav>
        <h2 class="section-heading">Выбор Абонемента <?= $this->params['club']->title ?></h2>
        <h3 class="mb-5 text-center">В каждый абонемент входит</h3>

        <div class="row justify-content-center">
            <div class="col-md-3 col-6 text-center">
                <img src="/images/card-choice-services-1.svg" alt="" />
                <p>Безлимитный <br>фитнес *</p>
            </div>
            <div class="col-md-3 col-6 text-center">
                <img src="/images/card-choice-services-3.svg" alt="" />
                <p>Полный доступ <br>во все зоны клуба *</p>
            </div>
            <div class="col-md-3 col-6 text-center">
                <img src="/images/card-choice-services-5.svg" alt="" />
                <p><?= explode('.', $_SERVER['HTTP_HOST'])[0] == "piter" || explode('.', $_SERVER['HTTP_HOST'])[0] == "matros" ? 'Финская сухая сауна' : 'Турецкая парная' ?>,<br>
                    неограничено</p>
            </div>
            <?php if (explode('.', $_SERVER['HTTP_HOST'])[0] == "piter") { ?>
            <div class="col-md-3 col-6 text-center">
                <img src="/images/card-choice-services-6.svg" alt="" />
                <p>Плавательный<br> бассейн *</p>
            </div>
            <?php } ?>
        </div>

        <div class="text-center my-3"><span>* наличие и период варьируются в зависимости от абонемента</span></div>

        <div class="list-view">
            <div class="row">
                <?php $i = 1; foreach ($models as $key => $model) { ?>
                <div class="col-md-6">
                    <div class="card p-3">
                        <div class=" d-flex align-items-center justify-content-center">
                            <div class="card-header position-absolute w-100">
                                <div class="row">
                                    <div class="col-5 d-flex align-items-center justify-content-end">
                                        <h1 class="text-uppercase month"><?= Html::encode($model->title) ?></h1>
                                    </div>
                                    <div class="col-2 p-0">
                                        <img class="w-100" src="<?= Url::home(); ?>images/logo-short.svg" alt="" style="opacity: .85;">
                                    </div>
                                    <div class="col-5 d-flex align-items-center justify-content-start">
                                        <h3 class="price"><?= Html::encode($model->price) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <video muted loop autoplay class="w-100 border">
                                <source src="<?= Url::home(); ?>video/card-bg-<?= $i++ ?>.mp4"
                                    type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
                            </video>
                        </div>
                          <a href="#card-popup<?= $key ?>" data-bs-toggle="modal" id="card-<?= $model->id ?>">
                        <div class="btn btn-primary btn-lg mt-4 w-100" style="background-color: #000000bc;">
                            <i class="fa-sharp fa-solid fa-phone-arrow-down-left me-1"></i>Заказать звонок
                        </div>
                    </a>
                    </div>
                  

                    <div class="card-choice__seo-text">
                        <?= HtmlPurifier::process($params->text) ?>
                    </div>


                    <div class="modal fade popup-wrap" id="card-popup<?= $key ?>" tabindex="-1" role="dialog"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-sm">
                            <div class="modal-content">
                                <div class="close-modal" data-bs-dismiss="modal"><button
                                        class="btn-close btn-close-white" type="button" aria-label="Close"></button>
                                </div>
                                <div class="container">
                                    <div class="row justify-content-center">
                                        <div class="modal-body p-4">
                                            <?php $form = ActiveForm::begin(['id' => 'callback_' . $key, 'action' => Url::to(['/club/subscribe3/']), 'options' => ['onsubmit' => (explode('.', $_SERVER['HTTP_HOST'])[0] == "piter" ? "gtag('event', 'zayavka', {'event_category': 'form'});" : "") . "ym(21525628, 'reachGoal', 'zayavka');  dataLayer.push({'event': 'zayavka'});", 'class' => 'popup__form callback2']]) ?>
                                            <div class="row">
                                                <div class="col-12 input-row form-group mb-4">
                                                    <?= Html::textInput('name', null, ['class' => 'inputbox inputbox--border form-control', 'placeholder' => 'Ваше имя *'/*, 'required' => true*/]) ?>
                                                </div>
                                                <div class="col-12 input-row form-group mb-4">
                                                    <?= MaskedInput::widget([
                                                'options' => ['class' => 'form-control input-phone inputbox inputbox--border', 'placeholder' => 'Ваш телефон *'/*, 'required' => true*/],
                                                'name' => 'tel',
                                                'mask' => '+7 999 999 99 99',
                                            ]) ?>
                                                </div>
                                            </div>
                                            <div class="form-section__soglas mb-3">
                                                <div class="checkbox">
                                                    <?= Html::checkbox('accept', false, ['id' => "soglas-$key"]) ?>
                                                    <label class="d-inline" for="soglas-<?= $key ?>">Ознакомлен с <a
                                                            href="<?= Url::to(['/privacy/']) ?>"
                                                            target="_blank">политикой
                                                            конфиденциальности</a></label>
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <button type="submit" class=" btn btn-primary btn-lg">Заказать
                                                    звонок</button>
                                            </div>

                                            <?= ReCaptcha3::widget([
                                        'name' => 'reCaptcha',
                                        'siteKey' => '6Ld2_VopAAAAALCXizlp0XRDUqiFZuQdbrUmCBHl',
                                        'action' => '/club/subscribe3/'
                                    ]) ?>
                                            <?= Html::hiddenInput('title', Html::encode($model->title)) ?>
                                            <?= Html::hiddenInput('url', Yii::$app->getRequest()->getAbsoluteUrl()) ?>
                                            <?php ActiveForm::end() ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <?php } ?>
            </div>
        </div>



    </div>
</section>

<?= $this->render('/club/_subscribe') ?>
<?php
JSRegister::begin(); ?>
<script>
$('.callback2').on('beforeValidate', function(e) {
    $('.help-block').remove();
    var ret = true;

    if ($(this)[0][1].value.length == 0) {
        $(this).find('div.input-row:first').append(
            '<div class="help-block" style="color: #ff0000;">Поле имя не может быть пустым!</div>');
        ret = false;
    } else if ($(this)[0][1].value.match(/[^а-яё ]+/gi)) {
        $(this).find('div.input-row:first').append(
            '<div class="help-block" style="color: #ff0000;">Поле имя должно содержать только буквы кириллицы</div>'
        );
        ret = false;
    }
    if ($(this)[0][2].value.length == 0) {
        $(this).find('div.input-row:last').append(
            '<div class="help-block" style="color: #ff0000;">Поле телефон не может быть пустым</div>');
        ret = false;
    } else if ($(this)[0][2].value.match(/[_]+/ig)) {
        $(this).find('div.input-row:last').append(
            '<div class="help-block" style="color: #ff0000;">Поле телефон заполнено неправильно</div>');
        ret = false;
    }
    if (!$(this)[0][3].checked) {
        $(this).find('div.checkbox').append(
            '<div class="help-block" style="color: #ff0000;">Для продолжения, пожалуйста, установите флажок "Ознакомлен"</div>'
        );
        ret = false;
    }

    return ret;
});
</script>
<?php JSRegister::end();

if (isset($_POST['id'])) {
    $this->registerJs(
        <<<JS
jQuery('#card-{$_POST['id']}').click();
JS
    );
}

$this->registerJs(
    <<<JS
    $(".toggle-box").click(function(){
        var href = $(this).attr("href");
        $(this).toggleClass("active").parents(".parent-show-box").slideToggle(350);
        $(href).slideToggle(350);
        return false;
    });

    jQuery('.content-line .link-more').click(function (e) {
        jQuery('#popup_overflow').fadeIn(500);
        $(this).parents('.card-box').next('.popup_wrap').fadeIn(500);

        var top_h = jQuery(window).scrollTop() - 50;
        $(this).parents('.card-box').next('.popup_wrap').css("top", top_h);
        return false;
    });
JS
);
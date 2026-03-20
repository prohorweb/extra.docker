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
<section class="breadcramb-section">
    <div class="container">
        <div class="breadcramb">
            <a href="/">Главная страница</a>
            <a href="<?= Url::to(['/']) ?>"><?= $this->params['club']->title ?></a>
            <a href="<?= Url::to(['/card/type/']) ?>">Абонементы и цены</a>
            <span>Выбор абонемента</span>
        </div>
    </div>
</section>

<section class="nav-section">
    <div class="container">
        <div class="nav-section__row">
            <div class="nav-section__item active"><a href="#">Выбор абонемента </a></div>
            <div class="nav-section__item"><a href="<?= Url::to(['/card/gift/']) ?>">Фитнес тест-драйв</a></div>
        </div>
        <select name="" id="" class="nav-section__select select-noborder" onchange="if (this.value) window.location.href=this.value">
            <option value="#">Выбор абонемента</option>
            <option value="<?= Url::to(['/card/gift/']) ?>">Фитнес тест-драйв</option>
        </select>
    </div>
</section>

<main class="main-section main-section--card-choice card-choice">
    <div class="container">
        <h1 class="card-choice__title title-h1">Выбор Абонемента <?= $this->params['club']->title ?></h1>
        <div class="card-choice__subtitle title-h3">В каждый абонемент входит</div>
        <div class="card-choice__services">
            <div class="card-choice__services__item">
                <img src="/images/card-choice-services-1.svg" alt="" />
                <p>Безлимитный <br>фитнес *</p>
            </div>
            <div class="card-choice__services__item">
                <img src="/images/card-choice-services-2.svg" alt="" />
                <p>Неограниченное<br> количество визитов</p>
            </div>
            <div class="card-choice__services__item">
                <img src="/images/card-choice-services-3.svg" alt="" />
                <p>Полный доступ <br>во все зоны клуба *</p>
            </div>
            <?php if($_SESSION["_language"] != "db4") { ?>
            <div class="card-choice__services__item">
                <img src="/images/card-choice-services-4.svg" alt="" />
                <p>Ринг. Тренировки<br> по расписанию</p>
            </div>
            <?php } ?>
            <div class="card-choice__services__item">
                <img src="/images/card-choice-services-5.svg" alt="" />
                <p><?= $_SESSION["_language"] == "db" || $_SESSION["_language"] == "db4" ? 'Финская' : 'Турецкая парная' ?>,<br> неограничено</p>
            </div>
            <?php if($_SESSION["_language"] == "db") { ?>
            <div class="card-choice__services__item">
                <img src="/images/card-choice-services-6.svg" alt="" />
                <p>Плавательный<br> бассейн *</p>
            </div>
            <?php } ?>
        </div>
        <div class="card-choice__info-text"><span>* наличие и период варьируются в зависимости от абонемента</span></div>

        <div class="card-choice__cards">
            <?php foreach($models as $key => $model) { ?>
            <div class="card-choice__cards__item">
                <div class="card-block">
                    <img src="<?= $model->img ? '/uploads/image/club_cards/' . $model->img : '//placehold.it/1752x390' ?>" alt="" class="card-block__bg" />
                    <div class="card-block__img"><img src="/images/card-img.png" alt="" /></div>
                    <div class="card-block__wrap">
                        <div class="card-block__title"><?= Html::encode($model->title) ?></div>
                        <div class="card-block__text"><?= Html::encode($model->comment) ?></div>
                        <div class="card-block__button">
                            <a href="#card-popup<?= $key ?>" id="card-<?= $model->id ?>" class="btn btn--white popup-to">Заказать звонок</a>
                            <?php if($_SESSION["_language"] == 'db' && $model->price) { ?>
                            <a href="#buy-popup<?= $key ?>" id="card-<?= $model->id ?>" class="btn btn--white popup-to">Купить онлайн</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="popup-wrap" id="card-popup<?= $key ?>">
                <div class="popup-wrap__overflow"></div>

                <div class="popup popup--form">
                    <a href="#" class="popup__close close"></a>
                    <div class="popup__title">Заказать<br> обратный звонок</div>

                    <?php $form = ActiveForm::begin(['id' => 'callback_' . $key, 'action' => Url::to(['/club/subscribe3/']), 'options' => ['onsubmit' => ($_SESSION["_language"] == "db" ? "gtag('event', 'zayavka', {'event_category': 'form'});" : "") . "ym(21525628, 'reachGoal', 'zayavka');  dataLayer.push({'event': 'zayavka'});", 'class' => 'popup__form callback2']]) ?>
                    <div class="form-section__row row">
                        <div class="form-section__item col-12 input-row">
                            <?= Html::textInput('name', null, ['class' => 'inputbox inputbox--border', 'placeholder' => 'Ваше имя *', 'required' => true]) ?>
                        </div>
                        <div class="form-section__item col-12 input-row">
                            <?= MaskedInput::widget([
                                'options' => ['class' => 'input-phone inputbox inputbox--border', 'placeholder' => 'Ваш телефон *'],
                                'name' => 'tel',
                                'mask' => '+7 999 999 99 99',
                            ]) ?>
                        </div>
                    </div>
                    <div class="form-section__soglas">
                        <div class="checkbox">
                            <?= Html::checkbox('accept', false, ['id' => "soglas-$key"]) ?>
                            <label for="soglas-<?= $key ?>">Ознакомлен с <a href="/privacy/" target="_blank">политикой конфиденциальности</a></label>
                        </div>
                    </div>
                    <div class="form-section__button"><button type="submit" class="btn btn--lg">Заказать звонок</button></div>

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

            <div class="popup-wrap" id="buy-popup<?= $key ?>">
                <div class="popup-wrap__overflow"></div>

                <div class="popup popup--form">
                    <a href="#" class="popup__close close"></a>
                    <div class="form-section__title title-h1">Оплата</div>
                    <div class="form-section__text">Для отправки чека (согласно 54-ФЗ) и связи менеджера с вами, введите,
                        пожалуйста, данные:</div>

                    <?php $form = ActiveForm::begin(['id' => 'callback_' . $key, 'action' => Url::to(['/type/buy/', 'id' => $model->id]), 'options' => ['onsubmit' => ($_SESSION["_language"] == "db" ? "gtag('event', 'zayavka', {'event_category': 'form'});" : "") . "ym(21525628, 'reachGoal', 'zayavka');  dataLayer.push({'event': 'zayavka'});", 'class' => 'popup__form callback2']]) ?>
                    <div class="form-section__row row">
                        <div class="form-section__item col-12 input-row">
                            <?= Html::textInput('name', null, ['class' => 'inputbox inputbox--border', 'placeholder' => 'ФИО *', 'required' => true]) ?>
                        </div>
                        <div class="form-section__item col-12 input-row">
                            <?= MaskedInput::widget([
                                'options' => ['class' => 'input-phone inputbox inputbox--border', 'placeholder' => 'Ваш телефон *'],
                                'name' => 'tel',
                                'mask' => '+7 999 999 99 99',
                            ]) ?>
                        </div>
                        <div class="form-section__item col-12 input-row">
                            <?= Html::input('email', 'email', null, ['class' => 'inputbox inputbox--border', 'placeholder' => 'Email']) ?>
                        </div>
                    </div>
                    <div class="form-section__soglas">
                        <div class="checkbox">
                            <?= Html::checkbox('accept', false, ['id' => "soglas2-$key"]) ?>
                            <label for="soglas2-<?= $key ?>">Ознакомлен с <a href="/privacy/" target="_blank">политикой конфиденциальности</a></label>
                        </div>
                    </div>
                    <div class="form-section__button"><button type="submit" class="btn btn--lg">Перейти к оплате</button></div>

                    <?= Html::hiddenInput('title', Html::encode($model->title)) ?>
                    <?= Html::hiddenInput('url', Yii::$app->getRequest()->getAbsoluteUrl()) ?>
                    <?php ActiveForm::end() ?>
                </div>
            </div>
            <?php } ?>


        </div>

        <div class="card-choice__seo-text">
            <?= HtmlPurifier::process($params->text) ?>
        </div>

    </div>
</main>

<?= $this->render('/club/_subscribe') ?>

<?php
JSRegister::begin(); ?>
<script>
    $('.callback2').on('beforeValidate', function (e) {
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

if(isset($_POST['id'])){
    $this->registerJs(<<<JS
jQuery('#card-{$_POST['id']}').click();
JS
);
}

$this->registerJs(<<<JS
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

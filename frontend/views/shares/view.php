<?php

use himiklab\yii2\recaptcha\ReCaptcha3;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\HtmlPurifier;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model \common\models\Shares */
/* @var $dataProviderOther yii\data\ActiveDataProvider */

$this->title = $model->meta_title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $model->meta_keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $model->meta_description]);
if ($model->only_url) {
    $this->registerMetaTag(['name' => 'robot', 'content' => 'noindex, nofollow']);
}
?>
<section class="page-item actions" id="actions">
    <div class="container">
        <nav class="d-md-block d-none" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="<?= Url::to(['/']) ?>"><?= $this->params['club']->title ?></a></li>
                <li class="breadcrumb-item"><a href="<?= Url::to('/card/shares/') ?>">Акции</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= Html::encode($model->title) ?></li>
            </ol>
        </nav>

        <h2 class="section-heading"><?= Html::encode($model->title) ?></h2>

        <div class="row content pb-3">
            <div class="col-lg-6">
                <div class="card m-0">
                    <?php if (!empty($model->title2)) { ?>
                    <div class="date-action"><?= $model->title2 ?></div>
                    <?php } ?>
                    <img class="w-100 card-img-top "
                        src="<?= $model->img ? '/uploads/image/share/' . $model->img : '//placehold.it/876x680' ?>"
                        alt="" />
                </div>
                <div class="d-flex justify-content-between  align-items-center">
                    <div class="social">
                        <div class="d-flex py-3">
                            <div class="soc-list__label pe-3">Поделиться</div>
                            <div class="soc-list__item"><a href="#"
                                    onclick="Share.vkontakte('<?= Url::base(true) . Url::to() ?>', '<?= Html::encode($model->title) ?>', '')"><img
                                        src="/images/soc-share-2.png" alt="" /></a></div>
                        </div>
                    </div>
                    <div class="form-action">
                        <a href="#offer-popup" data-bs-toggle="modal"
                            onclick="ym(21525628, 'reachGoal', 'click_reserve');">
                            <div class="btn btn-primary btn-lg"><i
                                    class="fa-sharp fa-solid fa-phone-arrow-down-left me-1"></i>Забронировать</div>
                        </a>
                        <?php if (explode('.', $_SERVER['HTTP_HOST'])[0] == 'piter' && $model->price) { ?>
                        <a href="#buy-popup" data-bs-toggle="modal">
                            <div class="btn btn-primary btn-lg"><i class="fa-light fa-bag-shopping me-1"></i></i>Купить
                                онлайн</div>
                        </a>
                        <?php } ?>
                    </div>
                </div>

            </div>

            <div class="share-page__info col-lg-6 p-3">
                <div class="share-page__subtitle title-h1"><?= Html::encode($model->intro) ?></div>
                <div class="item-page">
                    <?= $model->content ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if (explode('.', $_SERVER['HTTP_HOST'])[0] != "piter" && explode('.', $_SERVER['HTTP_HOST'])[0] != "matros") { ?>
<section class="vr-banner  hieght-75">
    <a href="<?= $this->params['club']->url_3d_tour ?>" target="_blank" class="service-header">
        <div class="d-flex align-items-center justify-content-center title" target="_blank">VR-тур по клубу</div>
        <div class="overlay"></div>
        <img class="w-100"
            src="/images/<?= explode('.', $_SERVER['HTTP_HOST'])[0] == "matros" ? 'vr-full-img' : 'vr-full-piter-img' ?>.jpg"
            alt="" class="vr-banner__img" />
    </a>
</section>
<?php } ?>


<?php if (!empty($dataProviderOther)) { ?>
<section class="sale-section page-item actions">
    <div class="container">
        <h2 class="section-heading m-0 py-5">Другие акции клуба</h2>
        <?= ListView::widget([
                'id' => 'shares_other-list',
                'dataProvider' => $dataProviderOther,
                'itemOptions' => function ($model, $key, $index, $widget) {
                    return ['class' => 'col-lg-4 col-md-6 '];
                },
                'layout' => '<div class="row">{items}</div>',
                'emptyText' => 'Записей не найдено',
                'itemView' => '_post',
            ]); ?>
        <div class="d-flex justify-content-center pb-5">
            <a class="btn btn-primary btn-xl" href="<?= Url::to(['/card/shares/']) ?>">Все акции</a>
        </div>
    </div>
</section>
<?php } ?>

<?= $this->render('/club/_subscribe') ?>

<div class="modal fade popup-wrap" id="offer-popup" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="close-modal" data-bs-dismiss="modal"><button class="btn-close btn-close-white" type="button"
                    aria-label="Close"></button></div>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="modal-body p-4">
                        <?php $form = ActiveForm::begin(['id' => 'callback1', 'action' => Url::to(['/club/subscribe3/']), 'options' => ['onsubmit' => (explode('.', $_SERVER['HTTP_HOST'])[0] == "piter" ? "gtag('event', 'zayavka', {'event_category': 'form'});" : "") . "ym(21525628, 'reachGoal', 'zayavka'); ym(21525628, 'reachGoal', 'reserve'); dataLayer.push({'event': 'zayavka'});", 'class' => 'popup__form']]) ?>
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
                                <?= Html::checkbox('accept', false, ['id' => 'soglas-1']) ?>
                                <label class="d-inline" for="soglas-1">Ознакомлен с <a
                                        href="<?= Url::to(['/privacy/']) ?>" target="_blank">политикой
                                        конфиденциальности</a></label>
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
                        <?= Html::hiddenInput('title', Html::encode($model->title)) ?>
                        <?= Html::hiddenInput('url', Yii::$app->getRequest()->getAbsoluteUrl()) ?>
                        <?php ActiveForm::end() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade popup-wrap" id="buy-popup" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="close-modal" data-bs-dismiss="modal"><button class="btn-close btn-close-white" type="button"
                    aria-label="Close"></button></div>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="modal-body p-4">
                        <h3>Оплата</h3>
                        <p>Для отправки чека (согласно 54-ФЗ) и связи менеджера с вами, введите,пожалуйста, данные:</p>
                        <?php $form = ActiveForm::begin(['id' => 'callback2', 'action' => Url::to(['/club/subscribe3/']), 'options' => ['onsubmit' => (explode('.', $_SERVER['HTTP_HOST'])[0] == "piter" ? "gtag('event', 'zayavka', {'event_category': 'form'});" : "") . "ym(21525628, 'reachGoal', 'zayavka'); ym(21525628, 'reachGoal', 'reserve'); dataLayer.push({'event': 'zayavka'});", 'class' => 'popup__form']]) ?>
                        <div class="row">
                            <div class="col-12 input-row form-group mb-4">
                                <?= Html::textInput('name', null, ['class' => 'inputbox inputbox--border form-control', 'placeholder' => 'ФИО *']) ?>
                            </div>
                            <div class="col-12 input-row form-group mb-4">
                                <?= MaskedInput::widget([
                                    'options' => ['class' => 'form-control input-phone inputbox inputbox--border', 'placeholder' => 'Ваш телефон *'/*, 'required' => true*/],
                                    'name' => 'tel',
                                    'mask' => '+7 999 999 99 99',
                                ]) ?>
                            </div>
                            <div class="col-12 input-row form-group mb-4">
                                <?= Html::input('email', 'email', null, ['class' => 'form-control', 'placeholder' => 'Email']) ?>
                            </div>
                        </div>
                        <div class="form-section__soglas mb-3">
                            <div class="checkbox">
                                <?= Html::checkbox('accept', false, ['id' => "soglas-2"]) ?>
                                <label class="d-inline" for="soglas-2">Ознакомлен с <a
                                        href="<?= Url::to(['/privacy/']) ?>" target="_blank">политикой
                                        конфиденциальности</a></label>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class=" btn btn-primary btn-lg">Заказать оплату</button>
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



<?php
$this->registerJs(
    <<<JS
    $('#callback1').on('beforeValidate', function (e) {
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

    $('#callback2').on('beforeValidate', function (e) {
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
    
     jQuery('.content-line .link-more').click(function (e) {

        jQuery('#popup_overflow').fadeIn(500);
        jQuery('#zapis-popup').fadeIn(500);
        
        var top_h = jQuery(window).scrollTop() - 50;
        jQuery('#zapis-popup').css("top", top_h);
        
        return false;
    });

Share = {
	vkontakte: function(purl, ptitle, pimg) {
		url  = 'http://vkontakte.ru/share.php?';
		url += 'url='          + encodeURIComponent(purl);
		url += '&title='       + encodeURIComponent(ptitle);
		url += '&image='       + encodeURIComponent(pimg);
		//url += '&noparse=true';
		Share.popup(url);
	},

	popup: function(url) {
		window.open(url,'','toolbar=0,status=0,width=626,height=436');
	}
}
JS
);
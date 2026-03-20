<?php

use richardfan\widget\JSRegister;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\HtmlPurifier;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model \common\models\Jobs */

$this->title = $model->title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $model->meta_keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $model->meta_description]);

?>
<section class="content-line">
    <div class="container clearfix">
        <div class="white-wrap vacance-page">
            <h1 class="vacance-page__title">Вакансии</h1>

            <div class="row row--w100-750">
                <div class="col span3 vacance-page__nav">
                    <div class="title-h4 mb35">ОТКРЫТЫЕ ВАКАНСИИ:</div>
                    <ul class="menu-lmenu">
                        <?php foreach ($models as $elem) { ?>
                            <li <?= $model->id == $elem->id ? 'class="active"' : '' ?>><a href="<?= Url::to('/wg/job/' . $elem->alias . '/') ?>"><?= $elem->title ?></a></li>
                        <?php } ?>
                    </ul>
                </div>

                <div class="col span9 item-page vacance-page__text">
                    <div class="well vacancy-box">
                        <div class="vacancy-box__title"><?= $model->title ?></div>
                        <div class="vacancy-box__obyaz">
                            <?= HtmlPurifier::process($model->content) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="popup_wrap" id="zapis-popup">
    <div class="popup popup--icon">
        <a href="#" class="popup__close close icon-close"></a>
        <div class="popup__content">
            <div class="popup__img"><img src="<?= Url::to('/images/img-icon-doc.png') ?>" alt=""></div>
            <div class="popup__title">ОСТАВИТЬ ОТКЛИК НА ВАКАНСИЮ</div>
            <p class="tcenter clr-500 mb50">Присоединяйтесь к нашей команде</p>
            <?php $form = ActiveForm::begin(['id' => 'job', 'action' => Url::to(['/job/subscribe']), 'options' => ['class' => 'form-box']]) ?>
            <div class="input-row">
                <?= Html::textInput('name', null, ['class' => 'inputbox inputbox--border', 'placeholder' => 'Имя и фамилия *', 'required' => true]) ?>
            </div>
            <div class="input-row">
                <?= MaskedInput::widget([
                    'options' => ['class' => 'inputbox inputbox--border', 'placeholder' => 'Мобильный телефон *', /*'required' => true*/],
                    'name' => 'tel',
                    'mask' => '+7(999)999-9999',
                ]) ?>
            </div>
            <div class="input-row">
                <?= Html::input('email', 'email', null, ['class' => 'inputbox inputbox--border', 'placeholder' => 'Ваш email']) ?>
            </div>
            <?= Html::hiddenInput('title', $model->title) ?>
            <div class="tcenter">
                <?= Html::submitButton('<span>Оставить отклик</span>', ['class' => 'btn']) ?>
            </div>
            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>

<?php if (Yii::$app->session->hasFlash('mailerFormSubmitted')) : ?>
    <div class="popup_wrap" id="finish-popup" style="display: block;">
        <div class="popup">
            <a href="#" class="popup__close close icon-close"></a>
            <div class="popup__content">
                <div class="popup__title">Отклик отправлен</div>
                <p class="tcenter mb25">Если вы еще никогда не были в Клубе <?= $this->params['club']['title'] ?>, отправьте заявку на гостевой визит и мы подарим вам целый день здоровья и удовольствия!</p>
                <div class="tcenter"><a href="#" class="btn close"><span>Закрыть</span></a></div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php
JSRegister::begin(); ?>
<script>
    jQuery('#job').on('beforeValidate', function (e) {
        jQuery('.help-block').remove();

        if($(this)[0][1].value.length == 0){
            jQuery('#job div.input-row:first').append('<div class="help-block" style="color: #ff0000;"><?= Yii::t('js', 'Поле имя и фамилия не может быть пустым') ?></div>');
            if($(this)[0][2].value.length == 0){
                jQuery('#job div.input-row:nth-child(3)').append('<div class="help-block" style="color: #ff0000;"><?= Yii::t('js', 'Поле мобильный телефон не может быть пустым') ?></div>');
            }
            return false;
        }
        if($(this)[0][2].value.length == 0){
            jQuery('#job div.input-row:nth-child(3)').append('<div class="help-block" style="color: #ff0000;"><?= Yii::t('js', 'Поле мобильный телефон не может быть пустым') ?></div>');
            return false;
        }

        return true;
    });
</script>
<?php JSRegister::end();

$this->registerJs(<<<JS
     jQuery('.content-line .popup').click(function (e) {

        jQuery('#popup_overflow').fadeIn(500);
        jQuery('#zapis-popup').fadeIn(500);
        
        var top_h = jQuery(window).scrollTop() - 50;
        jQuery('#zapis-popup').css("top", top_h);
        
        return false;
    });
JS
);
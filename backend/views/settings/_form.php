<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Settings */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(['action' => ['upgrade']]); ?>
<h4>Доступные обновления</h4>
<div class="form-inline">
    <label for="">Обновление от xx.xx.xxxx</label>
    <?= Html::submitButton('Обновить', ['class' => 'btn btn--grey']) ?>
</div>
<?php ActiveForm::end(); ?>

<hr>
<?php $form = ActiveForm::begin(); ?>
<h4>Доступность сайта</h4>
<?= $form->field($model, 'status', ['labelOptions' => ['class' => 'input-label']])->radioList([1 => 'сайт доступен', 0 => 'сайт закрыт от пользователей'], [
    'class' => 'form-inline input-row',
    'item' => function ($index, $label, $name, $checked, $value) {
        return '<div class="radiobox">' . Html::radio($name, $checked, ['value' => $value, 'id' => 'status_' . $index]) . '<label for="status_' . $index . '">' . $label . '</label></div>';
    },
])->label(false) ?>

<div class="input-row">
    <?= Html::submitButton('Сохранить', ['class' => 'btn']) ?>
</div>
<hr>

<h4>Таймер</h4>
<div class="input-row">
    <?= $form->field($model, 'timer_title', ['labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox']) ?>
</div>

<div class="input-row">
    <?= $form->field($model, 'timer_intro', ['labelOptions' => ['class' => 'input-label']])->textarea() ?>
</div>

<div class="row input-row clearfix">
    <?= $form->field($model, 'date_timer', ['options' => ['class' => 'col span3'], 'labelOptions' => ['class' => 'input-label']])->textInput(['class' => 'inputbox input-date'])->label('Дата') ?>
<?= $form->field($model, 'timer_start', ['options' => ['class' => 'col span2'], 'labelOptions' => ['class' => 'input-label']])->textInput()->widget(\yii\widgets\MaskedInput::className(), [
    'mask' => '99:99', 'options' => ['size' => 5, 'class' => 'inputbox']]) ?>
<?= $form->field($model, 'timer_end', ['options' => ['class' => 'col span2'], 'labelOptions' => ['class' => 'input-label']])->textInput()->widget(\yii\widgets\MaskedInput::className(), [
    'mask' => '99:99', 'options' => ['size' => 5, 'class' => 'inputbox']]) ?>
</div>

<label for="" style="display: block; color: #565855; margin: 0 0 15px;">Email-адреса, на которые необходимо отправлять формы таймера</label>
<div class="input-row">
<?= $form->field($model, 'email_timer', ['options' => ['class' => 'col span7'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox', 'placeholder' => 'go@pro2t.com, me@ivanlapaev.ru'])->hint('Если адресатов несколько, то укажите их через запятую')->label(false) ?>
</div>

<div class="input-row">
    <?= Html::submitButton('Сохранить', ['class' => 'btn']) ?>
</div>

<div class="row input-row clearfix">
    <div class="col span2">
        <?= Html::a('Включить таймер', 'timer-start', ['class' => 'btn', 'data-method' => 'post']) ?>
    </div>
    <div class="col span2">
        <?= Html::a('Отключить таймер', 'timer-end', ['class' => 'btn', 'data-method' => 'post']) ?>
    </div>
</div>

<hr>

<h4>Бонус</h4>
<div class="row input-row clearfix">
    <?= $form->field($model, 'bonus_percent', ['options' => ['class' => 'col span2'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox']) ?>
    <?= $form->field($model, 'bonus_time', ['options' => ['class' => 'col span2'], 'labelOptions' => ['class' => 'input-label']])->textInput()->widget(\yii\widgets\MaskedInput::className(), [
        'mask' => '99:99', 'options' => ['size' => 5, 'class' => 'inputbox']]) ?>
</div>

<div class="row input-row clearfix">
    <?= $form->field($model, 'bonus_timer', ['options' => ['class' => 'col span3'], 'labelOptions' => ['class' => 'input-label']])->textInput(['class' => 'inputbox input-date'])->label('Дата') ?>
    <?= $form->field($model, 'bonus_start', ['options' => ['class' => 'col span2'], 'labelOptions' => ['class' => 'input-label']])->textInput()->widget(\yii\widgets\MaskedInput::className(), [
        'mask' => '99:99', 'options' => ['size' => 5, 'class' => 'inputbox']]) ?>
    <?= $form->field($model, 'bonus_end', ['options' => ['class' => 'col span2'], 'labelOptions' => ['class' => 'input-label']])->textInput()->widget(\yii\widgets\MaskedInput::className(), [
        'mask' => '99:99', 'options' => ['size' => 5, 'class' => 'inputbox']]) ?>
</div>

<label for="" style="display: block; color: #565855; margin: 0 0 15px;">Email-адреса, на которые необходимо отправлять формы бонуса</label>
<div class="input-row">
    <?= $form->field($model, 'email_bonus', ['options' => ['class' => 'col span7'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox', 'placeholder' => 'go@pro2t.com, me@ivanlapaev.ru'])->hint('Если адресатов несколько, то укажите их через запятую')->label(false) ?>
</div>

<div class="input-row">
    <?= Html::submitButton('Сохранить', ['class' => 'btn']) ?>
</div>

<div class="row input-row clearfix">
    <div class="col span2">
        <?= Html::a('Включить бонус', 'bonus-start', ['class' => 'btn', 'data-method' => 'post']) ?>
    </div>
    <div class="col span2">
        <?= Html::a('Отключить бонус', 'bonus-end', ['class' => 'btn', 'data-method' => 'post']) ?>
    </div>
</div>

<hr>

<dl class="tabs-list">
    <dt>Параметры оптимизации материала</dt>
    <dd>
        <div class="input-row">
            <?= $form->field($model, 'meta_title', ['labelOptions' => ['class' => 'input-label']])->textInput(['class' => 'inputbox span10', 'maxlength' => true])->hint('осталось символов: <span id="charsLeftMetaTitle"></span>') ?>
        </div>
        <div class="input-row">
            <?= $form->field($model, 'meta_keywords', ['labelOptions' => ['class' => 'input-label']])->textInput(['class' => 'inputbox span10', 'maxlength' => true]) ?>
        </div>
        <div class="input-row">
            <?= $form->field($model, 'meta_description', ['labelOptions' => ['class' => 'input-label']])->textInput(['class' => 'inputbox span10', 'maxlength' => true]) ?>
        </div>
        <div class="input-row">
            <?= Html::submitButton('Сохранить', ['class' => 'btn']) ?>
        </div>
    </dd>
</dl>

<hr>

<h4>Настройка email-адресов</h4>
<label for="" style="display: block; color: #565855; margin: 0 0 15px;">Email-адрес сайта (от этого адреса будут приходить письма)</label>
<div class="row clearfix input-row">
    <?= $form->field($model, 'email_from', ['options' => ['class' => 'col span10'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox', 'placeholder' => 'go@pro2t.com'])->label(false) ?>
    <div class="col span2"><?= Html::submitButton('Сохранить', ['class' => 'btn btn--grey']) ?></div>
</div>

<label for="" style="display: block; color: #565855; margin: 0 0 15px;">Email-адреса, на которые необходимо отправлять формы подарочного сертификата</label>
<div class="row clearfix input-row">
    <?= $form->field($model, 'email_form_guest', ['options' => ['class' => 'col span10'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox', 'placeholder' => 'go@pro2t.com, me@ivanlapaev.ru'])->hint('Если адресатов несколько, то укажите их через запятую')->label(false) ?>
    <div class="col span2"><?= Html::submitButton('Сохранить', ['class' => 'btn btn--grey']) ?></div>
    <?= $form->field($model, 'code_form_guest', ['options' => ['class' => 'col span10'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox', 'placeholder' => 'yaCounterXXXXXX.reachGoal(\'ORDER\'); return true;'])->label(false) ?>
</div>

<label for="" style="display: block; color: #565855; margin: 0 0 15px;">Email-адреса, на которые необходимо отправлять формы записи на мероприятия</label>
<div class="row clearfix input-row">
    <?= $form->field($model, 'email_form_visit', ['options' => ['class' => 'col span10'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox', 'placeholder' => 'go@pro2t.com, me@ivanlapaev.ru'])->hint('Если адресатов несколько, то укажите их через запятую')->label(false) ?>
    <div class="col span2"><?= Html::submitButton('Сохранить', ['class' => 'btn btn--grey']) ?></div>
    <?= $form->field($model, 'code_form_visit', ['options' => ['class' => 'col span10'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox', 'placeholder' => 'yaCounterXXXXXX.reachGoal(\'ORDER\'); return true;'])->label(false) ?>
</div>

<label for="" style="display: block; color: #565855; margin: 0 0 15px;">Email-адреса, на которые необходимо отправлять формы заявок на персональный тренинг</label>
<div class="row clearfix input-row">
    <?= $form->field($model, 'email_request_training', ['options' => ['class' => 'col span10'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox', 'placeholder' => 'go@pro2t.com, me@ivanlapaev.ru'])->hint('Если адресатов несколько, то укажите их через запятую')->label(false) ?>
    <div class="col span2"><?= Html::submitButton('Сохранить', ['class' => 'btn btn--grey']) ?></div>
    <?= $form->field($model, 'code_request_training', ['options' => ['class' => 'col span10'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox', 'placeholder' => 'yaCounterXXXXXX.reachGoal(\'ORDER\'); return true;'])->label(false) ?>
</div>

<label for="" style="display: block; color: #565855; margin: 0 0 15px;">Email-адреса, на которые необходимо отправлять формы заявок на клубные карты</label>
<div class="row clearfix input-row">
    <?= $form->field($model, 'email_club_card', ['options' => ['class' => 'col span10'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox', 'placeholder' => 'go@pro2t.com, me@ivanlapaev.ru'])->hint('Если адресатов несколько, то укажите их через запятую')->label(false) ?>
    <div class="col span2"><?= Html::submitButton('Сохранить', ['class' => 'btn btn--grey']) ?></div>
    <?= $form->field($model, 'code_club_card', ['options' => ['class' => 'col span10'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox', 'placeholder' => 'yaCounterXXXXXX.reachGoal(\'ORDER\'); return true;'])->label(false) ?>
</div>

<label for="" style="display: block; color: #565855; margin: 0 0 15px;">Email-адреса, на которые необходимо отправлять формы заявок на заморозку клубной карты</label>
<div class="row clearfix input-row">
    <?= $form->field($model, 'email_request_freezing', ['options' => ['class' => 'col span10'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox', 'placeholder' => 'go@pro2t.com, me@ivanlapaev.ru'])->hint('Если адресатов несколько, то укажите их через запятую')->label(false) ?>
    <div class="col span2"><?= Html::submitButton('Сохранить', ['class' => 'btn btn--grey']) ?></div>
    <?= $form->field($model, 'code_request_freezing', ['options' => ['class' => 'col span10'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox', 'placeholder' => 'yaCounterXXXXXX.reachGoal(\'ORDER\'); return true;'])->label(false) ?>
</div>

<label for="" style="display: block; color: #565855; margin: 0 0 15px;">Email-адреса, на которые необходимо отправлять формы обратной связи</label>
<div class="row clearfix input-row">
    <?= $form->field($model, 'email_feedback', ['options' => ['class' => 'col span10'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox', 'placeholder' => 'go@pro2t.com, me@ivanlapaev.ru'])->hint('Если адресатов несколько, то укажите их через запятую')->label(false) ?>
    <div class="col span2"><?= Html::submitButton('Сохранить', ['class' => 'btn btn--grey']) ?></div>
</div>

<label for="" style="display: block; color: #565855; margin: 0 0 15px;">Email-адреса, на которые необходимо отправлять формы о покупке карты или акции</label>
<div class="row clearfix input-row">
    <?= $form->field($model, 'email_buy', ['options' => ['class' => 'col span10'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox', 'placeholder' => 'go@pro2t.com, me@ivanlapaev.ru'])->hint('Если адресатов несколько, то укажите их через запятую')->label(false) ?>
    <div class="col span2"><?= Html::submitButton('Сохранить', ['class' => 'btn btn--grey']) ?></div>
</div>
<hr>

<h4>Установка счетчиков на сайт</h4>
<p class="hint-block">(при необходимости, коды онлайн чата и callback сервисов разместите в поле кода Яндекс Метрики)</p>
<label for="" style="display: block; color: #565855; margin: 0 0 15px;">Яндекс.Метрика</label>
<?= $form->field($model, 'yandex_metrica', ['options' => ['class' => 'input-row']])->textarea(['cols' => 30, 'rows' => 10])->label(false) ?>

<div class="input-row">
    <label for="" class="input-label">Файл-подтверждение</label>
    <?= $form->field($model, 'file_yandex', ['template' => '{input}{label}{hint}{error}', 'options' => ['class' => 'upload-box'], 'labelOptions' => ['class' => 'btn']])->fileInput()->hint('<span>' . ($model->file_yandex ?: 'Файл не выбран') . '<span>') ?>
</div>

<div class="input-row mb30">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn--grey']) ?>
</div>


<label for="" style="display: block; color: #565855; margin: 0 0 15px;">Google Analytics</label>
<?= $form->field($model, 'google_analytics', ['options' => ['class' => 'input-row']])->textarea(['cols' => 30, 'rows' => 10])->label(false) ?>

<div class="input-row">
    <label for="" class="input-label">Файл-подтверждение</label>
    <?= $form->field($model, 'file_google', ['template' => '{input}{label}{hint}{error}', 'options' => ['class' => 'upload-box'], 'labelOptions' => ['class' => 'btn']])->fileInput()->hint('<span>' . ($model->file_google ?: 'Файл не выбран') . '<span>') ?>
</div>

<div class="input-row mb30">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn--grey']) ?>
</div>

<hr>

<label for="" style="display: block; color: #565855; margin: 0 0 15px;">Для размещения кода после тега “head”, вставьте код ниже</label>
<?= $form->field($model, 'code_head', ['options' => ['class' => 'input-row']])->textarea(['cols' => 30, 'rows' => 10])->label(false) ?>

<label for="" style="display: block; color: #565855; margin: 0 0 15px;">Для размещения кода сразу после тега “body”, вставьте код ниже</label>
<?= $form->field($model, 'code_body', ['options' => ['class' => 'input-row']])->textarea(['cols' => 30, 'rows' => 10])->label(false) ?>

<label for="" style="display: block; color: #565855; margin: 0 0 15px;">Редактирование файла robots.txt</label>
<?= Html::textarea('robots', $robots, ['cols' => 30, 'rows' => 10]) ?>
<br><br>

<div class="input-row mb30">
    <label for="" style="display: block; color: #565855; margin: 0 0 15px;">О приложении</label>
    <?= $form->field($model, 'about')->textarea(['cols' => 30, 'rows' => 10])->label(false) ?>
</div>

<div class="input-row">
    <label for="" class="input-label">Лого1</label>
    <?= $form->field($model, 'logo1', ['template' => '{input}{label}{hint}{error}', 'options' => ['class' => 'upload-box'], 'labelOptions' => ['class' => 'btn']])->fileInput(['accept' => 'image/png'])->hint('<span>' . ($model->logo1 ?: 'Файл не выбран') . '<span>') ?>
    <div class="hint-block">Размер изображения 400 px * 400 px<br> Допустимый формат: PNG</div>
</div>

<div class="input-row">
    <label for="" class="input-label">Лого2</label>
    <?= $form->field($model, 'logo2', ['template' => '{input}{label}{hint}{error}', 'options' => ['class' => 'upload-box'], 'labelOptions' => ['class' => 'btn']])->fileInput(['accept' => 'image/png'])->hint('<span>' . ($model->logo2 ?: 'Файл не выбран') . '<span>') ?>
    <div class="hint-block">Размер изображения 400 px * 400 px<br> Допустимый формат: PNG</div>
</div>
<hr>

<h2>Виджет мессенджер</h2>
<div class="input-row">
    <label for="" class="input-label">WhatsApp</label>
    <?= $form->field($model, 'wa', ['labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox'])->label(false) ?>
</div>

<div class="input-row">
    <label for="" class="input-label">ВКонтакте</label>
    <?= $form->field($model, 'vk', ['labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox'])->label(false) ?>
</div>

<div class="input-row">
    <label for="" class="input-label">Telegram</label>
    <?= $form->field($model, 'tg', ['labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox'])->label(false) ?>
</div>

<div class="input-row mb30">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn--grey']) ?>
</div>

<?php ActiveForm::end(); ?>

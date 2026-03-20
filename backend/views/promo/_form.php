<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Promo */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>

<div class="input-row">
    <?= Html::submitButton('Сохранить', ['class' => 'btn']) ?>
</div>
<hr>

<label for="" style="display: block; color: #565855; margin: 0 0 15px;">Телефон в "шапке"</label>
<div class="row clearfix input-row">
    <?= $form->field($model, 'phone', ['options' => ['class' => 'col span6'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox'])->label(false) ?>
    <div class="col span2"><?= Html::submitButton('Сохранить', ['class' => 'btn btn--grey']) ?></div>
</div>

<div class="input-row">
    <?= Html::label('Баннер', '', ['class' => 'input-label']) ?>
    <?php if($model->img): ?>
        <img src="/uploads/image/promo/<?= $model->img ?>" height="150">
        <br><br>
    <?php endif; ?>
    <?= $form->field($model, 'img', ['template' => '{input}{label}{hint}{error}', 'options' => ['class' => 'upload-box'], 'labelOptions' => ['class' => 'btn']])->fileInput(['accept' => 'image/jpeg, image/png'])->hint('<span>Файл не выбран<span>') ?>
    <div class="hint-block">Размер изображения не меньше 1904 px * 643 px<br> Допустимые форматы: PNG, JPG</div>
</div>

<div class="input-row">
    <?= $form->field($model, 'text', ['labelOptions' => ['class' => 'input-label']])->textarea(['rows' => 6, 'class' => 'form-control js-rich-editor']) ?>
</div>

<div class="input-row">
    <?= $form->field($model, 'text2', ['labelOptions' => ['class' => 'input-label']])->textarea(['rows' => 6, 'class' => 'form-control js-rich-editor']) ?>
</div>

<h4>Цены</h4>
<label for="" style="display: block; color: #565855; margin: 0 0 15px;">Питер</label>
<div class="row clearfix input-row">
    <?= $form->field($model, 'price1_piter', ['options' => ['class' => 'col span3'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox']) ?>
    <?= $form->field($model, 'price3_piter', ['options' => ['class' => 'col span3'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox']) ?>
    <?= $form->field($model, 'price6_piter', ['options' => ['class' => 'col span3'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox']) ?>
    <?= $form->field($model, 'price12_piter', ['options' => ['class' => 'col span3'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox']) ?>
</div>

<label for="" style="display: block; color: #565855; margin: 0 0 15px;">Родео</label>
<div class="row clearfix input-row">
    <?= $form->field($model, 'price1_rodeo', ['options' => ['class' => 'col span3'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox']) ?>
    <?= $form->field($model, 'price3_rodeo', ['options' => ['class' => 'col span3'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox']) ?>
    <?= $form->field($model, 'price6_rodeo', ['options' => ['class' => 'col span3'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox']) ?>
    <?= $form->field($model, 'price12_rodeo', ['options' => ['class' => 'col span3'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox']) ?>
</div>

<label for="" style="display: block; color: #565855; margin: 0 0 15px;">Июнь</label>
<div class="row clearfix input-row">
    <?= $form->field($model, 'price1_june', ['options' => ['class' => 'col span3'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox']) ?>
    <?= $form->field($model, 'price3_june', ['options' => ['class' => 'col span3'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox']) ?>
    <?= $form->field($model, 'price6_june', ['options' => ['class' => 'col span3'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox']) ?>
    <?= $form->field($model, 'price12_june', ['options' => ['class' => 'col span3'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox']) ?>
</div>

<label for="" style="display: block; color: #565855; margin: 0 0 15px;">Полюс</label>
<div class="row clearfix input-row">
    <?= $form->field($model, 'price1_polis', ['options' => ['class' => 'col span3'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox']) ?>
    <?= $form->field($model, 'price3_polis', ['options' => ['class' => 'col span3'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox']) ?>
    <?= $form->field($model, 'price6_polis', ['options' => ['class' => 'col span3'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox']) ?>
    <?= $form->field($model, 'price12_polis', ['options' => ['class' => 'col span3'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox']) ?>
</div>

<label for="" style="display: block; color: #565855; margin: 0 0 15px;">Матрос</label>
<div class="row clearfix input-row">
    <?= $form->field($model, 'price1_matros', ['options' => ['class' => 'col span3'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox']) ?>
    <?= $form->field($model, 'price3_matros', ['options' => ['class' => 'col span3'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox']) ?>
    <?= $form->field($model, 'price6_matros', ['options' => ['class' => 'col span3'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox']) ?>
    <?= $form->field($model, 'price12_matros', ['options' => ['class' => 'col span3'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox']) ?>
</div>

<h4>Иконки месяца</h4>
<div class="input-row">
    <?= Html::label('1 мес', '', ['class' => 'input-label']) ?>
    <?php if($model->icon1): ?>
        <img src="/uploads/image/promo/<?= $model->icon1 ?>" height="150">
        <br><br>
    <?php endif; ?>
    <?= $form->field($model, 'icon1', ['template' => '{input}{label}{hint}{error}', 'options' => ['class' => 'upload-box'], 'labelOptions' => ['class' => 'btn']])->fileInput(['accept' => 'image/png'])->hint('<span>Файл не выбран<span>') ?>
    <div class="hint-block">Размер изображения не меньше 73 px * 74 px<br> Допустимый формат: PNG</div>
</div>

<div class="input-row">
    <?= Html::label('3 мес', '', ['class' => 'input-label']) ?>
    <?php if($model->icon3): ?>
        <img src="/uploads/image/promo/<?= $model->icon3 ?>" height="150">
        <br><br>
    <?php endif; ?>
    <?= $form->field($model, 'icon3', ['template' => '{input}{label}{hint}{error}', 'options' => ['class' => 'upload-box'], 'labelOptions' => ['class' => 'btn']])->fileInput(['accept' => 'image/png'])->hint('<span>Файл не выбран<span>') ?>
    <div class="hint-block">Размер изображения не меньше 73 px * 74 px<br> Допустимый формат: PNG</div>
</div>

<div class="input-row">
    <?= Html::label('6 мес', '', ['class' => 'input-label']) ?>
    <?php if($model->icon6): ?>
        <img src="/uploads/image/promo/<?= $model->icon6 ?>" height="150">
        <br><br>
    <?php endif; ?>
    <?= $form->field($model, 'icon6', ['template' => '{input}{label}{hint}{error}', 'options' => ['class' => 'upload-box'], 'labelOptions' => ['class' => 'btn']])->fileInput(['accept' => 'image/png'])->hint('<span>Файл не выбран<span>') ?>
    <div class="hint-block">Размер изображения не меньше 73 px * 74 px<br> Допустимый формат: PNG</div>
</div>

<div class="input-row">
    <?= Html::label('12 мес', '', ['class' => 'input-label']) ?>
    <?php if($model->icon12): ?>
        <img src="/uploads/image/promo/<?= $model->icon12 ?>" height="150">
        <br><br>
    <?php endif; ?>
    <?= $form->field($model, 'icon12', ['template' => '{input}{label}{hint}{error}', 'options' => ['class' => 'upload-box'], 'labelOptions' => ['class' => 'btn']])->fileInput(['accept' => 'image/png'])->hint('<span>Файл не выбран<span>') ?>
    <div class="hint-block">Размер изображения не меньше 73 px * 74 px<br> Допустимый формат: PNG</div>
</div>

<div class="input-row mb30">
    <?= Html::submitButton('Сохранить', ['class' => 'btn']) ?>
</div>

<?php ActiveForm::end(); ?>

<?php
$this->registerJs(<<<JS
	jQuery('#form-event').on('afterValidate', function (event, messages, errorAttributes) {
        if(errorAttributes.length){
            jQuery('#alerts').append('<div class="message-box">Не все поля заполнены корректно</div>');
            //window.setTimeout(function() { jQuery("#alerts").html(''); }, 2000);
        }
        return true;
    });
JS
);

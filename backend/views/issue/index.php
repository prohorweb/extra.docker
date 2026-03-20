<?php

use common\widgets\Alert;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Issue */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Сообщить об ошибке';

if(Yii::$app->session->getAllFlashes()) {
    $this->registerJs(<<<JS
	window.setTimeout(function() { jQuery(".message-box").alert('close'); }, 2000);
JS
    );
}
?>

<div class="content-box clearfix">
    <?= Alert::widget(['closeButton' => false, 'options' => ['class' => 'message-box']]) ?>
    <div id="alerts"></div>
    <div class="title-box clearfix">
        <h1 class="page-title"><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="wrapper">

        <?php $form = ActiveForm::begin(['id' => 'form-issue']); ?>

        <div class="input-row">
            <?= Html::submitButton('Отправить', ['class' => 'btn']) ?>
        </div>

        <div class="input-row">
            <?= $form->field($model, 'title', ['labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox'])->hint('осталось символов: <span id="charsLeftTitle"></span>') ?>
        </div>

        <div class="input-row">
            <?= $form->field($model, 'text', ['labelOptions' => ['class' => 'input-label']])->textarea()->hint('осталось символов: <span id="charsLeftIntro"></span>') ?>
        </div>

        <div class="input-row">
            <?= Html::label('Изображение ошибки', '', ['class' => 'input-label']) ?>
            <?= $form->field($model, 'img', ['template' => '{input}{label}{hint}{error}', 'options' => ['class' => 'upload-box'], 'labelOptions' => ['class' => 'btn']])->fileInput(['accept' => 'image/jpeg, image/png'])->hint('<span>Файл не выбран<span>') ?>
            <div class="hint-block">Допустимые форматы: PNG, JPG</div>
        </div>

        <div class="input-row span6">
            <?= Html::label('Укажите Ваш e-mail для возможных уточнений', 'email', ['class' => 'input-label']) ?>
            <?= $form->field($model, 'email')->textInput(['id' => 'email', 'maxlength' => true, 'class' => 'inputbox'])->label(false) ?>
        </div>

        <div class="input-row">
            <?= Html::submitButton('Отправить', ['class' => 'btn']) ?>
        </div>

        <?php ActiveForm::end() ?>

    </div>
</div>

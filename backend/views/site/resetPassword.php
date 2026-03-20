<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Сменить пароль';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-box clearfix">
    <div class="title-box clearfix">
        <h1 class="page-title"><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="wrapper">
        <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

        <div class="input-row">
            <?= $form->field($model, 'password', ['labelOptions' => ['class' => 'input-label']])->passwordInput(['maxlength' => true, 'class' => 'inputbox']) ?>
        </div>

        <div class="input-row">
            <?= $form->field($model, 'new_password', ['labelOptions' => ['class' => 'input-label']])->passwordInput(['maxlength' => true, 'class' => 'inputbox'])->hint('не менее 7 символов') ?>
        </div>

        <div class="input-row">
            <?= $form->field($model, 'password_repeat', ['labelOptions' => ['class' => 'input-label']])->passwordInput(['maxlength' => true, 'class' => 'inputbox']) ?>
        </div>

        <div class="input-row">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

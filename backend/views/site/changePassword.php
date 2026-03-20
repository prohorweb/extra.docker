<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \backend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Сменить пароль';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-box clearfix">
    <div class="title-box clearfix">
        <h1 class="page-title"><?= Html::encode($this->title) ?></h1>
        <a href="<?= Url::to(['/user/update', 'id' => Yii::$app->request->get('id')]) ?>" class="btn btn--grey"><i class="flaticon-left-arrow"></i>Назад</a>
    </div>
    <div class="wrapper">
        <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

        <div class="input-row">
            <?= $form->field($model, 'password', ['labelOptions' => ['class' => 'input-label']])->hiddenInput(['maxlength' => true, 'class' => 'inputbox', 'value' => '1234567'])->label(false) ?>
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

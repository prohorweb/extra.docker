<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Авторизация';
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="enter-wrapper">
    <div class="table w100p h100p">
        <div class="table_cell h100p">

            <div class="enter-form">
                <div class="wrapper mb15">
                    <h3><?= Html::encode($this->title) ?></h3>
                    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                    <?= $form->field($model, 'username', ['options' => ['class' => 'input-row mb20']])->textInput(['class' => 'inputbox', 'autofocus' => true, 'placeholder' => 'Логин'])->label(false) ?>

                    <?= $form->field($model, 'password')->passwordInput(['class' => 'inputbox', 'placeholder' => 'Пароль'])->label(false) ?>

                    <?php //= $form->field($model, 'rememberMe')->checkbox() ?>

                    <div class="buttons clearfix">
                        <?= Html::submitButton('Войти', ['class' => 'btn fright', 'name' => 'login-button']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
                <p class="tcenter">Разработка и поддержка сайта: <a href="http://ra-vozduh.ru" target="_blank">ra-vozduh.ru</a></p>
            </div>
        </div>
    </div>
</section>

<?php

/* @var $this yii\web\View */
/* @var $model backend\models\UserForm */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<?php $form = ActiveForm::begin(['id' => 'form-user']); ?>

<div class="input-row">
    <?= Html::submitButton($this->context->action->id == "create" ? 'Сохранить' : 'Обновить', ['class' => $this->context->action->id == "create" ? 'btn' : 'btn btn-primary']) ?>
</div>

<div class="row input-row clearfix">
    <?= $form->field($model, 'username', ['options' => ['class' => 'col span5'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox'])->hint('осталось символов: <span id="charsLeftUsername"></span>') ?>
    <?= $form->field($model, 'email', ['options' => ['class' => 'col span5'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox']) ?>
</div>

<?php if ($this->context->action->id == "create"): ?>
<div class="row input-row clearfix">
    <div class="col span5">
        <?= $form->field($model, 'password', ['labelOptions' => ['class' => 'input-label']])->passwordInput(['class' => 'inputbox']) ?>
        <div class="help-block">не менее 7 символов</div>
    </div>
    <div class="col span5">
        <?= $form->field($model, 'password_repeat', ['labelOptions' => ['class' => 'input-label']])->passwordInput(['class' => 'inputbox']) ?>
    </div>
</div>
<?php else: ?>
<div class="row input-row clearfix">
    <div class="col span5">
    <a href="<?= Url::to(['/site/change-password', 'id' => $model->id]) ?>" class="btn btn--grey">Изменить пароль</a>
    </div>
</div>
<?php endif; ?>

<div class="input-label">Разрешить доступ</div>
<div class="row input-row clearfix">
    <?= Html::checkboxList('access', $access, $model->getPermissions(), [
        //'class' => 'col span3',
        'item' => function ($index, $label, $name, $checked, $value) {
            $checked = $checked ? 'checked' : '';
            $index++;
            return ($index % 4 == 1 ? "<div class='col span3'>" : "")."<div class='checkbox mb5'><input type='checkbox' {$checked} id='{$value}' name='{$name}' value='{$value}'><label for='{$value}'>{$label}</label></div>".($index % 4 == 0 ? "</div>" : "");
        }
    ]) ?>
    </div>
</div>

<?= $form->field($model, 'status', ['labelOptions' => ['class' => 'input-label']])->radioList([10 => 'Нет', 0 => 'Да'], [
    'class' => 'row input-row clearfix',
    'item' => function ($index, $label, $name, $checked, $value) {
        return '<div class="col span3"><div class="radiobox mb5">' . Html::radio($name, $checked, ['value' => $value, 'id' => 'status_' . $index]) . '<label for="status_' . $index . '">' . $label . '</label></div></div>';
    },
]) ?>

<div class="input-row">
    <?= Html::submitButton($this->context->action->id == "create" ? 'Сохранить' : 'Обновить', ['class' => $this->context->action->id == "create" ? 'btn' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end();

$this->registerJs(<<<JS
	jQuery('#userform-username').limit('125','#charsLeftUsername');

    jQuery('#form-user').on('afterValidate', function (event, messages, errorAttributes) {
        if(errorAttributes.length){
            jQuery('#alerts').append('<div class="message-box">Не все поля заполнены корректно</div>');
            //window.setTimeout(function() { jQuery("#alerts").html(''); }, 2000);
        }
        return true;
    });
JS
);

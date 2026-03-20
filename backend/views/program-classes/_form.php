<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ProgramClasses */
/* @var $form yii\widgets\ActiveForm */
/* @var $id string */
?>
<?php $form = ActiveForm::begin(['id' => 'form-program-classes']); ?>

<div class="input-row">
    <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn' : 'btn btn-primary']) ?>
</div>

<div class="row input-row clearfix">
    <?= $form->field($model, 'title', ['options' => ['class' => 'col span9'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox'])->hint('осталось символов: <span id="charsLeftTitle"></span>') ?>
    <?= $form->field($model, 'duration', ['options' => ['class' => 'col span3'], 'labelOptions' => ['class' => 'input-label']])->textInput(['class' => 'inputbox']) ?>
</div>

<div class="input-row">
    <?= $form->field($model, 'intro', ['labelOptions' => ['class' => 'input-label']])->textarea(['rows' => 6, 'class' => 'form-control js-rich-editor']) ?>
</div>

<?= $form->field($model, 'group_programs_id')->hiddenInput(['value' => $id])->label(false) ?>

<div class="input-row">
    <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end();

$this->registerJs(<<<JS
    jQuery('#programclasses-title').limit('50','#charsLeftTitle');
    
    jQuery('#form-program-classes').on('afterValidate', function (event, messages, errorAttributes) {
        if(errorAttributes.length){
            jQuery('#alerts').append('<div class="message-box">Не все поля заполнены корректно</div>');
            //window.setTimeout(function() { jQuery("#alerts").html(''); }, 2000);
        }
        return true;
    });
JS
);


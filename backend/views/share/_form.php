<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Shares */
/* @var $form yii\widgets\ActiveForm */

?>

<?php $form = ActiveForm::begin(['id' => 'form-share']); ?>

<div class="input-row">
    <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn' : 'btn btn-primary']) ?>
</div>

<div class="input-row">
    <?= $form->field($model, 'only_url', ['options' => ['class' => 'checkbox'], 'template' => "{input}\n{label}\n{hint}\n{error}"])->checkbox(['label' => '<label for="shares-only_url">Доступ только по ссылке</label>'], false) ?>
</div>

<?php if(!$model->isNewRecord) { ?>
<div class="input-row">
    <a href="<?= 'https://' . $model->getNameClub()  . '.' . $_SERVER['SERVER_NAME'] . '/card/shares/' . $model->alias . '/' ?>" target="_blank"><?= 'https://' . $model->getNameClub()  . '.' . $_SERVER['SERVER_NAME'] . '/card/shares/' . $model->alias . '/' ?></a>
</div>
<?php } ?>

<div class="input-row">
    <?= $form->field($model, 'title', ['labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox'])->hint('осталось символов: <span id="charsLeftTitle"></span>') ?>
</div>

<div class="input-row">
    <?= $form->field($model, 'comment', ['labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox'])->hint('осталось символов: <span id="charsLeftComment"></span>') ?>
</div>

<div class="row input-row">
    <?= $form->field($model, 'price', ['options' => ['class' => 'col span2'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => 8, 'class' => 'inputbox']) ?>
</div>

<div class="input-row">
    <?= $form->field($model, 'title2', ['labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox'])->hint('осталось символов: <span id="charsLeftTitle"></span>') ?>
</div>

<div class="input-row">
    <?= $form->field($model, 'intro', ['labelOptions' => ['class' => 'input-label']])->textarea()->hint('осталось символов: <span id="charsLeftIntro"></span>') ?>
</div>

<div class="input-row">
    <?= Html::label('Изображение для превью', '', ['class' => 'input-label']) ?>
    <?php if($model->img): ?>
        <img src="/uploads/image/share/<?= $model->img ?>" height="150">
        <br><br>
    <?php endif; ?>
    <?= $form->field($model, 'img', ['template' => '{input}{label}{hint}{error}', 'options' => ['class' => 'upload-box'], 'labelOptions' => ['class' => 'btn']])->fileInput(['accept' => 'image/jpeg, image/png'])->hint('<span>Файл не выбран<span>') ?>
    <div class="hint-block">Размер изображения не меньше 876 px * 680 px<br> Допустимые форматы: PNG, JPG</div>
</div>

<div class="input-row">
    <?= $form->field($model, 'content')->textarea(['class' => 'form-control js-rich-editor']) ?>
</div>

<dl class="tabs-list">
    <dt>Параметры оптимизации материала</dt>
    <dd>
        <div class="input-row">
            <?= $form->field($model, 'alias', ['labelOptions' => ['class' => 'input-label']])->textInput(['class' => 'inputbox span10', 'maxlength' => true])->hint('осталось символов: <span id="charsLeftAlias"></span>') ?>
        </div>

        <div class="input-row">
            <?= $form->field($model, 'meta_title', ['labelOptions' => ['class' => 'input-label']])->textInput(['class' => 'inputbox span10', 'maxlength' => true])->hint('осталось символов: <span id="charsLeftMetaTitle"></span>') ?>
        </div>

        <div class="input-row">
            <?= $form->field($model, 'meta_keywords', ['labelOptions' => ['class' => 'input-label']])->textInput(['class' => 'inputbox span10', 'maxlength' => true]) ?>
        </div>

        <div class="input-row">
            <?= $form->field($model, 'meta_description', ['labelOptions' => ['class' => 'input-label']])->textInput(['class' => 'inputbox span10', 'maxlength' => true]) ?>
        </div>
    </dd>
</dl>

<div class="input-row">
    <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn' : 'btn btn-primary']) ?>
</div>



<?php ActiveForm::end();

$this->registerJs(<<<JS
	jQuery('#shares-title').limit('60','#charsLeftTitle');
    jQuery('#shares-comment').limit('125','#charsLeftComment');
	jQuery('#shares-intro').limit('500','#charsLeftIntro');
	jQuery('#shares-alias').limit('100','#charsLeftAlias');
	jQuery('#shares-meta_title').limit('125','#charsLeftMetaTitle');
	
	jQuery('#form-share').on('afterValidate', function (event, messages, errorAttributes) {
        if(errorAttributes.length){
            jQuery('#alerts').append('<div class="message-box">Не все поля заполнены корректно</div>');
            //window.setTimeout(function() { jQuery("#alerts").html(''); }, 2000);
        }
        return true;
    });
JS
);

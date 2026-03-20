<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Partners */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(['id' => 'form-partners']); ?>

<div class="input-row">
    <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn' : 'btn btn-primary']) ?>
</div>

<div class="input-row">
    <?= $form->field($model, 'title', ['options' => ['class' => 'col span9'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox'])->hint('осталось символов: <span id="charsLeftTitle"></span>') ?>
</div>

<div class="input-row">
    <?= $form->field($model, 'content', ['labelOptions' => ['class' => 'input-label']])->textarea(['rows' => 6, 'class' => 'form-control js-rich-editor']) ?>
</div>

<div class="input-row">
    <?= Html::label('Изображение для превью', '', ['class' => 'input-label']) ?>
    <?php if($model->img): ?>
    <img src="/uploads/image/partners/<?= $model->img ?>" height="150">
    <br><br>
    <?php endif; ?>
    <?= $form->field($model, 'img', ['template' => '{input}{label}{hint}{error}', 'options' => ['class' => 'upload-box'], 'labelOptions' => ['class' => 'btn']])->fileInput(['accept' => 'image/jpeg, image/png'])->hint('<span>Файл не выбран<span>') ?>
    <div class="hint-block">Размер изображения не меньше 330 px * 240 px<br> Допустимые форматы: PNG, JPG</div>
</div>

<div class="input-row">
    <?= $form->field($model, 'discount', ['options' => ['class' => 'col span3'], 'labelOptions' => ['class' => 'input-label']])->textInput(['class' => 'inputbox']) ?>
</div>

<div class="input-row">
    <?= $form->field($model, 'is_gift', ['options' => ['class' => 'checkbox'], 'template' => "{input}\n{label}\n{hint}\n{error}"])->checkbox(['label' => '<label for="partners-is_gift">Подарок</label>'], false) ?>
</div>

<div class="input-row">
    <?= $form->field($model, 'address', ['labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox span10'])->hint('осталось символов: <span id="charsLeftAddress"></span>') ?>
</div>

<div class="input-row">
    <?= $form->field($model, 'coordinates')->widget('kolyunya\yii2\widgets\MapInputWidget', [
        'key' => 'AIzaSyBiN8t2gCkvdrVUQxvzzD5QBwhynixKd-c',
        'latitude' => 60,
        'longitude' => 30,
        'zoom' => 10,
        'pattern' => '%latitude%,%longitude%'
    ])->label(false) ?>
</div>

<div class="input-row">
    <?= $form->field($model, 'site', ['labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox span10'])->hint('осталось символов: <span id="charsLeftAddress"></span>') ?>
</div>

<div class="input-row">
    <?= $form->field($model, 'tel', ['labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox span10'])->hint('осталось символов: <span id="charsLeftAddress"></span>') ?>
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
	//jQuery('#news-title').limit('55','#charsLeftTitle');
	//jQuery('#news-intro').limit('85','#charsLeftIntro');
	//jQuery('#news-alias').limit('55','#charsLeftAlias');
	//jQuery('#news-meta_title').limit('125','#charsLeftMetaTitle');
	
    jQuery('#form-news').on('afterValidate', function (event, messages, errorAttributes) {
        if(errorAttributes.length){
            jQuery('#alerts').append('<div class="message-box">Не все поля заполнены корректно</div>');
            //window.setTimeout(function() { jQuery("#alerts").html(''); }, 2000);
        }
        return true;
    });
JS
);



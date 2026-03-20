<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Services */
/* @var $banners \common\models\Banners */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(['id' => 'form-services']); ?>

<div class="input-row">
    <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn' : 'btn btn-primary']) ?>
</div>

<div class="input-row">
    <?= $form->field($model, 'title', ['labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox'])->hint('осталось символов: <span id="charsLeftTitle"></span>') ?>
</div>

<div class="input-row">
    <?= Html::label('Изображение для превью', '', ['class' => 'input-label']) ?>
    <?php if($model->img): ?>
        <img src="/uploads/image/services/<?= $model->img ?>" height="150">
        <br><br>
    <?php endif; ?>
    <?= $form->field($model, 'img', ['template' => '{input}{label}{hint}{error}', 'options' => ['class' => 'upload-box'], 'labelOptions' => ['class' => 'btn']])->fileInput(['accept' => 'image/jpeg, image/png'])->hint('<span>Файл не выбран<span>') ?>
    <div class="hint-block">Размер изображения не меньше 644 px * 400 px<br> Допустимые форматы: PNG, JPG</div>
</div>

<hr>

<div id="add-banner" class="input-row form-inline">
    <label>Баннеры на странице</label>
    <?= Html::a('Добавить', '#', ['class' => 'btn add']) ?>
</div>

<div class="foto-list none">
    <?= Html::a('<i class="flaticon-delete"></i>', '#', ['class' => 'del-icon']) ?>
    <div class="row clearfix">
        <div class="col span5 foto-list_box">
            <?= Html::label('Изображение для слайдера', '', ['class' => 'input-label']) ?>
            <?= $form->field($banners, 'img1440[]', ['template' => '{input}{label}{hint}{error}', 'options' => ['class' => 'upload-box'], 'labelOptions' => ['class' => 'btn']])->fileInput(['accept' => 'image/jpeg, image/png'])->hint('<span>Файл не выбран<span>') ?>
            <div class="hint-block">Размер изображения не меньше 1904 px * 698 px<br> Допустимые форматы: PNG, JPG</div>
        </div>
    </div>
</div>

<?php if (isset($model_banners)) {
    foreach ($model_banners as $banners) { ?>
        <div class="foto-list">
            <?= Html::a('<i class="flaticon-delete"></i>', '#', ['class' => 'del-icon', 'data-id' => $banners->id]) ?>
            <div class="row clearfix">
                <div class="col span5 foto-list_box">
                    <?php if($banners->img1440): ?>
                        <img src="/uploads/image/banners/1440/<?= $banners->img1440 ?>" height="150">
                        <br><br>
                    <?php endif; ?>
                    <?= Html::label('Изображение для слайдера', '', ['class' => 'input-label']) ?>
                    <?= $form->field($banners, 'img1440[]', ['template' => '{input}{label}{hint}{error}', 'options' => ['class' => 'upload-box'], 'labelOptions' => ['class' => 'btn']])->fileInput(['accept' => 'image/jpeg, image/png'])->hint('<span>Файл не выбран<span>') ?>
                    <div class="hint-block">Размер изображения не меньше 1904 px * 698 px<br> Допустимые форматы: PNG, JPG</div>
                </div>
            </div>
        </div>
    <?php }
} ?>

<div class="input-row">
    <?= $form->field($model, 'content', ['labelOptions' => ['class' => 'input-label']])->textarea(['rows' => 6, 'class' => 'form-control js-rich-editor']) ?>
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
	jQuery('#services-title').limit('45','#charsLeftTitle');
	jQuery('#services-alias').limit('45','#charsLeftAlias');
	jQuery('#services-meta_title').limit('125','#charsLeftMetaTitle');
	
    jQuery('.del-icon').on('click', function (e) {
        e.preventDefault();
        jQuery(this).parent().remove();
        
        if($(this).data("id")){
            jQuery.ajax({
                url: 'banner-delete?id=' + this.dataset.id,
                type: 'post'
            });
        }
    });
    
    jQuery('.add').click(function (e) {
        e.preventDefault();
        jQuery('.foto-list.none').clone(true).removeClass('none').insertAfter('.foto-list:last');
        addFiles();
    });
JS
);


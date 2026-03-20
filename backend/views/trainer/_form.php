<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Trainers */
/* @var $banners common\models\Banners */
/* @var $options_type common\models\TrainerOptionsType */
/* @var $form yii\widgets\ActiveForm */

$items1 = ['rasp' => 'Расписание', 'articles' => 'Статьи', 'club' => 'Информация о клубе', 'services' => 'Услуги'];
?>

<?php $form = ActiveForm::begin(['id' => 'form-trainer']); ?>

    <div class="input-row">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn' : 'btn btn-primary']) ?>
    </div>

    <div class="input-row">
        <?= $form->field($model, 'title', ['labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox'])->hint('осталось символов: <span id="charsLeftTitle"></span>') ?>
    </div>

    <div class="input-row">
        <?= $form->field($model, 'post', ['labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox'])->hint('осталось символов: <span id="charsLeftPost"></span>') ?>
    </div>

    <div class="input-row">
        <?= $form->field($model, 'options_field')->checkboxList(ArrayHelper::map($options_type, 'id', 'title'), [
            'class' => 'form-inline span7',
            'item' => function ($index, $label, $name, $checked, $value) {
                $checked = $checked ? 'checked' : '';
                return "<div class='checkbox mb15'><input type='checkbox' {$checked} id='{$value}' name='{$name}' value='{$value}'><label for='{$value}'>{$label}</label></div>";
            }
        ])->label(false) ?>
    </div>

    <div class="input-row">
        <?= Html::label('Изображение для превью', '', ['class' => 'input-label']) ?>
        <?php if ($model->img): ?>
            <img src="/uploads/image/trainer/<?= $model->img ?>" height="150">
            <br><br>
        <?php endif; ?>
        <?= $form->field($model, 'img', ['template' => '{input}{label}{hint}{error}', 'options' => ['class' => 'upload-box'], 'labelOptions' => ['class' => 'btn']])->fileInput(['accept' => 'image/jpeg, image/png'])->hint('<span>Файл не выбран<span>') ?>
        <div class="hint-block">Размер изображения не меньше 330 px * 330 px<br> Допустимые форматы: PNG, JPG</div>
    </div>

    <div class="input-row">
        <?= $form->field($model, 'content', ['labelOptions' => ['class' => 'input-label']])->textarea(['rows' => 6, 'class' => 'form-control js-rich-editor']) ?>
    </div>

    <hr>

    <div id="add-banner" class="input-row form-inline">
        <label>Дополнительные фото</label>
        <?= Html::a('Добавить', '', ['class' => 'btn add']) ?>
    </div>

    <div class="foto-list none">
        <?= Html::a('<i class="flaticon-delete"></i>', '', ['class' => 'del-icon']) ?>
        <div class="row clearfix">
            <div class="col span5 foto-list_box">
                <?= Html::label('Изображение для слайдера', '', ['class' => 'input-label']) ?>
                <?= $form->field($banners, 'img1440[]', ['template' => '{input}{label}{hint}{error}', 'options' => ['class' => 'upload-box'], 'labelOptions' => ['class' => 'btn']])->fileInput(['accept' => 'image/jpeg, image/png'])->hint('<span>Файл не выбран<span>') ?>
                <div class="hint-block">Размер изображения не меньше 645 px * 645 px<br> Допустимые форматы: PNG, JPG</div>
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
                        <div class="hint-block">Размер изображения не меньше 645 px * 645 px<br> Допустимые форматы: PNG, JPG</div>
                    </div>
                </div>
            </div>
        <?php }
    } ?>

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
	jQuery('#trainers-title').limit('25','#charsLeftTitle');
	jQuery('#trainers-post').limit('35','#charsLeftPost');
	jQuery('#trainers-alias').limit('25','#charsLeftAlias');
	jQuery('#trainers-meta_title').limit('125','#charsLeftMetaTitle');
	
	jQuery('#form-trainer').on('afterValidate', function (event, messages, errorAttributes) {
        if(errorAttributes.length){
            jQuery('#alerts').append('<div class="message-box">Не все поля заполнены корректно</div>');
            //window.setTimeout(function() { jQuery("#alerts").html(''); }, 2000);
        }
        return true;
    });
	
	$('.del-icon').on('click', function (e) {
        e.preventDefault();
        $(this).parent().remove();
        
        if($(this).data("id")){
            $.ajax({
                url: '/admin/trainer/banner-delete?id=' + this.dataset.id,
                type: 'post'
            });
        }
    });
	
	$('.add').click(function (e) {
        e.preventDefault();
        $('.foto-list.none').clone(true).removeClass('none').insertAfter('.foto-list:last');
        addFiles();
    });
JS
);


<?php

use kartik\depdrop\DepDrop;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ClubBanners */
/* @var $form yii\widgets\ActiveForm */

if($model->url_category) {
    $class = 'common\\models\\' . ucfirst((string)$model->url_category);
}
?>

<?php $form = ActiveForm::begin(['id' => 'form-banners']); ?>

<div class="input-row">
    <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn' : 'btn btn-primary']) ?>
</div>

<div class="input-row">
    <?= $form->field($model, 'title', ['labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox'])->hint('осталось символов: <span id="charsLeftTitle"></span>') ?>
</div>

<div class="input-row">
    <?= $form->field($model, 'comment', ['labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox'])->hint('осталось символов: <span id="charsLeftComment"></span>') ?>
</div>

<div class="row input-row clearfix">
<?= $form->field($model, 'url', ['options' => ['class' => 'col span6'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox']) ?>

<?= $form->field($model, 'open_new_tab', ['options' => ['class' => 'col span6'], 'labelOptions' => ['class' => 'input-label']])->radioList([0 => 'в текущей вкладке', 10 => 'в новой вкладке'], [
    'class' => 'form-inline',
    'item' => function ($index, $label, $name, $checked, $value) {
        return '<div class="radiobox" style="white-space: nowrap;">' . Html::radio($name, $checked, ['value' => $value, 'id' => 'status_' . $index]) . '<label for="status_' . $index . '">' . $label . '</label></div>';
    },
]) ?>
</div>

<div class="input-row">
    <?= Html::label('Переход по внутренним страницам для моб. приложения', '', ['class' => 'input-label']) ?>
    <?= Html::dropDownList('ClubBanners[url_category]', $model->url_category, ['Club' => 'Наш клуб', 'News' => 'Новости', 'Events' => 'Мероприятия', 'Services' => 'Услуги', 'Shares' => 'Предложение месяца', 'ClubCards' => 'Клубные карты', 'Trainers' => 'Команда'], ['id'=>'url_category', 'class' => 'col span4', 'prompt' => 'Выберите категорию ...']) ?>
    <?= DepDrop::widget([
        'name' => 'ClubBanners[url_id]',
        //'data' => [$model->url_id => $model->url_category ? $class::findOne($model->url_id)['title'] : []],
        'options' => ['class' => 'col span4'],
        'pluginOptions' => [
            'depends' => ['url_category'],
            'placeholder' => 'Выберите материал ...',
            'url' => Url::to('subcat/')
        ]
    ]) ?>
</div>

<div class="input-row">
    <?= $form->field($model, 'title2', ['labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox'])->hint('осталось символов: <span id="charsLeftTitle2"></span>') ?>
</div>

<div class="input-row">
    <?= $form->field($model, 'intro', ['labelOptions' => ['class' => 'input-label']])->textarea()->hint('осталось символов: <span id="charsLeftIntro"></span>') ?>
</div>

<hr>

<div class="input-row">
    <?= Html::label('Баннер в приложении', '', ['class' => 'input-label']) ?>
    <?php if($model->img1440): ?>
        <img src="/uploads/image/banners/1440/<?= $model->img1440 ?>" height="150">
        <br><br>
    <?php endif; ?>
    <?= $form->field($model, 'img1440', ['template' => '{input}{label}{hint}{error}', 'options' => ['class' => 'upload-box'], 'labelOptions' => ['class' => 'btn']])->fileInput(['accept' => 'image/jpeg, image/png'])->hint('<span>Файл не выбран<span>') ?>
    <div class="hint-block">Размер изображения не меньше 1904 px * 1080 px<br> Допустимые форматы: PNG, JPG</div>
</div>

<div class="input-row">
    <?= Html::label('Баннер на сайте', '', ['class' => 'input-label']) ?>
    <?php if($model->img1200): ?>
        <img src="/uploads/image/banners/1200/<?= $model->img1200 ?>" height="150">
        <br><br>
    <?php endif; ?>
    <?= $form->field($model, 'img1200', ['template' => '{input}{label}{hint}{error}', 'options' => ['class' => 'upload-box'], 'labelOptions' => ['class' => 'btn']])->fileInput(['accept' => 'image/jpeg, image/png'])->hint('<span>Файл не выбран<span>') ?>
    <div class="hint-block">Размер изображения не меньше 1904 px * 698 px<br> Допустимые форматы: PNG, JPG</div>
</div>

<div class="input-row">
    <?= Html::label('Видео', '', ['class' => 'input-label']) ?>
    <?php if($model->video): ?>
        <video class="slideshow-wrapper__video" height="150" src="/uploads/video/<?= $model->video ?>" autoplay muted loop preload></video>
        <br><br>
    <?php endif; ?>
    <?= $form->field($model, 'video', ['template' => '{input}{label}{hint}{error}', 'options' => ['class' => 'upload-box'], 'labelOptions' => ['class' => 'btn']])->fileInput(['accept' => 'video/*'])->hint('<span>Файл не выбран<span>') ?>
    <div class="hint-block">Размер видео 1920*1080 пикселей<br>Вес до 3 Мб</div>

    <a href="/admin/club/video-delete?id=<?= $model->id ?>" data-method="post" data-pjax="0"><i class="flaticon-delete"></i></a>
</div>

<hr>

<div class="input-row">
    <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end();

$this->registerJs(<<<JS
	jQuery('#clubbanners-title').limit('125','#charsLeftTitle');
	jQuery('#clubbanners-comment').limit('125','#charsLeftComment');
    jQuery('#clubbanners-title2').limit('50','#charsLeftTitle2');
    jQuery('#clubbanners-intro').limit('55','#charsLeftIntro');
	
	jQuery('#form-banners').on('afterValidate', function (event, messages, errorAttributes) {
        if(errorAttributes.length){
            jQuery('#alerts').append('<div class="message-box">Не все поля заполнены корректно</div>');
            //window.setTimeout(function() { jQuery("#alerts").html(''); }, 2000);
        }
        return true;
    });
	
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

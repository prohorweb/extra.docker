<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\Club */
/* @var $metro common\models\Metros */
/* @var $form yii\widgets\ActiveForm */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $dataProviderMetros yii\data\ActiveDataProvider */
?>

<h3>Баннеры для главной страницы</h3>

<p>
    <?= Html::a('Создать', ['banner-create'], ['class' => 'btn']) ?>
</p>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'layout' => '{items}{pager}',
    'tableOptions' => ['class' => 'table-style'],
    'emptyText' => 'Записей не найдено',
    'rowOptions'   => ['style' => 'cursor:pointer;'],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn', 'header' => '№'],
        //'id',
        [
            'attribute' => 'status',
            'headerOptions' => ['class' => 'tcenter'],
            'contentOptions' => ['class' => 'tcenter'],
            'content' => function ($data) {
                return Html::a('', Url::to([($data->status ? 'disable' : 'activate') . '?id='.$data->id]), ['class' => 'flaticon-eye' . ($data->status ? '' : '-off') . ' icon25 '.($data->status ? 'color_green' : '')]);
            }
        ],
        //'position',
        [
            'label' => 'Название',
            'attribute' => 'title',
        ],
        [
            'label' => 'Примечание',
            'attribute' => 'comment',
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => 'Действия',
            //'controller' => 'banner',
            'template' => '<ul class="panel-icons clearfix">{update} {delete} {up} {down}</ul>',
            'buttons' => [
                'update' => function ($url, $model) {
                    $url = Url::to(['club/banner-update', 'id' => $model->id]);
                    return '<li>' . Html::a('<i class="flaticon-pancil"></i>', $url) . '</li>';
                },
                'delete' => function ($url, $model) {
                    $url = Url::to(['club/banner-delete', 'id' => $model->id]);
                    return '<li>' . Html::a('<i class="flaticon-delete"></i>', $url, ['data-method' => 'post', 'data-pjax' => '0']) . '</li>';
                },
                'up' => function ($url, $model) {
                    return '<li>' . Html::a('<i class="flaticon-arrow-up"></i>', $url, ['data-method' => 'post', 'data-pjax' => '0']) . '</li>';
                },
                'down' => function ($url, $model) {
                    return '<li>' . Html::a('<i class="flaticon-arrow-down"></i>', $url, ['data-method' => 'post', 'data-pjax' => '0']) . '</li>';
                },
            ]
        ],
    ],
]); ?>

<?php $form = ActiveForm::begin(['id' => 'form-club']); ?>

<h2>Оптимизация страницы карточки клуба</h2>
<div class="input-row">
    <?= $form->field($model, 'meta_title', ['labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox span10'])->hint('осталось символов: <span id="charsLeftMetaTitle"></span>') ?>
</div>

<div class="input-row">
    <?= $form->field($model, 'meta_keywords', ['labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox span10']) ?>
</div>

<div class="input-row">
    <?= $form->field($model, 'meta_description', ['labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox span10']) ?>
</div>

<div class="input-row">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
</div>

<hr>

<h2>Информация в «шапке» сайта</h2>

<div class="input-row">
    <?= $form->field($model, 'tel', ['labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox span10'])->hint('осталось символов: <span id="charsLeftTel"></span>') ?>
</div>

<div class="input-row">
    <?= $form->field($model, 'address', ['labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox span10'])->hint('осталось символов: <span id="charsLeftAddress"></span>') ?>
</div>

<div class="input-row">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
</div>

<hr>

<h2>Информация в «подвале» сайта</h2>
<div class="input-row">
    <?= $form->field($model, 'email', ['labelOptions' => ['class' => 'input-label']])->input('email', ['maxlength' => true, 'class' => 'inputbox span10'])->hint('осталось символов: <span id="charsLeftEmail"></span>') ?>
</div>

<div class="input-row">
    <?= $form->field($model, 'url_appstore', ['labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox span10']) ?>
</div>

<div class="input-row">
    <?= $form->field($model, 'url_googleplay', ['labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox span10']) ?>
</div>

<div class="input-row">
    <?= $form->field($model, 'url_vk', ['labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox span10']) ?>
</div>

<div class="input-row">
    <?= $form->field($model, 'url_facebook', ['labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox span10']) ?>
</div>

<div class="input-row">
    <?= $form->field($model, 'url_instagram', ['labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox span10']) ?>
</div>

<div class="input-row">
    <?= $form->field($model, 'url_ok', ['labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox span10']) ?>
</div>

<div class="input-row">
    <?= $form->field($model, 'legal_title', ['labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox span10']) ?>
</div>

<div class="input-row">
    <?= $form->field($model, 'legal', ['labelOptions' => ['class' => 'input-label']])->textarea(['rows' => 6, 'class' => 'form-control js-rich-editor']) ?>
</div>

<div class="input-row">
    <?= $form->field($model, 'privacy_title', ['labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox span10']) ?>
</div>

<div class="input-row">
    <?= $form->field($model, 'privacy', ['labelOptions' => ['class' => 'input-label']])->textarea(['rows' => 6, 'class' => 'form-control js-rich-editor']) ?>
</div>

<div class="input-row">
    <?= $form->field($model, 'title', ['labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox'])->hint('осталось символов: <span id="charsLeftTitle"></span>') ?>
</div>

<div class="input-row">
    <?= $form->field($model, 'contacts_title', ['labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox span10']) ?>
</div>

<div class="input-row">
    <?= Html::label('Правила обработки персональных данных', '', ['class' => 'input-label']) ?>
    <?php if($model->pdf2): ?>
        <a href="/uploads/<?= $model->pdf2 ?>"><?= $model->pdf2 ?></a>
        <br><br>
    <?php endif; ?>
    <?= $form->field($model, 'pdf2', ['template' => '{input}{label}{hint}{error}', 'options' => ['class' => 'upload-box'], 'labelOptions' => ['class' => 'btn']])->fileInput(['accept' => 'application/pdf'])->hint('<span>Файл не выбран<span>') ?>
    <div class="hint-block">Формат файла PDF (pdf), размер файла не более 5 Мб</div>
</div>

<div class="input-row">
    <?= Html::label('Правила клуба', '', ['class' => 'input-label']) ?>
    <?php if($model->pdf): ?>
        <a href="/uploads/<?= $model->pdf ?>"><?= $model->pdf ?></a>
        <br><br>
    <?php endif; ?>
    <?= $form->field($model, 'pdf', ['template' => '{input}{label}{hint}{error}', 'options' => ['class' => 'upload-box'], 'labelOptions' => ['class' => 'btn']])->fileInput(['accept' => 'application/pdf'])->hint('<span>Файл не выбран<span>') ?>
    <div class="hint-block">Формат файла PDF (pdf), размер файла не более 5 Мб</div>
</div>

<div class="input-row">
    <?= Html::label('Договор оферты', '', ['class' => 'input-label']) ?>
    <?php if($model->pdf3): ?>
        <a href="/uploads/<?= $model->pdf3 ?>"><?= $model->pdf3 ?></a>
        <br><br>
    <?php endif; ?>
    <?= $form->field($model, 'pdf3', ['template' => '{input}{label}{hint}{error}', 'options' => ['class' => 'upload-box'], 'labelOptions' => ['class' => 'btn']])->fileInput(['accept' => 'application/pdf'])->hint('<span>Файл не выбран<span>') ?>
    <div class="hint-block">Формат файла PDF (pdf), размер файла не более 5 Мб</div>
</div>

<div class="input-row">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
</div>

<hr>

<h2>Информация в карточке клуба</h2>

<div class="input-row">
    <?= $form->field($model, 'start_work', ['labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox span10'])->hint('осталось символов: <span id="charsLeftWork"></span>') ?>
</div>

<div class="input-row">
    <?= $form->field($model, 'start_work_weekend', ['labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox span10'])->hint('осталось символов: <span id="charsLeftWorkWeekend"></span>') ?>
</div>

<div class="input-row">
    <?= $form->field($model, 'url_3d_tour', ['labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox span10']) ?>
</div>

<div class="row clearfix input-row">
    <?= $form->field($model, 'coordinates', ['options' => ['class' => 'col span6'], 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox'])->hint('осталось символов: <span id="charsLeftTitle"></span>') ?>
    <!--<div class="col span6"><label for="" style="margin: 0 0 15px; display: block;">&nbsp;</label><button type="button" class="btn btn--grey" data-toggle="modal" data-target="#myModal">Определить</button></div>-->
</div>

<?= $form->field($model, 'coordinates')->widget('kolyunya\yii2\widgets\MapInputWidget', [
    'key' => 'AIzaSyBiN8t2gCkvdrVUQxvzzD5QBwhynixKd-c',
    'latitude' => 60,
    'longitude' => 30,
    'zoom' => 10,
    'pattern' => '%latitude%,%longitude%'
])->label(false) ?>

<div class="input-row">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
</div>

<hr>

<h2>Текстовый блок вверху страницы "О клубе"</h2>

<div class="input-row">
    <?= $form->field($model, 'content', ['labelOptions' => ['class' => 'input-label']])->textarea(['rows' => 6, 'class' => 'form-control js-rich-editor']) ?>
</div>

<div class="input-row">
    <?= $form->field($model, 'club_content', ['labelOptions' => ['class' => 'input-label']])->textarea(['rows' => 6, 'class' => 'form-control js-rich-editor']) ?>
</div>

<div class="input-row">
    <?= $form->field($model, 'main_content', ['labelOptions' => ['class' => 'input-label']])->textarea(['rows' => 6, 'class' => 'form-control js-rich-editor']) ?>
</div>

<div class="input-row">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>

<?php
$this->registerJs(<<<JS
    jQuery('#club-content_cards').limit('100','#charsLeftContentCards');
	jQuery('#club-meta_title').limit('125','#charsLeftMetaTitle');
	jQuery('#club-tel').limit('125','#charsLeftTel');
	jQuery('#club-address').limit('125','#charsLeftAddress');
	jQuery('#club-email').limit('125','#charsLeftEmail');
	jQuery('#club-start_work').limit('125','#charsLeftWork');
	jQuery('#club-start_work_weekend').limit('125','#charsLeftWorkWeekend');
	jQuery('#club-start_year').limit('4','#charsLeftStartYear');
	jQuery('#club-square').limit('7','#charsLeftSquare');
    jQuery('#club-title').limit('125','#charsLeftTitle');
    
    jQuery('td').click(function (e) {
        var id = jQuery(this).closest('tr').data('key');
        if(e.target == this && id != undefined)
            location.href = 'banner-update?id=' + id;
    });
    
    jQuery('#form-club').on('afterValidate', function (event, messages, errorAttributes) {
        if(errorAttributes.length){
            jQuery('#alerts').append('<div class="message-box">Не все поля заполнены корректно</div>');
            //window.setTimeout(function() { jQuery("#alerts").html(''); }, 2000);
        }
        return true;
    });
    
    google.maps.event.addDomListener(document.getElementById('w2'), 'click', function() {
          //window.alert('Map was clicked!');
          jQuery('#club-coordinates.inputbox').val(jQuery('#club-coordinates.kolyunya-map-input-widget-input').val());
    });

    google.maps.event.addDomListener(document.getElementById('w2'), 'mouseup', function() {
          //window.alert('Map was clicked!');
          jQuery('#club-coordinates.inputbox').val(jQuery('#club-coordinates.kolyunya-map-input-widget-input').val());
    });
JS
);

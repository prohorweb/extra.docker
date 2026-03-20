<?php

/* @var $this yii\web\View */
/* @var $rooms \common\models\Rooms */
/* @var $typeRasp \common\models\TypeRasp */
/* @var $typeRaspProvider yii\data\ActiveDataProvider */
/* @var $programClassesProvider yii\data\ActiveDataProvider */
/* @var $dataProvider yii\data\ActiveDataProvider */

use common\models\GroupPrograms;
use kartik\color\ColorInput;
use kartik\editable\Editable;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use common\widgets\Alert;
use yii\widgets\ActiveForm;

$this->title = 'Параметры расписания';

if(Yii::$app->session->getAllFlashes()) {
    $this->registerJs(<<<JS
	window.setTimeout(function() { jQuery(".message-box").alert('close'); }, 2000);
JS
);
}

$colorPluginOptions =  [
    'showPalette' => true,
    'showPaletteOnly' => true,
    'showSelectionPalette' => true,
    'showAlpha' => false,
    'allowEmpty' => false,
    'preferredFormat' => 'name',
    'hideAfterPaletteSelect' => true,
    'showInitial' => true,
    'palette' => [["#a89091", "#eac1ba", "#d28d8a", "#dddd6e", "#e3c88a", "#95bbc2"]]
];
?>
<div class="content-box clearfix">
    <?= Alert::widget(['closeButton' => false, 'options' => ['class' => 'message-box']]) ?>
    <div class="title-box clearfix">
        <h1 class="page-title"><?= Html::encode($this->title) ?></h1>
        <a href="<?= Url::to(['/rasp']) ?>" class="btn btn--grey"><i class="flaticon-left-arrow"></i>Назад</a>
    </div>
    <div class="wrapper">
        <h2>Параметры оптимизации материала</h2>
        <?php $form = ActiveForm::begin(['action' => 'params2', 'options' => ['data-pjax' => true]]); ?>
        <div class="input-row">
            <?= $form->field($model, 'title', ['labelOptions' => ['class' => 'input-label']])->textInput(['class' => 'inputbox span10', 'maxlength' => true])->hint('осталось символов: <span id="charsLeftMetaTitle"></span>') ?>
        </div>

        <div class="input-row">
            <?= $form->field($model, 'keywords', ['labelOptions' => ['class' => 'input-label']])->textInput(['class' => 'inputbox span10', 'maxlength' => true]) ?>
        </div>

        <div class="input-row">
            <?= $form->field($model, 'description', ['labelOptions' => ['class' => 'input-label']])->textInput(['class' => 'inputbox span10', 'maxlength' => true]) ?>
        </div>

        <div class="input-row">
            <?= Html::submitButton('Обновить', ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>

        <h2>Зоны клуба</h2>
        <?php $form = ActiveForm::begin(['action' => 'params', 'options' => ['data-pjax' => true]]); ?>
        <div class="input-row">
            <?= $form->field($rooms, 'title', ['template' => '{label}<div class="form-inline">{input}<button type="submit" class="btn ml15">Добавить</button></div>{error}{hint}', 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox span10'])->hint('осталось символов: <span id="charsLeftTitle"></span>') ?>
        </div>
        <?php ActiveForm::end(); ?>

        <?= GridView::widget([
            'bootstrap' => false,
            'export' => false,
            'resizableColumns' => false,
            'dataProvider' => $dataProvider,
            'layout' => '{items}{pager}',
            'tableOptions' => ['class' => 'table-style'],
            'emptyText' => 'Записей не найдено',
            'columns' => [
                [
                    'class' => 'kartik\grid\EditableColumn',
                    'attribute' => 'title',
                    'vAlign' => 'middle',
                    //'width' => '210px',
                    //'refreshGrid' => true,
                    'editableOptions' => [
                        'formOptions' => ['action' => ['/rasp/params-update']],
                        'preHeader' => '<i class="glyphicon glyphicon-edit"></i> Редактирование',
                        'header' => ' ',
                        'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                    ],
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'headerOptions' => ['class' => 'w20p'],
                    'header' => 'Действия',
                    'template' => '<ul class="panel-icons clearfix">{delete}</ul>',
                    'buttons' => [
                        'delete' => function ($url, $rooms) {
                            $url = Url::to(['rasp/params-delete', 'id' => $rooms->id]);
                            return '<li>' . Html::a('<i class="flaticon-delete"></i>', $url, ['data-method' => 'post', 'data-pjax' => '0']) . '</li>';
                        },
                    ]
                ],
            ],
        ]); ?>

        <h3 class="mb30">Настройка цветовой гаммы</h3>
        <?= GridView::widget([
            'bootstrap' => false,
            'export' => false,
            'resizableColumns' => false,
            'dataProvider' => $programClassesProvider,
            'layout' => '{items}{pager}',
            'tableOptions' => ['class' => 'table-style'],
            'emptyText' => 'Записей не найдено',
            'columns' => [
                [
                    'attribute' => 'title',
                    'label' => 'Название',
                ],
                [
                    'class' => 'kartik\grid\EditableColumn',
                    'attribute' => 'color',
                    'format'=>'raw',
                    'value'=>function ($model, $key, $index, $widget) {
                        if($model->color){
                        return "<span class='badge' style='background-color: {$model->color}; padding: 14px; border-radius: 4px;'> </span>  <code>" . $model->color . '</code>';
                        } else {
                            return "<span class='badge' style='background-color: transparent; padding: 14px; border-radius: 4px;'> </span>  <code>Не задано</code>";
                        }
                    },
                    'label' => 'Действия',
                    'vAlign' => 'middle',
                    //'width' => '210px',
                    //'refreshGrid' => true,
                    'editableOptions' => function ($model, $key, $index) use ($colorPluginOptions) {
                        return [
                            'formOptions' => ['action' => ['/rasp/color-update']],
                            'header' => 'Цвет',
                            'inputType' => Editable::INPUT_HIDDEN,
                            'afterInput' => function ($form, $widget) use ($model, $index, $colorPluginOptions) {
                                $model->color = $model->color ? $model->color : 'blue';
                                return $form->field($model, "color")->widget(ColorInput::classname(), [
                                    'showDefaultPalette' => false,
                                    'options' => ['id' => "color-{$index}"],
                                    'pluginOptions' => $colorPluginOptions,
                                ]);
                            }
                        ];
                    }
                ],
            ],
        ]); ?>
    </div>
</div>
<?php
$this->registerJs(<<<JS
    $(".color-select .color-select__current a").click(function(){
	    $(this).parents(".color-select").find(".color-select__wrapper").toggle(100);
	return false;
    });

    jQuery('#seo-title').limit('125','#charsLeftMetaTitle');
JS
);

$this->registerCss(<<<CSS
.sp-palette .sp-thumb-el {
    width: 25px;
    height: 25px;
    }
CSS
);
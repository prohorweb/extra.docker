<?php

use common\widgets\Alert;
use kartik\grid\GridView;
//use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\TrainerOptionsType */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Команда клуба: параметры';
$this->params['breadcrumbs'][] = $this->title;

if(Yii::$app->session->getAllFlashes()) {
    $this->registerJs(<<<JS
	window.setTimeout(function() { jQuery(".message-box").alert('close'); }, 2000);
JS
    );
}
?>
<div class="content-box clearfix">
    <?= Alert::widget(['closeButton' => false, 'options' => ['class' => 'message-box']]) ?>
    <div class="title-box clearfix">
        <h1 class="page-title"><?= Html::encode($this->title) ?></h1>
        <a href="<?= Url::to(['/trainer']) ?>" class="btn btn--grey"><i class="flaticon-left-arrow"></i>Назад</a>
    </div>
    <div class="wrapper">
        <h2>Параметры оптимизации материала</h2>
        <?php $form = ActiveForm::begin(['action' => 'params2', 'options' => ['data-pjax' => true]]); ?>
        <div class="input-row">
            <?= $form->field($seo, 'title', ['labelOptions' => ['class' => 'input-label']])->textInput(['class' => 'inputbox span10', 'maxlength' => true])->hint('осталось символов: <span id="charsLeftMetaTitle"></span>') ?>
        </div>

        <div class="input-row">
            <?= $form->field($seo, 'keywords', ['labelOptions' => ['class' => 'input-label']])->textInput(['class' => 'inputbox span10', 'maxlength' => true]) ?>
        </div>

        <div class="input-row">
            <?= $form->field($seo, 'description', ['labelOptions' => ['class' => 'input-label']])->textInput(['class' => 'inputbox span10', 'maxlength' => true]) ?>
        </div>

        <div class="input-row">
            <?= Html::submitButton('Обновить', ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>

        <?php $form = ActiveForm::begin(['action' => 'options-create', 'options' => ['data-pjax' => true]]); ?>
        <div class="input-row">
            <?= $form->field($model, 'title', ['template' => '{label}<div class="form-inline">{input}<button type="submit" class="btn ml15">Добавить</button></div>{error}{hint}', 'labelOptions' => ['class' => 'input-label']])->textInput(['maxlength' => true, 'class' => 'inputbox span10'])->hint('осталось символов: <span id="charsLeftTitle"></span>') ?>
        </div>
        <?php ActiveForm::end(); ?>

        <?php Pjax::begin(); ?>
        <?= GridView::widget([
            'bootstrap' => false,
            'export' => false,
            'resizableColumns' => false,
            'dataProvider' => $dataProvider,
            'layout' => '{items}{pager}',
            'tableOptions' => ['class' => 'table-style'],
            'emptyText' => 'Записей не найдено',
            //'rowOptions'   => ['style' => 'cursor:pointer;'],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn', 'header' => '№'],

                //'id',
                [
                    'attribute' => 'status',
                    'headerOptions' => ['class' => 'tcenter'],
                    'contentOptions' => ['class' => 'tcenter'],
                    'content' => function ($data) {
                        return Html::a('', Url::to([($data->status ? 'options-disable' : 'options-activate') . '?id=' . $data->id]), ['class' => 'flaticon-eye' . ($data->status ? '' : '-off') . ' icon25 '.($data->status ? 'color_green' : '')]);
                    }
                ],
                //'position',
                //'title',
                [
                    'class' => 'kartik\grid\EditableColumn',
                    'attribute' => 'title',
                    //'vAlign' => 'middle',
                    //'width' => '210px',
                    'readonly' => function ($model, $key, $index, $widget) {
                        return (!$model->status); // do not allow editing of inactive records
                    },
                    //'refreshGrid' => true,
                    'editableOptions' => [
                        'formOptions' => ['action' => ['/trainer/options-update']],
                        'preHeader' => '<i class="glyphicon glyphicon-edit"></i> Редактирование',
                        'header' => ' ',
                        'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                    ],
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => 'Действия',
                    'template' => '<ul class="panel-icons clearfix">{delete} {up} {down}</ul>',
                    'buttons' => [
                        /*'update' => function ($url, $model) {
                            return '<li>' . Html::a('<i class="flaticon-pancil"></i>', $url) . '</li>';
                        },*/
                        'delete' => function ($url, $model) {
                            $url = Url::to(['trainer/options-delete', 'id' => $model->id]);
                            return '<li>' . Html::a('<i class="flaticon-delete"></i>', $url, ['data-method' => 'post', 'data-pjax' => '0']) . '</li>';
                        },
                        'up' => function ($url, $model) {
                            $url = Url::to(['trainer/options-up', 'id' => $model->id]);
                            return '<li>' . Html::a('<i class="flaticon-arrow-up"></i>', $url, ['data-method' => 'post', 'data-pjax' => '0']) . '</li>';
                        },
                        'down' => function ($url, $model) {
                            $url = Url::to(['trainer/options-down', 'id' => $model->id]);
                            return '<li>' . Html::a('<i class="flaticon-arrow-down"></i>', $url, ['data-method' => 'post', 'data-pjax' => '0']) . '</li>';
                        },
                    ]
                ],
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>

<?php
$this->registerJs(<<<JS
	jQuery('#traineroptionstype-title').limit('125','#charsLeftTitle');
	jQuery('#seo-title').limit('125','#charsLeftMetaTitle');
JS
);
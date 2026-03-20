<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $metro common\models\Metros */
/* @var $form yii\widgets\ActiveForm */
/* @var $dataProviderMetros yii\data\ActiveDataProvider */
?>

<hr>

<h2>Информация о ближайших станций метро</h2>
<p>Если в Вашем городе нет метро, то оставьте данный блок незаполненным.</p>

<?php Pjax::begin(['id' => 'metro']); ?>
<?php $form = ActiveForm::begin(['action' => 'metro-create', 'options' => ['data-pjax' => true]]); ?>

    <div class="input-row row clearfix">
        <?= $form->field($metro, 'name', ['options' => ['class' => 'col span6'], 'labelOptions' => ['class' => 'input-label']])->textInput(['class' => 'inputbox'])->label('Название станции метро')->hint('осталось символов: <span id="charsLeftMetro"></span>') ?>
        <div class="col span6">
            <label for="" style="margin: 0 0 15px; display: block;">&nbsp;</label>
            <?= Html::submitButton('Добавить', ['class' => 'btn btn--grey']) ?>
        </div>
    </div>

    <?= $form->field($metro, 'partners_id')->hiddenInput(['value' => $_GET['id']])->label(false) ?>

<?php ActiveForm::end(); ?>
<?php Pjax::end(); ?>

<?php Pjax::begin(['id' => 'metros']); ?>
<?= GridView::widget([
    'dataProvider' => $dataProviderMetros,
    'layout' => '{items}{pager}',
    'tableOptions' => ['class' => 'table-style'],
    'emptyText' => 'Записей не найдено',
    'columns' => [
        ['class' => 'yii\grid\SerialColumn', 'header' => '№'],
        //'id',
        'name',
        //'position',
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => 'Действия',
            //'controller' => 'banner',
            'template' => '<ul class="panel-icons clearfix">{delete} {up} {down}</ul>',
            'buttons' => [
                'delete' => function ($url, $model) {
                    $url = Url::to(['metro-delete', 'id' => $model->id, '#' => 'metro']);
                    return '<li>' . Html::a('<i class="flaticon-delete"></i>', $url, ['data-method' => 'post', 'data-pjax' => 'metros']) . '</li>';
                },
                'up' => function ($url, $model) {
                    $url = Url::to(['metro-up', 'id' => $model->id, '#' => 'metro']);
                    return '<li>' . Html::a('<i class="flaticon-arrow-up"></i>', $url, ['data-method' => 'post', 'data-pjax' => 'metros']) . '</li>';
                },
                'down' => function ($url, $model) {
                    $url = Url::to(['metro-down', 'id' => $model->id, '#' => 'metro']);
                    return '<li>' . Html::a('<i class="flaticon-arrow-down"></i>', $url, ['data-method' => 'post', 'data-pjax' => 'metros']) . '</li>';
                },
            ]
        ],
    ],
]); ?>
<?php Pjax::end(); ?>

<a name="metro"></a>

<?php
$this->registerJs(<<<JS
    //jQuery('#metros-name').limit('125','#charsLeftMetro');
    
    jQuery("#metro").on("pjax:end", function() {
            jQuery('#metropartners-name').val('');
            jQuery.pjax.reload({container:"#metros"});  //Reload GridView
    });
JS
);

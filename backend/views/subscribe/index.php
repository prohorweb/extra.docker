<?php

use common\widgets\Alert;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Подписки';
$this->params['breadcrumbs'][] = $this->title;

if (Yii::$app->session->getAllFlashes()) {
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
    </div>
    <div class="wrapper">

        <div class="buttons mb25 clearfix">
            <?= Html::a('Скачать базу', ['export-csv'], ['class' => 'btn fleft']) ?>
            <div class="fright page-count">
                <p>Показать на странице:</p>
                <?= Html::beginForm('', 'get', ['class' => 'fright']) ?>
                <?= Html::dropDownList('per-page', $dataProvider->pagination->pageSize, [
                    '20' => '20',
                    '50' => '50',
                    '100' => '100',
                ], ['onchange' => 'this.form.submit()']) ?>
                <?= Html::endForm() ?>
            </div>
        </div>
        <?php Pjax::begin(); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => '{items}{pager}',
            'tableOptions' => ['class' => 'table-style'],
            'emptyText' => 'Записей не найдено',
            //'rowOptions' => ['style' => 'cursor:pointer;'],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn', 'header' => '№'],
                //'id',
                'email',
                [
                    'label' => 'Дата подписки',
                    'attribute' => 'created_at',
                    'format' => ['date', 'php:d.m.Y'],
                    'headerOptions' => ['class' => 'tcenter'],
                    'contentOptions' => ['class' => 'color_black55 tcenter']
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => 'Действия',
                    'template' => '<ul class="panel-icons clearfix">{delete}</ul>',
                    'buttons' => [
                        'delete' => function ($url, $model) {
                            return '<li>' . Html::a('<i class="flaticon-delete"></i>', $url, ['data-method' => 'post', 'data-pjax' => '0']) . '</li>';
                        },
                    ]
                ],
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>

<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use common\widgets\Alert;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Менеджер пользователей';

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
    </div>
    <div class="wrapper">

        <div class="buttons mb25 clearfix">
            <?= Html::a('Создать', ['create'], ['class' => 'btn fleft']) ?>
            <div class="fright page-count">
                <p>Показать на странице:</p>
                <?= Html::beginForm('', 'get', ['class' => 'fright']) ?>
                <?= Html::dropDownList('per-page', $dataProvider->pagination->pageSize, [
                    '10' => '10',
                    '20' => '20',
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
            'rowOptions'   => ['style' => 'cursor:pointer;'],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn', 'header' => '№'],
                [
                    'label' => 'Создано',
                    'attribute' => 'created_at',
                    'format' => ['date', 'php:d.m.Y']
                ],
                [
                    'label' => 'Доступ',
                    'attribute' => 'status',
                    'headerOptions' => ['class' => 'tcenter'],
                    'contentOptions' => ['class' => 'tcenter'],
                    'content' => function ($data) {
                        return Html::a('', Url::to([($data->status ? 'disable' : 'activate') . '?id=' . $data->id . '&page=' . (isset($_GET['page']) ? $_GET['page'] : 0)]), ['class' => 'flaticon-eye' . ($data->status ? '' : '-off') . ' icon25 '.($data->status ? 'color_green' : '')]);
                    }
                ],
                [
                    'label' => 'Имя',
                    'attribute' => 'username'
                ],
                [
                    'label' => 'Email',
                    'attribute' => 'email',
                    'format' => 'email'
                ],
                //'auth_key',
                //'password_hash',
                //'password_reset_token',
                // 'created_at',
                // 'updated_at',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => 'Действия',
                    'template' => '<ul class="panel-icons clearfix">{update} {delete}</ul>',
                    'buttons' => [
                        'update' => function ($url, $model) {
                            return '<li>' . Html::a('<i class="flaticon-pancil"></i>', $url) . '</li>';
                        },
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

<?php
$this->registerJs(<<<JS
    jQuery('td').click(function (e) {
        var id = jQuery(this).closest('tr').data('key');
        if(e.target == this && id != undefined)
            location.href = '/admin/user/update?id=' + id;
    });
JS
);

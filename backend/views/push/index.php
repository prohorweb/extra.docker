<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use yii\widgets\Pjax;
use common\widgets\Alert;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $token string */

$this->title = 'Push уведомления';

if(Yii::$app->session->getAllFlashes()) {
    $this->registerJs(<<<JS
	window.setTimeout(function() { jQuery(".message-box").alert('close'); }, 4000);
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
            <div class="input-row">
                <div class="col span5" style="float:right;">
                    <h2>Уведомления,<br> запланированные и отправленные</h2>
                    <table class="table-style">
                        <tbody>
                        <?php if ($model) {
                            foreach ($model as $notification) {
                                $filterValue = $notification['filters'][0]['value'] ?? null;
                                if (!($notification['canceled'] ?? false) && $filterValue === $token) { ?>
                                <tr>
                                    <td><?= isset($notification['contents']['ru']) ? $notification['contents']['ru'] : '' ?></td>
                                    <td>
                                    <?php if($notification['successful']){ ?>
                                        Отправлено
                                    <?php } elseif(Yii::$app->session->getFlash('delete')) { ?>
                                        <?= Yii::$app->session->getFlash('delete') ?>
                                    <?php } else { ?>
                                    <?= Html::a('<img src="' . Url::to(['/images/close.png']) . '">', ['delete', 'id' => $notification['id']], ['data-method' => 'post']) ?>
                                    <?php } ?>
                                    </td>
                                </tr>
                            <?php } }
                        } ?>
                        </tbody>
                    </table>
                </div>
                <div class="col span6">
                    <?php $form = ActiveForm::begin(['action' => 'push/create']) ?>
                    <div class="input-row">
                        <div class="col span12">
                            <?= Html::label('Заголовок сообщения', '', ['class' => 'input-label']) ?>
                            <?= Html::textInput('title', null, ['class' => 'inputbox', 'id' => 'push-title']) ?>
                            <div class="hint-block">осталось символов: <span id="charsLeftTitle"></span></div>
                        </div>
                    </div>
                    <div class="input-row">
                        <div class="col span12">
                            <?= Html::label('Текст сообщения', '', ['class' => 'input-label']) ?>
                            <?= Html::textarea('text', null, ['class' => 'inputbox', 'id' => 'push-text']) ?>
                            <div class="hint-block">осталось символов: <span id="charsLeftText"></span></div>
                        </div>
                    </div>
                    <br>
                    <div class="input-row">
                        <div class="col span12">
                            <div class="checkbox">
                                <?= Html::checkbox('now', null, ['id' => 'now']) ?>
                                <?= Html::label('Отправить позже', 'now') ?>
                            </div>
                        </div>
                    </div>
                    <div class="row input-row clearfix">
                        <div class="col span6">
                            <?= Html::label('Дата отправления', '', ['class' => 'input-label']) ?>
                            <?= Html::textInput('date', null, ['class' => 'inputbox input-date']) ?>
                        </div>
                        <div class="col span6">
                            <?= Html::label('Время отправления', '', ['class' => 'input-label']) ?>
                            <?= MaskedInput::widget([
                                'name' => 'time',
                                'mask' => '99:99',
                                'value' => Yii::$app->formatter->asTime(new DateTime(), 'HH:mm'),
                                'options' => ['class' => 'inputbox']
                            ]) ?>
                        </div>
                        <div class="col span12 mt15">
                            <?= Html::submitButton('Отправить', ['class' => 'btn']) ?>
                        </div>
                    </div>
                    <?php ActiveForm::end() ?>
                </div>
            </div>
        </div>
    </div>
<?php
$this->registerJs(<<<JS
	jQuery('#push-title').limit('40','#charsLeftTitle');
	jQuery('#push-text').limit('60','#charsLeftText');

JS
);
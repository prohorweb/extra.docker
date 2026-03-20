<?php

use common\widgets\Alert;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Club */
/* @var $metro common\models\Metros */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $dataProviderMetros yii\data\ActiveDataProvider */

$this->title = 'Информация о клубе';
$this->params['breadcrumbs'][] = ['label' => 'Clubs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';

if(Yii::$app->session->getAllFlashes()) {
    $this->registerJs(<<<JS
	window.setTimeout(function() { jQuery(".message-box").alert('close'); }, 2000);
JS
    );
}
?>
<div class="content-box clearfix">
    <?= Alert::widget(['closeButton' => false, 'options' => ['class' => 'message-box']]) ?>
    <div id="alerts"></div>
    <div class="title-box clearfix">
        <h1 class="page-title"><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="wrapper">

        <?= $this->render('_form', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]) ?>

        <?= $this->render('_metro', [
            'dataProviderMetros' => $dataProviderMetros,
            'metro' => $metro,
        ]) ?>

    </div>
</div>

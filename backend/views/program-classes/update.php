<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\ProgramClasses */
/* @var $id string */

$this->title = 'Менеджер занятий: обновить материал';
$this->params['breadcrumbs'][] = ['label' => 'Program Classes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';

$_SESSION['referrer'] = strpos((string)Yii::$app->request->referrer, 'page') !== false ? Yii::$app->request->referrer : Url::to(['/program-classes', 'id' => $id]);
?>
<div class="content-box clearfix">
    <div id="alerts"></div>
    <div class="title-box clearfix">
        <h1 class="page-title"><?= Html::encode($this->title) ?></h1>
        <a href="<?= $_SESSION['referrer'] ?>" class="btn btn--grey"><i class="flaticon-left-arrow"></i>Назад</a>
    </div>
    <div class="wrapper">

        <?= $this->render('_form', [
            'model' => $model,
            'id' => $id,
        ]) ?>

    </div>
</div>

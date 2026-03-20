<?php

use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model common\models\ProgramClasses */
/* @var $id string */

$this->title = 'Менеджер занятий: создать материал';
$this->params['breadcrumbs'][] = ['label' => 'Program Classes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-box clearfix">
    <div id="alerts"></div>
    <div class="title-box clearfix">
        <h1 class="page-title"><?= Html::encode($this->title) ?></h1>
        <a href="<?= Url::to(['/program-classes', 'id' => $id]) ?>" class="btn btn--grey"><i class="flaticon-left-arrow"></i>Назад</a>
    </div>
    <div class="wrapper">

        <?= $this->render('_form', [
            'model' => $model,
            'id' => $id,
        ]) ?>

    </div>
</div>

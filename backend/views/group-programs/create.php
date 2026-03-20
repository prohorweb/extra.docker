<?php

use common\widgets\Alert;
use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model common\models\GroupPrograms */
/* @var $banners \common\models\Banners */

$this->title = 'Менеджер групповых программ: создать материал';
$this->params['breadcrumbs'][] = ['label' => 'Group Programs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

if(Yii::$app->session->getAllFlashes()) {
    $this->registerJs(<<<JS
	window.setTimeout(function() { jQuery(".message-box").alert('close'); }, 2000);
    $(".tabs-list dd").addClass("active");
JS
);
}
?>
<div class="content-box clearfix">
    <?= Alert::widget(['closeButton' => false, 'options' => ['class' => 'message-box']]) ?>
    <div id="alerts"></div>
    <div class="title-box clearfix">
        <h1 class="page-title"><?= Html::encode($this->title) ?></h1>
        <a href="<?= Url::to(['/group-programs']) ?>" class="btn btn--grey"><i class="flaticon-left-arrow"></i>Назад</a>
    </div>
    <div class="wrapper">

        <?= $this->render('_form', [
            'model' => $model,
            'banners' => $banners,
        ]) ?>

    </div>
</div>

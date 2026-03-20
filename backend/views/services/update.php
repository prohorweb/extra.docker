<?php

use common\widgets\Alert;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Services */
/* @var $banners \common\models\Banners */

$this->title = 'Менеджер услуг: обновить материал';

$_SESSION['referrer'] = strpos((string)Yii::$app->request->referrer, 'page') !== false ? Yii::$app->request->referrer : (isset($_SESSION['referrer']) && strpos($_SESSION['referrer'], 'services') !== false ? $_SESSION['referrer'] : Url::to(['/services']));

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
        <a href="<?=$_SESSION['referrer'] ?>" class="btn btn--grey"><i class="flaticon-left-arrow"></i>Назад</a>
    </div>
    <div class="wrapper">

        <?= $this->render('_form', [
            'model' => $model,
            'banners' => $banners,
            'model_banners' => $model_banners,
        ]) ?>

    </div>
</div>

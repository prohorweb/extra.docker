<?php
/* @var $this yii\web\View */
/* @var $model \common\models\GroupProgramsParams */
use yii\helpers\Html;
use yii\helpers\Url;
use common\widgets\Alert;
use yii\widgets\ActiveForm;

$this->title = 'Параметры групповых программ';
//$this->params['breadcrumbs'][] = $this->title;

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
        <a href="<?= Url::to(['/group-programs']) ?>" class="btn btn--grey"><i class="flaticon-left-arrow"></i>Назад</a>
    </div>
    <div class="wrapper">
        <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true]]); ?>

        <div class="input-row">
            <?= Html::submitButton('Сохранить', ['class' => 'btn']) ?>
        </div>

        <div class="input-row">
            <?= $form->field($model, 'text', ['labelOptions' => ['class' => 'input-label']])->textarea(['rows' => 6, 'class' => 'form-control js-rich-editor']) ?>
        </div>

        <div class="input-row">
            <?= $form->field($model, 'meta_title', ['labelOptions' => ['class' => 'input-label']])->textInput(['class' => 'inputbox span10', 'maxlength' => true])->hint('осталось символов: <span id="charsLeftMetaTitle"></span>') ?>
        </div>

        <div class="input-row">
            <?= $form->field($model, 'meta_keywords', ['labelOptions' => ['class' => 'input-label']])->textInput(['class' => 'inputbox span10', 'maxlength' => true]) ?>
        </div>

        <div class="input-row">
            <?= $form->field($model, 'meta_description', ['labelOptions' => ['class' => 'input-label']])->textInput(['class' => 'inputbox span10', 'maxlength' => true]) ?>
        </div>

        <div class="input-row">
            <?= Html::submitButton('Сохранить', ['class' => 'btn']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>

<?php
$this->registerJs(<<<JS
JS
);
<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $trainerOptions \common\models\TrainerOptionsType */

/* @var $seo \common\models\Seo */

$this->title = $seo->title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $seo->keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $seo->description]);

?>

<section class="page-item">
    <div class="container">

        <nav class="d-md-block d-none" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= Url::to(['/']) ?>"><?= $this->params['club']->title ?></a></li>
                <li class="breadcrumb-item"><a href="<?= Url::to(['/es/club/']) ?>">О клубе</a></li>
                <li class="breadcrumb-item active" aria-current="page">Тренеры</li>
            </ol>
        </nav>

        <h2 class="section-heading">ТРЕНЕРЫ клуба <?= $this->params['club']->title ?></h2>

        <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-3">
            <div class="container-fluid">
                <a class="navbar-brand fs-6" href="#">Выберите направление</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDarkDropdown" aria-controls="navbarNavDarkDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse  my-1" id="navbarNavDarkDropdown">
                    <?php $form = ActiveForm::begin(['options' => ['class' => 'd-flex justify-content-evenly']]) ?>
                    <?= Html::dropDownList(
                        'filter',
                        isset($_POST['filter']) && !isset($_POST['reset']) ? $_POST['filter'] : null,
                        ArrayHelper::map($trainerOptions, 'id', 'title'),
                        ['class' => 'form-select me-3', 'prompt' => 'Все направления']
                    ) ?>
                    <button type="submit" class="btn btn-primary me-3">Показать</button>
                    <button type="submit" name="reset" class="btn btn-primary btn-dark me-3">Сбросить</button>
                    <?php ActiveForm::end() ?>
                </div>
            </div>
        </nav>

        <div class="row">
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'layout' => '{items}',
                'emptyText' => 'Записей не найдено',
                'itemView' => function ($model, $key, $index, $widget) {
                    echo $this->render('_post', ['model' => $model]);
                }
            ]); ?>
        </div>

    </div>
</section>

<?= $this->render('/club/_subscribe') ?>
<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\HtmlPurifier;
use yii\widgets\ListView;


/* @var $this yii\web\View */
/* @var $model \common\models\Services */
/* @var $banners \common\models\Banners */
/* @var $trainer \common\models\Trainers */
/* @var $dataProviderOther yii\data\ActiveDataProvider */

$this->title = $model->title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $model->meta_keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $model->meta_description]);
?>
<div class="service-header">
    <div class="d-flex align-items-center justify-content-center title"><?= Html::encode($model->title) ?></div>
    <div class="overlay"></div>
    <img class="w-100" src="/uploads/image/banners/1440/banner_site-1556301986.jpg" alt="">
</div>

<section class="page-item actions"  id="actions">
    <div class="container">
        <nav class="d-md-block d-none" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= Url::to(['/']) ?>"><?= $this->params['club']->title ?></a></li>
                <li class="breadcrumb-item"><a href="<?= Url::to(['/services/']) ?>">Услуги</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= Html::encode($model->title) ?></li>
            </ol>
        </nav>
        <div class="row content  mt-3 p-3">
            <div class="item-page">
                <?= HtmlPurifier::process($model->content) ?>
            </div>
        </div>
    </div>
</section>

<section class="other-teams">
    <div class="container">
        <h2 class="section-heading text-uppercase my-5 text-center">ТРЕНЕРЫ клуба <?= $this->params['club']->title ?></h2>
        <?= ListView::widget([
            'dataProvider' => $dataProviderOther,
            'options' => ['class' => 'row'],            
            'itemOptions' => ['tag' => null],
            'layout' => '{items}',
            'emptyText' => 'Записей не найдено',
            'itemView' => '_post2',
        ]); ?>
        <div class="d-flex justify-content-center mb-4">
            <a class="btn btn-primary btn-xl" href="<?= Url::to(['/es/command/']) ?>">Все тренеры</a>
        </div>
    </div>

</section>

<?= $this->render('/history/_subscribe') ?>
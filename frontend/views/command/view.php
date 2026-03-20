<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */
/* @var $model \common\models\Trainers */
/* @var $banners \common\models\Banners */

$this->title = $model->meta_title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $model->meta_keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $model->meta_description]);

?>
<section class="page-item">
    <div class="container">
        <div class="breadcramb">
            <nav class="d-md-block d-none" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= Url::to(['/']) ?>"><?= $this->params['club']->title ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= Url::to(['/es/command/']) ?>">Тренеры</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= Html::encode($model->title) ?></li>
                </ol>
            </nav>
        </div>
        <h2 class="section-heading"><?= Html::encode($model->title) ?></h2>
        <div class="row">
            <div class="col-lg-6">
                <div class="item-page">
                    <?= HtmlPurifier::process($model->content) ?>
                </div>
            </div>
            <div class="col-lg-6">
                <?php foreach ($banners as $banner) break; { ?>
                    <img class="w-100" src="<?= $banner->img1440 ? '/uploads/image/banners/1440/' . $banner->img1440 : 'http://placehold.it/645x450' ?>" alt="" />
                <?php } ?>
            </div>
        </div>
    </div>
</section>

<?= $this->render('/history/_subscribe') ?>
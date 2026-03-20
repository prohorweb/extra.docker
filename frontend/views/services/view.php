<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */
/* @var $model \common\models\Services */
/* @var $banners \common\models\Banners */
/* @var $service \common\models\Services */

$this->title = $model->meta_title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $model->meta_keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $model->meta_description]);

?>

<section class="page-item actions" id="actions">
    <div class="container">
        <nav class="d-md-block d-none" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= Url::to(['/']) ?>"><?= $this->params['club']->title ?></a></li>
                <li class="breadcrumb-item"><a href="<?= Url::to(['/services/']) ?>">Услуги</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= Html::encode($model->title) ?></li>
            </ol>
        </nav>
        <h2 class="section-heading"><?= Html::encode($model->title) ?></h2>
        <p><?php foreach ($banners as $banner) break; { ?>
            <img class="w-100"
                src="<?= $banner->img1440 ? '/uploads/image/banners/1440/' . $banner->img1440 : '//placehold.it/1904x698' ?>"
                alt="" />
            <?php } ?>
        </p>
        <div class="content p-3">
            <?= $model->content ?>
        </div>

    </div>
    <div class="container">
        <h2 class="section-heading">Другие услуги клуба</h2>

        <div class="row">
            <?php foreach ($services as $service) { ?>
            <div class="col-lg-4 col-md-6">
                <a class="card" href="<?= Url::to(['/services/' . $service->alias]) ?>">
                    <img class="card-img-top"
                        src="<?= $service->img ? '/uploads/image/services/' . $service->img : '//placehold.it/646x400' ?>"
                        alt="...">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="card-body_wrapper">
                                <h5 class="card-title"><?= $service->title ?></h5>
                            </div>
                            <div class="btn-arrow d-flex align-items-center"><i
                                    class="fa-sharp fa-solid fa-arrow-right"> </i></div>
                        </div>
                    </div>
                </a>
            </div>
            <?php } ?>
        </div>
    </div>
</section>

<?= $this->render('/club/_subscribe') ?>
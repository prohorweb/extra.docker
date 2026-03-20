<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */
/* @var $model \common\models\GroupPrograms */
/* @var $programClasses \common\models\ProgramClasses */
/* @var $groupProgram \common\models\GroupPrograms */

$this->title = $model->meta_title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $model->meta_keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $model->meta_description]);

?>

<section class="actions" id="actions">
    <div class="container">
        <nav class="d-md-block d-none" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= Url::to(['/']) ?>"><?= $this->params['club']->title ?></a></li>
                <li class="breadcrumb-item"><a href="<?= Url::to(['/services/']) ?>">Услуги</a></li>
                <li class="breadcrumb-item"><a href="<?= Url::to(['/services/programs/']) ?>">Групповые программы</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"><?= Html::encode($model->title) ?></li>
            </ol>
        </nav>
        <h2 class="section-heading pt-3"><?= Html::encode($model->title) ?></h2>
        <div class="content">
            <div class="page__desc"><?= $model->content ?></div>

            <?php foreach ($programClasses as $programClass) { ?>
            <h3><?= $programClass->title ?></h3>
            <b><?= $programClass->intro ?></b>
            <p><?= $programClass->duration ?></p>
            <?php } ?>
        </div>
        <h2 class="section-heading my-5">другие групповые программы</h2>

        <div class="row">
            <?php foreach ($groupPrograms as $groupProgram) { ?>
            <div class="col-lg-4 col-md-6">
                <a class="card" href="<?= Url::to(['/services/programs/' . $groupProgram->alias]) ?>/">
                    <img class="card-img-top"
                        src="<?= $groupProgram->img ? '/uploads/image/group_programs/' . $groupProgram->img : '//placehold.it/646x400' ?>"
                        alt="...">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="card-body_wrapper">
                                <h5 class="card-title"><?= $groupProgram->title ?></h5>
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
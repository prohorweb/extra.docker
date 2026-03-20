<?php

use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
use yii\widgets\ListView;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $params \common\models\ClubCardsParams */

$this->title = $params->meta_title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $params->meta_keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $params->meta_description]);

?>
<section class="actions" id="actions">
    <div class="container">
        <nav class="d-md-block d-none" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= Url::to(['/']) ?>"><?= $this->params['club']->title ?></a></li>
                <li class="breadcrumb-item active" aria-current="page">Акции</li>
            </ol>
        </nav>
        <h2 class="section-heading mb-5">Акции клуба <?= $this->params['club']->title ?></h2>
                <?= ListView::widget([
                    'dataProvider' => $dataProvider,
                    'itemOptions' => function ($model, $key, $index, $widget) {
                        return ['class' => 'col-md-6 '];
                    },
                    'layout' => '<div class="row">{items}</div>',
                    'emptyText' => 'Записей не найдено',
                    'itemView' => '_post',
                ]) ?>
    </div>
</section>
<!-- Contact-->
<?= $this->render('/club/_subscribe') ?>
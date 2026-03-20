<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use lajax\translatemanager\helpers\Language;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $seo \common\models\Seo */

$this->title = $seo->title;
$this->registerMetaTag(['name' => 'keywords', 'content' => $seo->keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $seo->description]);

$models = $dataProvider->getModels();

$alphabet = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
    'А','Б','В','Г','Д','Е','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Э','Ю','Я'];
?>
<main class="main-section main-section--club-page">
    <div class="container">
        <div class="breadcrumb">
            <a href="/" class="breadcrumb__item"><?= Language::t('breadcrumb', 'Главная страница') ?></a>
            <a href="<?= Url::to('/ff/club/') ?>" class="breadcrumb__item"><?= Language::t('breadcrumb', 'Клуб') ?></a>
            <span class="breadcrumb__item"><?= Language::t('breadcrumb', 'Партнеры') ?></span>
        </div>

        <h1 class="main-section__title"><?= Language::t('club', 'Партнеры') ?></h1>

        <div class="tab-nav hidden-xs">
            <div class="tab-nav__item"><a href="<?= Url::to('/ff/club/') ?>"><?= Language::t('club', 'Обзор клуба') ?></a></div>
            <div class="tab-nav__item"><a href="<?= Url::to('/ff/command/') ?>"><?= Language::t('club', 'Команда') ?></a></div>
            <div class="tab-nav__item"><a href="<?= Url::to('/ff/news/') ?>"><?= Language::t('club', 'Новости') ?></a></div>
            <div class="tab-nav__item active"><a href="#"><?= Language::t('club', 'Партнеры') ?></a></div>
            <div class="tab-nav__type">
                <a href="<?= Url::to('/ff/partners/') ?>"><img src="/images/tab-block.png" alt=""/></a>
                <a href="#"><img src="/images/tab-list.png" alt=""/></a>
            </div>
        </div>

        <div class="tab-nav-select tab-nav-select--group">
            <select name="" id="" onchange="if (this.value) window.location.href=this.value">
                <option value="<?= Url::to('/ff/club/') ?>"><?= Language::t('club', 'Обзор клуба') ?></option>
                <option value="<?= Url::to('/ff/command/') ?>"><?= Language::t('club', 'Команда') ?></option>
                <option value="<?= Url::to('/ff/news/') ?>"><?= Language::t('club', 'Новости') ?></option>
                <option value="" selected><?= Language::t('club', 'Партнеры') ?></option>
            </select>
            <a href="<?= Url::to('/ff/partners/') ?>"><img src="/images/tab-block.png" alt=""/></a>
            <a href="#"><img src="/images/tab-list.png" alt=""/></a>
        </div>

        <div class="tab-wrapper">
            <?php Pjax::begin() ?>
            <div class="filter-letter">
                <div class="filter-letter__row">
                    <?php
                    $arr = [];
                    foreach ($models as $key => $model) {
                        $arr[mb_substr($model->title, 0, 1)] = 1;
                    }

                    if(count(array_diff_key([0,1,2,3,4,5,6,7,8,9], $arr)) < 10){ ?>
                        <a href="#partner-0-9">0-9</a>
                    <?php } else { ?>
                        <span style="color: gainsboro">0-9</span>
                    <?php }

                    foreach ($alphabet as $key => $letter) {
                        if ($key <= 25) {
                            if (key_exists($letter, $arr)) { ?>
                                <a href="#partner-<?= $letter ?>"><?= $letter ?></a>
                            <?php } else { ?>
                                <span style="color: gainsboro"><?= $letter ?></span>
                            <?php }
                        }
                    } ?>
                </div>
                <div class="filter-letter__row">
                    <?php foreach ($alphabet as $key => $letter) {
                        if ($key >= 26) {
                            if (key_exists($letter, $arr)) { ?>
                                <a href="#partner-<?= $letter ?>"><?= $letter ?></a>
                            <?php } else { ?>
                                <span style="color: gainsboro"><?= $letter ?></span>
                            <?php }
                        }
                    } ?>
                </div>
            </div>

            <div class="partner-letter-wrap">

                <?php
                $flag = false;
                $flag2 = false;
                $first = true;
                $letter = 0;
                foreach ($models as $key => $model) {
                    while (isset($alphabet[$letter]) && !is_numeric(mb_substr($model->title, 0, 1)) && $alphabet[$letter] != mb_strtoupper(mb_substr($model->title, 0, 1))) {
                        $letter++;
                        $first = true;
                        if ($flag) { ?>
                            </ul>
                            </div>
                            </div>
                        <?php $flag = false;
                        }
                    }
                    if($flag2 && $alphabet[$letter] == mb_substr($model->title, 0, 1)) { ?>
                        </ul>
                        </div>
                        </div>
                    <?php $first = true;
                    }
                    if ($letter < count($alphabet) && $first && !is_numeric(mb_substr($model->title, 0, 1))) { ?>
                        <div class="partner-letter-list" id="partner-<?= $alphabet[$letter] ?>">
                        <div class="partner-letter-list__head"><?= $alphabet[$letter] ?></div>
                        <div class="partner-letter-list__body">
                        <ul class="partner-letter-list__list">
                        <?php
                        $first = false;$flag2 = false;
                    } elseif(is_numeric(mb_substr($model->title, 0, 1)) && $first) { ?>
                            <div class="partner-letter-list" id="partner-0-9">
                            <div class="partner-letter-list__head">0-9</div>
                            <div class="partner-letter-list__body">
                            <ul class="partner-letter-list__list">
                       <?php $first = false; $flag2 = true;} ?>
                    <li><a href="/ff/partners/<?= $model->alias ?>/"><?= $model->title ?></a></li>
                    <?php $flag = true;
                } ?>
                    </ul>
                    </div>
                    </div>
            </div>

            <?php Pjax::end() ?>
        </div>

    </div>
</main>

<?= $this->render('/history/_subscribe') ?>

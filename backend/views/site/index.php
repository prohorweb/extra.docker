<?php

/* @var $this yii\web\View */

use common\widgets\Alert;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Панель управления';
?>
<div class="content-box">
    <?= Alert::widget(['closeButton' => false, 'options' => ['class' => 'message-box']]) ?>
    <h1 class="page-title"><?= Html::encode($this->title) ?></h1>
    <div class="wrapper">
        <div class="row row--small mb20 clearfix">
            <?php if(in_array('rasp', $this->params['roles'])) { ?>
            <div class="col">
                <div class="panel-box panel-box--min">
                    <a href="<?= Url::to('/admin/rasp') ?>">
                        <span class="title">Расписание</span>
                    </a>
                </div>
            </div>
            <?php }
            if(in_array('news', $this->params['roles'])) { ?>
            <div class="col">
                <div class="panel-box panel-box--min">
                    <a href="<?= Url::to('/admin/news') ?>">
                        <span class="title">Новости</span>
                    </a>
                </div>
            </div>
            <?php }
            if(in_array('events', $this->params['roles'])) { ?>
            <div class="col">
                <div class="panel-box panel-box--min">
                    <a href="<?= Url::to('/admin/event') ?>">
                        <span class="title">Мероприятия</span>
                    </a>
                </div>
            </div>
            <?php }
            if(in_array('main_banners', $this->params['roles'])) { ?>
            <div class="col">
                <div class="panel-box panel-box--min">
                    <a href="<?= Url::to('/admin/banners') ?>">
                        <span class="title">Баннеры</span>
                    </a>
                </div>
            </div>
            <?php }
            if(in_array('articles', $this->params['roles'])) { ?>
                <div class="col">
                    <div class="panel-box panel-box--min">
                        <a href="<?= Url::to('/admin/article') ?>">
                            <span class="title">Советы тренеров</span>
                        </a>
                    </div>
                </div>
            <?php }
            if(in_array('shares', $this->params['roles'])) { ?>
            <div class="col">
                <div class="panel-box panel-box--min">
                    <a href="<?= Url::to('/admin/share') ?>">
                        <span class="title">Акции</span>
                    </a>
                </div>
            </div>
            <?php }
            if(in_array('club', $this->params['roles'])) { ?>
            <div class="col">
                <div class="panel-box panel-box--min">
                    <a href="<?= Url::to('/admin/club/update') ?>">
                        <span class="title">Информация о клубе</span>
                    </a>
                </div>
            </div>
            <?php }
            if(in_array('club_cards', $this->params['roles'])) { ?>
            <div class="col">
                <div class="panel-box panel-box--min">
                    <a href="<?= Url::to('/admin/club-cards') ?>">
                        <span class="title">Абонементы</span>
                    </a>
                </div>
            </div>
            <?php } ?>
            <div class="col">
                <div class="panel-box panel-box--min">
                    <a href="<?= Url::to('/admin/site/reset-password') ?>">
                        <span class="title">Мой профиль</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="row row--small mb20">
            <?php if(in_array('trainers', $this->params['roles'])) { ?>
            <div class="col">
                <div class="panel-box panel-box--min">
                    <a href="<?= Url::to('/admin/trainer') ?>">
                        <span class="title">Команда</span>
                    </a>
                </div>
            </div>
            <?php }
            if(in_array('group_programs', $this->params['roles'])) { ?>
            <div class="col">
                <div class="panel-box panel-box--min">
                    <a href="<?= Url::to('/admin/group-programs') ?>">
                        <span class="title">Групповые программы</span>
                    </a>
                </div>
            </div>
            <?php }
            if(in_array('services', $this->params['roles'])) { ?>
            <div class="col">
                <div class="panel-box panel-box--min">
                    <a href="<?= Url::to('/admin/services') ?>">
                        <span class="title">Услуги</span>
                    </a>
                </div>
            </div>
            <?php }
            if(in_array('jobs', $this->params['roles'])) { ?>
                <div class="col">
                    <div class="panel-box panel-box--min">
                        <a href="<?= Url::to('/admin/jobs') ?>">
                            <span class="title">Вакансии</span>
                        </a>
                    </div>
                </div>
            <?php }
            if(in_array('user', $this->params['roles'])) { ?>
            <div class="col">
                <div class="panel-box panel-box--min">
                    <a href="<?= Url::to('/admin/user') ?>">
                        <span class="title">Управление пользоват.</span>
                    </a>
                </div>
            </div>
            <?php }
            if(in_array('settings', $this->params['roles'])) { ?>
            <div class="col">
                <div class="panel-box panel-box--min">
                    <a href="<?= Url::to('/admin/settings/update') ?>">
                        <span class="title">Настройки сайта</span>
                    </a>
                </div>
            </div>
            <?php }
            if(in_array('settings', $this->params['roles'])) { ?>
            <div class="col">
                <div class="panel-box panel-box--min">
                    <a href="<?= Url::to('/admin/subscribe') ?>">
                        <span class="title">Подписки</span>
                    </a>
                </div>
            </div>
            <?php }
            if (Yii::$app->user->identity->id == 1) { ?>
            <div class="col">
                <div class="panel-box panel-box--min" style="background: #a7917a;">
                    <a href="<?= Url::to('/admin/site/backup') ?>">
                        <span class="title">Резервная копия</span>
                    </a>
                </div>
            </div>
            <?php } ?>
        </div>

    </div>
</div>

<?php
$this->registerJs(<<<JS
	window.setTimeout(function() { jQuery(".message-box").alert('close'); }, 2000);
JS
);

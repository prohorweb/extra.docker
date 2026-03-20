<?php

use yii\helpers\Url;

?>
<aside class="left-section">
    <div class="left-section__top">
        <a href="<?= Url::to('/admin') ?>"><div class="logo-box">ExtraSport</div></a>
        <nav class="main-menu">
            <ul class="left-menu">
                <?php if(in_array('rasp', $this->params['roles'])) { ?>
                <li <?= $this->context->id == 'rasp' ? 'class="active"' : '' ?>><a href="<?= Url::to('/admin/rasp') ?>" class="flaticon-calendar">Расписание</a></li>
                <?php }
                if(in_array('news', $this->params['roles'])) { ?>
                <li <?= $this->context->id == 'news' ? 'class="active"' : '' ?>><a href="<?= Url::to('/admin/news') ?>" class="flaticon-global">Новости</a></li>
                <?php }
                if(in_array('news', $this->params['roles'])) { ?>
                <li <?= $this->context->id == 'news2' ? 'class="active"' : '' ?>><a href="<?= Url::to('/admin/news2') ?>" class="flaticon-global">Новости домена</a></li>
                <?php }
                if(in_array('articles', $this->params['roles'])) { ?>
                    <li <?= $this->context->id == 'article' ? 'class="active"' : '' ?>><a href="<?= Url::to('/admin/article') ?>" class="flaticon-left">Советы тренеров</a></li>
                <?php }
                if(in_array('events', $this->params['roles'])) { ?>
                <li <?= $this->context->id == 'event' ? 'class="active"' : '' ?>><a href="<?= Url::to('/admin/event') ?>" class="flaticon-star">Мероприятия</a></li>
                <?php }
                if(in_array('main_banners', $this->params['roles'])) { ?>
                <li <?= $this->context->id == 'banners' ? 'class="active"' : '' ?>><a href="<?= Url::to('/admin/banners') ?>" class="flaticon-computer">Баннеры</a></li>
                <?php }
                if(in_array('history', $this->params['roles'])) { ?>
                    <li <?= $this->context->id == 'history' ? 'class="active"' : '' ?>><a href="<?= Url::to('/admin/history') ?>" class="flaticon-left">Истории успеха</a></li>
                <?php }
                if(in_array('shares', $this->params['roles'])) { ?>
                <li <?= $this->context->id == 'share' ? 'class="active"' : '' ?>><a href="<?= Url::to('/admin/share') ?>" class="flaticon-star">Акции</a></li>
                <?php } ?>
                <?php if(array_intersect(['club', 'club_cards', 'trainers', 'group_programs', 'services', 'partners'], $this->params['roles'])) { ?>
                <li class="<?= in_array($this->context->id, ['club', 'club_cards', 'trainers', 'group_programs', 'services', 'partners']) ? 'active' : '' ?> parent">
                    <a href="#" class="flaticon-info">О клубе</a>
                    <ul>
                    <?php if(in_array('club', $this->params['roles'])) { ?>
                    <li <?= $this->context->id == 'club' ? 'class="active"' : '' ?>><a href="<?= Url::to('/admin/club/update') ?>">Информация о клубе</a></li>
                    <?php }
                    if(in_array('club_cards', $this->params['roles'])) { ?>
                    <li <?= $this->context->id == 'club-cards' ? 'class="active"' : '' ?>><a href="<?= Url::to('/admin/club-cards') ?>">Абонементы</a></li>
                    <?php }
                    if(in_array('trainers', $this->params['roles'])) { ?>
                    <li <?= $this->context->id == 'trainer' ? 'class="active"' : '' ?>><a href="<?= Url::to('/admin/trainer') ?>">Команда</a></li>
                    <?php }
                    if(in_array('group_programs', $this->params['roles'])) { ?>
                    <li <?= $this->context->id == 'group-programs' ? 'class="active"' : '' ?>><a href="<?= Url::to('/admin/group-programs') ?>">Групповые программы</a></li>
                    <?php }
                    if(in_array('services', $this->params['roles'])) { ?>
                    <li <?= $this->context->id == 'services' ? 'class="active"' : '' ?>><a href="<?= Url::to('/admin/services') ?>">Услуги</a></li>
                    <?php }
                    if(in_array('jobs', $this->params['roles'])) { ?>
                        <li <?= $this->context->id == 'jobs' ? 'class="active"' : '' ?>><a href="<?= Url::to('/admin/jobs') ?>">Вакансии</a></li>
                    <?php } ?>
                    </ul>
                </li>
                <?php } ?>
                <?php if(in_array('push', $this->params['roles']) && Yii::$app->language == 'ru-RU') { ?>
                    <li <?= $this->context->id == 'push' ? 'class="active"' : '' ?>><a href="<?= Url::to('/admin/push') ?>" class="flaticon-star">Push уведомления</a></li>
                <?php } ?>
            </ul>
        </nav>
    </div>
    <div class="left-section__bottom">
        <div class="bottom-menu">
            <ul class="left-menu">
                <?php if(in_array('user', $this->params['roles'])) { ?>
                <li <?= $this->context->id == 'user' ? 'class="active"' : '' ?>><a href="<?= Url::to('/admin/user') ?>" class="flaticon-profile">Управления пользователями</a></li>
                <?php }
                if(in_array('settings', $this->params['roles'])) { ?>
                <li <?= $this->context->id == 'settings' ? 'class="active"' : '' ?>><a href="<?= Url::to('/admin/settings/update') ?>" class="flaticon-settings">Настройки сайта</a></li>
                <?php } ?>
                <?php if(in_array('subscribe', $this->params['roles'])) { ?>
                    <li <?= $this->context->id == 'subscribe' ? 'class="active"' : '' ?>><a href="<?= Url::to('/admin/subscribe') ?>" class="flaticon-star">Подписки</a></li>
                <?php }
                if(empty(Yii::$app->request->cookies->get('bd')->value) && in_array('settings', $this->params['roles'])) { ?>
                    <li <?= $this->context->id == 'promo' ? 'class="active"' : '' ?>><a href="<?= Url::to('/admin/promo/update') ?>" class="flaticon-info">Промо</a></li>
                <?php } ?>
            </ul>
        </div>
        <div class="copyright">
            <div class="tcenter mb20"><a href="<?= Url::to(['/issue']) ?>">Сообщить об ошибке</a></div>
            <div class="copyright__wrap">Разработка и поддержка сайта:<br><a href="http://ra-vozduh.ru" target="_blank">ra-vozduh.ru</a></div>
        </div>
    </div>
</aside>

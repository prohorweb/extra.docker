<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        $rasp = $auth->createRole('rasp');
        $auth->add($rasp);

        $articles = $auth->createRole('articles');
        $auth->add($articles);

        $club = $auth->createRole('club');
        $auth->add($club);

        $services = $auth->createRole('services');
        $auth->add($services);

        $news = $auth->createRole('news');
        $auth->add($news);

        $shares = $auth->createRole('shares');
        $auth->add($shares);

        $club_cards = $auth->createRole('club_cards');
        $auth->add($club_cards);

        $jobs = $auth->createRole('jobs');
        $auth->add($jobs);

        $events = $auth->createRole('events');
        $auth->add($events);

        $trainers = $auth->createRole('trainers');
        $auth->add($trainers);

        $user = $auth->createRole('user');
        $auth->add($user);

        $main_banners = $auth->createRole('main_banners');
        $auth->add($main_banners);

        $group_programs = $auth->createRole('group_programs');
        $auth->add($group_programs);

        $settings = $auth->createRole('settings');
        $auth->add($settings);

        $push = $auth->createRole('push');
        $auth->add($push);

        $partners = $auth->createRole('partners');
        $auth->add($partners);

        $subscribe = $auth->createRole('subscribe');
        $auth->add($subscribe);

        $history = $auth->createRole('history');
        $auth->add($history);

        // Назначение ролей пользователям. 1 и 2 это IDs возвращаемые IdentityInterface::getId()
        // обычно реализуемый в модели Users.
        $auth->assign($rasp, 1);
        $auth->assign($articles, 1);
        $auth->assign($club, 1);
        $auth->assign($services, 1);
        $auth->assign($news, 1);
        $auth->assign($shares, 1);
        $auth->assign($club_cards, 1);
        $auth->assign($jobs, 1);
        $auth->assign($events, 1);
        $auth->assign($trainers, 1);
        $auth->assign($user, 1);
        $auth->assign($main_banners, 1);
        $auth->assign($group_programs, 1);
        $auth->assign($settings, 1);
        $auth->assign($push, 1);
        $auth->assign($partners, 1);
        $auth->assign($subscribe, 1);
        $auth->assign($history, 1);

        /*$auth->assign($rasp, 2);
        $auth->assign($articles, 2);
        $auth->assign($club, 2);
        $auth->assign($services, 2);
        $auth->assign($news, 2);
        $auth->assign($shares, 2);
        $auth->assign($club_cards, 2);
        $auth->assign($jobs, 2);
        $auth->assign($events, 2);
        $auth->assign($trainers, 2);
        $auth->assign($user, 2);
        $auth->assign($main_banners, 2);
        $auth->assign($group_programs, 2);
        $auth->assign($settings, 2);
        $auth->assign($push, 2);
        $auth->assign($partners, 2);
        $auth->assign($subscribe, 2);*/
    }

    public function actionAssign()
    {
        $auth = Yii::$app->authManager;

        $subscribe = $auth->createRole('history');
        $auth->add($subscribe);
        $auth->assign($subscribe, 1);
        //$auth->assign($subscribe, 2);

    }

    public function actionTest()
    {
        var_dump(Yii::$app->getDb()->tablePrefix);
    }
}
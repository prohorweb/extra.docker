<?php

namespace api\controllers;

use common\models\ClubCardsParams;
use common\models\Events;
use common\models\Settings;
use common\models\TrainerOptions;
use Yii;
use common\models\Trainers;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;

/**
 * SendMail Controller API
 */
class SendMailController extends ActiveController
{
    public $modelClass = 'common\models\Club';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
        ];

        return $behaviors;
    }

    public function actions(){
        $actions = parent::actions();
        unset($actions['subscribe']);
        return $actions;
    }


    /**
     * Rest Description: Отправка письма для записи на гостевой визит (персональный тренинг или заявки на клубную карту)
     * <br>{
     * <br>"subject": "Запись на гостевой визит Extrasport",
     * <br>"name": "Илья Рочев",
     * <br>"tel": "+7(911)798-3033",
     * <br>"email": "illyar80@gmail&#46;com",
     * <br>"url": "указать название экрана в приложении"
     * <br>}.
     * Rest Fields: ['subject', 'name', 'tel', 'email', 'url'].
     */
    public function actionSubscribe()
    {
        $settings = Settings::findOne(1);
        $subject = Yii::$app->request->post()['subject'];

        $res = Yii::$app->mailer->compose(['html' => 'subscribe-html', 'text' => 'subscribe-text'], ['subject' => $subject])
            ->setFrom($settings->email_from)
            ->setTo(array_map('trim', explode(',', $settings->email_form_visit)))
            ->setSubject($subject)
            ->send();

        return ['result' => $res];
    }

    /**
     * Rest Description: Отправка письма для обратной связи
     * <br>{
     * <br>"subject": "Вопрос о покупке карты",
     * <br>"name": "Илья Рочев",
     * <br>"tel": "+7(911)798-3033",
     * <br>"email": "illyar80@gmail&#46;com",
     * <br>"text": "Добрый день, можно у вас купить карту на полгода?",
     * <br>"url": "указать название экрана в приложении"
     * <br>}.
     * Rest Fields: ['subject', 'name', 'tel', 'email', 'text', 'url'].
     */
    public function actionFeedback()
    {
        $settings = Settings::findOne(1);
        $subject = Yii::$app->request->post()['subject'];

        $res = Yii::$app->mailer->compose(['html' => 'feedback-html', 'text' => 'feedback-text'], ['subject' => $subject])
            ->setFrom($settings->email_from)
            ->setTo(array_map('trim', explode(',', $settings->email_feedback)))
            ->setSubject($subject)
            ->send();

        return ['result' => $res];
    }

    /**
     * Rest Description: Отправка письма для регистрации на мероприятие по id из Events
     * <br>{
     * <br>"subject": "Регистрация на мероприятие FitFashion",
     * <br>"name": "Илья Рочев",
     * <br>"tel": "+7(911)798-3033",
     * <br>"email": "illyar80@gmail&#46;com",
     * <br>"url": "указать название экрана в приложении"
     * <br>}.
     * Rest Fields: ['subject', 'name', 'tel', 'email', 'url'].
     */
    public function actionEvent($id)
    {
        $settings = Settings::findOne(1);
        $subject = Yii::$app->request->post()['subject'];

        $event = Events::findOne($id);
        if (!$event) throw new NotFoundHttpException("Object not found: $id");

        $res = Yii::$app->mailer->compose(['html' => 'subscribe-html', 'text' => 'subscribe-text'],
            ['subject' => $subject, 'event' => $event])
            ->setFrom($settings->email_from)
            ->setTo(array_map('trim', explode(',', $settings->email_form_visit)))
            ->setSubject($subject)
            ->send();

        return ['result' => $res];
    }

    public function actionFreezingText()
    {
        return (object)['freezing_text' => ClubCardsParams::findOne(1)['freezing_text']];
    }

}
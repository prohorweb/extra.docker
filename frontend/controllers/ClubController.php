<?php

namespace frontend\controllers;

use Yii;
use common\models\MainBanners;
use common\models\Subscribe;
use common\models\Metros;
use common\models\Settings;
use common\models\Club;
use common\models\Services;
use himiklab\yii2\recaptcha\ReCaptchaValidator3;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ServerErrorHttpException;

/**
 * ClubController implements the CRUD actions for Club model.
 */
class ClubController extends Controller
{
    protected $pageSize = 4;

    public function init()
    {
        parent::init();
        Yii::$app->view->params['club'] = Club::findOne(1);
        Yii::$app->view->params['settings'] = Settings::findOne(1);
        Yii::$app->view->params['services'] = Services::find()->where(['status' => 10])->orderBy(['position' => SORT_ASC])->all();
        Yii::$app->session->set('group_programs_id', null); // reset filter
        Yii::$app->session->set('program_classes_id', null); // reset filter
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Club models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'banners' => MainBanners::find()->where(['status' => 10])->orderBy(['position' => SORT_ASC])->all(),
            'metros' => Metros::find()->orderBy(['position' => SORT_ASC])->all(),
        ]);
    }


    /**
     * Displays a single Club model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    /**
     * @return string|\yii\web\Response
     */
    public function actionSubscribe()
    {
        $mod = new ReCaptchaValidator3('6Ld2_VopAAAAAFEkKE3MXvL0PbTHUfnUJdQN5PRO');
        if (!$mod->validate(Yii::$app->request->post()['reCaptcha'])) {
            return $this->redirect(Yii::$app->request->referrer);
        }

        if (empty(Yii::$app->request->post()['tel']) || empty(Yii::$app->request->post()['name'])) {
            return $this->redirect(Yii::$app->request->referrer);
        }

        $settings = Settings::findOne(1);

        if (strpos(Yii::$app->request->referrer, 'gift')) {
            $subject = 'Заявка на подарочный сертификат Extra Sport';
        } else {
            $subject = 'Запись на пробную тренировку Extra Sport';
        }

        if(empty($settings->email_from) || empty($settings->email_form_guest)){
            return $this->render('//site/error', ['name' => 'email', 'message' => 'Незаполнено поле email в админке', 'exception' => new ServerErrorHttpException()]);
        }

        Yii::$app->mailer->compose(['html' => 'subscribe-html', 'text' => 'subscribe-text'], ['subject' => $subject])
            ->setFrom($settings->email_from)
            ->setTo(array_map('trim',explode(',', $settings->email_form_guest)))
            ->setSubject($subject)
            ->send();

        Yii::$app->session->setFlash('mailerFormSubmitted');

        if(isset($_COOKIE['_ct_session_id'])){
            $call_value = $_COOKIE['_ct_session_id']; /* ID сессии Calltouch, полученный из cookie */
            $ct_site_id = $_COOKIE['_ct_site_id'];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/x-www-form-urlencoded;charset=utf-8"));
            curl_setopt($ch, CURLOPT_URL,'https://api.calltouch.ru/calls-service/RestAPI/requests/'.$ct_site_id.'/register/');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,
                "fio=".urlencode(Yii::$app->request->post()['name'])
                ."&phoneNumber=".Yii::$app->request->post()['tel']
                //."&email=".$_POST['email']
                ."&subject=".urlencode('Заявка с сайта')
                ."".($call_value != 'undefined' ? "&sessionId=".$call_value : ""));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $calltouch = curl_exec($ch);
            curl_close($ch);

            Yii::$app->session->setFlash('mailerFormSubmitted');
        }

        return $this->redirect(Yii::$app->request->referrer);
    }


    /**
     * @return string|\yii\web\Response
     */
    public function actionSubscribe2()
    {
        $settings = Settings::findOne(1);
        $subject = 'Подписка на новости Extra Sport';

        $model = new Subscribe();
        if (Yii::$app->request->isPost) {
            $model->email = Yii::$app->request->post('email');
            $model->save();
            Yii::$app->session->setFlash('mailerFormSubmitted');
        }

        if(empty($settings->email_from) || empty($settings->email_form_guest)){
            return $this->render('//site/error', ['name' => 'email', 'message' => 'Незаполнено поле email в админке', 'exception' => new ServerErrorHttpException()]);
        }

        Yii::$app->mailer->compose(['html' => 'subscribe-html', 'text' => 'subscribe-text'], ['subject' => $subject])
            ->setFrom($settings->email_from)
            ->setTo(array_map('trim',explode(',', $settings->email_form_guest)))
            ->setSubject($subject)
            ->send();

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * @return mixed
     */
    public function actionSubscribe3()
    {
        $post = Yii::$app->request->post();

        $mod = new ReCaptchaValidator3('6Ld2_VopAAAAAFEkKE3MXvL0PbTHUfnUJdQN5PRO');
        if (!$mod->validate($post['reCaptcha'])) {
            return $this->redirect(Yii::$app->request->referrer);
        }

        if (empty($post['tel']) || empty($post['name'])) {
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            if (preg_match_all('/[^\+?\d ]+/i', $post['tel']) || preg_match_all('/[^а-яё ]+/iu', $post['name'])) {
                return $this->redirect(Yii::$app->request->referrer);
            }
        }

        $settings = Settings::findOne(1);
        $subject = 'Заказ на обратный звонок ' . Yii::$app->view->params['club']->title;

        if(empty($settings->email_from) || empty($settings->email_feedback)){
            return $this->render('//site/error', ['name' => 'email', 'message' => 'Незаполнено поле email в админке', 'exception' => new ServerErrorHttpException()]);
        }

        $mailer = Yii::$app->mailer->compose(['html' => 'subscribe-html', 'text' => 'subscribe-text'], ['subject' => $subject])
            ->setFrom([$settings->email_from => "Администратор " . $settings->email_from])
            ->setTo(array_map('trim',explode(',', $settings->email_feedback)))
            ->setSubject($subject);

        if($mailer->send() && isset($_COOKIE['_ct_session_id'])){
            $call_value = $_COOKIE['_ct_session_id']; /* ID сессии Calltouch, полученный из cookie */
            $ct_site_id = $_COOKIE['_ct_site_id'];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/x-www-form-urlencoded;charset=utf-8"));
            curl_setopt($ch, CURLOPT_URL,'https://api.calltouch.ru/calls-service/RestAPI/requests/'.$ct_site_id.'/register/');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,
                "fio=".urlencode($post['name'])
                ."&phoneNumber=".$post['tel']
                //."&email=".$_POST['email']
                ."&subject=".urlencode('Заявка с сайта')
                ."".($call_value != 'undefined' ? "&sessionId=".$call_value : ""));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $calltouch = curl_exec($ch);
            curl_close($ch);

            Yii::$app->session->setFlash('mailerFormSubmitted');
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * @return mixed
     */
    public function actionSubscribe4()
    {
        Yii::info('', 'mail');

//        $validator = new ReCaptchaValidator3();
//        $validator->action = 'subscribe4';
//        $isValid = $validator->validate(Yii::$app->request->post('reCaptcha'));
//        if (!$isValid) {
//            return $this->redirect(Yii::$app->request->referrer);
//        }

        $post = Yii::$app->request->post();
        if (empty($post['tel']) || empty($post['name'])) {
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            if (preg_match_all('/[^\+?\d ]+/i', $post['tel']) || preg_match_all('/[^а-яё ]+/iu', $post['name'])) {
                return $this->redirect(Yii::$app->request->referrer);
            }
        }

        $settings = Settings::findOne(1);
        $subject = 'Заявка с виджета таймер ' . Yii::$app->view->params['club']->title;

        if(empty($settings->email_from) || empty($settings->email_timer)){
            return $this->render('//site/error', ['name' => 'email', 'message' => 'Незаполнено поле email в админке', 'exception' => new ServerErrorHttpException()]);
        }

        $mailer = Yii::$app->mailer->compose(['html' => 'subscribe-html', 'text' => 'subscribe-text'], ['subject' => $subject])
            ->setFrom([$settings->email_from => "Администратор " . $settings->email_from])
            ->setTo(array_map('trim',explode(',', $settings->email_timer)))
            ->setSubject($subject);

        if($mailer->send()/* && isset($_COOKIE['_ct_session_id'])*/){
            /*$call_value = $_COOKIE['_ct_session_id'];
            $ct_site_id = $_COOKIE['_ct_site_id'];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/x-www-form-urlencoded;charset=utf-8"));
            curl_setopt($ch, CURLOPT_URL,'https://api.calltouch.ru/calls-service/RestAPI/requests/'.$ct_site_id.'/register/');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,
                "fio=".urlencode($post['name'])
                ."&phoneNumber=".$post['tel']
                ."&subject=".urlencode('Заявка с сайта (таймер)')
                ."".($call_value != 'undefined' ? "&sessionId=".$call_value : ""));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $calltouch = curl_exec($ch);
            curl_close($ch);*/

            Yii::$app->session->setFlash('mailerFormSubmitted');
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * @return mixed
     */
    public function actionSubscribe5()
    {
        $post = Yii::$app->request->post();
        if (empty($post['tel']) || empty($post['name'])) {
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            if (preg_match_all('/[^\+?\d ]+/i', $post['tel']) || preg_match_all('/[^а-яё ]+/iu', $post['name'])) {
                return $this->redirect(Yii::$app->request->referrer);
            }
        }

        $email = '';
        switch ($post['club']) {
            case 0;
                $email = 'piter@extra.local,d.samolovov@ra-vozduh.ru';
                break;
            case 1;
                $email = 'rodeo_manager@de-vision.ru,d.samolovov@ra-vozduh.ru';
                break;
            case 2;
                $email = 'rzevka@extra.local,d.samolovov@ra-vozduh.ru';
                break;
            case 3;
                $email = 'polus@extra.local,d.samolovov@ra-vozduh.ru';
                break;
            case 4;
                $email = 'matros@extra.local,d.samolovov@ra-vozduh.ru';
                break;
        }

        $settings = Settings::findOne(1);
        $subject = 'Заявка с ПРОМО страницы';

        /*if(empty($settings->email_from) || empty($settings->email_feedback)){
            return $this->render('//site/error', ['name' => 'email', 'message' => 'Незаполнено поле email в админке', 'exception' => new ServerErrorHttpException()]);
        }*/

        $mailer = Yii::$app->mailer->compose(['html' => 'promo-html', 'text' => 'promo-text'], ['subject' => $subject])
            ->setFrom([$settings->email_from => "Администратор " . $settings->email_from])
            ->setTo(array_map('trim',explode(',', $email)))
            ->setSubject($subject);

        if($mailer->send()){
            Yii::$app->session->setFlash('mailerFormSubmitted');
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * @return mixed
     */
    public function actionSubscribe6()
    {
        Yii::info('', 'mail');

        $post = Yii::$app->request->post();
        if (empty($post['tel']) || empty($post['name'])) {
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            if (preg_match_all('/[^\+?\d ]+/i', $post['tel']) || preg_match_all('/[^а-яё ]+/iu', $post['name'])) {
                return $this->redirect(Yii::$app->request->referrer);
            }
        }

        $settings = Settings::findOne(1);
        $subject = 'Заявка с виджета бонус ' . Yii::$app->view->params['club']->title;

        if(empty($settings->email_from) || empty($settings->email_bonus)){
            return $this->render('//site/error', ['name' => 'email', 'message' => 'Незаполнено поле email в админке', 'exception' => new ServerErrorHttpException()]);
        }

        $mailer = Yii::$app->mailer->compose(['html' => 'subscribe-html', 'text' => 'subscribe-text'], ['subject' => $subject])
            ->setFrom([$settings->email_from => "Администратор " . $settings->email_from])
            ->setTo(array_map('trim',explode(',', $settings->email_bonus)))
            ->setSubject($subject);

        if($mailer->send()){
            Yii::$app->session->setFlash('mailerFormSubmitted2');
            Yii::$app->response->cookies->add(new \yii\web\Cookie([
                'name' => 'bonus-popup',
                'value' => 1,
            ]));
        }

        return $this->redirect(Yii::$app->request->referrer);
    }


    /**
     * Finds the Club model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Club the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Club::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

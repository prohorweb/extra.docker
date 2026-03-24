<?php
namespace frontend\controllers;

use common\models\Shares;
use Yii;
use common\models\Banners;
use common\models\ClubCards;
use common\models\Metros;
use common\models\Services;
use yii\base\InvalidParamException;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Subscribe;
use common\models\LoginForm;
use common\models\ClubBanners;
use common\models\Settings;
use common\models\Club;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\web\ServerErrorHttpException;
use YooKassa\Client;

/**
 * Site controller
 */
class SiteController extends Controller
{
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
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup', 'subscribe'],
                'rules' => [
                    [
                        'actions' => ['signup', 'subscribe'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'shares' => Shares::find()->where(['status' => 10])->orderBy(['position' => SORT_ASC])->limit(3)->all(),
            'club' => Club::findOne(1),
            'banners_club' => ClubBanners::find()->where(['status' => 10])->orderBy(['position' => SORT_ASC])->all(),
            'settings' => Settings::findOne(1),
            'metros' => Metros::find()->orderBy(['position' => SORT_ASC])->all(),
            'banners' => ArrayHelper::index(Banners::find()->where('service_id > 0')->all(), null, 'service_id'),
        ]);
    }


    /**
     * @return \yii\web\Response
     */
    public function actionChangeClub()
    {
        Yii::$app->response->cookies->add(new \yii\web\Cookie([
            'name' => 'bd',
            'value' => $_POST['bd'],
        ]));

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionMaintenance()
    {
        return $this->render('maintenance');
    }

    public function actionInvalidKey()
    {
        return $this->render('invalid-key');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * @return \yii\web\Response
     */
    public function actionSubscribe()
    {
        $model = new Subscribe();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            Yii::$app->session->setFlash('mailerFormSubmitted');
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays welcome page.
     *
     * @return mixed
     */
    public function actionWelcome()
    {
        $this->layout = 'empty';

        return $this->render('welcome');
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }


    public function actionSuccess2($id, $type)
    {
        $client = new Client();
        if(YII_DEBUG){
            $client->setAuth('817464', 'test_Nfc8N47hkRwDfjOxZFgnuild1TyIvMVcAUAxjCs1QGo');
        } else {
            $client->setAuth('815254', 'live_9S2ITuBoy7oLTb8Z_VReib98XD30mtRek0P7x0CePNo');
        }
        $info = $client->getPaymentInfo($id);

        $settings = Settings::findOne(1);

        if (empty($settings->email_from) || empty($settings->email_buy)) {
            return $this->render('//site/error', ['name' => 'email', 'message' => 'Незаполнено поле email в админке', 'exception' => new ServerErrorHttpException()]);
        }

        if ($type == 'card') {
            $subject = 'Покупка карты "' . $info->description . '" в клубе Питер Extra Sport';
            Yii::$app->mailer->compose(['html' => 'buy-html', 'text' => 'buy-text'], ['subject' => $subject, 'info' => $info])
                ->setFrom($settings->email_from)
                ->setTo(array_map('trim', explode(',', $settings->email_buy)))
                ->setSubject($subject)
                ->send();

            Yii::$app->mailer->compose(['html' => 'buy2-html', 'text' => 'buy2-text'], ['subject' => $subject, 'info' => $info])
                ->setFrom($settings->email_from)
                ->setTo($info->metadata->email)
                ->setSubject($subject)
                ->send();

        } elseif($type == 'share') {
            $subject = 'Покупка акции "' . $info->description . '" в клубе Питер Extra Sport';
            Yii::$app->mailer->compose(['html' => 'buy-html', 'text' => 'buy-text'], ['subject' => $subject, 'info' => $info])
                ->setFrom($settings->email_from)
                ->setTo(array_map('trim',explode(',', $settings->email_buy)))
                ->setSubject($subject)
                ->send();

            Yii::$app->mailer->compose(['html' => 'buy2-html', 'text' => 'buy2-text'], ['subject' => $subject, 'info' => $info])
                ->setFrom($settings->email_from)
                ->setTo($info->metadata->email)
                ->setSubject($subject)
                ->send();
        }

        return $this->render('success');
    }


    /**
     * Displays success page.
     *
     * @return mixed
     */
    public function actionSuccess()
    {
        return $this->render('success');
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionCancel()
    {
        return $this->render('_cancel');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}

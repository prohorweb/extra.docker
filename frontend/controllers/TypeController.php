<?php

namespace frontend\controllers;

use Yii;
use common\models\ClubCardsParams;
use common\models\Services;
use common\models\ClubCards;
use common\models\Settings;
use common\models\Club;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ServerErrorHttpException;
use YooKassa\Client;

/**
 * TypeController implements the CRUD actions for Shares model.
 */
class TypeController extends Controller
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
     * Lists all ClubCards models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'models' => ClubCards::find()->where(['status' => 10])->orderBy(['position' => SORT_ASC])->all(),
            'params' => ClubCardsParams::findOne(1),
        ]);
    }

    /**
     * Lists all ClubCards models.
     * @return mixed
     */
    public function actionIndex2()
    {
        return $this->render('index2', [
            'models' => ClubCards::find()->where(['status' => 10])->orderBy(['position' => SORT_ASC])->all(),
            'params' => ClubCardsParams::findOne(1),
        ]);
    }

    /**
     * Displays a single Shares model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'shares' => ClubCards::find()->where(['status' => 10])->andWhere('id != '.intval($id))->limit(3)->all(),
        ]);
    }


    /**
     * @return string
     */
    public function actionFreezing()
    {
        return $this->render('freezing', ['freezing_text' => ClubCardsParams::findOne(1)['freezing_text']]);
    }

    /**
     * @return string
     */
    public function actionGift()
    {
        return $this->render('gift', ['gift_text' => ClubCardsParams::findOne(1)['gift_text']]);
    }


    /**
     * @return mixed
     */
    public function actionSubscribe()
    {
        $settings = Settings::findOne(1);

        if(empty($settings->email_from) || empty($settings->email_request_freezing)){
            return $this->render('//site/error', ['name' => 'email', 'message' => 'Незаполнено поле email в админке', 'exception' => new ServerErrorHttpException()]);
        }

        $mailer = Yii::$app->mailer->compose(['html' => 'freezing-html', 'text' => 'freezing-text'])
            ->setFrom($settings->email_from)
            ->setTo(array_map('trim',explode(',', $settings->email_request_freezing)))
            ->setSubject('Заморозить клубную карту Extra Sport');

        if($mailer->send()){
            Yii::$app->session->setFlash('mailerFormSubmitted');
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * @return mixed
     */
    public function actionSubscribe2()
    {
        $settings = Settings::findOne(1);

        if(empty($settings->email_from) || empty($settings->email_club_card)){
            return $this->render('//site/error', ['name' => 'email', 'message' => 'Незаполнено поле email в админке', 'exception' => new ServerErrorHttpException()]);
        }

        $subject = 'Приобрести клубную карту Extra Sport';
        $mailer = Yii::$app->mailer->compose(['html' => 'subscribe-html', 'text' => 'subscribe-text'], ['subject' => $subject])
            ->setFrom($settings->email_from)
            ->setTo(array_map('trim',explode(',', $settings->email_club_card)))
            ->setSubject($subject);

        if($mailer->send()){
            Yii::$app->session->setFlash('mailerFormSubmitted');
        }

        return $this->redirect(Yii::$app->request->referrer);
    }


    public function actionBuy($id)
    {
        $model = $this->findModel($id);

        $client = new Client();
        if(YII_DEBUG){
            $client->setAuth('817464', 'test_Nfc8N47hkRwDfjOxZFgnuild1TyIvMVcAUAxjCs1QGo');
        } else {
            $client->setAuth('815254', 'live_9S2ITuBoy7oLTb8Z_VReib98XD30mtRek0P7x0CePNo');
        }
        $response = $client->createPayment(
            [
                'amount' => [
                    'value' => $model->price,
                    'currency' => 'RUB',
                ],
                'confirmation' => [
                    'type' => 'embedded'
                ],
                "receipt" => [
                    "customer" => [
                        "full_name" => $_POST['name'],
                        "phone" => preg_replace('/[^\d]/', '', $_POST['tel']),
                        'email' => $_POST['email']
                    ],
                    "items" => [
                        [
                            "description" => $_POST['title'],
                            "quantity" => "1",
                            "amount" => [
                                "value" => strval($model->price) . '.00',
                                "currency" => "RUB"
                            ],
                            "vat_code" => "2",
                            "payment_mode" => "full_prepayment",
                            "payment_subject" => "commodity"
                        ],
                    ]
                ],
                'capture' => true,
                'description' => $_POST['title'],
                'metadata' => [
                    "name" => $_POST['name'],
                    "phone" => $_POST['tel'],
                    'email' => $_POST['email']
                ]
            ],
            uniqid('', true)
        );

        return $this->render('buy', ['type' => 'card', 'id' => $response->id, 'token' => $response->confirmation->confirmationToken]);
    }


    /**
     * Finds the ClubCards model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ClubCards the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ClubCards::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

<?php

namespace frontend\controllers;

use Yii;
use common\models\ShareParams;
use common\models\Services;
use common\models\Settings;
use common\models\Club;
use common\models\Shares;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use YooKassa\Client;

/**
 * SharesController implements the CRUD actions for Shares model.
 */
class SharesController extends Controller
{
    protected $pageSize = 100;

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
     * Lists all Shares models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Shares::find()->where(['only_url' => 0, 'status' => 10]),
            'sort' => ['defaultOrder' => ['position' => SORT_ASC]],
            'pagination' => [
                'defaultPageSize' => $this->pageSize,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'params' => ShareParams::findOne(1),
        ]);
    }

    /**
     * Displays a single Shares model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $dataProviderOther = new ActiveDataProvider([
            'query' => Shares::find()->where(['only_url' => 0,'status' => 10])->andWhere('id != '.intval($id)),
            'sort' => ['defaultOrder' => ['position' => SORT_ASC]],
            'pagination' => [
                'defaultPageSize' => 6,
            ],
        ]);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'dataProviderOther' => $dataProviderOther,
        ]);
    }


    public function actionSubscribe()
    {
        $settings = Settings::findOne(1);
        $subject = 'Заявка на клубную карту - ' . Yii::$app->request->post()['title'];

        Yii::$app->mailer->compose(['html' => 'subscribe-html', 'text' => 'subscribe-text'], ['subject' => $subject, 'share' => 'share'])
            ->setFrom($settings->email_from)
            ->setTo(array_map('trim',explode(',', $settings->email_club_card)))
            ->setSubject($subject)
            ->send();

        Yii::$app->session->setFlash('mailerFormSubmitted2');

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

        return $this->render('/type/buy', ['type' => 'share', 'id' => $response->id, 'token' => $response->confirmation->confirmationToken]);
    }


    /**
     * Finds the Shares model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Shares the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Shares::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

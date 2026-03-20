<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use common\models\Notifications;
use linslin\yii2\curl\Curl;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * ModelController implements the CRUD actions for Model model.
 */
class PushController extends Controller
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $auth = Yii::$app->authManager;
        Yii::$app->view->params['roles'] = array_keys($auth->getRolesByUser(Yii::$app->user->id));
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        //'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['push'],
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }


    public function actionIndex()
    {
        $token = User::findOne(1)->access_token;
        $cookies = Yii::$app->request->cookies;
        if (($cookie = $cookies->get('bd')) !== null && $cookie->value) {
            $token = User::findOne($cookie->value)->access_token;
        }

        $result = (new Curl())->setOption(
                CURLOPT_HTTPHEADER, [
                    'Authorization: Basic ' . Yii::$app->onesignal->apiKey,
                    'Content-Type: application/json'
                ]
            )
            ->setOption(CURLOPT_CUSTOMREQUEST, 'GET')
            ->setOption(CURLOPT_RETURNTRANSFER, TRUE)
            ->setOption(CURLOPT_FORBID_REUSE, TRUE)
            ->setOption(CURLOPT_FRESH_CONNECT, TRUE)
            ->get('https://onesignal.com/api/v1/notifications?app_id=' . Yii::$app->onesignal->appId . '&limit=20');

        $notifications = json_decode($result, true);

        return $this->render('index', ['model' => $notifications['notifications'], 'token' => $token]);
    }


    /**
     * @return \yii\web\Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreate()
    {
        $post = Yii::$app->request->post();

        $token = User::findOne(1)->access_token;
        $cookies = Yii::$app->request->cookies;
        if (($cookie = $cookies->get('bd')) !== null && $cookie->value) {
            $token = User::findOne($cookie->value)->access_token;
        }

        $options = [
            'headings' => ['ru' => $post['title']],
            'filters' => [
                /*['field' => 'tag', 'key' => 'info', 'relation' => '=', 'value' => 'TRUE'],*/
                ['field' => 'tag', 'key' => 'clubToken', 'relation' => '=', 'value' => $token]
            ],
        ];

        if (isset($post['now']) && $post['now']) {
            $options = array_merge($options, ['send_after' => Yii::$app->formatter->asDate($post['date'], 'Y-MM-dd') . ' ' . $post['time'] . ':00 UTC+03:00']);
        }
        $message = ['ru' => $post['text'], 'en' => $post['text']];
        $result = Yii::$app->onesignal->notifications()->create($message, $options);

        $model = new Notifications();
        $model->title = $post['title'];
        $model->content = $post['text'];
        $model->date = Yii::$app->formatter->asDate($post['date'], 'Y-MM-dd');
        $model->save();

        Yii::$app->session->setFlash('success', "Уведомление создано. Обновите страницу через 10 секунд для обновления списка уведомлений");

        return $this->redirect(['/push']);
    }


    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        $result = (new Curl())->setOption(
            CURLOPT_HTTPHEADER, [
                'Authorization: Basic ' . Yii::$app->onesignal->apiKey,
                'Content-Type: application/json'
            ]
        )
            ->setOption(CURLOPT_CUSTOMREQUEST, 'DELETE')
            ->setOption(CURLOPT_RETURNTRANSFER, TRUE)
            ->delete("https://onesignal.com/api/v1/notifications/{$id}?app_id=" . Yii::$app->onesignal->appId . '&limit=10');

        Yii::$app->session->setFlash('delete', "Уведомление будет удалено через 20 секунд. По истечению которых перезагрузите страницу");

        return $this->redirect(['index']);

    }

}

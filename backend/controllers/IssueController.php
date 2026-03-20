<?php

namespace backend\controllers;

use Yii;
use common\models\UploadFile;
use Bitbucket\API\Http\Listener\OAuthListener;
use Bitbucket\API\Repositories\Issues;
use backend\models\Issue;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * IssueController.
 */
class IssueController extends Controller
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
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
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

    /**
     * Return php info .
     */
    public function actionIndex()
    {
        $model = new Issue();

        if (Yii::$app->request->isPost) {

            if(!empty($_FILES['Issue']['tmp_name']['img'])) {
                $modelFile = new UploadFile();
                $modelFile->file = UploadedFile::getInstance($model, 'img');
                $path = Yii::getAlias('@frontend') . '/web/uploads/issue/';

                if (!empty($modelFile->file) && $modelFile->upload($path)) {
                    // file is uploaded successfully
                    $model->img = $modelFile->newFilename;
                } elseif ($modelFile->hasErrors('file')) {
                    $model->addError('img', $modelFile->getFirstError('file'));
                }
            }

            $post = Yii::$app->request->post();
            $issue = new Issues();
            $issue->getClient()->addListener(new OAuthListener(['oauth_consumer_key' => '5nBGQqdKExN5BbP7cU', 'oauth_consumer_secret' => 'wMZPVHPzAN4ALXnPGuVehrBtt95TSfQ9']));

            //$res = $issue->all('ivanlapaev', 'worldgym');
            $res = $issue->create('artur_blago', 'extrasport', [
                'title' => $post['Issue']['title'],
                'content' => $post['Issue']['text'] . ($model->img ? "\n \n ![{$model->img}](" . Yii::$app->urlManager->hostInfo . "/uploads/issue/{$model->img})" : '') .  " \n \n Email для возможных уточнений: " . $post['Issue']['email']
            ]);

            if (!$model->errors) {
                Yii::$app->session->setFlash('success', "Сообщение об ошибке отправлено");
            } else {
                if ($model->errors) {
                    Yii::$app->session->setFlash('success', 'Не все поля заполнены корректно.');
                }
            }
        }

        return $this->render('index', [
            'model' => $model,
        ]);

    }

}

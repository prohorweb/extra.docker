<?php

namespace frontend\controllers;

use Yii;
use common\models\Seo;
use common\models\Trainers;
use common\models\Banners;
use common\models\Settings;
use common\models\Club;
use common\models\Services;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ServerErrorHttpException;

/**
 * ServicesController implements the CRUD actions for Services model.
 */
class ServicesController extends Controller
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Services models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Services::find()->where(['status' => 10]),
            'sort' => ['defaultOrder' => ['position' => SORT_ASC]],
            'pagination' => false,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'seo' => Seo::findOne(['type' => 'services']),
        ]);
    }

    /**
     * Displays a single Services model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render(($id == 1 ? 'training' : 'view'), [
            'model' => $this->findModel($id),
            'services' => Services::find()->where(['status' => 10])->andWhere('id !=' . intval($id))->orderBy(['position' => SORT_ASC])->all(),
            'banners' => Banners::find()->where(['service_id' => intval($id)])->all(),
            'trainers' => $id == 1 ? Trainers::find()->where(['status' => 10])->all() : null,
            'dataProviderOther' => $id == 1 ? new ActiveDataProvider([
                'query' => Trainers::find()->where(['status' => 10])->andWhere('id != '.intval($id)),
                'sort' => ['defaultOrder' => ['position' => SORT_ASC]],
                'pagination' => [
                    'defaultPageSize' => 100,
                    'route' => 'services/personal_training/view'
                ],
            ]) : null
        ]);
    }

    public function actionSubscribe()
    {
        $settings = Settings::findOne(1);
        $subject = 'Запись на персональный тренинг Extra Sport';

        if(empty($settings->email_from) || empty($settings->email_request_training)){
            return $this->render('//site/error', ['name' => 'email', 'message' => 'Незаполнено поле email в админке', 'exception' => new ServerErrorHttpException()]);
        }

        Yii::$app->mailer->compose(['html' => 'subscribe-html', 'text' => 'subscribe-text'], ['subject' => $subject])
            ->setFrom($settings->email_from)
            ->setTo(array_map('trim',explode(',', $settings->email_request_training)))
            ->setSubject($subject)
            ->send();

        Yii::$app->session->setFlash('mailerFormSubmitted');

        return $this->redirect(Yii::$app->request->referrer);
    }


    /**
     * Finds the Services model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Services the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Services::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

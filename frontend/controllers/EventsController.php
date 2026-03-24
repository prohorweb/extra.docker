<?php

namespace frontend\controllers;

use Yii;
use common\models\Seo;
use common\models\Services;
use common\models\Settings;
use common\models\Club;
use common\models\Events;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ServerErrorHttpException;

/**
 * EventsController implements the CRUD actions for Events model.
 */
class EventsController extends Controller
{
    protected $pageSize = 3;

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
     * Lists all Events models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new SqlDataProvider([
            'db' => Yii::$app->language !== 'ru-RU' ? Yii::$app->language : Yii::$app->db,
            'sql' => 'SELECT *
                    FROM (SELECT * FROM ' . Events::tableName() .' WHERE status = 10 AND `date` >= DATE(NOW())
                          ORDER BY `date` ASC LIMIT 1) AS a
                    UNION SELECT *
                          FROM (SELECT * FROM ' . Events::tableName() .' WHERE status = 10 AND `date` >= DATE(NOW())
                                ORDER BY `date` ASC) AS b',
            //'sort' => ['defaultOrder' => ['date' => SORT_ASC]],
            'key' => 'id',
            'pagination' => [
                'defaultPageSize' => $this->pageSize,
                'route' => 'es/events'
            ],
        ]);

        $dataProviderPast = new ActiveDataProvider([
            'query' => Events::find()->where(['status' => 10])->andWhere('`date` < DATE(NOW())')->orderBy(['date' =>SORT_DESC]),
            //'sort' => ['defaultOrder' => ['date' => SORT_ASC]],
            'pagination' => [
                'defaultPageSize' => 6,
                'route' => 'es/events'
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'dataProviderPast' => $dataProviderPast,
            'seo' => Seo::findOne(['type' => 'event']),
        ]);
    }

    /**
     * Displays a single Events model.
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
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionSubscribe($id)
    {
        $settings = Settings::findOne(1);
        $subject = 'Регистрация на мероприятие Extra Sport';
        $event = Events::findOne($id);

        if(empty($settings->email_from) || empty($settings->email_form_visit)){
            return $this->render('//site/error', ['name' => 'email', 'message' => 'Незаполнено поле email в админке', 'exception' => new ServerErrorHttpException()]);
        }

        Yii::$app->mailer->compose(['html' => 'subscribe-html', 'text' => 'subscribe-text'],
            ['subject' => $subject, 'event' => $event])
            ->setFrom($settings->email_from)
            ->setTo(array_map('trim', explode(',', $settings->email_form_visit)))
            ->setSubject($subject)
            ->send();

        Yii::$app->session->setFlash('mailerFormSubmitted');

        return $this->redirect(Yii::$app->request->referrer);
    }


    /**
     * Finds the Events model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Events the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Events::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

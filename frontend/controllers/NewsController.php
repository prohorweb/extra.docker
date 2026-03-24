<?php

namespace frontend\controllers;

use Yii;
use common\models\Services;
use common\models\Seo;
use common\models\Settings;
use common\models\Club;
use common\models\News;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NewsController implements the CRUD actions for News model.
 */
class NewsController extends Controller
{
    protected $pageSize = 10;

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
     * Lists all News models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => News::find()->where(['status' => 10]),
            'sort' => ['defaultOrder' => ['date' => SORT_DESC]],
            'pagination' => [
                'defaultPageSize' => $this->pageSize,
                'route' => '/es/news/'
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'seo' => Seo::findOne(['type' => 'news']),
        ]);
    }

    /**
     * Displays a single News model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    /**
     * Finds the News model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return News the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = News::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

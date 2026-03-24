<?php

namespace frontend\controllers;

use common\models\Settings;
use Yii;
use common\models\Seo;
use common\models\Services;
use common\models\Articles;
use common\models\Club;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ArticleController implements the CRUD actions for Articles model.
 */
class ArticleController extends Controller
{
    protected $pageSize = 12;

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
     * Lists all Articles models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Articles::find()->where(['status' => 10]),
            'sort' => ['defaultOrder' => ['position' => SORT_ASC]],
            'pagination' => [
                'defaultPageSize' => $this->pageSize,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'seo' => Seo::findOne(['type' => 'article']),
        ]);
    }

    /**
     * Displays a single Articles model.
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
     * Finds the Articles model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Articles the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Articles::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

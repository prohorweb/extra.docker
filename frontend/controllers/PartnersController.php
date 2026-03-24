<?php

namespace frontend\controllers;

use Yii;
use common\models\Services;
use common\models\Settings;
use common\models\MetroPartners;
use common\models\Seo;
use common\models\Club;
use common\models\Partners;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PartnersController implements the CRUD actions for News model.
 */
class PartnersController extends Controller
{
    protected $pageSize = 8;

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
     * Lists all Partners models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Partners::find()->where(['status' => 10]),
            'sort' => ['defaultOrder' => ['position' => SORT_DESC]],
            'pagination' => [
                'defaultPageSize' => $this->pageSize,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'seo' => Seo::findOne(['type' => 'partners']),
        ]);
    }

    /**
     * Lists all Partners models.
     * @return mixed
     */
    public function actionList()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Partners::find()->where(['status' => 10]),
            'sort' => ['defaultOrder' => ['title' => SORT_ASC]],
            /*'pagination' => [
                'defaultPageSize' => $this->pageSize,
            ],*/
            'pagination' => false
        ]);

        return $this->render('index-list', [
            'dataProvider' => $dataProvider,
            'seo' => Seo::findOne(['type' => 'partners']),
        ]);
    }

    /**
     * Displays a single Partners model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'metros' => MetroPartners::findAll(['partners_id' => $id]),
        ]);
    }


    /**
     * Finds the News model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Partners the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Partners::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

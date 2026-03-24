<?php

namespace frontend\controllers;

use common\models\Banners;
use Yii;
use common\models\Seo;
use common\models\Services;
use common\models\Settings;
use common\models\Club;
use common\models\TrainerOptionsType;
use common\models\Trainers;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CommandController implements the CRUD actions for Club model.
 */
class CommandController extends Controller
{
    protected $pageSize = 9;

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
     * Lists all Trainers models.
     * @param null $filter
     * @return mixed
     */
    public function actionIndex($filter = null)
    {
        if (Yii::$app->request->isPost && !isset($_POST['reset'])) {
            $filter = Yii::$app->request->post()['filter'];
        }
        $dataProvider = new ActiveDataProvider([
            'query' => Trainers::find()->distinct()->joinWith(['trainerOptions' => function ($query) use ($filter) {
                if ($filter) {
                    $query->andWhere(['option_id' => $filter]);
                }
            }])->where(['status' => 10]),
            'sort' => ['defaultOrder' => ['position' => SORT_ASC]],
            'pagination' => false,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'trainerOptions' => TrainerOptionsType::find()->where(['status' => 10])->all(),
            'seo' => Seo::findOne(['type' => 'trainer']),
        ]);
    }

    /**
     * Displays a single Trainers model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'banners' => Banners::find()->where(['trainer_id' => $id])->all(),
        ]);
    }


    /**
     * Finds the Trainers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Trainers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Trainers::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

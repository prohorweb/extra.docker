<?php

namespace frontend\controllers;

use Yii;
use common\models\GroupProgramsParams;
use common\models\Services;
use common\models\Settings;
use common\models\Club;
use common\models\GroupPrograms;
use common\models\ProgramClasses;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProgramsController implements the CRUD actions for GroupPrograms model.
 */
class ProgramsController extends Controller
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
     * Lists all GroupPrograms models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => GroupPrograms::find()->where(['status' => 10]),
            'sort' => ['defaultOrder' => ['position' => SORT_ASC]],
            'pagination' => false,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'params' => GroupProgramsParams::findOne(1),
        ]);
    }

    /**
     * Displays a single GroupPrograms model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'programClasses' => ProgramClasses::find()->where(['status' => 10, 'group_programs_id' => intval($id)])->all(),
            'groupPrograms' => GroupPrograms::find()->where(['status' => 10])->andWhere('id !=' . intval($id))->all(),
        ]);
    }
    

    /**
     * Finds the GroupPrograms model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GroupPrograms the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GroupPrograms::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

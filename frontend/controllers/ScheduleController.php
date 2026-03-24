<?php

namespace frontend\controllers;

use Yii;
use common\models\Services;
use common\models\Seo;
use common\models\Settings;
use common\models\GroupPrograms;
use common\models\ProgramClasses;
use common\models\TypeRasp;
use common\models\Club;
use common\models\Rasp;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ScheduleController implements the CRUD actions for Club model.
 */
class ScheduleController extends Controller
{
    protected $pageSize = 4;

    public function init()
    {
        parent::init();
        Yii::$app->view->params['club'] = Club::findOne(1);
        Yii::$app->view->params['settings'] = Settings::findOne(1);
        Yii::$app->view->params['services'] = Services::find()->where(['status' => 10])->orderBy(['position' => SORT_ASC])->all();
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
     * @param null $id
     * @param null $week
     * @param null $year
     * @return string
     */
    public function actionIndex($id = null, $week = null, $year = null)
    {
        $id = $id ? $id : TypeRasp::find()->limit(1)->one()->id;
        $year = $year ? $year : (new \DateTime())->format('o');
        $week = $week ? $week : (new \DateTime())->format('W');

        $group_programs_id = Yii::$app->session->get('group_programs_id');
        $program_classes_id = Yii::$app->session->get('program_classes_id');
        $trainer_id = Yii::$app->session->get('trainer_id');
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if (isset($post['reset'])) {
                $program_classes_id = $_SESSION['program_classes_id'] = null;
                $group_programs_id = $_SESSION['group_programs_id'] = null;
                $trainer_id = $_SESSION['trainer_id'] = null;
            } else {
                if (isset($post['Rasp']['program_classes_id']) && $post['Rasp']['program_classes_id']) {
                    $program_classes_id = $_SESSION['program_classes_id'] = $post['Rasp']['program_classes_id'];
                    $group_programs_id = $_SESSION['group_programs_id'] = $post['Rasp']['group_programs_id'];
                } elseif (isset($post['Rasp']['group_programs_id']) && $post['Rasp']['group_programs_id']) {
                    $group_programs_id = $_SESSION['group_programs_id'] = $post['Rasp']['group_programs_id'];
                    $trainer_id = $_SESSION['trainer_id'] = $post['Rasp']['trainer_id'];
                } elseif(isset($post['Rasp']['trainer_id']) && $post['Rasp']['trainer_id'] && empty($post['Rasp']['program_classes_id']) && empty($post['Rasp']['group_programs_id'])) {
                    $trainer_id = $_SESSION['trainer_id'] = $post['Rasp']['trainer_id'];
                } elseif(empty($post['Rasp']['program_classes_id']) && empty($post['Rasp']['group_programs_id'])) {
                    $program_classes_id = $group_programs_id = $_SESSION['group_programs_id'] = $_SESSION['program_classes_id'] = null;
                }
            }
        }

        return $this->render('index_table', [
            'id_typeRasp' => $id,
            'week' => $week,
            'year' => $year,
            'model' => Rasp::getTableRasp($id, $year, $week, $group_programs_id, $program_classes_id, $trainer_id),
            'arr_time' => Rasp::getNewArrTimes($id, $year, $week, $group_programs_id, $program_classes_id, $trainer_id),
            'groupPrograms' => GroupPrograms::find()->where(['status' => 10])->andWhere($id == 0 ? "id!=1" : ($id == 1 ? "id=1" : "1=1"))->all(),
            'seo' => Seo::findOne(['type' => 'rasp']),
        ]);
    }


    /**
     * @param null $id
     * @param null $week
     * @param null $year
     * @return string
     */
    public function actionList($id = null, $week = null, $year = null)
    {
        $id = $id ? $id : TypeRasp::find()->limit(1)->one()->id;
        $year = $year ? $year : (new \DateTime())->format('o');
        $week = $week ? $week : (new \DateTime())->format('W');

        $group_programs_id = Yii::$app->session->get('group_programs_id');
        $program_classes_id = Yii::$app->session->get('program_classes_id');
        $trainer_id = Yii::$app->session->get('trainer_id');
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if (isset($post['reset'])) {
                $program_classes_id = $_SESSION['program_classes_id'] = null;
                $group_programs_id = $_SESSION['group_programs_id'] = null;
                $trainer_id = $_SESSION['trainer_id'] = null;
            } else {
                if (isset($post['Rasp']['program_classes_id']) && $post['Rasp']['program_classes_id']) {
                    $program_classes_id = $_SESSION['program_classes_id'] = $post['Rasp']['program_classes_id'];
                    $group_programs_id = $_SESSION['group_programs_id'] = $post['Rasp']['group_programs_id'];
                } elseif (isset($post['Rasp']['group_programs_id']) && $post['Rasp']['group_programs_id']) {
                    $group_programs_id = $_SESSION['group_programs_id'] = $post['Rasp']['group_programs_id'];
                    $trainer_id = $_SESSION['trainer_id'] = $post['Rasp']['trainer_id'];
                } elseif(isset($post['Rasp']['trainer_id']) && $post['Rasp']['trainer_id'] && empty($post['Rasp']['program_classes_id']) && empty($post['Rasp']['group_programs_id'])) {
                    $trainer_id = $_SESSION['trainer_id'] = $post['Rasp']['trainer_id'];
                } elseif(empty($post['Rasp']['program_classes_id']) && empty($post['Rasp']['group_programs_id'])) {
                    $program_classes_id = $group_programs_id = $_SESSION['group_programs_id'] = $_SESSION['program_classes_id'] = null;
                }
            }
        }

        return $this->render('index_list', [
            'id_typeRasp' => $id,
            'week' => $week,
            'year' => $year,
            'model' => Rasp::getTableRasp($id, $year, $week, $group_programs_id, $program_classes_id, $trainer_id),
            'groupPrograms' => GroupPrograms::find()->where(['status' => 10])->andWhere($id == 0 ? "id!=1" : ($id == 1 ? "id=1" : "1=1"))->all(),
            'seo' => Seo::findOne(['type' => 'rasp']),
        ]);
    }

    public function actionSubcat()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $cat_id = $parents[0];
                $out = ProgramClasses::find()->select('id, title as name')->where(['status' => 10])->andWhere(['group_programs_id' => $cat_id])->asArray()->all();
                echo Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
    }


    /**
     * @return string
     */
    public function actionTv()
    {
        $this->layout = 'empty';
        return $this->render('tv');
    }


    /**
     * @param $id
     * @return string
     */
    public function actionDay($id)
    {
        $this->layout = 'empty';

        $date = (new \DateTime('monday this week +'.($id-1).' days'))->format('Y-m-d');
        $rasp = Rasp::find()->where(['date' => $date])->orderBy(['time' => SORT_ASC])->all();

        return $this->render('day', compact("id", "rasp"));
    }


    /**
     * Displays a single Rasp model.
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
     * Finds the Rasp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Rasp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Rasp::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

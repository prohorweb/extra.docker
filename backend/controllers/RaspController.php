<?php

namespace backend\controllers;

use common\models\Seo;
use Yii;
use DateTime;
use common\models\Weeks;
use common\models\GroupPrograms;
use common\models\ProgramClasses;
use common\models\Trainers;
use common\models\Rooms;
use common\models\TypeRasp;
use common\models\Rasp;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * RaspController implements the CRUD actions for Rasp model.
 */
class RaspController extends Controller
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['rasp'],
                    ],
                    // everything else is denied
                ],
            ],
        ];
    }

    /**
     * Lists all Rasp models.
     * @param null $yearMonth
     * @return mixed
     * @internal param $month
     */
    public function actionIndex($yearMonth = null)
    {
        $yearMonth = $yearMonth ? $yearMonth : (new \DateTime())->format('Y-m');
        $firstDayMonth = new \DateTime("{$yearMonth}-01");
        $firstDayMonth->modify('first day of this month');
        $firstDayMonth->sub(new \DateInterval('P' . ($firstDayMonth->format('N') - 1) . 'D'));

        $lastDayMonth = new \DateTime("{$yearMonth}-01");
        $lastDayMonth->modify('last day of this month');
        $lastDayMonth->add(new \DateInterval('P' . (7 - $lastDayMonth->format('N')) . 'D'));

        $_SESSION['yearMonth'] = $yearMonth;

        return $this->render('index', [
            'firstDayMonth' => $firstDayMonth,
            'lastDayMonth' => $lastDayMonth,
            'yearMonth' => $yearMonth,
            'typeRasp' => TypeRasp::find()->all(),
        ]);
    }

    /**
     * Displays a single Rasp model.
     * @param integer $id
     * @param integer $week
     * @return mixed
     */
    public function actionView($id, $week, $year)
    {
        if (Yii::$app->request->isAjax) {
            //$post = Yii::$app->request->post(); 
            $rasp = Rasp::find()->where(['id' => $id])->asArray()->one();

            Yii::$app->response->format = Response::FORMAT_JSON;
            return $rasp;
        }

        $weeks = Weeks::findOne(['week' => $week, 'year' => $year, 'type_rasp_id' => $id]);
        if (!$weeks) {
            $weeks = new Weeks();
            $weeks->is_empty = 1;
        }

        return $this->render('view', [
            'week' => $week,
            'year' => $year,
            'weeks' => $weeks,
            'rasp' => Rasp::getTableRasp($id, $year, $week),
            'arr_time' => Rasp::getNewArrTimes($id, $year, $week),
            'typeRasp' => TypeRasp::findOne($id),
            'rooms' => Rooms::find()->all(),
            'groupPrograms' => GroupPrograms::find()->where(['status' => 10])->all(),
            'trainers' => Trainers::find()->where(['status' => 10])->orderBy('title')->all(),
            'weeksComplete' => Weeks::find()->where(['not', "week = $week and year = $year"])->andWhere(['type_rasp_id' => $id])->orderBy(['id' => SORT_DESC])->limit(10)->all(),
            'model' => new Rasp(),
        ]);
    }

    /**
     * Creates a new Rasp model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Rasp();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Creates a new Rasp model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param $week
     * @param $year
     * @return mixed
     */
    public function actionCreateRasp($week, $year)
    {
        $model = new Rasp();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->type_rasp_id, 'week' => $week, 'year' => $year]);
        } else {
            /*return $this->render('create', [
                'model' => $model,
            ]);*/
        }
    }


    /**
     * @param $id
     * @param $week
     * @param $year
     * @return \yii\web\Response
     */
    public function actionMakeEmpty($id, $week, $year)
    {
        $model = Weeks::findOne(['week' => $week, 'year' => $year, 'type_rasp_id' => $id]);
        $model = $model ? $model: new Weeks();
        $model->type_rasp_id = $id;
        $model->week = $week;
        $model->year = $year;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $id, 'week' => $week, 'year' => $year]);
        } else {
            /*return $this->render('create', [
                'model' => $model,
            ]);*/
        }
    }

    /**
     * Updates an existing Rasp model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param $week
     * @param $year
     * @return mixed
     */
    public function actionUpdate($id, $week, $year)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->type_rasp_id, 'week' => $week, 'year' => $year]);
        } else {
            /*return $this->render('update', [
                'model' => $model,
            ]);*/
        }
    }

    /**
     * Deletes an existing Rasp model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param $week
     * @param $year
     * @return mixed
     */
    public function actionDelete($id, $week, $year)
    {
        $model = $this->findModel($id);
        $model->delete();

        return $this->redirect(['view', 'id' => $model->type_rasp_id, 'week' => $week, 'year' => $year]);
    }


    /**
     * Clear an existing Rasp models.
     * If clear is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param $week
     * @param $year
     * @return mixed
     */
    public function actionClear($id, $week, $year)
    {
        $models = Rasp::find()->where(['between', 'date', (new \DateTime())->setISODate($year, $week)->format('Y-m-d'), (new \DateTime())->setISODate($year, $week, 7)->format('Y-m-d')])
            ->andWhere(['type_rasp_id' => $id])->all();

        foreach ($models as $model) {
            $model->delete();
        }

        return $this->redirect(['view', 'id' => $id, 'week' => $week, 'year' => $year]);
    }


    /**
     * @param $id
     * @param $week
     * @param $year
     * @return Response
     */
    public function actionAutoComplete($id, $week, $year)
    {
        $model = Weeks::findOne(Yii::$app->request->post()['Weeks']['id']);

        $models = Rasp::find()->where(['between', 'date', (new DateTime())->setISODate($model->year, $model->week)->format('Y-m-d'), (new DateTime())->setISODate($model->year, $model->week, 7)->format('Y-m-d')])
            ->andWhere(['type_rasp_id' => $id])->all();
        $diff_weeks = (int)((new DateTime())->setISODate($model->year, $model->week)->diff((new DateTime())->setISODate($year, $week))->days / 7);
        foreach ($models as $model) {
            $newModel = new Rasp();
            $newModel->setAttributes($model->getAttributes());
            $newModel->status = 10;
            $newDate = new DateTime($model->date);
            $newModel->date = $newDate->modify($diff_weeks . ' week')->format('Y-m-d');
            $newModel->save();
        }

        return $this->redirect(['view', 'id' => $id, 'week' => $week, 'year' => $year]);
    }


    /**
     * @return mixed
     */
    public function actionParams()
    {
        $rooms = new Rooms();
        $typeRasp = new TypeRasp();
        $model = Seo::findOne(['type' => 'rasp']);

        $roomsDataProvider = new ActiveDataProvider(['query' => Rooms::find(), 'pagination' => false]);
        $typeRaspProvider = new ActiveDataProvider(['query' => TypeRasp::find(), 'pagination' => false]);
        $programClassesProvider = new ActiveDataProvider(['query' => ProgramClasses::find(), 'pagination' => false]);

        if ($rooms->load(Yii::$app->request->post()) && $rooms->save()) {
            return $this->redirect(['params']);
        } else {
            return $this->render('params', [
                'model' => $model,
                'rooms' => $rooms,
                'typeRasp' => $typeRasp,
                'typeRaspProvider' => $typeRaspProvider,
                'programClassesProvider' => $programClassesProvider,
                'dataProvider' => $roomsDataProvider,
            ]);
        }
    }

    /**
     * @return mixed
     */
    public function actionParams2()
    {
        $model = Seo::findOne(['type' => 'rasp']);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Материал опубликован на сайте");
            return $this->redirect(['params']);
        } else {
            return $this->redirect(['params']);
        }
    }

    /**
     * @return mixed
     */
    public function actionTypeRasp()
    {
        $typeRasp = new TypeRasp();

        if ($typeRasp->load(Yii::$app->request->post())) {
            $typeRasp->title = GroupPrograms::findOne(Yii::$app->request->post()['TypeRasp']['id'])['title'];
            $typeRasp->save();
            return $this->redirect(['params']);
        }

        return $this->redirect(['params']);
    }

    /**
     * Updates an existing Rooms model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @internal param int $id
     */
    public function actionParamsUpdate()
    {
        $id = Yii::$app->request->post()['editableKey'];
        $index = Yii::$app->request->post()['editableIndex'];
        $model = Rooms::findOne($id);

        if (Yii::$app->request->isPost && ($model->title = Yii::$app->request->post()['Rooms'][$index]['title']) && $model->save()) {
            return $this->redirect(['params']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TypeRasp model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @internal param int $id
     */
    public function actionTypeRaspUpdate()
    {
        $id = Yii::$app->request->post()['editableKey'];
        $index = Yii::$app->request->post()['editableIndex'];
        $model = TypeRasp::findOne($id);

        if (Yii::$app->request->isPost && ($model->title = Yii::$app->request->post()['TypeRasp'][$index]['title']) && $model->save()) {
            return $this->redirect(['params']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
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
     * Updates an existing ProgramClasses model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @internal param int $id
     */
    public function actionColorUpdate()
    {
        $id = Yii::$app->request->post()['editableKey'];
        $index = Yii::$app->request->post()['editableIndex'];
        $model = ProgramClasses::findOne($id);

        if (Yii::$app->request->isPost && ($model->color = Yii::$app->request->post()['ProgramClasses']['color']) && $model->save()) {
            return $this->redirect(['params']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Rooms model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionParamsDelete($id)
    {
        Rooms::findOne($id)->delete();

        return $this->redirect(['params']);
    }

    /**
     * Deletes an existing TypeRasp model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionTypeRaspDelete($id)
    {
        TypeRasp::findOne($id)->delete();

        return $this->redirect(['params']);
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

<?php

namespace backend\controllers;

use common\models\Seo;
use common\models\User;
use Yii;
use common\models\UploadImage;
use common\models\Events;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * EventController implements the CRUD actions for Events model.
 */
class EventController extends Controller
{
    protected $pageSize = 10;

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
                        'roles' => ['events'],
                    ],
                    // everything else is denied
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
            'db' => Yii::$app->request->cookies->get('bd') !== null ? Yii::$app->get('db' . Yii::$app->request->cookies->get('bd')->value) : Yii::$app->db,
            'sql' => 'SELECT * FROM (SELECT * FROM ' . Events::tableName() .' WHERE `date` >= DATE(NOW())
                          ORDER BY `date` ASC LIMIT 1) AS  a
                    UNION SELECT *
                          FROM (SELECT * FROM ' . Events::tableName() .' WHERE `date` >= DATE(NOW())
                                ORDER BY `date` ASC) AS b
                    UNION SELECT *
                          FROM (SELECT * FROM ' . Events::tableName() .' WHERE `date` < DATE(NOW())
                                ORDER BY `date` DESC) AS c',
            'sort' => ['attributes' => ['date', 'status']],
            'key' => 'id',
            'pagination' => [
                'defaultPageSize' => $this->pageSize,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return mixed
     */
    public function actionParams()
    {
        $model = Seo::findOne(['type' => 'event']);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Материал опубликован на сайте");
            return $this->redirect(['params']);
        } else {
            return $this->render('params', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays a single Events model.
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
     * Creates a new Events model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Events();

        if (Yii::$app->request->isPost) {
            $modelFile = new UploadImage(450, 300);
            $modelFile->imageFile = UploadedFile::getInstance($model, 'img');
            $path = Yii::getAlias('@frontend') . '/web/uploads/image/event/';

            if (!empty($modelFile->imageFile) && $modelFile->upload($path)) {
                // file is uploaded successfully
                $model->img = $modelFile->newFilename;
                $model->imageCrop($path);
            } elseif ($modelFile->hasErrors('imageFile')) {
                $model->addError('img', $modelFile->getFirstError('imageFile'));
            }
        }

        if ($model->load(Yii::$app->request->post()) && !$model->errors && $model->save()) {
            return $this->redirect(['index']);
        } else {
            if ($model->errors) {
                Yii::$app->session->setFlash('success', 'Не все поля заполнены корректно.');
            }
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Events model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isPost) {
            $modelFile = new UploadImage(450, 300);
            $modelFile->imageFile = UploadedFile::getInstance($model, 'img');
            $path = Yii::getAlias('@frontend') . '/web/uploads/image/event/';

            if (!empty($modelFile->imageFile) && $modelFile->upload($path)) {
                // file is uploaded successfully
                $model->img = $modelFile->newFilename;
                $model->imageCrop($path);
            } elseif ($modelFile->hasErrors('imageFile')) {
                $model->addError('img', $modelFile->getFirstError('imageFile'));
            }
        }

        if (!$model->errors && $model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->render('update', [
                'model' => $model,
            ]);
        } else {
            if ($model->errors) {
                Yii::$app->session->setFlash('success', 'Не все поля заполнены корректно.');
            }
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Events model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Disable an existing Events model.
     * If disable is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDisable($id)
    {
        $model = $this->findModel($id);
        $model->status = 0;

        if ($model->save()) {
            Yii::$app->session->setFlash('warning', "Мероприятие снято с публикации на сайте");
        }

        return $this->redirect(['index', 'page' => isset($_GET['page']) ? $_GET['page'] : 0]);
    }

    /**
     * Activate an existing Events model.
     * If activate is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionActivate($id)
    {
        $model = $this->findModel($id);
        $model->status = 10;

        if ($model->save()) {
            Yii::$app->session->setFlash('success', "Мероприятие опубликовано на сайте");
        }

        return $this->redirect(['index', 'page' => isset($_GET['page']) ? $_GET['page'] : 0]);
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

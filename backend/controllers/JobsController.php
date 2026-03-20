<?php

namespace backend\controllers;

use common\models\Seo;
use Yii;
use common\models\Jobs;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * JobsController implements the CRUD actions for Jobs model.
 */
class JobsController extends Controller
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
                        'roles' => ['jobs'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['upload', 'upload2'],
                        'roles' => ['news', 'club', 'club_cards', 'event', 'group_programs', 'partners', 'services', 'shares'],
                    ],

                    // everything else is denied
                ],
            ],
        ];
    }

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if (in_array($action->id, ['upload', 'upload2'])) {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    /**
     * Lists all Jobs models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Jobs::find(),
            'sort' => ['defaultOrder' => ['position' => SORT_ASC], 'attributes' => ['position', 'date', 'status']],
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
        $model = Seo::findOne(['type' => 'jobs']);

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
     * Displays a single Jobs model.
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
     * Creates a new Jobs model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Jobs();

        if (Yii::$app->request->isPost) {
            if (($job = Jobs::find()->orderBy(['position' => SORT_DESC])->one()) !== null) {
                /** @var \common\models\Jobs $job */
                $model->position = $job->position + 10;
            } else {
                $model->position = 10;
            }
        }

        if ($model->load(Yii::$app->request->post()) && !$model->errors && $model->save()) {
            Yii::$app->session->setFlash('success', "Материал опубликован на сайте");
            return $this->render('create', [
                'model' => $model,
            ]);
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
     * Updates an existing Jobs model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (!$model->errors && $model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Материал опубликован на сайте");
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
     * Deletes an existing Jobs model.
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
     * Disable an existing Jobs model.
     * If disable is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDisable($id)
    {
        $model = $this->findModel($id);
        $model->status = 0;

        if ($model->save()) {
            Yii::$app->session->setFlash('warning', "Материал снят с публикации на сайте");
        }

        return $this->redirect(['index', 'page' => isset($_GET['page']) ? $_GET['page'] : 0]);
    }

    /**
     * Activate an existing Jobs model.
     * If activate is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionActivate($id)
    {
        $model = $this->findModel($id);
        $model->status = 10;

        if ($model->save()) {
            Yii::$app->session->setFlash('success', "Материал опубликован на сайте");
        }

        return $this->redirect(['index', 'page' => isset($_GET['page']) ? $_GET['page'] : 0]);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionUp($id)
    {
        $model = $this->findModel($id);
        $prevModel = Jobs::find()->where('position = (select max(position) from ' . Jobs::tableName() . ' where position < '. $model->position . ')')->one();

        if($prevModel) {
            $pos = $prevModel->position;
            $prevModel->position = $model->position;
            $prevModel->save();

            $model->position = $pos;
            $model->save();
        }

        return $this->redirect(['index', 'page' => isset($_GET['page']) ? $_GET['page'] : 0]);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionDown($id)
    {
        $model = $this->findModel($id);
        $nextModel = Jobs::find()->where('position = (select min(position) from ' . Jobs::tableName() . ' where position > '. $model->position . ')')->one();

        if($nextModel) {
            $pos = $nextModel->position;
            $nextModel->position = $model->position;
            $nextModel->save();

            $model->position = $pos;
            $model->save();
        }

        return $this->redirect(['index', 'page' => isset($_GET['page']) ? $_GET['page'] : 0]);
    }


    public function actionUpload()
    {
        $uploadedFile = UploadedFile::getInstanceByName('upload');
        $mime = FileHelper::getMimeType($uploadedFile->tempName);
        $file = time() . "_" . $uploadedFile->name;
        $url = Yii::$app->urlManager->getHostInfo() . '/uploads/image/editor/' . $file;
        $path = Yii::getAlias('@frontend') . '/web/uploads/image/editor/';
        FileHelper::createDirectory($path);
        $uploadPath = $path . $file;

        $size = getimagesize($uploadedFile->tempName);

        $message = "";
        if ($uploadedFile == null) {
            $message = "No file uploaded.";
        } else if ($uploadedFile->size == 0) {
            $message = "The file is of zero length.";
        } else if ($size[0] > 4000 || $size[1] > 4000) {
            $message = "The image width and height must be under 4000px.";
        } /*else if ($mime != "image/jpeg" && $mime != "image/png") {
            $message = "The image must be in either JPG or PNG format. Please upload a JPG or PNG instead.";
        }*/ else if ($uploadedFile->tempName == null) {
            $message = "You may be attempting to hack our server. We're on to you; expect a knock on the door sometime soon.";
        } else {
            $move = $uploadedFile->saveAs($uploadPath);
            if (!$move) {
                $message = "Error moving uploaded file. Check the script is granted Read/Write/Modify permissions.";
            }
        }
        return Json::encode($message ? ['error' => ['message' => $message]] : ['fileName' => $file, 'uploaded' => 1, 'url' => $url]);
    }


    public function actionUpload2()
    {
        $uploadedFile = UploadedFile::getInstanceByName('upload');
        $mime = FileHelper::getMimeType($uploadedFile->tempName);
        $file = time() . "_" . $uploadedFile->name;
        $url = Yii::$app->urlManager->getHostInfo() . '/uploads/image/editor/' . $file;
        $path = Yii::getAlias('@frontend') . '/web/uploads/image/editor/';
        FileHelper::createDirectory($path);
        $uploadPath = $path . $file;

        $size = getimagesize($uploadedFile->tempName);

        $message = "";
        if ($uploadedFile == null) {
            $message = "No file uploaded.";
        } else if ($uploadedFile->size == 0) {
            $message = "The file is of zero length.";
        } else if ($size[0] > 4000 || $size[1] > 4000) {
            $message = "The image width and height must be under 4000px.";
        } /*else if ($mime != "image/jpeg" && $mime != "image/png") {
            $message = "The image must be in either JPG or PNG format. Please upload a JPG or PNG instead.";
        }*/ else if ($uploadedFile->tempName == null) {
            $message = "You may be attempting to hack our server. We're on to you; expect a knock on the door sometime soon.";
        } else {
            $move = $uploadedFile->saveAs($uploadPath);
            if (!$move) {
                $message = "Error moving uploaded file. Check the script is granted Read/Write/Modify permissions.";
            }
        }

        if ($message) {
            return Json::encode(['error' => ['message' => $message]]);
        } else {
            return Json::encode(['fileName' => $file, 'uploaded' => 1, 'url' => $url]);
        }
    }


    /**
     * Finds the Jobs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Jobs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Jobs::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

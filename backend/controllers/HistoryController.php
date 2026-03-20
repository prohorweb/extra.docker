<?php

namespace backend\controllers;

use Yii;
use common\models\Banners;
use common\models\Seo;
use common\models\UploadImage;
use common\models\History;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * HistoryController implements the CRUD actions for History model.
 */
class HistoryController extends Controller
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
                        'roles' => ['articles'],
                    ],
                    // everything else is denied
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
            'query' => History::find(),
            'sort' => ['defaultOrder' => ['position' => SORT_ASC], 'attributes' => ['position', 'date', 'status']],
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
        $model = Seo::findOne(['type' => 'history']);

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
     * Displays a single History model.
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
     * Creates a new History model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new History();

        if (Yii::$app->request->isPost) {
            if (($article = History::find()->orderBy(['position' => SORT_DESC])->one()) !== null) {
                /** @var \common\models\History $article */
                $model->position = $article->position + 10;
            } else {
                $model->position = 10;
            }

            $modelFile = new UploadImage(450, 320);
            $modelFile->imageFile = UploadedFile::getInstance($model, 'img');
            $path = Yii::getAlias('@frontend') . '/web/uploads/image/article/';

            if (!empty($modelFile->imageFile) && $modelFile->upload($path)) {
                // file is uploaded successfully
                $model->img = $modelFile->newFilename;
                $model->imageCrop($path);
            } elseif ($modelFile->hasErrors('imageFile')) {
                $model->addError('img', $modelFile->getFirstError('imageFile'));
            }
        }

        if ($model->load(Yii::$app->request->post()) && !$model->errors && $model->save()) {
            foreach ($_FILES['Banners']['tmp_name']['img1440'] as $key => $banner) {
                if ($key > 0 && !empty($banner)) {
                    $banners = new Banners();
                    $modelFile = new UploadImage(645, 460);
                    $modelFile->imageFile = UploadedFile::getInstance($banners, "img1440[$key]");
                    $path = Yii::getAlias('@frontend') . '/web/uploads/image/banners/1440/';
                    if (!empty($modelFile->imageFile) && $modelFile->upload($path)) {
                        $banners->img1440 = $modelFile->newFilename;
                        $banners->imageCropHistory($path);
                    } elseif ($modelFile->hasErrors('imageFile')) {
                        Yii::$app->session->setFlash('error', 'Загружаемое изображение не соответствует требованиям');
                        return $this->render('create', [
                            'model' => $model,
                            'banners' => $banners
                        ]);
                    }
                    $banners->history_id = $model->id;
                    $banners->save();
                }
            }
            Yii::$app->session->setFlash('success', "Материал опубликован на сайте");
            return $this->redirect(['index']);
        } else {
            if ($model->errors) {
                Yii::$app->session->setFlash('success', 'Не все поля заполнены корректно.');
            }
            return $this->render('create', [
                'model' => $model,
                'banners' => new Banners()
            ]);
        }
    }

    /**
     * Updates an existing History model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isPost) {
            $modelFile = new UploadImage(450, 320);
            $modelFile->imageFile = UploadedFile::getInstance($model, 'img');
            $path = Yii::getAlias('@frontend') . '/web/uploads/image/article/';

            if (!empty($modelFile->imageFile) && $modelFile->upload($path)) {
                // file is uploaded successfully
                $model->img = $modelFile->newFilename;
                $model->imageCrop($path);
            } elseif ($modelFile->hasErrors('imageFile')) {
                $model->addError('img', $modelFile->getFirstError('imageFile'));
            }
        }

        if (!$model->errors && $model->load(Yii::$app->request->post()) && $model->save()) {
            foreach ($_FILES['Banners']['tmp_name']['img1440'] as $key => $banner) {
                if ($key > 0 && !empty($banner)) {
                    $banners = new Banners();
                    $modelFile = new UploadImage(645, 460);
                    $modelFile->imageFile = UploadedFile::getInstance($banners, "img1440[$key]");
                    $path = Yii::getAlias('@frontend') . '/web/uploads/image/banners/1440/';
                    if (!empty($modelFile->imageFile) && $modelFile->upload($path)) {
                        $banners->img1440 = $modelFile->newFilename;
                        $banners->imageCropHistory($path);
                    } elseif ($modelFile->hasErrors('imageFile')) {
                        Yii::$app->session->setFlash('error', 'Загружаемое изображение не соответствует требованиям');
                    }
                    $banners->history_id = $model->id;
                    $banners->save();
                }
            }
            Yii::$app->session->setFlash('success', "Материал опубликован на сайте");
        }

        if ($model->errors) {
            Yii::$app->session->setFlash('success', 'Не все поля заполнены корректно.');
        }
        return $this->render('update', [
            'model' => $model,
            'banners' => new Banners()
        ]);
    }

    /**
     * Deletes an existing History model.
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
     * Deletes an existing Banners model.
     * @param integer $id
     * @return mixed
     */
    public function actionBannerDelete($id)
    {
        Banners::findOne($id)->delete();
    }

    /**
     * Disable an existing History model.
     * If disable is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDisable($id)
    {
        $model = $this->findModel($id);
        $model->status = 0;

        if ($model->save()) {
            Yii::$app->session->setFlash('warning', "Статья снята с публикации на сайте");
        }

        return $this->redirect(['index', 'page' => isset($_GET['page']) ? $_GET['page'] : 0]);
    }

    /**
     * Activate an existing History model.
     * If activate is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionActivate($id)
    {
        $model = $this->findModel($id);
        $model->status = 10;

        if ($model->save()) {
            Yii::$app->session->setFlash('success', "Статья опубликована на сайте");
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
        $prevModel = History::find()->where('position = (select max(position) from ' . History::tableName() . ' where position < '. $model->position . ')')->one();

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
        $nextModel = History::find()->where('position = (select min(position) from ' . History::tableName() . ' where position > '. $model->position . ')')->one();

        if($nextModel) {
            $pos = $nextModel->position;
            $nextModel->position = $model->position;
            $nextModel->save();

            $model->position = $pos;
            $model->save();
        }

        return $this->redirect(['index', 'page' => isset($_GET['page']) ? $_GET['page'] : 0]);
    }

    /**
     * Finds the History model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Articles the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = History::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

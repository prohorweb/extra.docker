<?php

namespace backend\controllers;

use Yii;
use common\models\Banners;
use common\models\Seo;
use common\models\UploadImage;
use common\models\Trainers;
use common\models\TrainerOptionsType;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * TrainerController implements the CRUD actions for Trainers model.
 */
class TrainerController extends Controller
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
                        'roles' => ['trainers'],
                    ],
                    // everything else is denied
                ],
            ],
        ];
    }

    /**
     * Lists all Trainers models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Trainers::find(),
            'sort' => ['defaultOrder' => ['position' => SORT_ASC], 'attributes' => ['position', 'status']],
            'pagination' => [
                'defaultPageSize' => $this->pageSize,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
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
        ]);
    }

    /**
     * Creates a new Trainers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Trainers();

        $optionsType = TrainerOptionsType::find()->all();

        if (Yii::$app->request->isPost) {
            if (($trainer = Trainers::find()->orderBy(['position' => SORT_DESC])->one()) !== null) {
                /** @var \common\models\Trainers $trainer */
                $model->position = $trainer->position + 10;
            } else {
                $model->position = 10;
            }

            $modelFile = new UploadImage(330, 330);
            $modelFile->imageFile = UploadedFile::getInstance($model, 'img');
            $path = Yii::getAlias('@frontend') . '/web/uploads/image/trainer/';

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
                    $modelFile = new UploadImage(645, 645);
                    $modelFile->imageFile = UploadedFile::getInstance($banners, "img1440[$key]");
                    $path = Yii::getAlias('@frontend') . '/web/uploads/image/banners/1440/';
                    if (!empty($modelFile->imageFile) && $modelFile->upload($path)) {
                        $banners->img1440 = $modelFile->newFilename;
                        $banners->imageCropTrainers($path);
                    } elseif ($modelFile->hasErrors('imageFile')) {
                        Yii::$app->session->setFlash('error', 'Загружаемое изображение не соответствует требованиям');
                        return $this->render('create', [
                            'model' => $model,
                            'options_type' => $optionsType,
                            'banners' => $banners
                        ]);
                    }
                    $banners->trainer_id = $model->id;
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
                'options_type' => $optionsType,
                'banners' => new Banners()
            ]);
        }
    }

    /**
     * Creates a new TrainerOptionsType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionOptionsCreate()
    {
        $model = new TrainerOptionsType();
        $seo = Seo::findOne(['type' => 'trainer']);

        $dataProvider = new ActiveDataProvider([
            'query' => TrainerOptionsType::find(),
            'sort' => ['defaultOrder' => ['position' => SORT_ASC], 'attributes' => ['position', 'status']],
            'pagination' => [
                'defaultPageSize' => $this->pageSize,
            ],
        ]);

        if (Yii::$app->request->isPost) {
            if (($trainerOption = TrainerOptionsType::find()->orderBy(['position' => SORT_DESC])->one()) !== null) {
                /** @var \common\models\TrainerOptionsType $trainerOption */
                $model->position = $trainerOption->position + 10;
            } else {
                $model->position = 10;
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['options-create']);
        } else {
            return $this->render('options_create', [
                'model' => $model,
                'seo' => $seo,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * @return mixed
     */
    public function actionParams2()
    {
        $model = Seo::findOne(['type' => 'trainer']);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Материал опубликован на сайте");
            return $this->redirect(['options-create']);
        } else {
            return $this->redirect(['options-create']);
        }
    }

    /**
     * Updates an existing Trainers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $optionsType = TrainerOptionsType::find()->all();
        $model->options_field = ArrayHelper::getColumn(
            $model->getTrainerOptions()->asArray()->all(), 'option_id'
        );

        if (Yii::$app->request->isPost) {
            $modelFile = new UploadImage(330, 330);
            $modelFile->imageFile = UploadedFile::getInstance($model, 'img');
            $path = Yii::getAlias('@frontend') . '/web/uploads/image/trainer/';

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
                    $modelFile = new UploadImage(645, 645);
                    $modelFile->imageFile = UploadedFile::getInstance($banners, "img1440[$key]");
                    $path = Yii::getAlias('@frontend') . '/web/uploads/image/banners/1440/';
                    if (!empty($modelFile->imageFile) && $modelFile->upload($path)) {
                        $banners->img1440 = $modelFile->newFilename;
                        $banners->imageCropTrainers($path);
                    } elseif ($modelFile->hasErrors('imageFile')) {
                        Yii::$app->session->setFlash('error', 'Загружаемое изображение не соответствует требованиям');
                    }
                    $banners->trainer_id = $model->id;
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
            'options_type' => $optionsType,
            'banners' => new Banners()
        ]);
    }

    /**
     * Updates an existing TrainerOptionsType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @internal param int $id
     */
    public function actionOptionsUpdate()
    {
        $id = Yii::$app->request->post()['editableKey'];
        $index = Yii::$app->request->post()['editableIndex'];
        $model = TrainerOptionsType::findOne($id);

        if (Yii::$app->request->isPost && ($model->title = Yii::$app->request->post()['TrainerOptionsType'][$index]['title']) && $model->save()) {
            return $this->redirect(['options-create']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Trainers model.
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
     * Disable an existing Trainers model.
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
     * Activate an existing Trainers model.
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
     * Disable an existing TrainerOptionsType model.
     * If disable is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionOptionsDisable($id)
    {
        $model = TrainerOptionsType::findOne($id);
        $model->status = 0;

        if ($model->save()) {
            Yii::$app->session->setFlash('warning', "Материал снят с публикации на сайте");
        }

        return $this->redirect(['options-create']);
    }

    /**
     * Activate an existing TrainerOptionsType model.
     * If activate is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionOptionsActivate($id)
    {
        $model = TrainerOptionsType::findOne($id);
        $model->status = 10;

        if ($model->save()) {
            Yii::$app->session->setFlash('success', "Материал опубликован на сайте");
        }

        return $this->redirect(['options-create']);
    }

    /**
     * Deletes an existing TrainerOptionsType model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionOptionsDelete($id)
    {
        TrainerOptionsType::findOne($id)->delete();

        return $this->redirect(['options-create']);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionUp($id)
    {
        $model = $this->findModel($id);
        $prevModel = Trainers::find()->where('position = (select max(position) from ' . Trainers::tableName() . ' where position < '. $model->position . ')')->one();

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
        $nextModel = Trainers::find()->where('position = (select min(position) from ' . Trainers::tableName() . ' where position > '. $model->position . ')')->one();

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
     * @param integer $id
     * @return mixed
     */
    public function actionOptionsUp($id)
    {
        $model = TrainerOptionsType::findOne($id);
        $prevModel = TrainerOptionsType::findOne(['position' => $model->position - 10]);

        if($prevModel) {
            $prevModel->position = $prevModel->position + 10;
            $prevModel->save();

            $model->position = $model->position - 10;
            $model->save();
        }

        return $this->redirect(['options-create']);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionOptionsDown($id)
    {
        $model = TrainerOptionsType::findOne($id);
        $nextModel = TrainerOptionsType::findOne(['position' => $model->position + 10]);

        if($nextModel) {
            $nextModel->position = $nextModel->position - 10;
            $nextModel->save();

            $model->position = $model->position + 10;
            $model->save();
        }

        return $this->redirect(['options-create']);
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

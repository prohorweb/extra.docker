<?php

namespace backend\controllers;

use Yii;
use common\models\ClubCards;
use common\models\Shares;
use common\models\Trainers;
use common\models\UploadFile;
use common\models\Events;
use common\models\News;
use common\models\Services;
use common\models\ClubBanners;
use common\models\Metros;
use common\models\UploadImage;
use common\models\Club;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ClubController implements the CRUD actions for Club model.
 */
class ClubController extends Controller
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
                        'roles' => ['club'],
                    ],
                    // everything else is denied
                ],
            ],
        ];
    }

    /**
     * Lists all Club models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Club::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Club model.
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
     * Creates a new ClubBanners model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionBannerCreate()
    {
        $model = new ClubBanners();

        if (Yii::$app->request->isPost) {
            if (($clubBanners = ClubBanners::find()->orderBy(['position' => SORT_DESC])->one()) !== null) {
                /** @var \common\models\ClubBanners $clubBanners */
                $model->position = $clubBanners->position + 10;
            } else {
                $model->position = 10;
            }

            $modelFile1440 = new UploadImage(1904, 1080);
            if (!$model->upload1440($modelFile1440) && $modelFile1440->hasErrors('imageFile')) {
                $model->addError('img1440', $modelFile1440->getFirstError('imageFile'));
            }

            $modelFile1200 = new UploadImage(1904, 698);
            if (!$model->upload1200($modelFile1200) && $modelFile1200->hasErrors('imageFile')) {
                $model->addError('img1200', $modelFile1200->getFirstError('imageFile'));
            }

            $modelFile = new UploadFile();
            $modelFile->file = UploadedFile::getInstance($model, 'video');
            $path = Yii::getAlias('@frontend') . '/web/uploads/video/';

            if (!empty($modelFile->file) && $modelFile->upload($path)) {
                $model->video = $modelFile->newFilename;
            } elseif ($modelFile->hasErrors('file')) {
                $model->addError('video', $modelFile->getFirstError('file'));
            }
        }

        if ($model->load(Yii::$app->request->post()) && !$model->errors && $model->save()) {
            return $this->redirect(['banner-update?id=' . $model->id]);
        } else {
            return $this->render('banner_create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Creates a new Metros model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionMetroCreate()
    {
        $model = new Metros();

        if (Yii::$app->request->isPost) {
            if (($metro = Metros::find()->orderBy(['position' => SORT_DESC])->one()) !== null) {
                /** @var \common\models\Metros $metro */
                $model->position = $metro->position + 10;
            } else {
                $model->position = 10;
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update#metro']);
        } else {
            return $this->redirect(['update#metro']);
            /*return $this->render('update', [
                'model' => $model,
            ]);*/
        }
    }

    /**
     * Updates an existing Club model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @internal param int $id
     */
    public function actionUpdate()
    {
        $model = $this->findModel(1);
        $dataProvider = new ActiveDataProvider([
            'query' => ClubBanners::find()->orderBy(['position' => SORT_ASC]),
            'sort' => ['defaultOrder' => ['position' => SORT_ASC], 'attributes' => ['position', 'status']],
        ]);

        $metro = new Metros();
        $dataProviderMetros = new ActiveDataProvider([
            'query' => Metros::find(),
            'sort' => ['defaultOrder' => ['position' => SORT_ASC], 'attributes' => ['position']],
        ]);

        if (Yii::$app->request->isPost) {
            $modelFile = new UploadImage(721, 496);
            $modelFile->imageFile = UploadedFile::getInstance($model, 'img');
            $path = Yii::getAlias('@frontend') . '/web/uploads/image/club/';

            if (!empty($modelFile->imageFile) && $modelFile->upload($path)) {
                // file is uploaded successfully
                $model->img = $modelFile->newFilename;
                $model->imageCrop($path);
            } elseif ($modelFile->hasErrors('imageFile')) {
                $model->addError('img', $modelFile->getFirstError('imageFile'));
            }

            $modelFile = new UploadImage(459, 303);
            $modelFile->imageFile = UploadedFile::getInstance($model, 'img2');
            $path = Yii::getAlias('@frontend') . '/web/uploads/image/club/';

            if (!empty($modelFile->imageFile) && $modelFile->upload($path)) {
                // file is uploaded successfully
                $model->img2 = $modelFile->newFilename;
                $model->imageCrop2($path);
            } elseif ($modelFile->hasErrors('imageFile')) {
                $model->addError('img2', $modelFile->getFirstError('imageFile'));
            }

            $modelFile = new UploadFile();
            $modelFile->file = UploadedFile::getInstance($model, 'pdf');
            $path = Yii::getAlias('@frontend') . '/web/uploads/';

            if (!empty($modelFile->file) && $modelFile->upload($path)) {
                // file is uploaded successfully
                $model->pdf = $modelFile->newFilename;
            } elseif ($modelFile->hasErrors('file')) {
                $model->addError('pdf', $modelFile->getFirstError('file'));
            }

            $modelFile->file = UploadedFile::getInstance($model, 'pdf2');
            $path = Yii::getAlias('@frontend') . '/web/uploads/';

            if (!empty($modelFile->file) && $modelFile->upload($path)) {
                // file is uploaded successfully
                $model->pdf2 = $modelFile->newFilename;
            } elseif ($modelFile->hasErrors('file')) {
                $model->addError('pdf2', $modelFile->getFirstError('file'));
            }

            $modelFile->file = UploadedFile::getInstance($model, 'pdf3');
            $path = Yii::getAlias('@frontend') . '/web/uploads/';

            if (!empty($modelFile->file) && $modelFile->upload($path)) {
                // file is uploaded successfully
                $model->pdf3 = $modelFile->newFilename;
            } elseif ($modelFile->hasErrors('file')) {
                $model->addError('pdf3', $modelFile->getFirstError('file'));
            }
        }

        if (!$model->errors && $model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Информация о клубе успешно обновлена.');
            return $this->redirect(['update']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'dataProvider' => $dataProvider,
                'dataProviderMetros' => $dataProviderMetros,
                'metro' => $metro,
            ]);
        }
    }

    /**
     * Updates an existing ClubBanners model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBannerUpdate($id)
    {
        $model = ClubBanners::findOne($id);

        if (Yii::$app->request->isPost) {

            $modelFile1440 = new UploadImage(1426, 499);
            if (!$model->upload1440($modelFile1440) && $modelFile1440->hasErrors('imageFile')) {
                $model->addError('img1440', $modelFile1440->getFirstError('imageFile'));
            }

            $modelFile1200 = new UploadImage(748, 391);
            if (!$model->upload1200($modelFile1200) && $modelFile1200->hasErrors('imageFile')) {
                $model->addError('img1200', $modelFile1200->getFirstError('imageFile'));
            }

            $modelFile = new UploadFile();
            $modelFile->file = UploadedFile::getInstance($model, 'video');
            $path = Yii::getAlias('@frontend') . '/web/uploads/video/';

            if (!empty($modelFile->file) && $modelFile->upload($path)) {
                $model->video = $modelFile->newFilename;
            } elseif ($modelFile->hasErrors('file')) {
                $model->addError('video', $modelFile->getFirstError('file'));
            }
        }

        if (!$model->errors && $model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->render('banner_update', [
                'model' => $model,
            ]);
        } else {
            return $this->render('banner_update', [
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
                $out = [];
                switch ($parents[0]) {
                    case 'News':
                        $out = News::find()->select('id, title as name')->where(['status' => 10])->asArray()->all();
                        break;
                    case 'Events':
                        $out = Events::find()->select('id, title as name')->where(['status' => 10])->asArray()->all();
                        break;
                    case 'Services':
                        $out = Services::find()->select('id, title as name')->where(['status' => 10])->asArray()->all();
                        break;
                    case 'Shares':
                        $out = Shares::find()->select('id, title as name')->where(['status' => 10])->asArray()->all();
                        break;
                    case 'Trainers':
                        $out = Trainers::find()->select('id, title as name')->where(['status' => 10])->asArray()->all();
                        break;
                    case 'ClubCards':
                        $out = ClubCards::find()->select('id, title as name')->where(['status' => 10])->asArray()->all();
                        break;

                }
                echo Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
    }

    /**
     * Deletes an existing Club model.
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
     * Deletes an existing ClubBanners model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBannerDelete($id)
    {
        ClubBanners::findOne($id)->delete();

        return $this->redirect(['update']);
    }

    /**
     * Deletes an existing ClubBanners model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionVideoDelete($id)
    {
        $model = ClubBanners::findOne($id);
        $model->video = null;
        $model->save();

        return $this->redirect(['update']);
    }

    /**
     * Deletes an existing Metros model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionMetroDelete($id)
    {
        Metros::findOne($id)->delete();

        if (Yii::$app->getRequest()->isAjax) {
            $metro = new Metros();
            $dataProviderMetros = new ActiveDataProvider([
                'query' => Metros::find(),
                'sort' => ['defaultOrder' => ['position' => SORT_ASC], 'attributes' => ['position']],
            ]);
            return $this->renderPartial('_metro', [
                'dataProviderMetros' => $dataProviderMetros,
                'metro' => $metro,
            ]);
        }

        return $this->redirect(['update']);
    }

    /**
     * Disable an existing ClubBanners model.
     * If disable is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDisable($id)
    {
        $model = ClubBanners::findOne($id);
        $model->status = 0;

        if ($model->save()) {
            Yii::$app->session->setFlash('warning', "Баннер снят с публикации на сайте");
        }

        return $this->redirect(['update']);
    }

    /**
     * Activate an existing ClubBanners model.
     * If activate is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionActivate($id)
    {
        $model = ClubBanners::findOne($id);
        $model->status = 10;

        if ($model->save()) {
            Yii::$app->session->setFlash('success', "Баннер опубликован на сайте");
        }

        return $this->redirect(['update']);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionUp($id)
    {
        $model = ClubBanners::findOne($id);
        $prevModel = ClubBanners::find()->where('position = (select max(position) from ' . ClubBanners::tableName() . ' where position < '. $model->position . ')')->one();

        if($prevModel) {
            $pos = $prevModel->position;
            $prevModel->position = $model->position;
            $prevModel->save();

            $model->position = $pos;
            $model->save();
        }

        return $this->redirect(['update']);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionDown($id)
    {
        $model = ClubBanners::findOne($id);
        $nextModel = ClubBanners::find()->where('position = (select min(position) from ' . ClubBanners::tableName() . ' where position > '. $model->position . ')')->one();

        if($nextModel) {
            $pos = $nextModel->position;
            $nextModel->position = $model->position;
            $nextModel->save();

            $model->position = $pos;
            $model->save();
        }

        return $this->redirect(['update']);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionMetroUp($id)
    {
        $model = Metros::findOne($id);
        $prevModel = Metros::find()->where('position = (select max(position) from ' . Metros::tableName() . ' where position < '. $model->position . ')')->one();

        if($prevModel) {
            $pos = $prevModel->position;
            $prevModel->position = $model->position;
            $prevModel->save();

            $model->position = $pos;
            $model->save();
        }

        if (Yii::$app->getRequest()->isAjax) {
            $metro = new Metros();
            $dataProviderMetros = new ActiveDataProvider([
                'query' => Metros::find(),
                'sort' => ['defaultOrder' => ['position' => SORT_ASC], 'attributes' => ['position']],
            ]);
            return $this->renderPartial('_metro', [
                'dataProviderMetros' => $dataProviderMetros,
                'metro' => $metro,
            ]);
        }

        return $this->redirect(['update']);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionMetroDown($id)
    {
        $model = Metros::findOne($id);
        $nextModel = Metros::find()->where('position = (select min(position) from ' . Metros::tableName() . ' where position > '. $model->position . ')')->one();

        if($nextModel) {
            $pos = $nextModel->position;
            $nextModel->position = $model->position;
            $nextModel->save();

            $model->position = $pos;
            $model->save();
        }

        if (Yii::$app->getRequest()->isAjax) {
            $metro = new Metros();
            $dataProviderMetros = new ActiveDataProvider([
                'query' => Metros::find(),
                'sort' => ['defaultOrder' => ['position' => SORT_ASC], 'attributes' => ['position']],
            ]);
            return $this->renderPartial('_metro', [
                'dataProviderMetros' => $dataProviderMetros,
                'metro' => $metro,
            ]);
        }

        return $this->redirect(['update']);
    }

    /**
     * Finds the Club model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Club the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Club::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

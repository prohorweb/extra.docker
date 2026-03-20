<?php

namespace backend\controllers;

use common\models\Seo;
use Yii;
use common\models\MetroPartners;
use common\models\UploadImage;
use common\models\Partners;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * PartnersController implements the CRUD actions for Partners model.
 */
class PartnersController extends Controller
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
     * {@inheritdoc}
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
                        'roles' => ['partners'],
                    ],
                    // everything else is denied
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
            'query' => Partners::find(),
            'sort' => ['defaultOrder' => ['position' => SORT_ASC], 'attributes' => ['position', 'status', 'title']],
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
        $model = Seo::findOne(['type' => 'partners']);

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
     * Displays a single Partners model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Partners model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Partners();

        if (Yii::$app->request->isPost) {
            if (($partners = Partners::find()->orderBy(['position' => SORT_DESC])->one()) !== null) {
                /** @var \common\models\Partners $partners */
                $model->position = $partners->position + 10;
            } else {
                $model->position = 10;
            }

            $modelFile = new UploadImage(330, 240);
            $modelFile->imageFile = UploadedFile::getInstance($model, 'img');
            $path = Yii::getAlias('@frontend') . '/web/uploads/image/partners/';

            if (!empty($modelFile->imageFile) && $modelFile->upload($path)) {
                // file is uploaded successfully
                $model->img = $modelFile->newFilename;
                $model->imageCrop($path);
            } elseif ($modelFile->hasErrors('imageFile')) {
                $model->addError('img', $modelFile->getFirstError('imageFile'));
            }
        }

        if ($model->load(Yii::$app->request->post()) && !$model->errors && $model->save()) {
            Yii::$app->session->setFlash('success', "Материал опубликован на сайте");
            return $this->redirect(['update', 'id' => $model->id]);
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
     * Updates an existing Partners model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $dataProviderMetros = new ActiveDataProvider([
            'query' => MetroPartners::find()->where(['partners_id' => $id])->orderBy('position'),
            'sort' => ['defaultOrder' => ['position' => SORT_ASC], 'attributes' => ['position']],
        ]);

        if (Yii::$app->request->isPost) {
            $modelFile = new UploadImage(330, 240);
            $modelFile->imageFile = UploadedFile::getInstance($model, 'img');
            $path = Yii::getAlias('@frontend') . '/web/uploads/image/partners/';

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
                'dataProviderMetros' => $dataProviderMetros,
            ]);
        } else {
            if ($model->errors) {
                Yii::$app->session->setFlash('success', 'Не все поля заполнены корректно.');
            }
            return $this->render('update', [
                'model' => $model,
                'dataProviderMetros' => $dataProviderMetros,
            ]);
        }
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionUp($id)
    {
        $model = Partners::findOne($id);
        $prevModel = Partners::find()->where('position = (select max(position) from ' . Partners::tableName() . ' where position < '. $model->position . ')')->one();

        if($prevModel) {
            $pos = $prevModel->position;
            $prevModel->position = $model->position;
            $prevModel->save();

            $model->position = $pos;
            $model->save();
        }

        return $this->redirect(['index']);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionDown($id)
    {
        $model = Partners::findOne($id);
        $nextModel = Partners::find()->where('position = (select min(position) from ' . Partners::tableName() . ' where position > '. $model->position . ')')->one();

        if($nextModel) {
            $pos = $nextModel->position;
            $nextModel->position = $model->position;
            $nextModel->save();

            $model->position = $pos;
            $model->save();
        }

        return $this->redirect(['index']);
    }

    /**
     * Disable an existing Jobs model.
     * If disable is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
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
     * @throws NotFoundHttpException
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
     * Creates a new Metros model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionMetroCreate()
    {
        $model = new MetroPartners();

        if (Yii::$app->request->isPost) {
            if (($metro = MetroPartners::find()->orderBy(['position' => SORT_DESC])->one()) !== null) {
                /** @var \common\models\MetroPartners $metro */
                $model->position = $metro->position + 10;
            } else {
                $model->position = 10;
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $model->partners_id, '#' => 'metro']);
        } else {
            return $this->redirect(['update#metro']);
            /*return $this->render('update', [
                'model' => $model,
            ]);*/
        }
    }

    /**
     * Deletes an existing Metros model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionMetroDelete($id)
    {
        MetroPartners::findOne($id)->delete();

        if (Yii::$app->getRequest()->isAjax) {
            $metro = new MetroPartners();
            $dataProviderMetros = new ActiveDataProvider([
                'query' => MetroPartners::find()->where(['partners_id' => $model->partners_id])->orderBy('position'),
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
    public function actionMetroUp($id)
    {
        $model = MetroPartners::findOne($id);
        $prevModel = MetroPartners::find()->where('position = (select max(position) from ' . MetroPartners::tableName() . ' where position < '. $model->position . ')')->one();

        if($prevModel) {
            $pos = $prevModel->position;
            $prevModel->position = $model->position;
            $prevModel->save();

            $model->position = $pos;
            $model->save();
        }

        if (Yii::$app->getRequest()->isAjax) {
            $metro = new MetroPartners();
            $dataProviderMetros = new ActiveDataProvider([
                'query' => MetroPartners::find()->where(['partners_id' => $model->partners_id])->orderBy('position'),
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
        $model = MetroPartners::findOne($id);
        $nextModel = MetroPartners::find()->where('position = (select min(position) from ' . MetroPartners::tableName() . ' where position > '. $model->position . ')')->one();

        if($nextModel) {
            $pos = $nextModel->position;
            $nextModel->position = $model->position;
            $nextModel->save();

            $model->position = $pos;
            $model->save();
        }

        if (Yii::$app->getRequest()->isAjax) {
            $metro = new MetroPartners();
            $dataProviderMetros = new ActiveDataProvider([
                'query' => MetroPartners::find()->where(['partners_id' => $model->partners_id])->orderBy('position'),
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
     * Deletes an existing Partners model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Partners model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Partners the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Partners::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

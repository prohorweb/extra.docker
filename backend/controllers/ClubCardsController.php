<?php

namespace backend\controllers;

use Yii;
use common\models\UploadFile;
use common\models\ClubCards;
use common\models\ClubCardsParams;
use common\models\UploadImage;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ClubCardsController implements the CRUD actions for ClubCards model.
 */
class ClubCardsController extends Controller
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
                        'roles' => ['club_cards'],
                    ],
                    // everything else is denied
                ],
            ],
        ];
    }

    /**
     * Lists all ClubCards models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => ClubCards::find(),
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
     * Displays a single ClubCards model.
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
     * Creates a new ClubCards model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ClubCards();

        if (Yii::$app->request->isPost) {
            if (($clubCards = ClubCards::find()->orderBy(['position' => SORT_DESC])->one()) !== null) {
                /** @var \common\models\ClubCards $clubCards */
                $model->position = $clubCards->position + 10;
            } else {
                $model->position = 10;
            }

            $modelFile = new UploadImage(1752, 390);
            $modelFile->imageFile = UploadedFile::getInstance($model, 'img');
            $path = Yii::getAlias('@frontend') . '/web/uploads/image/club_cards/';

            if (!empty($modelFile->imageFile) && $modelFile->upload($path)) {
                // file is uploaded successfully
                $model->img = $modelFile->newFilename;
                $model->imageCrop($path);
            } elseif ($modelFile->hasErrors('imageFile')) {
                $model->addError('img', $modelFile->getFirstError('imageFile'));
            }

            $modelFile = new UploadFile();
            $modelFile->file = UploadedFile::getInstance($model, 'icon');
            $path = Yii::getAlias('@frontend') . '/web/uploads/image/club_cards/icons/';

            if (!empty($modelFile->file) && $modelFile->upload($path)) {
                // file is uploaded successfully
                $model->icon = $modelFile->newFilename;
            } elseif ($modelFile->hasErrors('file')) {
                $model->addError('icon', $modelFile->getFirstError('file'));
            }
        }

        if ($model->load(Yii::$app->request->post()) && !$model->errors && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ClubCards model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isPost) {
            $modelFile = new UploadImage(1752, 390);
            $modelFile->imageFile = UploadedFile::getInstance($model, 'img');
            $path = Yii::getAlias('@frontend') . '/web/uploads/image/club_cards/';

            if (!empty($modelFile->imageFile) && $modelFile->upload($path)) {
                // file is uploaded successfully
                $model->img = $modelFile->newFilename;
                $model->imageCrop($path);
            } elseif ($modelFile->hasErrors('imageFile')) {
                $model->addError('img', $modelFile->getFirstError('imageFile'));
            }

            $modelFile = new UploadFile();
            $modelFile->file = UploadedFile::getInstance($model, 'icon');
            $path = Yii::getAlias('@frontend') . '/web/uploads/image/club_cards/icons/';

            if (!empty($modelFile->file) && $modelFile->upload($path)) {
                // file is uploaded successfully
                $model->icon = $modelFile->newFilename;
            } elseif ($modelFile->hasErrors('file')) {
                $model->addError('icon', $modelFile->getFirstError('file'));
            }
        }

        if (!$model->errors && $model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Информация успешно обновлена.');
            return $this->render('update', [
                'model' => $model,
            ]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }


    /**
     * @return mixed
     */
    public function actionParams()
    {
        $model = ClubCardsParams::findOne(1);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Материал опубликован на сайте");
            return $this->render('params', [
                'model' => $model,
            ]);
        } else {
            return $this->render('params', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ClubCards model.
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
     * Disable an existing ClubCards model.
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
     * Activate an existing ClubCards model.
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
        $prevModel = ClubCards::find()->where('position = (select max(position) from ' . ClubCards::tableName() . ' where position < '. $model->position . ')')->one();

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
        $nextModel = ClubCards::find()->where('position = (select min(position) from ' . ClubCards::tableName() . ' where position > '. $model->position . ')')->one();

        if($nextModel) {
            $pos = $nextModel->position;
            $nextModel->position = $nmodel->position;
            $nextModel->save();

            $model->position = $pos;
            $model->save();
        }

        return $this->redirect(['index', 'page' => isset($_GET['page']) ? $_GET['page'] : 0]);
    }

    /**
     * Finds the ClubCards model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ClubCards the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ClubCards::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

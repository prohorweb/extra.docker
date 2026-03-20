<?php

namespace backend\controllers;

use common\models\ClubCards;
use common\models\Shares;
use common\models\Trainers;
use Yii;
use common\models\Events;
use common\models\News;
use common\models\Services;
use common\models\UploadImage;
use common\models\MainBanners;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BannersController implements the CRUD actions for MainBanners model.
 */
class BannersController extends Controller
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
                        'roles' => ['main_banners'],
                    ],
                    // everything else is denied
                ],
            ],
        ];
    }

    /**
     * Lists all MainBanners models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => MainBanners::find()->where('for_club = 0'),
            'sort' => ['defaultOrder' => ['position' => SORT_ASC], 'attributes' => ['position', 'status']],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MainBanners model.
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
     * Creates a new MainBanners model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MainBanners();

        if (Yii::$app->request->isPost) {
            if (($mainBanners = MainBanners::find()->where('for_club = 0')->orderBy(['position' => SORT_DESC])->one()) !== null) {
                /** @var \common\models\MainBanners $mainBanners */
                $model->position = $mainBanners->position + 10;
            } else {
                $model->position = 10;
            }

            $modelFile = new UploadImage(1904, 1080);
            if (!$model->upload($modelFile) && $modelFile->hasErrors('imageFile')) {
                $model->addError('img', $modelFile->getFirstError('imageFile'));
            }
        }

        if ($model->load(Yii::$app->request->post()) && !$model->errors && $model->save()) {
            return $this->redirect(['update?id=' . $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MainBanners model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isPost) {

            $modelFile = new UploadImage(1904, 1080);
            if (!$model->upload($modelFile) && $modelFile->hasErrors('imageFile')) {
                $model->addError('img', $modelFile->getFirstError('imageFile'));
            }
        }

        if (!$model->errors && $model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->render('update', [
                'model' => $model,
            ]);
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
     * Deletes an existing MainBanners model.
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
     * Disable an existing MainBanners model.
     * If disable is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDisable($id)
    {
        $model = $this->findModel($id);
        $model->status = 0;

        if ($model->save()) {
            Yii::$app->session->setFlash('warning', "Баннер снят с публикации на сайте");
        }

        return $this->redirect(['index', 'page' => isset($_GET['page']) ? $_GET['page'] : 0]);
    }

    /**
     * Activate an existing MainBanners model.
     * If activate is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionActivate($id)
    {
        $model = $this->findModel($id);
        $model->status = 10;

        if ($model->save()) {
            Yii::$app->session->setFlash('success', "Баннер опубликован на сайте");
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
        $prevModel = MainBanners::find()->where('for_club = 0 AND position = (select max(position) from ' . MainBanners::tableName() . ' where position < '. $model->position . ')')->one();

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
        $nextModel = MainBanners::find()->where('for_club = 0 AND position = (select min(position) from main_banners where position > '. $model->position . ')')->one();

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
     * Finds the MainBanners model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MainBanners the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MainBanners::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

<?php

namespace backend\controllers;

use Yii;
use common\models\Settings;
use common\models\UploadFile;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * SettingsController implements the CRUD actions for Settings model.
 */
class SettingsController extends Controller
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
                        'roles' => ['settings'],
                    ],
                    // everything else is denied
                ],
            ],
        ];
    }

    /**
     * Lists all Settings models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Settings::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Settings model.
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
     * Creates a new Settings model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Settings();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Settings model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @internal param int $id
     */
    public function actionUpdate()
    {
        $model = $this->findModel(1);

        $robots = '';
        $myfilename = "robots.txt";
        if (file_exists($myfilename)) {
            $robots = file_get_contents(Yii::getAlias('@frontend') . '/web/' . $myfilename);
        }

        if (Yii::$app->request->isPost) {
            $modelFile = new UploadFile();
            $modelFile->file = UploadedFile::getInstance($model, 'file_yandex');
            $path = Yii::getAlias('@frontend') . '/web/';

            if (!empty($modelFile->file) && $modelFile->upload($path)) {
                // file is uploaded successfully
                $model->file_yandex = $modelFile->newFilename;
            } elseif ($modelFile->hasErrors('file')) {
                $model->addError('file_yandex', $modelFile->getFirstError('file'));
            }

            $modelFile->file = UploadedFile::getInstance($model, 'file_google');
            if (!empty($modelFile->file) && $modelFile->upload($path)) {
                // file is uploaded successfully
                $model->file_google = $modelFile->newFilename;
            } elseif ($modelFile->hasErrors('file')) {
                $model->addError('file_google', $modelFile->getFirstError('file'));
            }

            $modelFile->file = UploadedFile::getInstance($model, 'logo1');
            if (!empty($modelFile->file) && $modelFile->upload($path)) {
                // file is uploaded successfully
                $model->logo1 = $modelFile->newFilename;
            } elseif ($modelFile->hasErrors('file')) {
                $model->addError('logo1', $modelFile->getFirstError('file'));
            }

            $modelFile->file = UploadedFile::getInstance($model, 'logo2');
            if (!empty($modelFile->file) && $modelFile->upload($path)) {
                // file is uploaded successfully
                $model->logo2 = $modelFile->newFilename;
            } elseif ($modelFile->hasErrors('file')) {
                $model->addError('logo2', $modelFile->getFirstError('file'));
            }

            if (!empty(Yii::$app->request->post()['robots']) && $robots != Yii::$app->request->post()['robots']) {
                file_put_contents(Yii::getAlias('@frontend') . '/web/' . $myfilename, Yii::$app->request->post()['robots']);
                $robots = Yii::$app->request->post()['robots'];
            }
        }

        if (!$model->errors && $model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Настройки сайта успешно обновлены.');
            return $this->render('update', [
                'model' => $model,
                'robots' => $robots,
            ]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'robots' => $robots,
            ]);
        }
    }

    public function actionUpgrade()
    {
        $op = shell_exec('cd ../../ && git checkout && git pull origin master');

        VarDumper::dump($op, 10, 1);
    }

    /**
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionTimerStart()
    {
        $model = $this->findModel(1);
        $model->timer = 1;
        $model->save();

        return $this->redirect('update');
    }

    /**
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionTimerEnd()
    {
        $model = $this->findModel(1);
        $model->timer = 0;
        $model->save();

        return $this->redirect('update');
    }

    /**
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionBonusStart()
    {
        $model = $this->findModel(1);
        $model->bonus = 1;
        $model->save();

        return $this->redirect('update');
    }

    /**
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionBonusEnd()
    {
        $model = $this->findModel(1);
        $model->bonus = 0;
        $model->save();

        return $this->redirect('update');
    }

    /**
     * Deletes an existing Settings model.
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
     * Finds the Settings model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Settings the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Settings::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

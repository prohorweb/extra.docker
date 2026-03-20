<?php

namespace backend\controllers;

use Yii;
use common\models\UploadImage;
use common\models\Promo;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * PromoController implements the CRUD actions for Promo model.
 */
class PromoController extends Controller
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
     * Updates an existing Promo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @internal param int $id
     */
    public function actionUpdate()
    {
        $model = $this->findModel(1);

        if (Yii::$app->request->isPost) {
            $modelFile = new UploadImage(1904, 643);
            $modelFile->imageFile = UploadedFile::getInstance($model, 'img');
            $path = Yii::getAlias('@frontend') . '/web/uploads/image/promo/';

            if (!empty($modelFile->imageFile) && $modelFile->upload($path)) {
                // file is uploaded successfully
                $model->img = $modelFile->newFilename;
                $model->imageCrop($path);
            } elseif ($modelFile->hasErrors('imageFile')) {
                $model->addError('img', $modelFile->getFirstError('imageFile'));
            }

            $modelFile = new UploadImage(73, 74);
            $modelFile->imageFile = UploadedFile::getInstance($model, 'icon1');
            if (!empty($modelFile->imageFile) && $modelFile->upload($path)) {
                // file is uploaded successfully
                $model->icon1 = $modelFile->newFilename;
            } elseif ($modelFile->hasErrors('imageFile')) {
                $model->addError('icon1', $modelFile->getFirstError('imageFile'));
            }

            $modelFile = new UploadImage(73, 74);
            $modelFile->imageFile = UploadedFile::getInstance($model, 'icon3');
            if (!empty($modelFile->imageFile) && $modelFile->upload($path)) {
                // file is uploaded successfully
                $model->icon3 = $modelFile->newFilename;
            } elseif ($modelFile->hasErrors('imageFile')) {
                $model->addError('icon3', $modelFile->getFirstError('imageFile'));
            }

            $modelFile = new UploadImage(73, 74);
            $modelFile->imageFile = UploadedFile::getInstance($model, 'icon6');
            if (!empty($modelFile->imageFile) && $modelFile->upload($path)) {
                // file is uploaded successfully
                $model->icon6 = $modelFile->newFilename;
            } elseif ($modelFile->hasErrors('imageFile')) {
                $model->addError('icon6', $modelFile->getFirstError('imageFile'));
            }

            $modelFile = new UploadImage(73, 74);
            $modelFile->imageFile = UploadedFile::getInstance($model, 'icon12');
            if (!empty($modelFile->imageFile) && $modelFile->upload($path)) {
                // file is uploaded successfully
                $model->icon12 = $modelFile->newFilename;
            } elseif ($modelFile->hasErrors('imageFile')) {
                $model->addError('icon12', $modelFile->getFirstError('imageFile'));
            }
        }

        if (!$model->errors && $model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Промо сайта успешно обновлено.');
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
     * Finds the Promo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Promo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Promo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

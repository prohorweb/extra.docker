<?php

namespace frontend\controllers;

use Yii;
use common\models\Seo;
use common\models\Services;
use common\models\Settings;
use common\models\Club;
use common\models\Jobs;
use yii\helpers\FileHelper;
use yii\helpers\Inflector;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * JobController implements the CRUD actions for Jobs model.
 */
class JobController extends Controller
{
    protected $pageSize = 4;

    public function init()
    {
        parent::init();
        Yii::$app->view->params['club'] = Club::findOne(1);
        Yii::$app->view->params['settings'] = Settings::findOne(1);
        Yii::$app->view->params['services'] = Services::find()->where(['status' => 10])->orderBy(['position' => SORT_ASC])->all();
        Yii::$app->session->set('group_programs_id', null); // reset filter
        Yii::$app->session->set('program_classes_id', null); // reset filter
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
        ];
    }

    /**
     * Lists all Jobs models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = Jobs::find()->where(['status' => 10])->orderBy(['position' => SORT_ASC])->one();

        $seo = Seo::findOne(['type' => 'jobs']);

        if ($model) {
            //return $this->redirect('/wg/job/' . $model->alias . '/');
            return $this->render('index', [
                'models' => Jobs::find()->where(['status' => 10])->orderBy(['position' => SORT_ASC])->all(),
                'seo' => $seo,
            ]);
        } else {
            return $this->render('no-vacancy', ['seo' => $seo]);
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
            'models' => Jobs::find()->where(['status' => 10])->orderBy(['position' => SORT_ASC])->all(),
            'model' => $this->findModel($id),
        ]);
    }


    /**
     * @return \yii\web\Response
     */
    public function actionSubscribe()
    {
        $settings = Settings::findOne(1);
        $club = Yii::$app->view->params['club'];

        $file = UploadedFile::getInstanceByName('rezume');
        $newFilename = str_replace(' ', '-', Inflector::transliterate($file->baseName)) . '-' . time() . '.' . $file->extension;

        Yii::$app->mailer->compose(['html' => 'job-html', 'text' => 'job-text'])
            ->setFrom($settings->email_from)
            ->setTo($club->email)
            ->setSubject('Отклик на вакансию ' . $_POST['title'])
            ->attach($file->tempName, ['fileName' => $newFilename])
            ->send();

        Yii::$app->session->setFlash('mailerFormSubmitted');

        return $this->redirect(Yii::$app->request->referrer);
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

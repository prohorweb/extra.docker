<?php
namespace frontend\controllers;

use Yii;
use common\models\Services;
use common\models\Settings;
use common\models\Club;
use yii\web\Controller;

class PrivacyController extends Controller
{
    public function init()
    {
        parent::init();
        Yii::$app->view->params['club'] = Club::findOne(1);
        Yii::$app->view->params['settings'] = Settings::findOne(1);
        Yii::$app->view->params['services'] = Services::find()->where(['status' => 10])->orderBy(['position' => SORT_ASC])->all();
        Yii::$app->session->set('group_programs_id', null); // reset filter
        Yii::$app->session->set('program_classes_id', null); // reset filter
    }

    public function actionIndex()
    {
        return $this->render('index');
    }
}
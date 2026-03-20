<?php

namespace api\controllers;

use common\models\Club;
use common\models\Notifications;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;

/**
 * App Controller API
 */
class AppController extends ActiveController
{
    public $modelClass = 'common\models\club';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
        ];

        return $behaviors;
    }

    public function actions(){
        $actions = parent::actions();
        unset($actions['index'], $actions['view'], $actions['options']);
        return $actions;
    }

    /**
     * Rest Description: initAppCall.
     */
    public function actionIndex(){

        return [
            'count_notifications' => Notifications::find()->count(),
            'club_phone_number' => preg_replace('/[^\d+]/', '', Club::findOne(1)['tel']),
            'clubs' => []
        ];
    }
}
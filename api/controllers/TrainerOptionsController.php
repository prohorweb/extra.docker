<?php

namespace api\controllers;

use Yii;
use common\models\TrainerOptions;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

/**
 * TrainerOptions Controller API
 */
class TrainerOptionsController extends ActiveController
{
    public $modelClass = 'common\models\TrainerOptions';

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
        unset($actions['index']);
        return $actions;
    }

    /**
     * Rest Description: Связующая таблица между направлениями и тренерами.
     */
    public function actionIndex(){
        $activeData = new ActiveDataProvider([
            'query' => TrainerOptions::find(),
            'pagination' => false,
        ]);
        return $activeData;
    }
}
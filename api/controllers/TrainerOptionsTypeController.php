<?php

namespace api\controllers;

use common\models\TrainerOptions;
use Yii;
use common\models\TrainerOptionsType;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

/**
 * TrainerOptionsType Controller API
 */
class TrainerOptionsTypeController extends ActiveController
{
    public $modelClass = 'common\models\TrainerOptionsType';

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
     * Rest Description: Список направлений команды клуба.
     */
    public function actionIndex(){
        $activeData = new ActiveDataProvider([
            'query' => TrainerOptionsType::find()->innerJoin(TrainerOptions::tableName(), TrainerOptions::tableName() . '.option_id = ' . TrainerOptionsType::tableName() . '.id')->where(['status' => 10]),
            'pagination' => false,
        ]);
        return $activeData;
    }
}
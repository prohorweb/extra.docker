<?php

namespace api\controllers;

use common\models\TrainerOptions;
use Yii;
use common\models\Trainers;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;

/**
 * Trainers Controller API
 */
class TrainersController extends ActiveController
{
    public $modelClass = 'common\models\trainers';

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
     * Rest Description: Список Тренеров.
     */
    public function actionIndex(){
        $activeData = new ActiveDataProvider([
            'query' => Trainers::find()->where(['status' => 10]),
            'sort' => ['defaultOrder' => ['position' => SORT_ASC]],
            'pagination' => false,
        ]);
        return $activeData;
    }

    /**
     * Rest Description: Тренер по идентификатору.
     */
    public function actionView($id)
    {
        $model = Trainers::findOne($id);

        if (isset($model)) {
            return $model;
        } else {
            throw new NotFoundHttpException("Object not found: $id");
        }
    }

    /**
     * Rest Description: Список тренеров по идентификатору команды.
     */
    public function actionOptions($id)
    {
        $activeData = new ActiveDataProvider([
            'query' => Trainers::find()->innerJoin(TrainerOptions::tableName(), TrainerOptions::tableName() . '.trainer_id = ' . Trainers::tableName() . '.id')->where(['option_id' => $id, 'status' => 10]),
            'pagination' => false,
        ]);
        return $activeData;
    }
}
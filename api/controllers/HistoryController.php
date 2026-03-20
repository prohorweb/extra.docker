<?php

namespace api\controllers;

use Yii;
use common\models\History;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;

/**
 * History Controller API
 */
class HistoryController extends ActiveController
{
    public $modelClass = 'common\models\History';

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
        unset($actions['index'], $actions['view']);
        return $actions;
    }

    /**
     * Rest Description: Список историй успеха.
     */
    public function actionIndex(){
        $activeData = new ActiveDataProvider([
            'query' => History::find()->where(['status' => 10])->orderBy(['date' => SORT_DESC]),
            'pagination' => [
                'defaultPageSize' => 10,
            ],
        ]);
        return $activeData;
    }

    /**
     * Rest Description: История успеха по идентификатору.
     */
    public function actionView($id)
    {
        $model = History::findOne($id);

        if (isset($model)) {
            return $model;
        } else {
            throw new NotFoundHttpException("Object not found: $id");
        }
    }
}
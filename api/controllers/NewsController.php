<?php

namespace api\controllers;

use Yii;
use common\models\News;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;

/**
 * News Controller API
 */
class NewsController extends ActiveController
{
    public $modelClass = 'common\models\News';

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
     * Rest Description: Список новостей.
     */
    public function actionIndex(){
        $activeData = new ActiveDataProvider([
            'query' => News::find()->where(['status' => 10])->orderBy(['date' => SORT_DESC]),
            'pagination' => [
                'defaultPageSize' => 10,
            ],
        ]);
        return $activeData;
    }

    /**
     * Rest Description: Новость по идентификатору.
     */
    public function actionView($id)
    {
        $model = News::findOne($id);

        if (isset($model)) {
            return $model;
        } else {
            throw new NotFoundHttpException("Object not found: $id");
        }
    }
}
<?php

namespace api\controllers;

use Yii;
use common\models\Articles;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;

/**
 * Articles Controller API
 */
class ArticleController extends ActiveController
{
    public $modelClass = 'common\models\Articles';

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
     * Rest Description: Список советы тренеров.
     */
    public function actionIndex(){
        $activeData = new ActiveDataProvider([
            'query' => Articles::find()->where(['status' => 10])->orderBy(['date' => SORT_DESC]),
            'pagination' => [
                'defaultPageSize' => 10,
            ],
        ]);
        return $activeData;
    }

    /**
     * Rest Description: Совет тренера по идентификатору.
     */
    public function actionView($id)
    {
        $model = Articles::findOne($id);

        if (isset($model)) {
            return $model;
        } else {
            throw new NotFoundHttpException("Object not found: $id");
        }
    }
}
<?php

namespace api\controllers;

use Yii;
use common\models\Services;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;

/**
 * Services Controller API
 */
class ServicesController extends ActiveController
{
    public $modelClass = 'common\models\services';

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
     * Rest Description: Список услуг клуба<br>
     * <i>под 1 идентификатором всегда «персональный тренинг», под 2 «Бассейн», под 3 «Детский клуб», под 4 "Групповые программы"</i>.
     */
    public function actionIndex(){
        $activeData = new ActiveDataProvider([
            'query' => Services::find()->where(['status' => 10])->orderBy(['position' => SORT_ASC]),
            'pagination' => false,
        ]);
        return $activeData;
    }

    /**
     * Rest Description: Услуга по идентификатору.
     */
    public function actionView($id)
    {
        $model = Services::findOne($id);

        if (isset($model)) {
            return $model;
        } else {
            throw new NotFoundHttpException("Object not found: $id");
        }
    }
}

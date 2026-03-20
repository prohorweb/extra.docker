<?php

namespace api\controllers;

use Yii;
use common\models\TypeRasp;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;

/**
 * TypeRasp Controller API
 */
class TypeRaspController extends ActiveController
{
    public $modelClass = 'common\models\TypeRasp';

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
     * Rest Description: Список типа расписания.
     */
    public function actionIndex(){
        $activeData = new ActiveDataProvider([
            'query' => TypeRasp::find(),
            'pagination' => false,
        ]);
        return $activeData;
    }
}
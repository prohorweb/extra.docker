<?php

namespace api\controllers;

use Yii;
use common\models\Club;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

/**
 * Club Controller API
 */
class ClubController extends ActiveController
{
    public $modelClass = 'common\models\Club';

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
     * Rest Description: Информация для страницы "Наш клуб".
     */
    public function actionIndex(){
        $activeData = new ActiveDataProvider([
            'query' => Club::find(),
            'pagination' => false,
        ]);
        return $activeData;
    }
}
<?php

namespace api\controllers;

use Yii;
use common\models\GroupPrograms;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

/**
 * GroupPrograms Controller API
 */
class GroupProgramsController extends ActiveController
{
    public $modelClass = 'common\models\GroupPrograms';

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
     * Rest Description: Список групповых программ<br>
     * <i>под 1 идентификатором всегда «Детский клуб», под 2 «Бассейн»</i>.
     */
    public function actionIndex(){
        $activeData = new ActiveDataProvider([
            'query' => GroupPrograms::find()->where(['status' => 10]),
            'pagination' => false,
        ]);
        return $activeData;
    }
}
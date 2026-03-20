<?php

namespace api\controllers;

use Yii;
use common\models\ProgramClasses;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

/**
 * ProgramClasses Controller API
 */
class ProgramClassesController extends ActiveController
{
    public $modelClass = 'common\models\ProgramClasses';

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
     * Rest Description: Список занятий групповой программы.
     */
    public function actionIndex(){
        $activeData = new ActiveDataProvider([
            'query' => ProgramClasses::find()->where(['status' => 10]),
            'pagination' => false,
        ]);
        return $activeData;
    }

    /**
     * Rest Description: Список занятий по идентификатору групповой программы.
     */
    public function actionGroupPrograms($id)
    {
        $activeData = new ActiveDataProvider([
            'query' => ProgramClasses::find()->where(['group_programs_id' => $id,'status' => 10]),
            'pagination' => false,
        ]);
        return $activeData;
    }
}
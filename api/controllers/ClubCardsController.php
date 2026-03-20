<?php

namespace api\controllers;

use Yii;
use common\models\ClubCards;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;

/**
 * ClubCards Controller API
 */
class ClubCardsController extends ActiveController
{
    public $modelClass = 'common\models\ClubCards';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
        ];

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'], $actions['view']);
        return $actions;
    }

    /**
     * Rest Description: Список клубных карт.
     */
    public function actionIndex()
    {
        $activeData = new ActiveDataProvider([
            'query' => ClubCards::find()->where(['status' => 10]),
            'pagination' => false,
        ]);
        return $activeData;
    }


    /**
     * Rest Description: Клубная карта по идентификатору.
     */
    public function actionView($id)
    {
        $model = ClubCards::findOne($id);

        if (isset($model)) {
            return $model;
        } else {
            throw new NotFoundHttpException("Object not found: $id");
        }
    }
}
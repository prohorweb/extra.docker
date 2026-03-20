<?php

namespace api\controllers;

use Yii;
use common\models\Shares;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;

/**
 * Shares Controller API
 */
class SharesController extends ActiveController
{
    public $modelClass = 'common\models\shares';

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
     * Rest Description: Список акций.
     */
    public function actionIndex(){
        $activeData = new ActiveDataProvider([
            'query' => Shares::find()->where(['only_url' => 0, 'status' => 10]),
            'sort' => ['defaultOrder' => ['position' => SORT_ASC]],
            'pagination' => [
                'defaultPageSize' => 10,
            ],
        ]);
        return $activeData;
    }

    /**
     * Rest Description: Акция по идентификатору.
     */
    public function actionView($id)
    {
        $model = Shares::findOne($id);

        if (isset($model)) {
            return $model;
        } else {
            throw new NotFoundHttpException("Object not found: $id");
        }
    }
}
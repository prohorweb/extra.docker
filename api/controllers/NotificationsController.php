<?php

namespace api\controllers;

use Yii;
use common\models\Notifications;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;

/**
 * Notifications Controller API
 */
class NotificationsController extends ActiveController
{
    public $modelClass = 'common\models\notifications';

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
        unset($actions['index'], $actions['view'], $actions['options']);
        return $actions;
    }

    /**
     * Rest Description: Список уведомлений.
     */
    public function actionIndex()
    {
        $activeData = new ActiveDataProvider([
            'query' => Notifications::find()->orderBy(['id' => SORT_DESC])->limit(40),
            //'pagination' => false,
            'pagination' => [
                'defaultPageSize' => 40,
            ],
        ]);
        return $activeData;
    }

    /**
     * Rest Description: Уведомление по идентификатору.
     * @param $id
     * @return Notifications|null
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = Notifications::findOne($id);

        if (isset($model)) {
            return $model;
        } else {
            throw new NotFoundHttpException("Object not found: $id");
        }
    }
}
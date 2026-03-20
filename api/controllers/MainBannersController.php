<?php

namespace api\controllers;

use Yii;
use common\models\MainBanners;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

/**
 * MainBanners Controller API
 */
class MainBannersController extends ActiveController
{
    public $modelClass = 'common\models\MainBanners';

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
     * Rest Description: Список слайдов акционного блока клуба на главной странице.
     */
    public function actionIndex(){
        $activeData = new ActiveDataProvider([
            'query' => MainBanners::find()->where(['status' => 10, 'for_club' => 0])->orderBy(['position' => SORT_ASC]),
            'pagination' => false,
        ]);
        return $activeData;
    }
}
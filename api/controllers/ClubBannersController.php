<?php

namespace api\controllers;

use Yii;
use common\models\ClubBanners;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

/**
 * ClubBanners Controller API
 */
class ClubBannersController extends ActiveController
{
    public $modelClass = 'common\models\ClubBanners';

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
     * Rest Description: Список слайдов имиджевого блока клуба на главной странице.
     */
    public function actionIndex(){
        $activeData = new ActiveDataProvider([
            'query' => ClubBanners::find()->where(['status' => 10])->orderBy(['position' => SORT_ASC]),
            'pagination' => false,
        ]);
        return $activeData;
    }
}
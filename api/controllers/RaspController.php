<?php

namespace api\controllers;

use DateTime;
use Yii;
use common\models\Rasp;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;

/**
 * Rasp Controller API
 */
class RaspController extends ActiveController
{
    public $modelClass = 'common\models\Rasp';

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
     * Rest Description: Список расписания занятий.
     */
    public function actionIndex(){

        $from = (new DateTime())->setISODate((new \DateTime())->format('o'), (new \DateTime())->format('W'), -6)->format('Y-m-d');
        $to = (new DateTime())->setISODate((new \DateTime())->format('o'), (new \DateTime())->format('W'), +21)->format('Y-m-d');
        $activeData = new ActiveDataProvider([
            'query' => Rasp::find()->where(['between', 'date', $from, $to]),
            'pagination' => false,
        ]);
        return $activeData;
    }

    /**
     * Rest Description: Список расписания занятий (+ id пользователя).
     * @param null $token
     * @return ActiveDataProvider
     */
    public function actionById($token = null){

        $from = (new DateTime())->setISODate((new \DateTime())->format('o'), (new \DateTime())->format('W'), -6)->format('Y-m-d');
        $to = (new DateTime())->setISODate((new \DateTime())->format('o'), (new \DateTime())->format('W'), +21)->format('Y-m-d');
        $activeData = new ActiveDataProvider([
            'query' => Rasp::find()->where(['between', 'date', $from, $to]),
            'pagination' => false,
        ]);
        return $activeData;
    }

    /**
     * Rest Description: Список расписания занятий по типу расписания.
     * @param $type_id
     * @param null $token
     * @return ActiveDataProvider
     */
    public function actionByType($type_id, $token = null)
    {
        $from = (new DateTime())->setISODate((new \DateTime())->format('o'), (new \DateTime())->format('W'), -6)->format('Y-m-d');
        $to = (new DateTime())->setISODate((new \DateTime())->format('o'), (new \DateTime())->format('W'), +21)->format('Y-m-d');
        $activeData = new ActiveDataProvider([
            'query' => Rasp::find()->where(['type_rasp_id' => $type_id])->andWhere(['between', 'date', $from, $to]),
            'pagination' => false,
        ]);
        return $activeData;
    }

    /**
     * Rest Description: Список расписания занятий по типу расписания и дате (yyyy-mm-dd).
     * @param null $token
     * @param $type_id
     * @param $date
     * @param null $group_programs_id
     * @param null $program_classes_id
     * @return ActiveDataProvider
     */
    public function actionByDate($token = null, $type_id, $date, $group_programs_id = null, $program_classes_id = null)
    {
        $query = Rasp::find()->where(['type_rasp_id' => $type_id])->andWhere(['date' => $date])->orderBy(['time' => SORT_ASC]);

        if ($group_programs_id) {
            $query->andWhere(['group_programs_id' => $group_programs_id]);
        }
        if ($program_classes_id) {
            $query->andWhere(['program_classes_id' => $program_classes_id]);
        }

        $activeData = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        return $activeData;
    }

    /**
     * Rest Description: Список расписания занятий по дате и тренеру (yyyy-mm-dd).
     * @param null $token
     * @param $type_id
     * @param $date
     * @param null $group_programs_id
     * @param null $program_classes_id
     * @param null $trainer_id
     * @return ActiveDataProvider
     */
    public function actionByTrainer($token = null, $type_id, $date, $group_programs_id = null, $program_classes_id = null, $trainer_id = null)
    {
        $query = Rasp::find()->where(['type_rasp_id' => $type_id])->andWhere(['date' => $date])->orderBy(['time' => SORT_ASC]);

        if ($group_programs_id) {
            $query->andWhere(['group_programs_id' => $group_programs_id]);
        }
        if ($program_classes_id) {
            $query->andWhere(['program_classes_id' => $program_classes_id]);
        }
        if ($trainer_id) {
            $query->andWhere(['trainer_id' => $trainer_id]);
        }

        $activeData = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        return $activeData;
    }

    /**
     * Rest Description: Расписание по идентификатору.
     * @param $id
     * @return array|Rasp|null
     */
    public function actionView($id)
    {
        $model = Rasp::findOne($id);

        if (isset($model)) {
            return $model;
        } else {
            return ["Object not found" => null];
        }
    }

    /**
     * Rest Description: Расписание по идентификатору (+ id пользователя).
     * @param $id
     * @param null $token
     * @return array|Rasp|null
     */
    public function actionViewById($id, $token = null)
    {
        $model = Rasp::findOne($id);

        if (isset($model)) {
            return $model;
        } else {
            return ["Object not found" => null];
        }
    }
}
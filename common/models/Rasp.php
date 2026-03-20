<?php

namespace common\models;

use api2\models\Send;
use Yii;
use manager\models\RaspLike;
use manager\models\UserClabis;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "rasp".
 *
 * @property integer $id
 * @property int $status
 * @property string $date
 * @property string $time
 * @property integer $is_pay
 * @property integer $is_preliminary
 * @property integer $is_new
 * @property string $comment
 * @property integer $program_classes_id
 * @property integer $type_rasp_id
 * @property integer $group_programs_id
 * @property integer $rooms_id
 * @property integer $trainer_id
 * @property int $last_trainer_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Trainers $trainer
 * @property Trainers $lastTrainer
 * @property Colors $color
 * @property GroupPrograms $groupPrograms
 * @property ProgramClasses $programClasses
 * @property Rooms $rooms
 * @property TypeRasp $typeRasp
 */
class Rasp extends ActiveRecord
{
    private $resp;

    /**
     * @inheritdoc
     * @throws \yii\base\InvalidConfigException
     */
    public static function getDb() {
        if(Yii::$app->id == 'app-api'){
            $user = User::findIdentityByAccessToken(explode(" ", Yii::$app->request->headers['authorization'])[1]);
            return Yii::$app->get('db' . ($user->id != 1 ? $user->id : ''));
        }
        if(Yii::$app->id == 'app-frontend') {
            if(Yii::$app->language !== 'ru-RU') {
                return Yii::$app->get(Yii::$app->language);
            } else {
                return Yii::$app->db;
            }
        }
        $cookies = Yii::$app->request->cookies;
        if(($cookie = $cookies->get('bd')) !== null) {
            return Yii::$app->get('db' . $cookie->value);
        } else {
            return Yii::$app->db;
        }
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%rasp}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'value' => function() { return date('Y-m-d H:i:s'); },
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'date', 'time', 'rooms_id', 'group_programs_id', 'program_classes_id'], 'required'],
            [['date', 'time', 'created_at', 'updated_at'], 'safe'],
            [['status', 'is_pay', 'is_preliminary', 'is_new', 'program_classes_id', 'type_rasp_id', 'group_programs_id', 'rooms_id', 'trainer_id', 'last_trainer_id'], 'integer'],
            [['comment'], 'string'],
            [['trainer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Trainers::className(), 'targetAttribute' => ['trainer_id' => 'id']],
            [['group_programs_id'], 'exist', 'skipOnError' => true, 'targetClass' => GroupPrograms::className(), 'targetAttribute' => ['group_programs_id' => 'id']],
            [['program_classes_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProgramClasses::className(), 'targetAttribute' => ['program_classes_id' => 'id']],
            [['rooms_id'], 'exist', 'skipOnError' => true, 'targetClass' => Rooms::className(), 'targetAttribute' => ['rooms_id' => 'id']],
            [['type_rasp_id'], 'exist', 'skipOnError' => true, 'targetClass' => TypeRasp::className(), 'targetAttribute' => ['type_rasp_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Статус',
            'date' => 'Date',
            'time' => 'Время начала',
            'is_pay' => 'Is Pay',
            'is_preliminary' => 'Is Preliminary',
            'is_new' => 'Is New',
            'comment' => 'Описание',
            'program_classes_id' => 'Занятие',
            'type_rasp_id' => 'Type Rasp ID',
            'group_programs_id' => 'Направление',
            'rooms_id' => 'Зал / Возраст',
            'trainer_id' => 'Тренер',
            'last_trainer_id' => 'Last Trainer ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


    /**
     * @param $id
     * @param $year
     * @param $week
     * @param null $group_programs_id
     * @param null $program_classes_id
     * @param null $trainer_id
     * @return array
     */
    public static function getTableRasp($id, $year, $week, $group_programs_id = null, $program_classes_id = null, $trainer_id = null)
    {
        $firstDayWeek = (new \DateTime())->setISODate($year, $week);
        $lastDayWeek = (new \DateTime())->setISODate($year, $week, 7);

        $rasp = [];
        while ($firstDayWeek <= $lastDayWeek) {
            $query = Rasp::find()->where(['date' => $firstDayWeek->format('Y-m-d'), 'type_rasp_id' => $id]);
            if ($program_classes_id) {
                $query->andWhere(['program_classes_id' => $program_classes_id]);
            } elseif ($group_programs_id) {
                $query->andWhere(['group_programs_id' => $group_programs_id]);
            }
            if ($trainer_id) {
                $query->andWhere(['trainer_id' => $trainer_id]);
            }
            $rasp[] = $query->orderBy(['time' => SORT_ASC])->all();
            $firstDayWeek->modify('+1 day');
        }

        return $rasp;
    }


    /**
     * @param $id
     * @param $year
     * @param $week
     * @param null $group_programs_id
     * @param null $program_classes_id
     * @param null $trainer_id
     * @return array
     */
    public static function getArrTimes($id, $year, $week, $group_programs_id = null, $program_classes_id = null, $trainer_id = null)
    {
        $arr_times = [];

        foreach(self::getTableRasp($id, $year, $week, $group_programs_id, $program_classes_id, $trainer_id) as $model) {
            $vals = [];
            $counts = array_count_values(ArrayHelper::getColumn($model, 'time'));
            $counts_times = array_count_values($arr_times);
            $break = true;
            foreach ($model as $lesson) {
                if (!in_array($lesson->time, $arr_times)) {
                    $vals[$lesson->id] = $lesson->time;
                } elseif ($counts[$lesson->time] > 1 && $counts[$lesson->time] > $counts_times[$lesson->time] && $break) {
                    $vals[$lesson->id] = $lesson->time;
                    $break = false;
                }
            }
            $arr_times = ArrayHelper::merge($arr_times, $vals);
        }
        asort($arr_times);

        return $arr_times;
    }


    /**
     * @param $id
     * @param $year
     * @param $week
     * @param null $group_programs_id
     * @param null $program_classes_id
     * @param null $trainer_id
     * @return array
     */
    public static function getNewArrTimes($id, $year, $week, $group_programs_id = null, $program_classes_id = null, $trainer_id = null)
    {
        $arr_times = [];

        foreach(self::getTableRasp($id, $year, $week, $group_programs_id, $program_classes_id, $trainer_id) as $model) {
            $vals = [];
            foreach ($model as $lesson) {
                if (!in_array($lesson->time, $arr_times)) {
                    $vals[$lesson->id] = $lesson->time;
                }
            }
            $arr_times = ArrayHelper::merge($arr_times, $vals);
        }
        asort($arr_times);
        $arr_times = array_unique($arr_times);

        return $arr_times;
    }

    public static function getAllTrainers()
    {
        return Trainers::findAll(['status' => 10]);
    }


    /**
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function getSchedule()
    {
        if (isset($this->resp)) return $this->resp;

        return false;

        /*if ($this->is_pay) {
            $curl = new Send();
            $method = "getSchedule";
            $param = [
                "StartDate" => $this->date . ' ' . $this->time,
                "EndDate" => $this->date . ' ' . $this->time
            ];
            $resp = json_decode($curl->send($param, $method));

            foreach ($resp->Parameters as $val) {
                if ($val->Employee->Id == $this->trainer->id_1C) {
                    return $this->resp = $val;
                }
            }
            return (object)['error' => Yii::t('main', 'Не совпадают тренеры в расписании. Обратитесь в клуб.')];
        } else {
            return false;
        }*/
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrainer()
    {
        return $this->hasOne(Trainers::className(), ['id' => 'trainer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLastTrainer()
    {
        return $this->hasOne(Trainers::className(), ['id' => 'last_trainer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getColor()
    {
        return $this->hasOne(Colors::className(), ['id' => 'color_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupPrograms()
    {
        return $this->hasOne(GroupPrograms::className(), ['id' => 'group_programs_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgramClasses()
    {
        return $this->hasOne(ProgramClasses::className(), ['id' => 'program_classes_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRooms()
    {
        return $this->hasOne(Rooms::className(), ['id' => 'rooms_id']);
    }

    /**
     * @param $value
     */
    public function setRooms($value)
    {
        $this->rooms = $value;
    }

    /**
     * @param $value
     */
    public function setTrainer($value)
    {
        $this->trainer = $value;
    }

    /**
     * @param $value
     */
    public function setProgramClasses($value)
    {
        $this->programClasses = $value;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTypeRasp()
    {
        return $this->hasOne(TypeRasp::className(), ['id' => 'type_rasp_id']);
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $token = User::findOne(1)->access_token;
            $cookies = Yii::$app->request->cookies;
            if (($cookie = $cookies->get('bd')) !== null && $cookie->value) {
                $token = User::findOne($cookie->value)->access_token;
            }

            if($this->status == 20) {
                $this->last_trainer_id = $this->trainer_id;
                $this->trainer_id = $this->getOldAttribute('trainer_id');

                $message = ['ru' => $this->programClasses->title . ' замена тренера на ' . $this->lastTrainer->title, 'en' => $this->programClasses->title . ' замена тренера на ' . $this->lastTrainer->title];
                Yii::$app->onesignal->notifications()->create($message, ['headings' => ['ru' => 'Замена тренера'], 'filters' => [['field' => 'tag', 'key' => 'clubToken', 'relation' => '=', 'value' => $token]]]);
            } elseif($this->status == 0) {
                $message = ['ru' => 'Занятие отменено. Приносим свои извинения', 'en' => 'Занятие отменено. Приносим свои извинения'];
                Yii::$app->onesignal->notifications()->create($message, ['headings' => ['ru' => $this->programClasses->title], 'filters' => [['field' => 'tag', 'key' => 'clubToken', 'relation' => '=', 'value' => $token]]]);
            }

            return true;
        }
        return false;
    }

    /**
     * @return array
     */
    public function fields()
    {
        if (Yii::$app->id == 'app-api') {
            if (in_array(Yii::$app->controller->action->id, ['view', 'view-by-id'])) {
                $fields = [
                    'id',
                    'status',
                    'comment',
                    'duration' => function ($model) {
                        return $model->programClasses->duration;
                    },
                    'trainer' => function ($model) {
                        if ($model->trainer) {
                            return $model->trainer->title;
                        }
                    },
                    'new_trainer' => function ($model) {
                        if ($model->lastTrainer) {
                            return $model->lastTrainer->title;
                        }
                    },
                    'isLike' => function ($model) {
                        if (isset($_GET['token'])) {
                            $clubis = UserClabis::findOne(['token' => $_GET['token']]);
                            $raspLike = RaspLike::findOne(['rasp_id' => $model->id, 'user_id' => $clubis->id]);
                            return $raspLike ? $raspLike['id'] : false;
                        }
                    },
                    'isCostExist' => function ($model) {
                        /** @var self $model */
                        $resp = $model->getSchedule();

                        if (!empty($resp)) {
                            return true;
                        }
                        return false;
                    },
                    'cost' => function ($model) {
                        /** @var self $model */
                        $resp = $model->getSchedule();

                        if (!empty($resp) && !isset($resp->error)) {
                            return $resp->Service->Price;
                        }
                    },
                    'costError' => function ($model) {
                        /** @var self $model */
                        $resp = $model->getSchedule();

                        if(isset($resp->error)){
                            return $resp->error;
                        }
                    },
                    'id_1C' => function ($model) {
                        /** @var self $model */
                        $resp = $model->getSchedule();

                        if($resp) {
                            if(isset($resp->error)){
                                return $resp->error;
                            }
                            return $resp->AppointmentID;
                        }
                    },
                    'isTrenerIdExist' => function ($model) {
                        return isset($model->trainer->id_1C);
                    },
                ];
            } else {
                $fields = [
                    'id',
                    'status',
                    'date',
                    'time',
                    'is_pay',
                    'is_preliminary',
                    'is_new',
                    'program_classes' => function ($model) {
                        return $model->programClasses->title;
                    },
                    'type_rasp_id',
                    'room' => function ($model) {
                        return $model->rooms->title;
                    },
                    'trainer' => function ($model) {
                        if ($model->trainer) {
                            return $model->trainer->title;
                        }
                    },
                    'trainer_id',
                    'new_trainer' => function ($model) {
                        if ($model->lastTrainer) {
                            return $model->lastTrainer->title;
                        }
                    },
                    'color' => function ($model) {
                        return (isset($model->programClasses->color) ? $model->programClasses->color : "#ffffff");
                    },
                    'isLike' => function ($model) {
                        if (isset($_GET['token'])) {
                            $clubis = UserClabis::findOne(['token' => $_GET['token']]);
                            $raspLike = RaspLike::findOne(['rasp_id' => $model->id, 'user_id' => $clubis->id]);
                            return $raspLike ? $raspLike['id'] : false;
                        }
                    },
                ];
            }

            if (!isset($_GET['token'])) {
                unset($fields['isLike']);
            }

            return $fields;
        }
    }
}
<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "trainer_options".
 *
 * @property integer $id
 * @property integer $trainer_id
 * @property integer $option_id
 *
 * @property TrainerOptionsType $option
 * @property Trainers $trainer
 */
class TrainerOptions extends ActiveRecord
{
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
        return '{{%trainer_options}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['trainer_id', 'option_id'], 'required', 'message' => '{attribute} не может быть пустым.'],
            [['trainer_id', 'option_id'], 'integer'],
            [['option_id'], 'exist', 'skipOnError' => true, 'targetClass' => TrainerOptionsType::className(), 'targetAttribute' => ['option_id' => 'id']],
            [['trainer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Trainers::className(), 'targetAttribute' => ['trainer_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'trainer_id' => 'Trainer ID',
            'option_id' => 'Option ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOption()
    {
        return $this->hasOne(TrainerOptionsType::className(), ['id' => 'option_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrainer()
    {
        return $this->hasOne(Trainers::className(), ['id' => 'trainer_id']);
    }
}

<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "trainer_options_type".
 *
 * @property integer $id
 * @property integer $status
 * @property integer $position
 * @property string $title
 *
 * @property TrainerOptions[] $trainerOptions
 */
class TrainerOptionsType extends ActiveRecord
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
        return '{{%trainer_options_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'position'], 'integer'],
            [['position', 'title'], 'required', 'message' => '{attribute} не может быть пустым.'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Публикация',
            'position' => 'Position',
            'title' => 'Направления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrainerOptions()
    {
        return $this->hasMany(TrainerOptions::className(), ['option_id' => 'id']);
    }
}

<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "weeks".
 *
 * @property integer $id
 * @property integer $week
 * @property integer $year
 * @property integer $type_rasp_id
 * @property integer $is_empty
 */
class Weeks extends ActiveRecord
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
        return '{{%weeks}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['week', 'year', 'type_rasp_id'], 'required'],
            [['week', 'year', 'type_rasp_id', 'is_empty'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'week' => 'Week',
            'year' => 'Year',
            'type_rasp_id' => 'Type Rasp ID',
            'is_empty' => 'Is Empty',
        ];
    }
}

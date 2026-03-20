<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "colors".
 *
 * @property integer $id
 * @property string $title
 * @property integer $group_programs_id
 *
 * @property GroupPrograms $groupPrograms
 * @property Rasp[] $rasps
 */
class Colors extends ActiveRecord
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
        return '{{%colors}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_programs_id'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['group_programs_id'], 'exist', 'skipOnError' => true, 'targetClass' => GroupPrograms::className(), 'targetAttribute' => ['group_programs_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'group_programs_id' => 'Group Programs ID',
        ];
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
    public function getRasps()
    {
        return $this->hasMany(Rasp::className(), ['color_id' => 'id']);
    }
}

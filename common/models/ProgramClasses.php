<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "program_classes".
 *
 * @property integer $id
 * @property integer $status
 * @property integer $position
 * @property string $title
 * @property string $duration
 * @property string $intro
 * @property string $content
 * @property integer $group_programs_id
 * @property string $color
 * @property string $created_at
 * @property string $updated_at
 *
 * @property GroupPrograms $groupPrograms
 */
class ProgramClasses extends ActiveRecord
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
        return '{{%program_classes}}';
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
            [['status', 'position', 'group_programs_id'], 'integer'],
            [['position', 'intro', 'group_programs_id'], 'required', 'message' => '{attribute} не может быть пустым.'],
            [['content', 'intro', 'color'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['title', 'duration'], 'string', 'max' => 255],
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
            'status' => 'Публикация',
            'position' => 'Position',
            'title' => 'Заголовок занятия',
            'duration' => 'Продолжительность',
            'intro' => 'Описание',
            'content' => 'Содержание материала',
            'group_programs_id' => 'Group Programs ID',
            'color' => 'Color',
            'created_at' => 'Создано',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupPrograms()
    {
        return $this->hasOne(GroupPrograms::className(), ['id' => 'group_programs_id']);
    }

    public function fields()
    {
        if (Yii::$app->id == 'app-api') {
            return [
                'id',
                'status',
                'position',
                'title',
                'duration',
                'intro',
                'group_programs_id',
                //'color'
            ];
        }
    }
}

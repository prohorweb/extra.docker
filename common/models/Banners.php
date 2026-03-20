<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "banners".
 *
 * @property integer $id
 * @property integer $group_program_id
 * @property integer $service_id
 * @property integer $trainer_id
 * @property integer $history_id
 * @property string $img1440
 * @property string $img1200
 * @property string $img768
 *
 * @property Services $service
 * @property GroupPrograms $groupProgram
 */
class Banners extends ActiveRecord
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
        return '{{%banners}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_program_id', 'service_id'], 'integer'],
            [['img1440', 'img1200', 'img768'], 'string', 'max' => 255],
            [['service_id'], 'exist', 'skipOnError' => true, 'targetClass' => Services::className(), 'targetAttribute' => ['service_id' => 'id']],
            [['group_program_id'], 'exist', 'skipOnError' => true, 'targetClass' => GroupPrograms::className(), 'targetAttribute' => ['group_program_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'group_program_id' => 'Group Program ID',
            'service_id' => 'Service ID',
            'img1440' => 'Выберите файл',
            'img1200' => 'Выберите файл',
            'img768' => 'Выберите файл',
        ];
    }


    public function imageCrop($path)
    {
        $image = Yii::$app->image->load($path . $this->img1440);
        /** @var \yii\image\drivers\Kohana_Image $image */
        $image->resize(1904);
        $image->crop(1904, 698);
        $image->resize(null, 698);
        $image->crop(1904, 698);
        $image->save();
    }


    public function imageCropGroupPrograms($path)
    {
        $image = Yii::$app->image->load($path . $this->img1440);
        /** @var \yii\image\drivers\Kohana_Image $image */
        $image->resize(1309);
        $image->crop(1309, 495);
        $image->resize(null, 495);
        $image->crop(1309, 495);
        $image->save();
    }


    public function imageCropTrainers($path)
    {
        $image = Yii::$app->image->load($path . $this->img1440);
        /** @var \yii\image\drivers\Kohana_Image $image */
        $image->resize(645);
        $image->crop(645, 645);
        $image->resize(null, 645);
        $image->crop(645, 645);
        $image->save();
    }


    public function imageCropHistory($path)
    {
        $image = Yii::$app->image->load($path . $this->img1440);
        /** @var \yii\image\drivers\Kohana_Image $image */
        $image->resize(645);
        $image->crop(645, 460);
        $image->resize(null, 460);
        $image->crop(645, 460);
        $image->save();
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getService()
    {
        return $this->hasOne(Services::className(), ['id' => 'service_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupProgram()
    {
        return $this->hasOne(GroupPrograms::className(), ['id' => 'group_program_id']);
    }

    public function fields()
    {
        if (Yii::$app->id == 'app-api') {
            return [
                'id',
                'img1440'
            ];
        }
    }
}

<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * This is the model class for table "club_banners".
 *
 * @property integer $id
 * @property integer $status
 * @property integer $position
 * @property string $title
 * @property string $comment
 * @property string $url
 * @property string $url_category
 * @property string $url_id
 * @property integer $open_new_tab
 * @property string $title2
 * @property string $intro
 * @property string $img1440
 * @property string $img1200
 * @property string $img768
 * @property string $video
 */
class ClubBanners extends ActiveRecord
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
        return '{{%club_banners}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'position', 'open_new_tab', 'url_id'], 'integer'],
            [['position', 'title', 'url', 'title2', 'intro'], 'required', 'message' => '{attribute} не может быть пустым.'],
            [['!img1440', '!img1200', '!img768', '!video'], 'string'],
            [['first_date', 'last_date'], 'safe'],
            [['title', 'comment', 'url', 'url_category', 'title2', 'intro', 'img1440', 'img1200', 'img768'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'position' => 'Position',
            'title' => 'Заголовок в приложении',
            'comment' => 'Примечание (видно только Вам)',
            'url' => 'Ссылка',
            'url_category' => 'Категория',
            'url_id' => 'Идентификатор',
            'open_new_tab' => 'Параметры открытия',
            'title2' => 'Заголовок',
            'intro' => 'Вступительный текст',
            'img1440' => 'Выберите файл',
            'img1200' => 'Выберите файл',
            'img768' => 'Выберите файл',
            'video' => 'Выберите видео'
        ];
    }

    public function upload1440($modelFile)
    {
        /** @var UploadForm $modelFile */
        $modelFile->imageFile = UploadedFile::getInstance($this, 'img1440');
        $path = Yii::getAlias('@frontend') . '/web/uploads/image/banners/1440/';

        if (!empty($modelFile->imageFile) && $modelFile->upload($path)) {
            $this->img1440 = $modelFile->newFilename;

            $image = Yii::$app->image->load($path . $this->img1440);
            /** @var \yii\image\drivers\Kohana_Image $image */
            $image->resize(1904);
            $image->crop(1904, 1080);
            $image->save();

            return true;
        } else {
            return false;
        }
    }

    public function upload1200($modelFile)
    {
        /** @var UploadForm $modelFile */
        $modelFile->imageFile = UploadedFile::getInstance($this, 'img1200');
        $path = Yii::getAlias('@frontend') . '/web/uploads/image/banners/1200/';

        if (!empty($modelFile->imageFile) && $modelFile->upload($path)) {
            $this->img1200 = $modelFile->newFilename;

            $image = Yii::$app->image->load($path . $this->img1200);
            /** @var \yii\image\drivers\Kohana_Image $image */
            $image->resize(1904);
            $image->crop(1904, 698);
            $image->save();

            return true;
        } else {
            return false;
        }
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->first_date = date('Y-m-d', strtotime($this->first_date));
            $this->last_date = date('Y-m-d', strtotime($this->last_date));
            return true;
        }
        return false;
    }

    public function fields()
    {
        if (Yii::$app->id == 'app-api') {
            return [
                'id',
                'status',
                'position',
                'title',
                'comment',
                'url',
                'url_category' => function ($model) {
                    return empty($model->url_category) ? null : $model->url_category;
                },
                'url_id',
                'open_new_tab',
                'title2',
                'intro',
                'img1440',
                'img1200',
            ];
        }
    }
}

<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * This is the model class for table "main_banners".
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
 * @property string $img
 * @property integer $for_club
 */
class MainBanners extends ActiveRecord
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
        return '{{%main_banners}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'position', 'open_new_tab', 'for_club', 'url_id'], 'integer'],
            [['position', 'title'], 'required', 'message' => '{attribute} не может быть пустым.'],
            [['!img'], 'string'],
            [['title', 'comment', 'url', 'url_category', 'img'], 'string', 'max' => 255],
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
            'title' => 'Заголовок материала (видно только Вам)',
            'comment' => 'Примечание (видно только Вам)',
            'url' => 'Ссылка',
            'url_category' => 'Категория',
            'url_id' => 'Идентификатор',
            'open_new_tab' => 'Параметры открытия',
            'img' => 'Выберите файл',
        ];
    }

    public function upload($modelFile)
    {
        /** @var UploadImage $modelFile */
        $modelFile->imageFile = UploadedFile::getInstance($this, 'img');
        $path = Yii::getAlias('@frontend') . '/web/uploads/image/banners/';

        if (!empty($modelFile->imageFile) && $modelFile->upload($path)) {
            $this->img = $modelFile->newFilename;

            $image = Yii::$app->image->load($path . $this->img);
            /** @var \yii\image\drivers\Kohana_Image $image */
            $image->resize(1904);
            $image->crop(1904, 1080);
            $image->save();

            return true;
        } else {
            return false;
        }
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
                'img',
                'for_club'
            ];
        }
    }

}

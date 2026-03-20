<?php

namespace common\models;

use himiklab\sitemap\behaviors\SitemapBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\BaseInflector;
use yii\helpers\Url;

/**
 * This is the model class for table "group_programs".
 *
 * @property integer $id
 * @property integer $status
 * @property integer $position
 * @property string $title
 * @property string $intro
 * @property string $content
 * @property string $img
 * @property string $alias
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $created_at
 * @property string $updated_at
 */
class GroupPrograms extends ActiveRecord
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
        return '{{%group_programs}}';
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
            'sitemap' => [
                'class' => SitemapBehavior::className(),
                'scope' => function ($model) {
                    /** @var \yii\db\ActiveQuery $model */
                    $model->select(['alias', 'updated_at']);
                    $model->andWhere(['status' => 10]);
                },
                'dataClosure' => function ($model) {
                    /** @var self $model */
                    return [
                        'loc' => Url::to('/services/programs/'. $model->alias . '/', 'https'),
                        'lastmod' => strtotime($model->updated_at),
                        'changefreq' => SitemapBehavior::CHANGEFREQ_DAILY,
                        'priority' => 0.6
                    ];
                }
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'position'], 'integer'],
            [['position', 'title', 'intro'], 'required', 'message' => '{attribute} не может быть пустым.'],
            [['!img', 'intro', 'content'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['title', 'img', 'alias', 'meta_title', 'meta_keywords', 'meta_description'], 'string', 'max' => 255],
            ['alias', 'match', 'pattern' => '/^[a-z0-9_-]+$/', 'message' => 'Псевдоним может содержать только буквенно-цифровые символы, символы подчеркивания и тире'],
            [['title', 'alias'], 'unique'],
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
            'title' => 'Заголовок материала',
            'intro' => 'Вступительный текст',
            'content' => 'Содержание материала',
            'img' => 'Выберите файл',
            'alias' => 'Алиас (url страницы)',
            'meta_title' => 'Meta Title',
            'meta_keywords' => 'Meta Keywords',
            'meta_description' => 'Meta Description',
            'created_at' => 'Создано',
            'updated_at' => 'Updated At',
        ];
    }


    public function imageCrop($path)
    {
        $image = Yii::$app->image->load($path . $this->img);
        /** @var \yii\image\drivers\Kohana_Image $image */
        $image->resize(646);
        $image->crop(646, 400);
        $image->resize(null, 400);
        $image->crop(646, 400);
        $image->save();
    }


    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (empty($this->alias)) {
                $this->alias = BaseInflector::slug($this->title);
            }
            if (empty($this->meta_title)) {
                $this->meta_title = $this->title;
            }

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
                'img',
                //'alias',
                //'meta_title',
                //'meta_keywords',
                //'meta_description'
            ];
        }
    }
}

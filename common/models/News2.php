<?php

namespace common\models;

use himiklab\sitemap\behaviors\SitemapBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\BaseInflector;
use yii\helpers\Url;

/**
 * This is the model class for table "news2".
 *
 * @property int $id
 * @property int $status
 * @property int $position
 * @property string $title
 * @property string $date
 * @property string $intro
 * @property string $img
 * @property string $content
 * @property string $alias
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $created_at
 * @property string $updated_at
 */
class News2 extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'news2';
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
                        'loc' => Url::to('/news/'. $model->alias . '/', 'https'),
                        'lastmod' => strtotime($model->updated_at),
                        'changefreq' => SitemapBehavior::CHANGEFREQ_DAILY,
                        'priority' => 0.6
                    ];
                }
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'position'], 'integer'],
            [['position', 'title', 'date', 'intro', 'content'], 'required'],
            [['date', 'created_at', 'updated_at'], 'safe'],
            [['content'], 'string'],
            [['title', 'intro', 'img', 'alias', 'meta_title', 'meta_keywords', 'meta_description'], 'string', 'max' => 255],
            [['alias'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Публикация',
            'position' => 'Position',
            'title' => 'Заголовок материала',
            'date' => 'Дата для материала',
            'intro' => 'Вступительный текст',
            'img' => 'Img',
            'content' => 'Содержание материала',
            'alias' => 'Алиас (url страницы)',
            'meta_title' => 'Meta Title',
            'meta_keywords' => 'Meta Keywords',
            'meta_description' => 'Meta Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->date = date('Y-m-d', strtotime($this->date));

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
}

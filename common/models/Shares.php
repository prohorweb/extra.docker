<?php

namespace common\models;

use himiklab\sitemap\behaviors\SitemapBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\BaseInflector;
use Eddieace\PhpSimple\HtmlDomParser;
use yii\helpers\Url;

/**
 * This is the model class for table "shares".
 *
 * @property integer $id
 * @property integer $status
 * @property integer $position
 * @property int $only_url
 * @property string $title
 * @property string $title2
 * @property string $comment
 * @property string $intro
 * @property string $img
 * @property string $content
 * @property int $price
 * @property string $alias
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $created_at
 * @property string $updated_at
 */
class Shares extends ActiveRecord
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
        return '{{%shares}}';
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
                        'loc' => Url::to('/card/shares/'. $model->alias . '/', 'https'),
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
            [['status', 'position', 'only_url', 'price'], 'integer'],
            [['position', 'title', 'title2',  'intro', 'content'], 'required', 'message' => '{attribute} не может быть пустым.'],
            [['content', '!img', 'intro'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['title', 'title2', 'comment', 'img', 'alias', 'meta_title', 'meta_keywords', 'meta_description'], 'string', 'max' => 255],
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
            'title2' => 'Дата',
            'comment' => 'Примечание (видно только Вам)',
            'intro' => 'Вступительный текст',
            'img' => 'Выберите файл',
            'content' => 'Содержание материала',
            'price' => 'Цена',
            'alias' => 'Алиас (url страницы)',
            'only_url' => 'Only Url',
            'meta_title' => 'Meta Title',
            'meta_keywords' => 'Meta Keywords',
            'meta_description' => 'Meta Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


    public function imageCrop($path)
    {
        $image = Yii::$app->image->load($path . $this->img);
        /** @var \yii\image\drivers\Kohana_Image $image */
        $image->resize(876);
        $image->crop(876, 680);
        $image->save();
    }

    public function getNameClub()
    {
        if (isset(Yii::$app->request->cookies->get('bd')->value)) {
            switch (Yii::$app->request->cookies->get('bd')->value) {
                case '1':
                    return "piter";
                case '2':
                    return "iyun";
                case '3':
                    return "polyus";
                case '4':
                    return "matros";
            }
        }

        return "piter";
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
                'title2',
                'intro',
                'img',
                'content' => function ($model) {
                    $array = [];
                    $paragraphsArray = [];
                    $imagesArray = [];
                    $dom = HtmlDomParser::str_get_html(html_entity_decode($model->content));
                    foreach ($dom->find('p') as $key => $element) {
                        $paragraphsArray[$element->tag_start] = ['paragraphId' => $key + 1, 'paragraphText' => $element->outertext];
                        foreach ($element->find('img') as $key2 => $img) {
                            $imagesArray[$element->tag_start] = ['paragraphId' => $key + 1, 'imageLink' => $img->src];
                        }
                    }
                    foreach ($dom->find('ul') as $key => $element) {
                        $paragraphsArray[$element->tag_start] = ['paragraphId' => $key + 1, 'paragraphText' => $element->outertext];
                        foreach ($element->find('img') as $key2 => $img) {
                            $imagesArray[$element->tag_start] = ['paragraphId' => $key + 1, 'imageLink' => $img->src];
                        }
                    }
                    ksort($paragraphsArray);
                    ksort($imagesArray);
                    $array['paragraphsArray'] = array_values($paragraphsArray);
                    $array['imagesArray']  = array_values($imagesArray);
                    //$array['original'] = $model->content;
                    return $array;
                },
                //'alias',
                //'meta_title',
                //'meta_keywords',
                //'meta_description'
            ];
        }
    }
}

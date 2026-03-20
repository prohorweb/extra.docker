<?php

namespace common\models;

use himiklab\sitemap\behaviors\SitemapBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\BaseInflector;
use Eddieace\PhpSimple\HtmlDomParser;
use yii\helpers\Url;
use yii\web\UrlManager;

/**
 * This is the model class for table "events".
 *
 * @property integer $id
 * @property integer $status
 * @property string $title
 * @property string $date
 * @property integer $is_pay
 * @property integer $is_open
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
class Events extends ActiveRecord
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
        return '{{%events}}';
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
                        'loc' => Url::to('/es/events/'. $model->alias . '/', 'https'),
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
            [['status', 'is_pay', 'is_open'], 'integer'],
            [[ 'title', 'date', 'intro', 'content'], 'required', 'message' => '{attribute} не может быть пустым.'],
            [['date', 'created_at', 'updated_at'], 'safe'],
            [['content', '!img'], 'string'],
            [['title', 'img', 'alias', 'meta_title', 'meta_keywords', 'meta_description'], 'string', 'max' => 255],
            ['intro', 'string', 'max' => 520],
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
            'title' => 'Заголовок мероприятия',
            'date' => 'Date',
            'is_pay' => 'Is Pay',
            'is_open' => 'Is Open',
            'intro' => 'Вступительный текст',
            'img' => 'Выберите файл',
            'content' => 'Содержание материала',
            'alias' => 'Алиас (url страницы)',
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
        $image->resize(564);
        $image->crop(564, 484);
        $image->resize(null, 484);
        $image->crop(564, 484);
        $image->save();
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

    public function fields()
    {
        if (Yii::$app->id == 'app-api') {
            return [
                'id',
                'status',
                'title',
                'date',
                'is_pay',
                'is_open',
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
                'url_twitter' => function ($model) {
                    $text = $model->title . '\r\n\r\n' . str_replace("\r\n", '\'+\'\r\n', $model->intro . '\n\n');
                    $url = Url::to(Yii::$app->urlManager->getHostInfo() . '/wg/events/' . $model->alias . '/');
                    return 'http://twitter.com/share?text=' . str_replace(" ", "+", $text) . '&url=' . $url . '&counturl=' . $url;
                },
                'url_facebook' => function ($model) {
                    $text = str_replace("\r\n", '\'+\'\r\n', $model->intro);
                    $url = Url::to(Yii::$app->urlManager->getHostInfo() . '/wg/events/' . $model->alias . '/');
                    return 'http://www.facebook.com/sharer.php?title=' . str_replace(" ", "+", $model->title) . '&description=' . str_replace(" ", "+", $text) . '&u=' . $url;
                },
                'url_vk' => function ($model) {
                    $text = str_replace("\r\n", '\'+\'\r\n', $model->intro);
                    $url = Url::to(Yii::$app->urlManager->getHostInfo() . '/wg/events/' . $model->alias . '/');
                    return 'http://vkontakte.ru/share.php?url=' . $url . '&title=' . str_replace(" ", "+", $model->title) . '&description=' . str_replace(" ", "+", $text) . '&u=' . $url . '&noparse=true';
                }
                //'alias',
                //'meta_title',
                //'meta_keywords',
                //'meta_description'
            ];
        }
    }
}

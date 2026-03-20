<?php

namespace common\models;

use Yii;
use Eddieace\PhpSimple\HtmlDomParser;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\BaseInflector;

/**
 * This is the model class for table "partners".
 *
 * @property int $id
 * @property int $status
 * @property int $position
 * @property string $title
 * @property string $content
 * @property string $img
 * @property string $discount
 * @property int $is_gift
 * @property string $address
 * @property string $coordinates
 * @property string $site
 * @property string $tel
 * @property string $alias
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $created_at
 * @property string $updated_at
 *
 * @property MetroPartners[] $metroPartners
 */
class Partners extends ActiveRecord
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
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%partners}}';
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
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'content', 'address'], 'required'],
            [['content', '!img'], 'string'],
            [['status', 'position', 'is_gift'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title', 'img', 'discount', 'address', 'coordinates', 'site', 'tel', 'alias', 'meta_title', 'meta_keywords', 'meta_description'], 'string', 'max' => 255],
            ['alias', 'match', 'pattern' => '/^[a-z0-9_-]+$/', 'message' => 'Псевдоним может содержать только буквенно-цифровые символы, символы подчеркивания и тире'],
            //['title', 'match', 'pattern' => '/[^A-Za-zА-Яа-я0-9]+/', 'message' => 'Название может содержать только буквенно-цифровые символы'],
            [['title', 'alias'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'status' => 'Публикация',
            'position' => 'Position',
            'content' => 'Содержание материала',
            'img' => 'Выберите файл',
            'discount' => 'Размер скидки',
            'is_gift' => 'Is Gift',
            'address' => 'Адрес',
            'coordinates' => 'Coordinates',
            'site' => 'Сайт партнёра',
            'tel' => 'Телефон',
            'alias' => 'Alias',
            'meta_title' => 'Meta Title',
            'meta_keywords' => 'Meta Keywords',
            'meta_description' => 'Meta Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMetroPartners()
    {
        return $this->hasMany(MetroPartners::className(), ['partners_id' => 'id'])->orderBy('position');
    }


    public function imageCrop($path)
    {
        $image = Yii::$app->image->load($path . $this->img);
        /** @var \yii\image\drivers\Kohana_Image $image */
        $image->resize(330);
        $image->crop(330, 240);
        $image->resize(null, 240);
        $image->crop(330, 240);
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
                "id",
                "status",
                "position",
                "title",
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
                "img",
                "discount",
                "is_gift",
                "address",
                "coordinates",
                "site",
                "tel",
                "metro" => function($model){
                    return $model->metroPartners;
                },
                "alias",
                "meta_title",
                "meta_keywords",
                "meta_description",
                "created_at",
                "updated_at",
            ];
        }
    }
}

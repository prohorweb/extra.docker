<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use Eddieace\PhpSimple\HtmlDomParser;

/**
 * This is the model class for table "club_cards".
 *
 * @property integer $id
 * @property integer $status
 * @property integer $position
 * @property string $title
 * @property string $comment
 * @property string $icon
 * @property string $img
 * @property string $content
 * @property string $color
 * @property string $code_button
 * @property int $price
 * @property string $created_at
 * @property string $updated_at
 */
class ClubCards extends ActiveRecord
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
        return '{{%club_cards}}';
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
            [['status', 'position', 'price'], 'integer'],
            [['position', 'title'], 'required', 'message' => '{attribute} не может быть пустым.'],
            [['content', 'color', '!icon', '!img'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['title', 'comment', 'img', 'code_button'], 'string', 'max' => 255],
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
            'comment' => 'Примечание',
            'icon' => 'Выберите файл',
            'img' => 'Выберите файл',
            'content' => 'Содержание материала',
            'color' => 'Цвет рамки и подложки',
            'code_button' => 'Code Button',
            'price' => 'Цена',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function imageCrop($path)
    {
        $image = Yii::$app->image->load($path . $this->img);
        /** @var \yii\image\drivers\Kohana_Image $image */
        $image->resize(1752);
        $image->crop(1752, 390);
        $image->resize(null, 390);
        $image->crop(1752, 390);
        $image->save();
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
                'img',
                'content' => function ($model) {
                    $array = [];
                    $paragraphsArray = [];
                    $dom = HtmlDomParser::str_get_html(html_entity_decode($model->content));
                    if($dom) {
                        $paragraphsArray[] = ['paragraphText' => $dom->save()];
                    }
                    $array['paragraphsArray'] = (object)$paragraphsArray;
                    //$array['original'] = $model->content;
                    return $array;
                },
                'color' => function ($model) {
                    return "";
                }
            ];
        }
    }
}

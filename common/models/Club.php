<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "club".
 *
 * @property integer $id
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $tel
 * @property string $address
 * @property string $url_appstore
 * @property string $url_googleplay
 * @property string $url_windowsmarket
 * @property string $url_vk
 * @property string $url_facebook
 * @property string $url_instagram
 * @property string $url_ok
 * @property string $pdf
 * @property string $pdf2
 * @property string $pdf3
 * @property string $email
 * @property string $start_work
 * @property string $start_work_weekend
 * @property string $url_3d_tour
 * @property string $start_year
 * @property integer $square
 * @property string $img
 * @property string $img2
 * @property string $title
 * @property string $content
 * @property string $main_content
 * @property string $club_content
 * @property string $content_cards
 * @property string $coordinates
 * @property string $legal
 * @property string $privacy
 * @property string $legal_title
 * @property string $privacy_title
 * @property string $contacts_title
 */
class Club extends ActiveRecord
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
        return '{{%club}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tel', 'address', 'content'], 'required', 'message' => '{attribute} не может быть пустым.'],
            [['start_work', 'start_work_weekend', 'start_year'], 'safe'],
            [['square'], 'integer'],
            [['coordinates', 'content', 'main_content', 'club_content', '!img', '!img2', '!pdf', '!pdf2', '!pdf3', 'content_cards', 'legal', 'privacy'], 'string'],
            [['meta_title', 'meta_keywords', 'meta_description', 'tel', 'address', 'url_appstore', 'url_googleplay', 'url_windowsmarket', 'url_vk', 'url_facebook', 'url_instagram', 'url_ok', 'email', 'url_3d_tour', 'title', 'legal_title', 'privacy_title', 'contacts_title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'meta_title' => 'Meta Title',
            'meta_keywords' => 'Meta Keywords',
            'meta_description' => 'Meta Description',
            'tel' => 'Телефон',
            'address' => 'Адрес',
            'url_appstore' => 'Ссылка на приложение в AppStore',
            'url_googleplay' => 'Ссылка на приложение в GooglePlay',
            'url_windowsmarket' => 'Ссылка на приложение в WindowsMarket',
            'url_vk' => 'Ссылка ВКонтакте',
            'url_facebook' => 'Ссылка Facebook',
            'url_instagram' => 'Ссылка Инстаграм',
            'url_ok' => 'Ссылка Youtube',
            'pdf' => 'Выберите файл',
            'pdf2' => 'Выберите файл',
            'pdf3' => 'Выберите файл',
            'email' => 'Контактный email',
            'start_work' => 'Время работы в будни (ПН – ПТ)',
            'start_work_weekend' => 'Время работы в выходные дни (СБ – ВС)',
            'url_3d_tour' => 'Ссылка на 3D тур',
            'start_year' => 'Год открытия клуба',
            'square' => 'Площадь клуба',
            'img' => 'Выберите файл',
            'img2' => 'Выберите файл',
            'title' => 'Заголовок',
            'content' => 'Содержание',
            'main_content' => 'Содержание на главной',
            'club_content' => 'Содержание на странице "О Клубе" над формой заявки',
            'content_cards' => 'Содержание',
            'coordinates' => 'GPS координаты клуба',
            'legal' => 'Правовая информация',
            'privacy' => 'Политика конфиденциальности',
            'legal_title' => 'Title - Правовая информация',
            'privacy_title' => 'Title - Политика конфиденциальности',
            'contacts_title' => 'Title - Контакты',
        ];
    }


    public function imageCrop($path)
    {
        $image = Yii::$app->image->load($path . $this->img);
        /** @var \yii\image\drivers\Kohana_Image $image */
        $image->resize(721);
        $image->crop(721, 496);
        $image->save();
    }

    public function imageCrop2($path)
    {
        $image = Yii::$app->image->load($path . $this->img2);
        /** @var \yii\image\drivers\Kohana_Image $image */
        $image->resize(459);
        $image->crop(459, 303);
        $image->save();
    }


    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->start_year = date('Y-m-d', strtotime($this->start_year. '-01-01'));
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
                'tel',
                'address',
                'pdf',
                'email',
                'start_work',
                'start_work_weekend',
                'title',
                'coordinates',
                'main_banners' => function ($model) {
                    return ClubBanners::find()->where(['status' => 10])->orderBy(['position' => SORT_ASC])->all();
                },
                'club_banners' => function ($model) {
                    return MainBanners::find()->where(['status' => 10])->andWhere('for_club = 0')->orderBy(['position' => SORT_ASC])->all();
                },
                'clubs' => function ($model) {
                    return [
                        [
                            'clubName' => 'EXTRASPORT ТК «Питер», с бассейном',
                            'address' => 'Санкт-Петербург, ул. Типанова, д. 21',
                            'clubToken' => 'bd9615e2871c56dddd8b88b576f131f51c20f3bc'
                        ],
                        [
                            'clubName' => 'EXTRASPORT ТРЦ «Июнь»',
                            'address' => 'Санкт-Петербург, Индустриальный пр., д. 24',
                            'clubToken' => 'hpeyyMDtOmXzWAAn9mBtCf2g6Ne7FCYE'
                        ],
                        [
                            'clubName' => 'EXTRASPORT ТРК «Южный полюс»',
                            'address' => 'Санкт-Петербург, ул. Пражская, 48/50',
                            'clubToken' => 'JKZU0F7SB5pmdEUSW8HuLV2xqTdLqb0r'
                        ],
                        [
                            'clubName' => 'EXTRASPORT Матроса Железняка',
                            'address' => 'Санкт-Петербург, ул. Матроса Железняка, 57А',
                            'clubToken' => 'REAYW15Ob7dWytT66uWwIsPLCpIOkBv3'
                        ]

                    ];
                },
                'about' => function ($model) {
                    $settings = Settings::findOne(1);
                    return [
                        'text' => $settings->about,
                        'logo1' => $settings->logo1,
                        'logo2' => $settings->logo2
                    ];
                },
            ];
        }
    }
}

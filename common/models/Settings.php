<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "settings".
 *
 * @property integer $id
 * @property integer $status
 * @property string $key
 * @property string $email_from
 * @property string $email_form_guest
 * @property string $code_form_guest
 * @property string $email_form_visit
 * @property string $code_form_visit
 * @property string $email_request_training
 * @property string $code_request_training
 * @property string $email_request_freezing
 * @property string $code_request_freezing
 * @property string $email_club_card
 * @property string $code_club_card
 * @property string $email_timer
 * @property string $form_title
 * @property string $form_text
 * @property string $email_feedback
 * @property string $email_buy
 * @property string $yandex_metrica
 * @property string $file_yandex
 * @property string $google_analytics
 * @property string $file_google
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $code_head
 * @property string $code_body
 * @property string $about
 * @property string $logo1
 * @property string $logo2
 * @property string $wa
 * @property string $vk
 * @property string $tg
 * @property string $timer_title
 * @property string $timer_intro
 * @property string $timer_start
 * @property string $timer_end
 * @property string $timer_last
 * @property integer $timer
 * @property string $bonus_percent
 * @property string $bonus_time
 * @property string $email_bonus
 * @property integer $bonus
 * @property string $bonus_start
 * @property string $bonus_end
 */
class Settings extends ActiveRecord
{
    private $_decrypt = '';

    private $pk = '-----BEGIN RSA PRIVATE KEY-----
MIICXQIBAAKBgQDBNHK7R2CCYGqljipbPoj3Pwyz4cF4bL5rsm1t8S30gbEbMnKn
1gpzteoPlKp7qp0TnsgKab13Fo1d+Yy8u3m7JUd/sBrUa9knY6dpreZ9VTNul8Bs
p2LNnAXOIA5xwT10PU4uoWOo1v/wn8eMeBS7QsDFOzIm+dptHYorB3DOUQIDAQAB
AoGBAKgwGyxy702v10b1omO55YuupEU3Yq+NopqoQeCyUnoGKIHvgaYfiwu9sdsM
ZPiwxnqc/7Eo6Zlw1XGYWu61GTrOC8MqJKswJvzZ0LrO3oEb8IYRaPxvuRn3rrUz
K7WnPJyQ2FPL+/D81NK6SH1eHZjemb1jV9d8uGb7ifvha5j9AkEA+4/dZV+dZebL
dRKtyHLfbXaUhJcNmM+04hqN1DUhdLAfnFthoiSDw3i1EFixvPSiBfwuWC6h9mtL
CeKgySaOkwJBAMSdBhn3C8NHhsJA8ihQbsPa6DyeZN+oitiU33HfuggO3SVIBN/7
HmnuLibqdxpnDOtJT+9A+1D29TkNENlTWgsCQGjVIC8xtFcV4e2s1gz1ihSE2QmU
JU9sJ3YeGMK5TXLiPpobHsnCK8LW16WzQIZ879RMrkeDT21wcvnwno6U6c8CQQCl
dsiVvXUmyOE+Rc4F43r0VRwxN9QI7hy7nL5XZUN4WJoAMBX6Maos2Af7NEM78xHK
SY59+aAHSW6irr5JR351AkBA+o7OZzHIhvJfaZLUSwTPsRhkdE9mx44rEjXoJsaT
e8DYZKr84Cbm+OSmlApt/4d6M4YA581Os1eC8kopewpy
-----END RSA PRIVATE KEY-----';

    public $date_timer;
    public $bonus_timer;

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
        return '{{%settings}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'timer', 'bonus'], 'integer'],
            [['yandex_metrica', 'google_analytics', 'meta_keywords', 'meta_description', 'code_head', 'code_body', 'code_form_guest', 'code_request_training', 'form_text', 'about'], 'string'],
            [['meta_title'], 'string', 'max' => 125],
            [['timer_start', 'timer_end', 'date_timer', 'bonus_timer', 'bonus_time', 'bonus_start', 'bonus_end'], 'safe'],
            [['key', '!file_yandex', '!file_google', 'email_from', 'email_form_guest', 'email_form_visit', 'code_form_visit', 'email_request_training', 'email_request_freezing', 'code_request_freezing', 'email_feedback', 'email_buy', 'email_club_card', 'code_club_card', 'email_timer', 'form_title', '!logo1', '!logo2', 'wa', 'vk', 'tg', 'timer_title', 'timer_intro', 'bonus_percent', 'email_bonus'], 'string', 'max' => 255],
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
            'key' => 'Key',
            'email_from' => 'Email From',
            'email_form_guest' => 'Email Form Guest',
            'code_form_guest' => 'Code Form Guest',
            'email_form_visit' => 'Email Form Visit',
            'code_form_visit' => 'Code Form Visit',
            'email_request_training' => 'Email Request Training',
            'code_request_training' => 'Code Request Training',
            'email_request_freezing' => 'Email Request Freezing',
            'code_request_freezing' => 'Code Request Freezing',
            'email_club_card' => 'Email Club Card',
            'code_club_card' => 'Code Club Card',
            'email_timer' => 'Email Timer',
            'form_title' => 'Заголовок формы',
            'form_text' => 'Заголовок текста',
            'email_feedback' => 'Email Feedback',
            'email_buy' => 'Email Buy',
            'yandex_metrica' => 'Yandex Metrica',
            'file_yandex' => 'Выберите файл',
            'google_analytics' => 'Google Analytics',
            'file_google' => 'Выберите файл',
            'meta_title' => 'Meta Title',
            'meta_keywords' => 'Meta Keywords',
            'meta_description' => 'Meta Description',
            'code_head' => 'Code Head',
            'code_body' => 'Code Body',
            'about' => 'About',
            'logo1' => 'Выберите файл',
            'logo2' => 'Выберите файл',
            'timer' => 'Таймер',
            'timer_title' => 'Заголовок',
            'timer_intro' => 'Описание',
            'timer_start' => 'Начало',
            'timer_end' => 'Окончание',
            'timer_last' => 'Timer Last',
            'bonus_percent' => 'Процент',
            'bonus_time' => 'Время',
            'email_bonus' => 'Email Bonus',
            'bonus' => 'Bonus',
            'bonus_start' => 'Начало',
            'bonus_end' => 'Окончание',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            $this->timer_start = date('Y-m-d H:i', strtotime($this->date_timer . ' ' . $this->timer_start));
            $this->timer_end = date('Y-m-d H:i', strtotime($this->date_timer . ' ' . $this->timer_end));
            $this->bonus_start = date('Y-m-d H:i', strtotime($this->bonus_timer . ' ' . $this->bonus_start));
            $this->bonus_end = date('Y-m-d H:i', strtotime($this->bonus_timer . ' ' . $this->bonus_end));
            return true;
        }
        return false;
    }

    public function afterSave($insert, $changedAttributes)
    {
        $this->date_timer = date('Y-m-d', strtotime($this->timer_start));
        $this->timer_start = date('H:i', strtotime($this->timer_start));
        $this->timer_end = date('H:i', strtotime($this->timer_end));
        $this->bonus_timer = date('Y-m-d', strtotime($this->bonus_start));
        $this->bonus_start = date('H:i', strtotime($this->bonus_start));
        $this->bonus_end = date('H:i', strtotime($this->bonus_end));
        parent::afterSave($insert, $changedAttributes);
    }

    public function afterFind()
    {
        $this->date_timer = date('Y-m-d', strtotime($this->timer_start));
        $this->timer_start = date('H:i', strtotime($this->timer_start));
        $this->timer_end = date('H:i', strtotime($this->timer_end));
        $this->bonus_timer = date('Y-m-d', strtotime($this->bonus_start));
        $this->bonus_start = date('H:i', strtotime($this->bonus_start));
        $this->bonus_end = date('H:i', strtotime($this->bonus_end));
        parent::afterFind();
    }


    private function decrypt()
    {
        $key = openssl_pkey_get_private($this->pk);

        $this->_decrypt = openssl_private_decrypt(base64_decode($this->key), $decrypted, $key) ? $decrypted : '';
    }


    public function getValidUntilKey()
    {
        if (!$this->_decrypt) {
            $this->decrypt();
        }

        return substr($this->_decrypt, strpos($this->_decrypt, '|') + 1);
    }


    public function getSiteUrlKey()
    {
        if (!$this->_decrypt) {
            $this->decrypt();
        }

        return substr($this->_decrypt, 0, strpos($this->_decrypt, '|'));
    }
}

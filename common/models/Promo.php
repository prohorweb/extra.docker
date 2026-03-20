<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "promo".
 *
 * @property int $id
 * @property string $phone
 * @property string $img
 * @property string $text
 * @property string $text2
 * @property string $price1_piter
 * @property string $price3_piter
 * @property string $price6_piter
 * @property string $price12_piter
 * @property string $price1_rodeo
 * @property string $price3_rodeo
 * @property string $price6_rodeo
 * @property string $price12_rodeo
 * @property string $price1_june
 * @property string $price3_june
 * @property string $price6_june
 * @property string $price12_june
 * @property string $price1_polis
 * @property string $price3_polis
 * @property string $price6_polis
 * @property string $price12_polis
 * @property string $price1_matros
 * @property string $price3_matros
 * @property string $price6_matros
 * @property string $price12_matros
 * @property string $icon1
 * @property string $icon3
 * @property string $icon6
 * @property string $icon12
 */
class Promo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'promo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['text', 'text2'], 'string'],
            [['phone', '!img', 'price1_piter', 'price3_piter', 'price6_piter', 'price12_piter', 'price1_rodeo', 'price3_rodeo', 'price6_rodeo', 'price12_rodeo', 'price1_june', 'price3_june', 'price6_june', 'price12_june', 'price1_polis', 'price3_polis', 'price6_polis', 'price12_polis', 'price1_matros', 'price3_matros', 'price6_matros', 'price12_matros', '!icon1', '!icon3', '!icon6', '!icon12'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone' => 'Phone',
            'img' => 'Выберите файл',
            'text' => 'Текст над ценами',
            'text2' => 'Текст под ценами',
            'price1_piter' => '1 мес',
            'price3_piter' => '3 мес',
            'price6_piter' => '6 мес',
            'price12_piter' => '12 мес',
            'price1_rodeo' => '1 мес',
            'price3_rodeo' => '3 мес',
            'price6_rodeo' => '6 мес',
            'price12_rodeo' => '12 мес',
            'price1_june' => '1 мес',
            'price3_june' => '3 мес',
            'price6_june' => '6 мес',
            'price12_june' => '12 мес',
            'price1_polis' => '1 мес',
            'price3_polis' => '3 мес',
            'price6_polis' => '6 мес',
            'price12_polis' => '12 мес',
            'price1_matros' => '1 мес',
            'price3_matros' => '3 мес',
            'price6_matros' => '6 мес',
            'price12_matros' => '12 мес',
            'icon1' => 'Выберите файл',
            'icon3' => 'Выберите файл',
            'icon6' => 'Выберите файл',
            'icon12' => 'Выберите файл',
        ];
    }

    public function imageCrop($path)
    {
        $image = Yii::$app->image->load($path . $this->img);
        /** @var \yii\image\drivers\Kohana_Image $image */
        $image->resize(1904);
        $image->crop(1904, 643);
        $image->resize(null, 643);
        $image->crop(1904, 643);
        $image->save();
    }
}

<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Issue
 */
class Issue extends Model
{
    public $title;
    public $text;
    public $img;
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'text', 'email'], 'required'],
            ['email', 'trim'],
            ['email', 'email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'title' => 'Заголовок ошибки',
            'text' => 'Подробное описание ошибки',
            'img' => 'Выберите файл',
            'email' => 'e-mail',
        ];
    }

}
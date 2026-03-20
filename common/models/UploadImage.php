<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\helpers\Inflector;
use yii\web\UploadedFile;

class UploadImage extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;
    public $newFilename;

    public $minWidth;
    public $minHeight;

    /**
     * UploadImage constructor.
     * @param int $minWidth
     * @param int $minHeight
     * @param array $config
     */
    public function __construct($minWidth, $minHeight, $config = [])
    {
        $this->minWidth = $minWidth;
        $this->minHeight = $minHeight;

        parent::__construct($config);
    }

    public function rules()
    {
        return [
            //[['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
            ['imageFile', 'image', 'skipOnEmpty' => false, 'extensions' => 'jpg, png',
                'minWidth' => $this->minWidth, 'minHeight' => $this->minHeight,
                'underWidth' => 'Загружаемое изображение не соответствует требованиям.',
                'underHeight' => 'Загружаемое изображение не соответствует требованиям.'],
        ];
    }

    public function upload($path)
    {
        if ($this->validate()) {
            FileHelper::createDirectory($path);
            $this->newFilename = str_replace(' ', '-', Inflector::transliterate($this->imageFile->baseName)) . '-' . time() . '.' . $this->imageFile->extension;
            $this->imageFile->saveAs($path . $this->newFilename);

            return true;
        } else {
            return false;
        }
    }
}
<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\helpers\Inflector;
use yii\web\UploadedFile;

class UploadFile extends Model
{
    /**
     * @var UploadedFile
     */
    public $file;
    public $newFilename;

    public function rules()
    {
        return [
            ['file', 'file', 'skipOnEmpty' => false, 'maxSize' => 5 * 1024 * 1024],
        ];
    }

    public function upload($path)
    {
        if ($this->validate()) {
            FileHelper::createDirectory($path);
            if (Yii::$app->controller->id == 'settings') {
                $this->newFilename = $this->file->baseName . '.' . $this->file->extension;
            } else {
                $this->newFilename = str_replace(' ', '-', Inflector::transliterate($this->file->baseName)) . '-' . time() . '.' . $this->file->extension;
            }
            $this->file->saveAs($path . $this->newFilename);
            return true;
        } else {
            return false;
        }
    }
}
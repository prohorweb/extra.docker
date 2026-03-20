<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/template.css',
    ];
    public $js = [
        'js/pickmeup.js',
        'js/main.js',
        'js/jquery.limit.js'
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
}

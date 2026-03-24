<?php

namespace frontend\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/fonts/Oswald.css',
        'css/fonts/Roboto.css',
        'css/styles.css',
        // 'css/clubs.css',
        'css/utilites.css',
        'css/present-video.css',
        'css/sections.css',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css'
    ];
    public $js = [

        '/js/nav.js',
        'js/main.js',
        'js/parallax.js',
        'js/present-video.js',
        'js/minified.js',
        'js/cookie.min.js',
        'js/flipclock.min.js',
        'js/cookieconsent.min.js',
        'js/bootstrap.bundle.min.js',
        // 'js/jquery.carouFredSel-6.2.1-packed.js',
        //'js/jquery.mousewheel.min.js',
        // 'js/jquery.touchSwipe.min.js',
    ];
    public $jsOptions = [
        //'async' => 'async',
        //'position' =>  \yii\web\View::POS_HEAD
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}

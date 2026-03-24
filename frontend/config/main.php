<?php

use yii\helpers\Url;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

$url = 'http://' . $_SERVER["SERVER_NAME"];
$sitemap = '';
$club = explode('.', $_SERVER["SERVER_NAME"])[0];
if ($club == 'extra') {
    $sitemap = [
        'class' => 'himiklab\sitemap\Sitemap',
        'models' => [
            // your models
            'common\models\News2'
        ],
        'urls' => [
            // your additional urls
            [
                'loc' => $url . '/',
                'lastmod' => time(),
                'priority' => 1.0,
            ],
            [
                'loc' => $url . '/news/',
                'lastmod' => time(),
                'priority' => 0.8,
            ],
        ],
        'enableGzip' => true, // default is false
        'cacheExpire' => 1, // 1 second. Default is 24 hours;
    ];
} else {
    $sitemap = [
        'class' => 'himiklab\sitemap\Sitemap',
        'models' => [
            // your models
            'common\models\News',
            'common\models\Events',
            'common\models\Shares',
            'common\models\History',
            //'common\models\Jobs',
            'common\models\Services',
            'common\models\GroupPrograms',
            'common\models\Trainers',
            'common\models\Articles',
        ],
        'urls' => [
            // your additional urls
            [
                'loc' => $url . '/',
                'lastmod' => time(),
                'priority' => 1.0,
            ],
            [
                'loc' => $url . '/es/club/',
                'lastmod' => time(),
                'priority' => 0.8,
            ],
            [
                'loc' => $url . '/es/command/',
                'lastmod' => time(),
                'priority' => 0.8,
            ],
            [
                'loc' => $url . '/es/history/',
                'lastmod' => time(),
                'priority' => 0.8,
            ],
            [
                'loc' => $url . '/es/events/',
                'lastmod' => time(),
                'priority' => 0.8,
            ],
            [
                'loc' => $url . '/es/job/',
                'lastmod' => time(),
                'priority' => 0.8,
            ],
            [
                'loc' => $url . '/es/article/',
                'lastmod' => time(),
                'priority' => 0.8,
            ],
            [
                'loc' => $url . '/services/',
                'lastmod' => time(),
                'priority' => 0.8,
            ],
            [
                'loc' => $url . '/services/programs/',
                'lastmod' => time(),
                'priority' => 0.8,
            ],
            [
                'loc' => $url . '/services/personal_training/',
                'lastmod' => time(),
                'priority' => 0.8,
            ],
            [
                'loc' => $url . '/es/news/',
                'lastmod' => time(),
                'priority' => 0.8,
            ],
            [
                'loc' => $url . '/contacts/',
                'lastmod' => time(),
                'priority' => 0.8,
            ],
            [
                'loc' => $url . '/card/type/',
                'lastmod' => time(),
                'priority' => 0.8,
            ],
            [
                'loc' => $url . '/card/shares/',
                'lastmod' => time(),
                'priority' => 0.8,
            ],
            [
                'loc' => $url . '/card/gift/',
                'lastmod' => time(),
                'priority' => 0.8,
            ],
            [
                'loc' => $url . '/card/schedule/',
                'lastmod' => time(),
                'priority' => 0.8,
            ],
            [
                'loc' => $url . '/card/schedule/list/',
                'lastmod' => time(),
                'priority' => 0.8,
            ],
        ],
        'enableGzip' => true, // default is false
        'cacheExpire' => 1, // 1 second. Default is 24 hours;
    ];
}

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'devicedetect'],
    'on beforeRequest' => function ($event) {
        $settings = \common\models\Settings::findOne(1);
        if ($settings->status == 0) {
            $letMeIn = Yii::$app->session['letMeIn'] || isset($_GET['letMeIn']);
            if (!$letMeIn) {
                Yii::$app->catchAll = [
                    // force route if portal in maintenance mode
                    'site/maintenance',
                ];
                //Yii::$app->session['letMeIn'] = 1;
            } else {
                Yii::$app->session['letMeIn'] = 0;
            }
        }

        $sub = explode('.', $_SERVER['HTTP_HOST'])[0];
        if ($sub == 'piter') {
            Yii::$app->set('db', Yii::$app->get('db'));
        } elseif ($sub == 'iyun') {
            Yii::$app->set('db', Yii::$app->get('db2'));
        } elseif ($sub == 'polyus') {
            Yii::$app->set('db', Yii::$app->get('db3'));
        } elseif ($sub == 'matros') {
            Yii::$app->set('db', Yii::$app->get('db4'));
        }
    },
    'controllerNamespace' => 'frontend\controllers',
    'homeUrl' => '/',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf',
            'baseUrl' => ''
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            //'enableAutoLogin' => true,
            'enableSession' => true,
            //'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced2-frontend',
            'class' => 'yii\web\Session',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'categories' => ['yii\swiftmailer\Logger::add'],
                    'logVars' => ['_POST'],
                    'logFile' => '@runtime/logs/mail.log',
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'devicedetect' => [
            'class' => 'alexandernst\devicedetect\DeviceDetect'
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'suffix' => '/',
            //'class' => 'codemix\localeurls\UrlManager',
            /*'languages' => ['/' => 'ru-RU', 'piter' => 'db', 'june' => 'db2', 'polus' => 'db3', 'matros' => 'db4'],
            'enableLanguageDetection' => false,
            //'enableLanguagePersistence' => false,
            //'enableDefaultLanguageUrlCode' => true,
            'languageCookieDuration' => 6 * 60 * 60,
            'on languageChanged' => function ($event) {
                if($event->language == 'db') {
                    Yii::$app->language = 'db';
                } elseif($event->language == 'db2') {
                    Yii::$app->language = 'db2';
                } elseif ($event->language == 'db3') {
                    Yii::$app->language = 'db3';
                } elseif ($event->language == 'db4') {
                    Yii::$app->language = 'db4';
                }
            },*/
            'rules' => [
                ['class' => 'frontend\components\JobsUrlRule'],
                ['pattern' => 'sitemap', 'route' => 'sitemap/default/index', 'suffix' => '.xml'],
                '//extra.local' => 'site/welcome',
            ],
        ],
        'assetManager' => [
            'bundles' => [
                'lo\widgets\loading\JqueryLoadingAsset' => [
                    'sourcePath' => '@vendor/bower-asset/jquery-loading/dist',
                ]
            ]
        ],
        'i18n' => [
            'translations' => [
                'yii' => [
                    'class'              => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage'     => 'ru-RU', // Developer language
                ],
            ],
        ],
        'view' => [
            'class' => '\rmrevin\yii\minify\View',
            'enableMinify' => !YII_DEBUG,
            'concatCss' => true, // concatenate css
            'minifyCss' => true, // minificate css
            'concatJs' => true, // concatenate js
            'minifyJs' => true, // minificate js
            'minifyOutput' => true, // minificate result html page
            'webPath' => '@web', // path alias to web base
            'basePath' => '@webroot', // path alias to web base
            'minifyPath' => '@webroot/minify', // path alias to save minify result
            'jsPosition' => [\yii\web\View::POS_HEAD], // positions of js files to be minified
            'forceCharset' => 'UTF-8', // charset forcibly assign, otherwise will use all of the files found charset
            'expandImports' => true, // whether to change @import on content
            'compressOptions' => ['extra' => true], // options for compress
        ]
    ],
    'modules' => [
        'sitemap' => $sitemap,
    ],
    'params' => $params,
];

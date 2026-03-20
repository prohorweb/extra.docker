<?php

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php')
    //require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'documentation'],
    'controllerNamespace' => 'api\controllers',
    'modules' => [
        'documentation' => 'nostop8\yii2\rest_api_doc\Module',
        'debug' => [
            'class' => 'yii\debug\Module',
        ],
    ],
    'components' => [
        'request' => [
            // Enable JSON Input:
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
            'enableSession' => false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'pluralize' => false,
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'app',
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ],
                    'except' => ['view', 'delete', 'create', 'update'],
                ],
                [
                    'pluralize' => false,
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'club',
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ],
                    'except' => ['view', 'delete', 'create', 'update'],
                ],
                [
                    'pluralize' => false,
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'news',
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ],
                    'except' => ['delete', 'create', 'update'],
                ],
                [
                    'pluralize' => false,
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'history',
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ],
                    'except' => ['delete', 'create', 'update'],
                ],
                [
                    'pluralize' => false,
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'article',
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ],
                    'except' => ['delete', 'create', 'update'],
                ],
                [
                    'pluralize' => false,
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'trainer-options',
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ],
                    'except' => ['view', 'delete', 'create', 'update'],
                ],
                [
                    'pluralize' => false,
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'trainer-options-type',
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ],
                    'except' => ['view', 'delete', 'create', 'update'],
                ],
                [
                    'pluralize' => false,
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'trainers',
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ],
                    'except' => ['delete', 'create', 'update'],
                    'extraPatterns' => [
                        'GET options/{id}' => 'options',
                    ],
                ],
                [
                    'pluralize' => false,
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'club-cards',
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ],
                    'except' => ['delete', 'create', 'update'],
                ],
                [
                    'pluralize' => false,
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'services',
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ],
                    'except' => ['delete', 'create', 'update'],
                ],
                [
                    'pluralize' => false,
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'group-programs',
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ],
                    'except' => ['view', 'delete', 'create', 'update'],
                ],
                [
                    'pluralize' => false,
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'program-classes',
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ],
                    'except' => ['view', 'delete', 'create', 'update'],
                    'extraPatterns' => [
                        'GET group-programs/{id}' => 'group-programs',
                    ],
                ],
                [
                    'pluralize' => false,
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'shares',
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ],
                    'except' => ['delete', 'create', 'update'],
                ],
                [
                    'pluralize' => false,
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'events',
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ],
                    'except' => ['delete', 'create', 'update'],
                ],
                [
                    'pluralize' => false,
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'type-rasp',
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ],
                    'except' => ['view', 'delete', 'create', 'update'],
                ],
                [
                    'pluralize' => false,
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'rasp',
                    'tokens' => [
                        '{id}' => '<id:\\w+>',
                        '{type_id}' => '<type_id:\\w+>',
                        '{date}' => '<date:\\d{4}-\d{2}-\d{2}>',
                        '{group_programs_id}' => '<group_programs_id:\\w+>',
                        '{program_classes_id}' => '<program_classes_id:\\w+>',
                        '{trainer_id}' => '<trainer_id:\\w+>',
                        '{token}' => '<token>'
                    ],
                    'except' => ['delete', 'create', 'update'],
                    'extraPatterns' => [
                        'GET {token}/byId' => 'by-id',
                        'GET byType/{type_id}' => 'by-type',
                        'GET {id}/{token}' => 'view-by-id',
                        'GET byType/{type_id}/{token}' => 'by-type',
                        'GET byType/{type_id}/byDate/{date}' => 'by-date',
                        'GET {token}/byType/{type_id}/byDate/{date}' => 'by-date',
                        'GET byType/{type_id}/byDate/{date}/{group_programs_id}' => 'by-date',
                        'GET {token}/byType/{type_id}/byDate/{date}/{group_programs_id}' => 'by-date',
                        'GET byType/{type_id}/byDate/{date}/{group_programs_id}/{program_classes_id}' => 'by-date',
                        'GET {token}/byType/{type_id}/byDate/{date}/{group_programs_id}/{program_classes_id}' => 'by-date',
                        // добавили фильтр по тренерам
                        'GET byTrainer/{type_id}/byDate/{date}/{trainer_id}' => 'by-trainer',
                        'GET {token}/byTrainer/{type_id}/byDate/{date}/{trainer_id}' => 'by-trainer',
                        'GET byTrainer/{type_id}/byDate/{date}/{group_programs_id}/{trainer_id}' => 'by-trainer',
                        'GET {token}/byTrainer/{type_id}/byDate/{date}/{group_programs_id}/{trainer_id}' => 'by-trainer',
                        'GET byTrainer/{type_id}/byDate/{date}/{group_programs_id}/{program_classes_id}/{trainer_id}' => 'by-trainer',
                        'GET {token}/byTrainer/{type_id}/byDate/{date}/{group_programs_id}/{program_classes_id}/{trainer_id}' => 'by-trainer',
                    ],
                ],
                [
                    'pluralize' => false,
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'send-mail',
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ],
                    'except' => ['index', 'view', 'delete', 'create', 'update'],
                    'extraPatterns' => [
                        'POST subscribe/' => 'subscribe',
                        'POST feedback/' => 'feedback',
                        'POST event/{id}' => 'event',
                        'GET freezing-text/' => 'freezing-text'
                    ],
                ],
                [
                    'pluralize' => false,
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'notifications',
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ],
                    'except' => ['delete', 'create', 'update'],
                ]
            ],
        ]
    ],
    'params' => $params,
];
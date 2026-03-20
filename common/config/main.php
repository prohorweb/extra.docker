<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'backup' => [
            'class' => 'demi\backup\Component',
            // The directory for storing backups files
            'backupsFolder' => dirname(dirname(__DIR__)) . '/backups', // <project-root>/backups
            // Directories that will be added to backup
            'directories' => [
                'files' => [
                    'path' => dirname(dirname(__DIR__)),
                    'regex' => '/^(?!(.*cgi-bin|.*backups|.*\.git|.*vendor))(.*)$/i',
                ],
            ],
            'db' => 'db',
        ],
    ],
    'language' => 'ru-RU',
    'timeZone' => 'Europe/Moscow',
];

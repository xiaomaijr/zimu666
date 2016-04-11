<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);
return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'runtimePath' => dirname(__DIR__) . '/../runtime/api',
    'bootstrap' => ['log'],
    'controllerNamespace' => 'api\controllers',
    'components' => [
        'user' => [
            'identityClass' => 'common\models\UserPassport',
            'enableAutoLogin' => true,
            'enableSession' => false,
            'loginUrl' => null,
        ],
        'view' => [
            'renderers' => [
                'tpl' => [
                    'class' => 'yii\smarty\ViewRenderer',
                    //'cachePath' => '@runtime/Smarty/cache',
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                '/mall/confirm-<id:\d+>.html' => 'site/confirm',
                '/<controller:\w+>/<action:\w+>' => '<controller>/<action>',

                'defaultRoute' => 'site/index',
            ],
        ],


        'cache' => [
            'class' => 'yii\redis\Cache',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 31 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['trace', 'info'],
                    'categories' => ['application'],
                    'logFile' => dirname(__DIR__) . '/../runtime/logs/app_' . date('Y-m-d') . '.log',
                    'maxLogFiles' => 50,
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'categories' => ['application'],
                    'logFile' => dirname(__DIR__) . '/../runtime/logs/error_' . date('Y-m-d') . '.log',
                    'maxLogFiles' => 50,
                ],

//                'email' => [
//                    'class' => 'yii\log\EmailTarget',
//                    'levels' => ['error', 'warning'],
//                    'message' => [
//                        'to' => ['shupan@jiadao.cn'],
//                        'subject' => 'New jiadao api log message',
//                    ],
//                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'logging' => [
            'class' => 'common\models\Logger',
            'dir' => dirname(__DIR__) . '/../runtime/logs/',
            'prefix' => 'api_',
        ],
    ],
    'params' => $params,
];

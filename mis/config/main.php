<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);




return [
    'id' => 'mis',
    'basePath' => dirname(__DIR__),
    'runtimePath' => dirname(__DIR__) . '/../../runtime/erp',
    'bootstrap' => ['log'],
    'controllerNamespace' => 'mis\controllers',
    'components' => [
        'user' => [
            'identityClass' => 'mis\models\SystemUser',
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
            'showScriptName' => false, //ying隐藏链接的index.php
            'enableStrictParsing' => false,
            'rules' => [//下面是重写
                // ...
//                ['class' => 'yii\rest\UrlRule', 'controller' => ['user', 'driverpassport']],

                'login' => 'site/login',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                'defaultRoute' => 'site/login',

            ],
        ],
//        'redis' => [
//            'class' => 'yii\redis\Connection',
//            'hostname' => 'localhost',
//            'port' => 6379,
//            'database' => 0,
//            'password' => 'jiadaoheiheihei',
//        ],
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
                    'logFile' => dirname(__DIR__) . '/../../runtime/erp/logs/app_' . date('Y-m-d') . '.log',
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'categories' => ['application'],
                    'logFile' => dirname(__DIR__) . '/../../runtime/erp/logs/error_' . date('Y-m-d') . '.log',
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'logging' => [
            'class' => 'mis\models\Logger',
            'file' => dirname(__DIR__) . '/../../runtime/erp/logs/operator_stat_' . date('Y-m-d') . '.log',
        ],
        'record' => [
            'class' => 'mis\models\AdminRecord',
        ],
    ],
    'params' => $params,
];

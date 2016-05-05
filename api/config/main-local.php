<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'hLjvfuIeSoiB5vAu1FAYzHhGbVGlRUGW',
        ],
//        'db' => [
//            'class' => 'yii\db\Connection',
//            'dsn' => 'mysql:host=127.0.0.1:3306;dbname=test',
//            'username' => 'root',
//            'password' => '',
//            'charset' => 'utf8',
//        ],
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}
define('yii_debug', 1);
return $config;

<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=101.201.208.181:3306;dbname=xmjrxb',
            'username' => 'xmjrtest',
            'password' => 'MGM4MGYyYW',
        'charset' => 'utf8',
    ],
        'db1' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=101.200.234.95:3910;dbname=devil',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'localhost',
            'port' => 6379,
            'database' => 0,
//            'password' => 'jiadaoheiheihei',
        ],
//        'mailer' => [
//            'class' => 'yii\swiftmailer\Mailer',
//            'viewPath' => '@common/mail',
//            // send all mails to a file by default. You have to set
//            // 'useFileTransport' to false and configure a transport
//            // for the mailer to send real emails.
//            'useFileTransport' => true,
//        ],
//        'mailer' => [
//            'class' => 'yii\swiftmailer\Mailer',
//            'useFileTransport' =>false,//这句一定有，false发送邮件，true只是生成邮件在runtime文件夹下，不发邮件
//            'transport' => [
//                'class' => 'Swift_SmtpTransport',
//                'host' => 'smtp.exmail.qq.com',
//                'username' => 'zhangxiao@jiadao.cn',
//                'password' => '',
//                'port' => '465',
//                'encryption' => 'ssl',
//
//            ],
//            'messageConfig'=>[
//                'charset'=>'UTF-8',
//                'from'=>['zhangxiao@jiadao.cn'=>'admin']
//            ],
//        ],
    ],
];

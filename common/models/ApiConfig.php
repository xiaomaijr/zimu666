<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao-pc
 * Date: 2016/2/25
 * Time: 15:32
 */

namespace common\models;


class ApiConfig
{
    public static $arrApiCheckParams = [
        'member' => [
            'get-verify-code' => ['key'],
            'get-message-code' => ['user_name', 'verify_code', 'verify_id', 'key'],
            'register' => ['user_name', 'agrement', 'verify_code', 'verify_id', 'phone_code', 'phone_code', 'key'],
            'login' => ['user_name', 'passwd'],
            'passwd' => ['user_name', 'verify_code', 'verify_id', 'key', 'phone_code'],
            'reset-pwd' => ['user_name', 'first_passwd', 'second_passwd'],
        ]
    ];

    public static $arrCommCheckParams = [
        'mobile_type', 'app_ver', 'api_ver', 'channel', 'app_name', 'sign'
    ];

    public static $arrNoNeedCheckApiSign = [

    ];

    const USER_LOGIN_TIMES_LIMIT = 5;//用户登录次数限制
    const USER_LOGIN_DURATION = 1*60;//用户登录次数限制期限
}
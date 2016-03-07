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
    public static $arrSellerCheckParams = [

    ];

    public static $arrNoNeedCheckApiSign = [

    ];

    const USER_LOGIN_TIMES_LIMIT = 5;//用户登录次数限制
    const USER_LOGIN_DURATION = 1*60;//用户登录次数限制期限
}
<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao-pc
 * Date: 2016/2/25
 * Time: 15:09
 */

namespace common\models;


class ApiErrorDescs
{
        //1000以下系统错误，以上为业务错误
    const SUCCESS = 0; //返回正确
    const ERR_PARAM_INVALID = 1; //参数错误
    const ERR_SIGN_ERR = 2;//签名sign错误
    const ERR_REDIS_KEY_NOE_EXISTS = 3;//redis key 不存在









    public static $arrApiErrDescs = [
        self::SUCCESS   =>  'success',
        self::ERR_PARAM_INVALID  => '参数错误',
        self::ERR_SIGN_ERR  => '签名错误',
        self::ERR_REDIS_KEY_NOE_EXISTS => '缓存key不存在',
    ];







}
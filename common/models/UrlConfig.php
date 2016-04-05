<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao-pc
 * Date: 2016/3/18
 * Time: 9:40
 */

namespace common\models;


class UrlConfig
{
    private static $configs = [
        'fonts_path' => 'c:/Windows/Fonts/',//字体路径
        'verify' => 'http://192.168.101.198/static/',//验证码
//        'qdd_notify' => 'http://101.201.208.181:8888/', //乾多多后台回调地址
        'qdd_notify' => 'http://192.168.101.192/', //乾多多后台回调地址

    ];

    public static function getUrl($index){
        if(!isset(self::$configs[$index])){
            throw new ApiBaseException(ApiErrorDescs::ERR_URL_CONFIG_KEY_ERR, $index . ' NOT EXIST IN URL CONFIGS');
        }
        $path = self::$configs[$index];
        $path = rtrim($path, '/') . '/';
        return $path;
    }
}
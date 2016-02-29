<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao-pc
 * Date: 2016/2/26
 * Time: 18:35
 */

namespace common\models;


class CacheKey
{
    private static $redisKeys = [
        'lzhborrowinfo_getlist' => [
            'key_name' =>  'borrowinfo_list',
            'expire' => 3*60
        ]
    ];
    /*
     * 获取reids keyName及其生命周期
     * @param $keyName string self::$redisKeys key
     * @param $append string if the $keyName not empty, the $keyName eq $keyName . $flag . $append
     * @param $flag string
     * return array ['key_name' => string , 'expirt' => 3 *100]
     */
    public static function getCacheKey($append = '', $keyName = '', $flag = '_'){
        if(empty($keyName)){
            $trace = debug_backtrace();
            $className = $trace[1]['class']?$trace[1]['class']:'';
            $funcName = $trace[1]['function']?$trace[1]['function']:'';
            if(($pos = strrpos($className, '\\'))){
                $className = substr($className, $pos+1);
            }
            $keyName = strtolower($className) . $flag . strtolower($funcName);
        }

        $cacheInfo = self::$redisKeys[$keyName];

        if(empty($cacheInfo)){
            throw new ApiBaseException(ApiErrorDescs::ERR_REDIS_KEY_NOE_EXISTS);
        }
        if($append){
            $cacheInfo['key_name'] .= $flag . $append;
        }
        if(!isset($cacheInfo['expire'])){
            $cacheInfo['expire'] = 0;
        }
        return $cacheInfo;
    }
}
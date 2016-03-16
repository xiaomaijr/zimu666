<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao-pc
 * Date: 2016/3/16
 * Time: 14:06
 */

namespace common\models;


use yii\base\Component;

class RedisUtil extends Component
{
    private $redis;

    public function __construct(){
        $config = \Yii::$app->redis;
        try{
            $this->redis = new \Redis();
            $con = $this->redis->connect($config->hostname, $config->port);
            if(!$con){
                throw new \Exception('redis connect fail');
            }
        }catch(\Exception $e){
            echo $e->getMessage();
            exit;
        }
    }

    public function getRedis(){
        return $this->redis;
    }

    public function hSet($key, $field, $value){
        return $this->redis->hSet($key, $field, json_encode($value));
    }

    public function hGet($key, $field){
        $value = $this->redis->hGet($key, $field);
        return json_decode($value, true);
    }

    public function hDel($key, $field){
        return $this->redis->hDel($key, $field);
    }

    public function hExists($key, $field){
        return $this->redis->hExists($key, $field);
    }
}
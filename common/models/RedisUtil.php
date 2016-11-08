<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao-pc
 * Date: 2016/3/16
 * Time: 14:06
 */

namespace common\models;


use yii\base\Component;

class RedisUtil 
{
    private $redis;
    
    private static $instance;

    private function __construct(){
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
    
    public static function getRedis(){
        if(self::$instance instanceof self){
            return self::$instance;
        }else{
            self::$instance = new self;
            return self::$instance;
        }
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

    public function lPush($key, $val){
        return $this->redis->lPush($key, $val);
    }

    public function lLen($key){
        return $this->redis->lLen($key);
    }

    public function lPop($key){
        return $this->redis->lPop($key);
    }

    public function rPush($key, $val){
        return $this->redis->rPush($key, $val);
    }

    public function rPop($key){
        return $this->redis->rPop($key);
    }

    public function exists($key)
    {
        return $this->redis->exists($key);
    }

    public function set($key, $value, $expire = 0)
    {
        if (!$expire) return $this->redis->set($key, json_encode($value));
        return $this->redis->setex($key, $expire, json_encode($value));
    }

    public function get($key)
    {
        $ret = $this->redis->get($key);
        if ($ret) {
            return json_decode($ret, true);
        }
        return false;
    }
}
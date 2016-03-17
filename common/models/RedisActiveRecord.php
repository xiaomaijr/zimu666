<?php


namespace common\models;


use yii\db\ActiveRecord;
use yii\filters\auth\AuthInterface;
use yii\redis\Cache;

abstract class RedisActiveRecord extends ActiveRecord
{
    public static $cache;

    public static $tableName = "redis_active_record";

    public static function tableName()
    {
        return self::$tableName;
    }

    public static function getCache()
    {
        if (null == self::$cache) {
            self::$cache = new RedisUtil();
        }
        return self::$cache;
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        $r = parent::save($runValidation, $attributeNames);
        $this->insertEvent();
        return $r;
    }

    public function update($runValidation = true, $attributeNames = null)
    {
        $r = parent::update($runValidation, $attributeNames);
        $this->updateEvent();
        return $r;
    }

    public function delete()
    {
        $r = parent::delete();
        $this->deleteEvent();
        return $r;
    }

    public static function get($id, $tableName = '')
    {
        $cache = self::getCache();
        $tableName = $tableName?$tableName:static::tableName();
        $module = [];

        if(!$cache->hExists($tableName, 'id:' . $id)){
            $module = self::find()->where(['id' => $id])->asArray()->one();
            $module AND $cache->hSet($tableName,  'id:' . $id, $module);
        }else{
            $module = $cache->hGet($tableName, 'id:' . $id);
        }
        return $module;
    }

    public static function gets($ids, $tableName = '')
    {
        $modules = array();
        $cache = self::getCache();
        $tableName = $tableName?$tableName:static::tableName();
        $isNeedRead = false;
        foreach ($ids as $id) {
            if (!$cache->hExists($tableName, 'id:' . $id)) {
                $isNeedRead = true;
                $modules = array();
                break;
            } else {
                $tmp = $cache->hGet($tableName, 'id:' . $id);
                $modules[$id] = $tmp;
            }
        }

        if ($isNeedRead) {
            $key = implode(',', $ids);
            $sql = "SELECT * FROM " . $tableName . " WHERE `id` IN (" . $key . ") ORDER BY field(id, " . $key . ")";
            $nueList = self::findBySql($sql)->asArray()->all();
//            $nueList = self::find()->where(['id' => $ids])->asArray()->all();

            foreach ($nueList as $module) {
                $cache->hSet($tableName, 'id:' . $module['id'], $module);
                $modules[$module['id']] = $module;
            }
        }

        return $modules;
    }

    /*
     *根据指定条件批量返回数据,至少二维数据
     */
    public static function getDataByConditions($condition = [], $order = 'id desc', $limit = 10, $offset = 1, $select = '*'){
        $strConditions = [];
        if(!empty($condition)){
            foreach($condition as $key=>$con){
                if(is_int($key) && is_string($con)){
                    array_push($strConditions, $con);
                    unset($condition[$key]);
                }
            }
        }
        $query = self::find()->select($select)->where($condition);
        if(!empty($strConditions)){
            foreach($strConditions as $strCon){
                $query->andWhere($strCon);
            }
        }
        $query->orderBy($order);
        if($limit && $offset){
            $query->limit($limit)->offset(($offset - 1) * $limit);
        }elseif($limit){
            $query->limit($limit);
        }
//        echo $query->createCommand()->getRawSql();exit;
        $infos = $query->asArray()->all();
        return $infos?$infos:[];
    }
    /*
     * 根据id返回单一数据
     */
    public static function getDataByID($id, $param = 'id'){
        if(!$id){
            return [];
        }
        $info = self::find()
            ->where([$param => $id])
            ->asArray()
            ->one();
        return $info?$info:[];
    }
    /*
     * 返回总数
     */
    public static function getCountByCondition($condition = []){
        $strConditions = [];
        if(!empty($condition)){
            foreach($condition as $key=>$con){
                if(is_int($key) && is_string($con)){
                    array_push($strConditions, $con);
                    unset($condition[$key]);
                }
            }
        }
        $query = self::find()->where($condition);
        if(!empty($strConditions)){
            foreach($strConditions as $strCon){
                $query->andWhere($strCon);
            }
        }
        $total = $query->count();
        return $total?$total:0;
    }
    /*
     * 判断是否存在满足条件的数据
     * @param $condition array
     * return bool exists true not else false
     */
    public static function checkExistByCondition($condition){
        $strConditions = [];
        if(!empty($condition)){
            foreach($condition as $key=>$con){
                if(is_int($key) && is_string($con)){
                    array_push($strConditions, $con);
                    unset($condition[$key]);
                }
            }
        }
        $query = self::find()->where($condition);
        if(!empty($strConditions)){
            foreach($strConditions as $strCon){
                $query->andWhere($strCon);
            }
        }
        return $query->exists();
    }

    abstract public function insertEvent();

    abstract public function updateEvent();

    abstract public function deleteEvent();
}
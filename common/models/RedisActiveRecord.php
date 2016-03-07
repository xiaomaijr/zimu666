<?php


namespace common\models;


use yii\db\ActiveRecord;
use yii\filters\auth\AuthInterface;
use yii\redis\Cache;

abstract class RedisActiveRecord extends BaseModel
{
    public static $cache;

    public static $tableName = "redis_active_record";

    public static function getCache()
    {
        if (null == self::$cache) {
            self::$cache = new Cache();
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

    public static function get($id, $tableName = '', $select = '*')
    {
        $cache = self::getCache();
        $tableName = $tableName?$tableName:self::tableName();

        if (!$cache->exists($tableName . ':' . $id)) {
            $module = self::find()->select($select)->where(['id' => $id])->asArray()->one();
            $cache->set($tableName . ':' . $id, $module);
        } else {
            $module = $cache->get($tableName . ':' . $id);
        }

        return $module;
    }

    public static function gets($ids, $tableName = '', $select = '*')
    {
        $modules = array();
        $cache = self::getCache();
        $tableName = $tableName?$tableName:self::tableName();

        $isNeedRead = false;
        foreach ($ids as $id) {
            if (!$cache->exists($tableName . ':' . $id)) {
                $isNeedRead = true;
                $modules = array();
                break;
            } else {
                $vehicleType = $cache->get($tableName . ':' . $id);
                $modules[$id] = $vehicleType;
            }
        }

        if ($isNeedRead) {
            $key = implode(',', $ids);
            $sql = "SELECT " . $select . " FROM " . $tableName . " WHERE `id` IN (" . $key . ") ORDER BY field(id, " . $key . ")";
            $nueList = self::findBySql($sql)->asArray()->all();
//            $nueList = self::find()->where(['in', 'id', $ids])->asArray()->all();

            foreach ($nueList as $module) {
                $cache->set($tableName . ':' . $module['id'], $module);
                $modules[$module['id']] = $module;
            }
        }

        return $modules;
    }

    abstract public function insertEvent();

    abstract public function updateEvent();

    abstract public function deleteEvent();
}
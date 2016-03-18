<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lzh_area".
 *
 * @property integer $id
 * @property integer $reid
 * @property string $name
 * @property integer $sort_order
 * @property integer $is_open
 * @property string $domain
 */
class Area extends RedisActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lzh_area';
    }

    public static $tableName = 'lzh_area';
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['reid', 'sort_order', 'is_open'], 'integer'],
            [['domain'], 'required'],
            [['name'], 'string', 'max' => 120],
            [['domain'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'reid' => 'Reid',
            'name' => 'Name',
            'sort_order' => 'Sort Order',
            'is_open' => 'Is Open',
            'domain' => 'Domain',
        ];
    }

    public function insertEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'id:' . $this->id);
    }

    public function updateEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'id:' . $this->id);
    }

    public function deleteEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'id:' . $this->id);
    }
    /*
     * 根据reid获取area
     */
    public static function getAreaByReid($reid = 1){
        $field = 'reid:' . $reid;
        $cache = self::getCache();
        if(false && $cache->hExists(self::$tableName, $field)){
            $ids = $cache->hGet(self::$tableName, $field);
            $infos = self::gets($ids);
        }else{
            $infos = self::getDataByConditions(['reid' => $reid]);
            if(empty($infos)) return $infos;
            $ids = ApiUtils::getCols($infos, 'id');
            $cache->hSet(self::$tableName, $field, $ids);
        }
        foreach($infos as $k => $info){
            $tmp = self::toApiArr($info);
            $infos[$k] = $tmp;
        }
        return $infos;
    }
    /*
     * api接口过滤字段
     */
    private static function toApiArr($arr){
        return [
            'id' => ApiUtils::getIntParam('id', $arr),
            'name' => ApiUtils::getStrParam('name', $arr),
        ];
    }
}

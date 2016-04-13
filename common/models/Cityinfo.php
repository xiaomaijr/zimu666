<?php

namespace common\models;

use common\models\ApiUtils;
use common\models\RedisActiveRecord;
use Yii;

/**
 * This is the model class for table "lzh_cityinfo".
 *
 * @property integer $id
 * @property string $cityname
 * @property integer $parentid
 */
class Cityinfo extends RedisActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lzh_cityinfo';
    }

    public static $tableName = 'lzh_cityinfo';
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'cityname', 'parentid'], 'required'],
            [['id', 'parentid'], 'integer'],
            [['cityname'], 'string', 'max' => 22],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cityname' => 'Cityname',
            'parentid' => 'Parentid',
        ];
    }
    public function insertEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'id:' . $this->id);
        $cache->hDel(self::$tableName, 'parentid:' . $this->parentid);
    }

    public function updateEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'id:' . $this->id);
        $cache->hDel(self::$tableName, 'parentid:' . $this->parentid);
    }

    public function deleteEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'id:' . $this->id);
        $cache->hDel(self::$tableName, 'parentid:' . $this->parentid);
    }
    /*
     * 根据reid获取area
     */
    public static function getCityByPid($parentId = 0){
        $field = 'parentid:' . $parentId;
        $cache = self::getCache();
        if(false && $cache->hExists(self::$tableName, $field)){
            $ids = $cache->hGet(self::$tableName, $field);
            $infos = self::gets($ids);
        }else{
            $infos = self::getDataByConditions(['parentid' => $parentId], 'id', 0, 0);
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
            'name' => ApiUtils::getStrParam('cityname', $arr),
        ];
    }
}

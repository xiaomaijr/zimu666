<?php

namespace front\models;

use common\models\ApiUtils;
use common\models\RedisActiveRecord;
use Yii;

/**
 * This is the model class for table "district".
 *
 * @property integer $district_id
 * @property string $district_name
 * @property string $short_name
 * @property string $pinyin
 * @property string $url
 * @property string $location
 * @property integer $city_id
 * @property integer $script_index
 * @property integer $display_order
 * @property integer $created_by
 * @property string $created_by_name
 * @property string $created_time
 * @property integer $modified_by
 * @property string $modified_by_name
 * @property string $modified_time
 */
class District extends RedisActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'district';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['district_name', 'short_name', 'pinyin', 'url', 'city_id', 'script_index', 'display_order', 'created_by', 'created_by_name', 'created_time'], 'required'],
            [['city_id', 'script_index', 'display_order', 'created_by', 'created_time', 'modified_by', 'modified_time'], 'integer'],
            [['district_name', 'created_by_name', 'modified_by_name'], 'string', 'max' => 20],
            [['short_name'], 'string', 'max' => 15],
            [['pinyin', 'url', 'location'], 'string', 'max' => 50],
            [['city_id', 'district_name'], 'unique', 'targetAttribute' => ['city_id', 'district_name'], 'message' => 'The combination of District Name and City ID has already been taken.'],
            [['city_id', 'pinyin'], 'unique', 'targetAttribute' => ['city_id', 'pinyin'], 'message' => 'The combination of Pinyin and City ID has already been taken.'],
            [['city_id', 'url'], 'unique', 'targetAttribute' => ['city_id', 'url'], 'message' => 'The combination of Url and City ID has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'district_id' => 'District ID',
            'district_name' => 'District Name',
            'short_name' => 'Short Name',
            'pinyin' => 'Pinyin',
            'url' => 'Url',
            'location' => 'Location',
            'city_id' => 'City ID',
            'script_index' => 'Script Index',
            'display_order' => 'Display Order',
            'created_by' => 'Created By',
            'created_by_name' => 'Created By Name',
            'created_time' => 'Created Time',
            'modified_by' => 'Modified By',
            'modified_by_name' => 'Modified By Name',
            'modified_time' => 'Modified Time',
        ];
    }

    public function insertEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'district_id:' . $this->district_id);
        $cache->hDel(self::$tableName, 'city_id:' . $this->city_id);
    }

    public function updateEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'district_id:' . $this->district_id);
        $cache->hDel(self::$tableName, 'city_id:' . $this->city_id);
    }

    public function deleteEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'district_id:' . $this->district_id);
        $cache->hDel(self::$tableName, 'city_id:' . $this->city_id);
    }
    public static function getDataByCityId($cityId)
    {
        $cache = self::getCache();
        if ($cache->hExists(static::$tableName, 'city_id:' . $cityId)) {
            $cityIds = $cache->hGet(static::$tableName, 'city_id:' . $cityId);
            $infos = self::gets($cityIds);
        } else {
            $infos = self::getDataByConditions(['city_id' => $cityId], 'district_id');
            if (empty($infos)) return [];
            $cityIds = ApiUtils::getCols($infos, 'district_id');
            $cache->hSet(static::$tableName, 'city_id:' . $cityId, $cityIds);
        }
        return $infos;
    }

    public static function get($id, $tableName = '')
    {
        $cache = self::getCache();
        $tableName = $tableName?$tableName:static::tableName();
        $module = [];
        if(!$cache->hExists($tableName, 'district_id:' . $id)){
            $module = self::find()->where(['district_id' => $id])->asArray()->one();
            $module AND $cache->hSet($tableName,  'district_id:' . $id, $module);
        }else{
            $module = $cache->hGet($tableName, 'district_id:' . $id);
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
            if (!$cache->hExists($tableName, 'district_id:' . $id)) {
                $isNeedRead = true;
                $modules = array();
                break;
            } else {
                $tmp = $cache->hGet($tableName, 'district_id:' . $id);
                $modules[$id] = $tmp;
            }
        }

        if ($isNeedRead) {
            $key = implode(',', $ids);
            $sql = "SELECT * FROM " . $tableName . " WHERE `district_id` IN (" . $key . ") ORDER BY field(district_id, " . $key . ")";
            $nueList = self::findBySql($sql)->asArray()->all();
//            $nueList = self::find()->where(['id' => $ids])->asArray()->all();

            foreach ($nueList as $module) {
                $cache->hSet($tableName, 'district_id:' . $module['district_id'], $module);
                $modules[$module['district_id']] = $module;
            }
        }

        return $modules;
    }
}

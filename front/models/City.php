<?php

namespace front\models;

use common\models\ApiUtils;
use common\models\RedisActiveRecord;
use Yii;

/**
 * This is the model class for table "city".
 *
 * @property integer $city_id
 * @property string $city_name
 * @property string $short_name
 * @property string $pinyin
 * @property string $standard_code
 * @property string $domain
 * @property string $domain_alias
 * @property string $database_name
 * @property integer $province_id
 * @property integer $script_index
 * @property integer $display_order
 * @property integer $province_script_index
 * @property string $google_analytics_code
 * @property integer $created_by
 * @property string $created_by_name
 * @property string $created_time
 * @property integer $modified_by
 * @property string $modified_by_name
 * @property string $modified_time
 * @property string $location
 */
class City extends RedisActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'city';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['city_name', 'short_name', 'pinyin', 'standard_code', 'domain', 'database_name', 'province_id', 'script_index', 'display_order', 'province_script_index', 'google_analytics_code', 'created_by', 'created_by_name', 'created_time'], 'required'],
            [['province_id', 'script_index', 'display_order', 'province_script_index', 'created_by', 'created_time', 'modified_by', 'modified_time'], 'integer'],
            [['city_name', 'created_by_name', 'modified_by_name'], 'string', 'max' => 20],
            [['short_name', 'google_analytics_code'], 'string', 'max' => 15],
            [['pinyin', 'domain', 'domain_alias', 'database_name', 'location'], 'string', 'max' => 50],
            [['standard_code'], 'string', 'max' => 6],
            [['city_name'], 'unique'],
            [['pinyin'], 'unique'],
            [['domain'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'city_id' => 'City ID',
            'city_name' => 'City Name',
            'short_name' => 'Short Name',
            'pinyin' => 'Pinyin',
            'standard_code' => 'Standard Code',
            'domain' => 'Domain',
            'domain_alias' => 'Domain Alias',
            'database_name' => 'Database Name',
            'province_id' => 'Province ID',
            'script_index' => 'Script Index',
            'display_order' => 'Display Order',
            'province_script_index' => 'Province Script Index',
            'google_analytics_code' => 'Google Analytics Code',
            'created_by' => 'Created By',
            'created_by_name' => 'Created By Name',
            'created_time' => 'Created Time',
            'modified_by' => 'Modified By',
            'modified_by_name' => 'Modified By Name',
            'modified_time' => 'Modified Time',
            'location' => 'Location',
        ];
    }
    public function insertEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'city_id:' . $this->city_id);
        $cache->hDel(self::$tableName, 'province_id:' . $this->province_id);
    }

    public function updateEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'city_id:' . $this->city_id);
        $cache->hDel(self::$tableName, 'province_id:' . $this->province_id);
    }

    public function deleteEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'city_id:' . $this->city_id);
        $cache->hDel(self::$tableName, 'province_id:' . $this->province_id);
    }
    public static function getDataByProvinceId($provinceId)
    {
        $cache = self::getCache();
        if ($cache->hExists(static::$tableName, 'province_id:' . $provinceId)) {
            $cityIds = $cache->hGet(static::$tableName, 'province_id:' . $provinceId);
            $infos = self::gets($cityIds);
        } else {
            $infos = self::getDataByConditions(['province_id' => $provinceId], 'city_id');
            if (empty($infos)) return [];
            $cityIds = ApiUtils::getCols($infos, 'city_id');
            $cache->hSet(static::$tableName, 'province_id:' . $provinceId, $cityIds);
        }
        return $infos;
    }

    public static function get($id, $tableName = '')
    {
        $cache = self::getCache();
        $tableName = $tableName?$tableName:static::tableName();
        $module = [];
        if(!$cache->hExists($tableName, 'city_id:' . $id)){
            $module = self::find()->where(['city_id' => $id])->asArray()->one();
            $module AND $cache->hSet($tableName,  'city_id:' . $id, $module);
        }else{
            $module = $cache->hGet($tableName, 'city_id:' . $id);
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
            if (!$cache->hExists($tableName, 'city_id:' . $id)) {
                $isNeedRead = true;
                $modules = array();
                break;
            } else {
                $tmp = $cache->hGet($tableName, 'city_id:' . $id);
                $modules[$id] = $tmp;
            }
        }

        if ($isNeedRead) {
            $key = implode(',', $ids);
            $sql = "SELECT * FROM " . $tableName . " WHERE `city_id` IN (" . $key . ") ORDER BY field(city_id, " . $key . ")";
            $nueList = self::findBySql($sql)->asArray()->all();
//            $nueList = self::find()->where(['id' => $ids])->asArray()->all();

            foreach ($nueList as $module) {
                $cache->hSet($tableName, 'city_id:' . $module['city_id'], $module);
                $modules[$module['city_id']] = $module;
            }
        }

        return $modules;
    }
}

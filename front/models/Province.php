<?php

namespace front\models;

use common\models\RedisActiveRecord;
use Yii;

/**
 * This is the model class for table "province".
 *
 * @property integer $province_id
 * @property string $province_name
 * @property string $short_name
 * @property string $pinyin
 * @property integer $script_index
 * @property integer $display_order
 * @property integer $created_by
 * @property string $created_by_name
 * @property string $created_time
 * @property integer $modified_by
 * @property string $modified_by_name
 * @property string $modified_time
 */
class Province extends RedisActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'province';
    }
    public static $tableName = 'province';
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['province_name', 'short_name', 'pinyin', 'script_index', 'display_order', 'created_by', 'created_by_name', 'created_time'], 'required'],
            [['script_index', 'display_order', 'created_by', 'created_time', 'modified_by', 'modified_time'], 'integer'],
            [['province_name', 'created_by_name', 'modified_by_name'], 'string', 'max' => 20],
            [['short_name'], 'string', 'max' => 15],
            [['pinyin'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'province_id' => 'Province ID',
            'province_name' => 'Province Name',
            'short_name' => 'Short Name',
            'pinyin' => 'Pinyin',
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
        $cache->hDel(static::$tableName, 'province_id:' . $this->province_id);
    }

    public function updateEvent(){
        $cache = self::getCache();
        $cache->hDel(static::$tableName, 'province_id:' . $this->province_id);
    }

    public function deleteEvent(){
        $cache = self::getCache();
        $cache->hDel(static::$tableName, 'province_id:' . $this->province_id);
    }
    
    public static function getAll()
    {
        $cache = self::getCache();
        $key = __CLASS__ . '_' . __FUNCTION__ . '_' . date('Ymd');
        if ($cache->exists($key)){
            return $cache->get($key);
        }
        $data = self::getDataByConditions([], 'province_id');
        if ($data) {
            $cache->set($key, $data, 24*3600);
        }
        return $data;
    }
}

<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lzh_platform_activity_manage".
 *
 * @property integer $id
 * @property string $activity_code
 * @property integer $start_time
 * @property integer $end_time
 * @property string $title
 * @property string $description
 * @property integer $add_time
 */
class PlatformActivityManage extends RedisActiveRecord
{

    const FIRST_CHARGE_BONUS = 'new_user_bonus3.0';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lzh_platform_activity_manage';
    }

    public static $tableName = 'lzh_platform_activity_manage';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['start_time', 'end_time', 'add_time'], 'integer'],
            [['activity_code', 'title'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'activity_code' => 'Activity Code',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'title' => 'Title',
            'description' => 'Description',
            'add_time' => 'Add Time',
        ];
    }

    public function insertEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'activity_code:' . $this->activity_code);
    }

    public function updateEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'activity_code:' . $this->activity_code);
    }

    public function deleteEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'activity_code:' . $this->activity_code);
    }

    public static function get($activityCode, $tableName = ''){
        $cache = self::getCache();
        $tableName = $tableName?$tableName:static::tableName();
        $module = [];
        if(!$cache->hExists($tableName, 'activity_code:' . $activityCode)){
            $module = self::find()->where(['activity_code' => $activityCode])->asArray()->one();
            $module AND $cache->hSet($tableName,  'activity_code:' . $activityCode, $module);
        }else{
            $module = $cache->hGet($tableName, 'activity_code:' . $activityCode);
        }
        return $module;
    }

    /**
     * 查询某个活动是否有效
     * @param $activity_code
     * @return bool
     */
    public static function getActivityValid($activity_code){
        $act = self::get($activity_code);
        if($act && $act['start_time'] <= time() && $act['end_time'] >= time()){
            return true;
        }
        return false;
    }
}

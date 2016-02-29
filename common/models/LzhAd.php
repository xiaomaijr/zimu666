<?php

namespace common\models;

use common\models\RedisActiveRecord;
use Yii;

/**
 * This is the model class for table "lzh_ad".
 *
 * @property string $id
 * @property string $content
 * @property integer $start_time
 * @property integer $end_time
 * @property integer $add_time
 * @property string $title
 * @property integer $ad_type
 * @property integer $platform
 */
class LzhAd extends RedisActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lzh_ad';
    }

    public static $tableName = "lzh_ad";

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content', 'start_time', 'end_time', 'title'], 'required'],
            [['start_time', 'end_time', 'add_time', 'ad_type', 'platform'], 'integer'],
            [['content'], 'string', 'max' => 5000],
            [['title'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => 'Content',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'add_time' => 'Add Time',
            'title' => 'Title',
            'ad_type' => 'Ad Type',
            'platform' => 'Platform',
        ];
    }

    public function insertEvent(){

    }

    public function updateEvent(){

    }

    public function deleteEvent(){

    }

    public static function getAppBanners(){
        $info = self::gets([3], self::$tableName);
        $banners = json_decode($info[3]['content'],true);
        return $banners;
    }
}

<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lzh_member_device_token".
 *
 * @property integer $id
 * @property string $token
 * @property string $mobile_type
 * @property integer $member_id
 * @property string $mobile
 * @property integer $identify
 * @property integer $status
 * @property integer $create_time
 * @property integer $update_time
 */
class LzhMemberDeviceToken extends RedisActiveRecord
{
    const DEVICE_TOKEN_IDENTIFY_USER = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lzh_member_device_token';
    }

    public static $tableName = 'lzh_member_device_token';
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'identify', 'status', 'create_time', 'update_time'], 'integer'],
            [['token'], 'string', 'max' => 100],
            [['mobile_type', 'mobile'], 'string', 'max' => 32],
            [['member_id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'token' => 'Token',
            'mobile_type' => 'Mobile Type',
            'member_id' => 'Member ID',
            'mobile' => 'Mobile',
            'identify' => 'Identify',
            'status' => 'Status',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
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
     * 用户device_token绑定
     */
    public static function bindToken($memberId, $token, $mobileType, $mobile, $identify = 1){
        $new = false;
        if(!($obj = self::findOne(['member_id' => $memberId, 'identify' => $identify]))){
            $obj = new static;
            $obj->member_id = $memberId;
            $obj->identify = $identify;
            $new = true;
        }
        self::updateAll(['status' => 1], ['token' => $token, 'status' => 0]);
        $obj->token = $token;
        $obj->mobile_type = $mobileType;
        $obj->mobile = $mobile;
        $obj->status = 0;
        $obj->create_time = time();
        return $new?$obj->save():$obj->update();
    }
    /*
     * 解绑
     */
    public static function unbindToken($memberId, $identify = 1){
        $obj = self::findOne(['member_id' => $memberId, 'identify' => $identify]);
        if(!$obj){
            return false;
        }
        $obj->status = 1;
        $obj->update_time = time();
        return  $obj->update();
    }
}

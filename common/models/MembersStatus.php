<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lzh_members_status".
 *
 * @property string $uid
 * @property integer $phone_status
 * @property string $phone_credits
 * @property integer $id_status
 * @property string $id_credits
 * @property integer $face_status
 * @property string $face_credits
 * @property integer $email_status
 * @property string $email_credits
 * @property integer $account_status
 * @property string $account_credits
 * @property integer $credit_status
 * @property string $credit_credits
 * @property integer $safequestion_status
 * @property string $safequestion_credits
 * @property integer $video_status
 * @property string $video_credits
 * @property integer $vip_status
 * @property string $vip_credits
 */
class MembersStatus extends RedisActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lzh_members_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid'], 'required'],
            [['uid', 'phone_status', 'phone_credits', 'id_status', 'id_credits', 'face_status', 'face_credits', 'email_status', 'email_credits', 'account_status', 'account_credits', 'credit_status', 'credit_credits', 'safequestion_status', 'safequestion_credits', 'video_status', 'video_credits', 'vip_status', 'vip_credits'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid' => 'Uid',
            'phone_status' => 'Phone Status',
            'phone_credits' => 'Phone Credits',
            'id_status' => 'Id Status',
            'id_credits' => 'Id Credits',
            'face_status' => 'Face Status',
            'face_credits' => 'Face Credits',
            'email_status' => 'Email Status',
            'email_credits' => 'Email Credits',
            'account_status' => 'Account Status',
            'account_credits' => 'Account Credits',
            'credit_status' => 'Credit Status',
            'credit_credits' => 'Credit Credits',
            'safequestion_status' => 'Safequestion Status',
            'safequestion_credits' => 'Safequestion Credits',
            'video_status' => 'Video Status',
            'video_credits' => 'Video Credits',
            'vip_status' => 'Vip Status',
            'vip_credits' => 'Vip Credits',
        ];
    }

    public function insertEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'id:' . $this->id);
        $cache->hDel(self::$tableName, 'uid:' . $this->uid);
    }

    public function updateEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'id:' . $this->id);
        $cache->hDel(self::$tableName, 'uid:' . $this->uid);
    }

    public function deleteEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'id:' . $this->id);
        $cache->hDel(self::$tableName, 'uid:' . $this->uid);
    }

    //添加新纪录
    public static function add($attrs){
        if(!isset($attrs['uid'])){
            return false;
        }
        if(!($obj = self::find()->where(['uid' => $attrs['uid']])->one())){
            $obj = new self;
        }
        $obj->attributes = $attrs;
        return $obj->save();
    }
}

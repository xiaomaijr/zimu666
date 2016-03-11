<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lzh_member_access_token".
 *
 * @property string $id
 * @property string $token
 * @property string $token_ctime
 * @property string $member_id
 * @property string $mobile_type
 * @property string $update_times
 */
class LzhMemberAccessToken extends RedisActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lzh_member_access_token';
    }

    public static $tableName = 'lzh_member_access_token';
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['token_ctime', 'member_id', 'update_times'], 'integer'],
            [['token'], 'string', 'max' => 100],
            [['mobile_type'], 'string', 'max' => 32],
            [['token'], 'unique'],
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
            'token_ctime' => 'Token Ctime',
            'member_id' => 'Member ID',
            'mobile_type' => 'Mobile Type',
            'update_times' => 'Update Times',
        ];
    }

    public function insertEvent(){
        $cache = self::getCache();
        $cache->delete(self::$tableName . ':' . $this->member_id);
    }

    public function updateEvent(){
        $cache = self::getCache();
        $cache->delete(self::$tableName . ':' . $this->member_id);
    }

    public function deleteEvent(){
        $cache = self::getCache();
        $cache->delete(self::$tableName . ':' . $this->member_id);
    }
    /*
     * 用户登录
     */
    public function login($memberId, $mobileType){
        $new = false;
        if(!($obj = self::findOne(['member_id' => $memberId]))){
            $obj = new self;
            $obj->member_id = $memberId;
            $obj->update_times = 0;
            $new = true;
        }
        $obj->token = \Yii::$app->getSecurity()->generateRandomString(64);
        $obj->mobile_type = $mobileType;
        $obj->token_ctime = time();
        $ret = $new?$obj->save():$obj->update();
        if(!$ret){
            throw new ApiBaseException(ApiErrorDescs::ERR_USER_LOGIN_FAIL);
        }
        $obj->updateCounters(['update_times' => 1]);
        return $obj->token;
    }
    /*
     * 验证用户登录
     */
    public static function checkUserLogin($accessToken, $memberId){
        $obj = self::get($memberId);
        if(!$obj){
            throw new ApiBaseException(ApiErrorDescs::ERR_USER_ACCESS_TOKEN_OVERDUE);
        }
        $accTokenCTime = ApiUtils::getIntParam('token_ctime', $obj);
        if(($accTokenCTime + 7*24*3600) < time()){
            throw new ApiBaseException(ApiErrorDescs::ERR_USER_ACCESS_TOKEN_OVERDUE);
        }
        if($accessToken != $obj['token']){
            throw new ApiBaseException(ApiErrorDescs::ERR_USER_ACCESS_TOKEN_OVERDUE);
        }
    }
    /*
     * 退出登录
     */
    public static function logOut($memberId){
        $obj = self::findOne(['member_id' => $memberId]);
        if(!$obj){
            throw new ApiBaseException(ApiErrorDescs::ERR_USER_INFO_ERROR);
        }
        $obj->token = '';
        $obj->token_ctime = 0;
        $ret = $obj->save();
        if(!$ret){
            throw new ApiBaseException(ApiErrorDescs::ERR_USER_LOGOUT_FAIL);
        }
    }

    public static function get($memberId, $tableName = '', $select = '*')
    {
        $cache = self::getCache();
        $tableName = $tableName?$tableName:self::tableName();

        if (!$cache->exists($tableName . ':' . $memberId)) {
            $module = self::find()->select($select)->where(['member_id' => $memberId])->asArray()->one();
            $cache->set($tableName . ':' . $memberId, $module);
        } else {
            $module = $cache->get($tableName . ':' . $memberId);
        }

        return $module;
    }
}

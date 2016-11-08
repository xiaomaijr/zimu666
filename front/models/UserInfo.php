<?php

namespace front\models;

use common\models\ApiBaseException;
use common\models\ApiErrorDescs;
use common\models\RedisActiveRecord;
use Yii;

/**
 * This is the model class for table "user_info".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $account
 * @property string $mobile
 * @property string $nick_name
 * @property string $avatar
 * @property string $email
 * @property integer $create_time
 * @property integer $update_time
 */
class UserInfo extends RedisActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_info';
    }

    public static $tableName = 'user_info';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'create_time', 'update_time'], 'integer'],
            [['account', 'nick_name'], 'string', 'max' => 64],
            [['mobile', 'email'], 'string', 'max' => 32],
            [['avatar'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'account' => 'Account',
            'mobile' => 'Mobile',
            'nick_name' =>  'Nick Name',
            'email' => 'Email',
            'avatar'=>  'Avatar',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    public function insertEvent(){
        $cache = self::getCache();
        $cache->hDel(static::$tableName, 'user_id:' . $this->user_id);
    }

    public function updateEvent(){
        $cache = self::getCache();
        $cache->hDel(static::$tableName, 'user_id:' . $this->user_id);
    }

    public function deleteEvent(){
        $cache = self::getCache();
        $cache->hDel(static::$tableName, 'user_id:' . $this->user_id);
    }

    public static function get($userId, $tableName = '')
    {
        $cache = self::getCache();
        $tableName = $tableName?$tableName:static::tableName();
        $module = [];
        if(!$cache->hExists($tableName, 'user_id:' . $userId)){
            $module = self::find()->where(['user_id' => $userId])->asArray()->one();
            $module AND $cache->hSet($tableName,  'user_id:' . $userId, $module);
        }else{
            $module = $cache->hGet($tableName, 'user_id:' . $userId);
        }
        return $module;
    }

    public static function gets($userIds, $tableName = '')
    {
        $modules = array();
        $cache = self::getCache();
        $tableName = $tableName?$tableName:static::tableName();
        $isNeedRead = false;
        foreach ($userIds as $userId) {
            if (!$cache->hExists($tableName, 'user_id:' . $userId)) {
                $isNeedRead = true;
                $modules = array();
                break;
            } else {
                $tmp = $cache->hGet($tableName, 'user_id:' . $userId);
                $modules[$userId] = $tmp;
            }
        }

        if ($isNeedRead) {
            $key = implode(',', $userIds);
            $sql = "SELECT * FROM " . $tableName . " WHERE `user_id` IN (" . $key . ") ORDER BY field(user_id, " . $key . ")";
            $nueList = self::findBySql($sql)->asArray()->all();
//            $nueList = self::find()->where(['id' => $ids])->asArray()->all();

            foreach ($nueList as $module) {
                $cache->hSet($tableName, 'user_id:' . $module['user_id'], $module);
                $modules[$module['user_id']] = $module;
            }
        }

        return $modules;
    }
    /**
     * 用户注册接口
     */
    public function register($params)
    {
        $this->attributes = array_intersect_key($params, $this->attributes);
        $this->avatar = '/image/user/default_avatar.jpg';
        $this->create_time = time();
        return $this->save();
    }
    /**
     * 修改用户昵称
     */
    public static function changeNickName($userId, $nickName)
    {
        if (!$userId || !$nickName) {
            throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '参数错误');
        }
        if (!($user = self::findOne(['user_id' => $userId]))) {
            throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '用户不存在');
        }
        $user->nick_name = $nickName;
        $user->update_time = time();
        if (!$user->update()) {
            throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '昵称更新失败');
        }
        return true;
    }
}

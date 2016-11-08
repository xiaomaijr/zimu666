<?php

namespace front\models;

use common\models\ApiBaseException;
use common\models\ApiErrorDescs;
use common\models\RedisActiveRecord;
use Yii;

/**
 * This is the model class for table "user_address".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $user_name
 * @property string $mobile
 * @property string $call_number
 * @property integer $city_id
 * @property integer $district_id
 * @property integer $street_id
 * @property string $address
 * @property integer $is_default
 * @property integer $is_del
 * @property integer $create_time
 * @property integer $update_time
 */
class UserAddress extends RedisActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_address';
    }
    public static $tableName = 'user_address';
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'city_id', 'district_id', 'street_id', 'is_default', 'is_del', 'create_time', 'update_time'], 'integer'],
            [['user_name'], 'string', 'max' => 64],
            [['mobile', 'call_number'], 'string', 'max' => 32],
            [['address'], 'string', 'max' => 255],
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
            'user_name' => 'User Name',
            'mobile' => 'Mobile',
            'call_number' => 'Call Number',
            'city_id' => 'City ID',
            'district_id' => 'District ID',
            'street_id' => 'Street ID',
            'address' => 'Address',
            'is_default' => 'Is Default',
            'is_del' => 'Is Del',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    public function insertEvent(){
        $cache = self::getCache();
        $cache->hDel(static::$tableName, 'id:' . $this->id);
    }

    public function updateEvent(){
        $cache = self::getCache();
        $cache->hDel(static::$tableName, 'id:' . $this->id);
    }

    public function deleteEvent(){
        $cache = self::getCache();
        $cache->hDel(static::$tableName, 'id:' . $this->id);
    }

    /**
     * @param $id
     * 设置默认地址
     */
    public static function setDefault($userId, $id)
    {
        if (!self::find()->where(['user_id' => $userId])->exists()) {
            throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '用户未添加收货地址');
        }
        if (!($obj = self::findOne($id))) {
            throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '参数有误');
        }
        $transaction = \Yii::$app->getDb()->beginTransaction();
        try{
            if (false === self::updateAll(['is_default' => 2, 'update_time' => time() ], ['user_id' => $userId, 'is_default' => 1])) {
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '更新数据失败');
            }
            $obj->is_default = 1;
            $obj->update_time = time();
            if (!$obj->save()) {
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '添加默认地址失败');
            }
            $transaction->commit();
        }catch (ApiBaseException $e) {
            $transaction->rollBack();
            throw new ApiBaseException($e->getCode(), $e->getMessage());
        }
        return true;
    }

    /**
     * 添加收货地址
     * @param $userId
     * @param $params
     * @return bool
     * @throws ApiBaseException
     */
    public static function add($userId, $params)
    {
        if (!$userId || empty($params)) {
            throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '参数有误');
        }
        $isFirst = true;
        if (self::find()->where(['user_id' => $userId, 'is_del' => 0])->exists()) {
            $isFirst = false;
        }
        $obj = new self;
        $obj->attributes = $params;
        $obj->user_id = $userId;
        $obj->create_time = time();
        $obj->is_default = $isFirst ? 1 : 2;
        $obj->is_del = 0;
        if (!$obj->save()) {
            throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '添加地址失败');
        }
        return true;
    }

    /**
     * 修改地址
     * @param $id
     * @param $params
     * @return bool
     * @throws ApiBaseException
     */
    public static function modifyAddress($userId, $id, $params)
    {
        if (!$id || empty($params)) {
            throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '参数有误');
        }
        if (!($obj = self::find()->where(['id' => $id, 'user_id' => $userId])->one())) {
            throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '数据不存在');
        }
        $obj->attributes = $params;
        $obj->update_time = time();
        if (!$obj->save()) {
            throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '修改地址失败');
        }
        return true;
    }

    /**
     * 获取用户默认地址
     * @param $userId
     * @return array
     */
    public static function getDefault($userId)
    {
        $userId = intval($userId);
        if (!$userId) {
            return [];
        }
        return self::find()->where(['user_id' => $userId, 'is_default' => 1, 'is_del' => 0])->one();
    }

    /**
     * 获取用户除默认地址以外的地址列表
     * @param $userId
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getList($userId)
    {
        $userId = intval($userId);
        if (!$userId) {
            return [];
        }
        return self::getDataByConditions(['user_id' => $userId, 'is_del' => 0]);
    }
}

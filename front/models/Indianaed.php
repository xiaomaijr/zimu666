<?php

namespace front\models;

use common\models\ApiBaseException;
use common\models\ApiConfig;
use common\models\ApiErrorDescs;
use common\models\ApiUtils;
use common\models\RedisActiveRecord;
use Yii;

/**
 * This is the model class for table "indianaed".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $good_id
 * @property integer $indiana_good_id
 * @property string $luck_number
 * @property string $order_no
 * @property integer $order_id
 * @property integer $is_del
 * @property integer $create_time
 * @property integer $update_time
 */
class Indianaed extends RedisActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'indianaed';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'indiana_good_id', 'good_id', 'order_id', 'is_del', 'create_time', 'update_time'], 'integer'],
            [['order_id'], 'required'],
            [['luck_number'], 'string', 'max' => 32],
            [['order_no', 'issue'], 'string', 'max' => 64],
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
			'good_id' => 'Good ID',
            'indiana_good_id' => 'Indiana Good ID',
			'issue'	=>	'Issue',
            'luck_number' => 'Luck Number',
            'order_no' => 'Order No',
            'order_id' => 'Order ID',
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
     * 中奖列表
     */
    public static function getList($condition, $limit = 5, $offset = 1, $order = 'id desc', $needFormat = true)
    {
        $condition['is_del'] = ApiConfig::IS_DEL_NOT;
        $list = self::getDataByConditions($condition, $order, $limit, $offset);
        if (empty($list)) return $list;

        if (!$needFormat) {
            return $list;
        }
        $userIds = ApiUtils::getCols($list, 'user_id');
        $users = ApiUtils::getMap(UserInfo::gets(array_unique($userIds)), 'user_id');
        $goodIds = ApiUtils::getCols($list, 'good_id');
        $goodInfos = ApiUtils::getMap(Goods::gets($goodIds));
        $orderIds = ApiUtils::getCols($list, 'order_id');
        $orders = ApiUtils::getMap(Orders::gets($orderIds));
        foreach ($list as $record) {
            $tmp[] = self::format($record, $users, $goodInfos, $orders);
        }
        return $tmp;
    }
    /**
     * 格式化记录
     */
    protected static function format($record, $users = [], $goods = [], $orders = [])
    {
        return [
            'id' => ApiUtils::getIntParam('id', $record),
            'user' => $users[$record['user_id']],
            'good' => $goods[$record['good_id']],
			'issue' =>	ApiUtils::getStrParam('issue', $record),
            'indiana_good_id'   =>  ApiUtils::getIntParam('indiana_good_id', $record),
            'luck_number' => ApiUtils::getStrParam('luck_number', $record),
            'order_no' => ApiUtils::getStrParam('order_no', $record),
            'order' =>  $orders[$record['order_id']],
            'create_time' => ApiUtils::getStrTime($record['create_time']),
        ];
    }

    public static function getIndianaedOrderData($userId, $orderIds)
    {
        if (empty($orderIds)) return [];
        $indianaedList = self::getDataByConditions(['order_id' => $orderIds, 'user_id' => $userId, 'is_del' => ApiConfig::IS_DEL_NOT]);
        if (empty($indianaedList)) return $indianaedList;
        $userIds = ApiUtils::getCols($indianaedList, 'user_id');
        $userInfos = ApiUtils::getMap(UserInfo::gets(array_unique($userIds)), 'user_id');
        foreach($indianaedList as &$record) {
            $record['user'] = $userInfos[$record['user_id']];
            $record['create_time'] = date('Y-m-d H:i:s');
        }
        return ApiUtils::getMap($indianaedList, 'order_id');
    }

    /**
     * 更新中奖纪录
     * @param $userId
     * @param $id
     * @param $params
     * @return bool
     * @throws ApiBaseException
     * @throws \Exception
     */
    public static function updateRecord($userId, $id, $params) {
        if (!($order = self::find()->where(['id' => $id, 'user_id' => $userId, 'is_del' => 0])->one())) {
            throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '纪录不存在');
        }
        $order->attributes = $params;
        $order->update_time = time();
        if (!$order->update()) {
            throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '状态更新失败');
        }
        return true;
    }

    /**
     * 添加已揭晓纪录
     */
    public static function add($params)
    {
        $obj = new self;
        $obj->attributes = $params;
        $obj->is_del = ApiConfig::IS_DEL_NOT;
        $obj->create_time = time();
        return $obj->save();
    }
}

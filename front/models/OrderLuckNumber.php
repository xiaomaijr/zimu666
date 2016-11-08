<?php

namespace front\models;

use common\models\ApiUtils;
use common\models\RedisActiveRecord;
use Yii;

/**
 * This is the model class for table "order_luck_number".
 *
 * @property integer $id
 * @property string $order_no
 * @property integer $order_id
 * @property integer $user_id
 * @property string $luck_number
 * @property integer $good_id
 * @property integer $indiana_good_id
 * @property string $issue
 * @property integer $create_time
 * @property integer $update_time
 */
class OrderLuckNumber extends RedisActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_luck_number';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_no', 'order_id', 'user_id', 'luck_number'], 'required'],
            [['order_id', 'user_id', 'create_time', 'update_time', 'good_id', 'indiana_good_id'], 'integer'],
            [['order_no', 'luck_number'], 'string', 'max' => 32],
			[['issue'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_no' => 'Order No',
            'order_id' => 'Order ID',
            'user_id' => 'User ID',
            'luck_number' => 'Luck Number',
			'good_id'	=>	'Good ID',
            'indiana_good_id' => 'Indiana Good Id',
			'issue'	=>	'Issue',
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
     * 批量插入幸运号
     * @param $orders
     */
    public static function create($orders)
    {
        if (empty($orders)) return false;
        $indianaGoodIds = ApiUtils::getCols($orders, 'indiana_good_id');
        if (empty($indianaGoodIds)) return false;
        $indianaGoods = ApiUtils::getMap(IndianaGoods::getDataByConditions(['id' => array_unique($indianaGoodIds)]));
        $luckNums = self::getList(['indiana_good_id' => array_unique($indianaGoodIds)]);
        foreach ($orders as $order) {
            $existsLuckNums = !empty($luckNums) ? ApiUtils::getCols($luckNums, 'luck_number') : [];
            $indianaGoodLuckNums = range(10000001, 10000000 + $indianaGoods[$order['indiana_good_id']]['total_inputs']);
            $notExistsLuckNums = array_diff($indianaGoodLuckNums, $existsLuckNums);
            $orderLuckNums = array_rand($notExistsLuckNums, $order['num']);
            if (!is_array($orderLuckNums)) $orderLuckNums = (array)$orderLuckNums;
            $insertAttrs = [
                'order_no' => $order['order_no'],
                'order_id' => $order['id'],
                'user_id'  => $order['user_id'],
                'indiana_good_id' => $order['indiana_good_id'],
				'good_id'	=>	$order['good_id'],
				'issue'		=>	$order['good_issue'],
                'create_time' => time(),
            ];
            foreach ($orderLuckNums as $luckNumKey) {
                $insertAttrs['luck_number'] = strval($notExistsLuckNums[$luckNumKey]);
                $obj = new self;
                $obj->attributes = $insertAttrs;
                if (!$obj->save()) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * @param $condition
     * @param $page
     * @param $pageSize
     * @return array
     */
    public static function getList($condition, $page = 1, $pageSize = 1000)
    {
        $list = self::getDataByConditions($condition, 'id desc',$pageSize, $page);
        if (empty($list)) return [];
        foreach ($list as &$row) {
            $row = self::toApiArr($row);
        }
        return $list;
    }

    /**
     * @param $row
     * @return mixed
     */
    public static function toApiArr($row)
    {
        return $row;
    }

    /**
     * @param $luckNum
     * @return array|mixed
     */
    public static function getRewardOrder($indianaGoodId, $luckNum)
    {
        $info = self::find()->where(['indiana_good_id' => $indianaGoodId, 'luck_number' => $luckNum])
            ->asArray()->one();
        if (empty($info)) return [];
        return $info['order_id'];
    }
}

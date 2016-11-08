<?php

namespace front\models;

use common\models\ApiBaseException;
use common\models\ApiConfig;
use common\models\ApiErrorDescs;
use common\models\ApiUtils;
use common\models\RedisActiveRecord;
use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property integer $id
 * @property string $order_no
 * @property integer $category_id
 * @property integer $good_id
 * @property integer $indiana_good_id
 * @property string $good_issue
 * @property integer $user_id
 * @property integer $cart_id
 * @property integer $num
 * @property string $price
 * @property string $total_price
 * @property string $color
 * @property string $style
 * @property integer $status
 * @property integer $is_del
 * @property integer $ip
 * @property string $trade_no
 * @property string $reward_luck_number
 * @property integer $pay_type
 * @property integer $create_time
 * @property integer $update_time
 */
class Orders extends RedisActiveRecord
{
    const ORDER_STATUS_CREATE = 1;//订单创建
    const ORDER_STATUS_PAY = 2;//已支付
    const ORDER_STATUS_CANCEL = 3;//已取消
    const ORDER_STATUS_NOT_WINNING = 4;//未中奖
    const ORDER_STATUS_WINNING = 5;//已中奖
    const ORDER_STATUS_SHIP = 6;//已发货
    const ORDER_STATUS_CONFIRM_RECEIVE = 7;//已收货
    const ORDER_STATUS_RETURNS = 8;//退货申请
    const ORDER_STATUS_RETURNS_FINISH = 9;//已退货
    const ORDER_STATUS_DISPLAY = 10;//已晒单
    public static $statusMap = [
        1   =>  '创建',
        2   =>  '已支付',
        3   =>  '取消',
        4   =>  '未中奖',
        5   =>  '已中奖',
        6   =>  '已发货',
        7   =>  '已收货',
        8   =>  '申请退货',
        9   =>  '已退货',
        10  => '已晒单',
    ];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders';
    }
    /**
     * @var string
     */
    public static $tableName = 'orders';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'good_id', 'indiana_good_id', 'user_id', 'cart_id', 'num', 'status', 'is_del', 'pay_type', 'ip', 'create_time', 'update_time'], 'integer'],
            [['price', 'total_price'], 'number'],
            [['create_time'], 'required'],
            [['order_no', 'color', 'style', 'trade_no'], 'string', 'max' => 64],
            [['good_issue', 'reward_luck_number'], 'string', 'max' => 32],
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
            'category_id' => 'Category ID',
			'good_id'	=>	'Good ID',
            'indiana_good_id' => 'Indiana Good ID',
            'good_issue' => 'Good Issue',
            'user_id' => 'User ID',
            'cart_id' => 'Cart ID',
            'num' => 'Num',
            'price' => 'Price',
            'total_price' => 'Total Price',
            'color' => 'Color',
            'style' => 'Style',
            'status' => 'Status',
            'reward_luck_number' => 'Reward Luck Number',
            'is_del' => 'Is Del',
            'trade_no' => 'Trade No',
            'pay_type' => 'Pay Type',
            'ip' => 'Ip',
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
     * @param $condition
     * @param int $limit
     * @param int $offset
     * @param string $order
     * @param bool $needFormat
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getList($condition, $limit = 5, $offset = 1, $order = 'id desc', $needFormat = true)
    {
        $condition['is_del'] = 0;
        $list = self::getDataByConditions($condition, $order, $limit, $offset);
        if (empty($list) || !$needFormat) return $list;
        foreach($list as $record){
            $categoryIds[] = $record['category_id'];
            $userIds[] = $record['user_id'];
            $goodIds[] = $record['good_id'];
            $indianaGoodIds[] = $record['indiana_good_id'];
        }
        $categorys = ApiUtils::getMap(Category::gets(array_unique($categoryIds)), 'id');
        $user = ApiUtils::getMap(UserInfo::gets(array_unique($userIds)), 'user_id');
        $goods = ApiUtils::getMap(Goods::gets(array_unique($goodIds)));
        $indianaGoods = ApiUtils::getMap((IndianaGoods::gets(array_unique($indianaGoodIds))));
        foreach ($list as &$record) {
            $record = self::format($record, $user, $goods, $indianaGoods, $categorys);
        }
        return $list;
    }
    /**
     * 格式化记录
     */
    protected static function format($record, $user, $goods, $indianaGoods, $categorys)
    {
        return [
            'id' => ApiUtils::getIntParam('id', $record),
            'issue' => ApiUtils::getStrParam('good_issue', $record),
            'good'  =>  $goods[$record['good_id']],
            'user'  =>  $user[$record['user_id']],
            'category' => $categorys[$record['category_id']],
            'indiana_good' => $indianaGoods[$record['indiana_good_id']],
            'order_no' => ApiUtils::getStrParam('order_no', $record),
            'user_name' => $user[$record['user_id']]['account'],
            'user_avatar' => $user[$record['user_id']]['avatar'],
            'price' => round(ApiUtils::getIntParam('price', $record)/100, 2),
            'total_price' => round(ApiUtils::getIntParam('total_price', $record)/100, 2),
            'color' => ApiUtils::getStrParam('color', $record),
            'reward_luck_number' => ApiUtils::getStrParam('reward_luck_number', $record),
            'image' => ApiUtils::getStrParam('image', $record),
            'num' => ApiUtils::getIntParam('num', $record),
            'status' => self::$statusMap[$record['status']],
            'original_status' => $record['status'],
            'ip' => ApiUtils::formatIp($record['ip']),
            'is_del' => ApiUtils::getIntParam('is_del', $record),
            'create_time' => ApiUtils::getStrTime($record['create_time']),
        ];
    }
    
    public static function formatPartRecords($records)
    {
        $list = [];
        foreach ($records as $record) {
            $timer = current(explode(' ', $record['create_time']));
            $list[$timer][] = $record;
        }
        return $list;
    }

    /**
     * 订单创建
     * @param $userId
     * @param $shopCartIds
     * @return array
     * @throws ApiBaseException
     */
    public static function create($userId, $shopCartIds)
    {
        if (!($userObj = UserPassport::findOne($userId))) {
            throw new ApiBaseException(ApiErrorDescs::ERR_USER_NOT_EXISTS);
        }
        if (!$shopCartIds) {
            throw new ApiBaseException(ApiErrorDescs::ERR_SHOP_CART_ID_ERROR);
        }
        $shopCartIds = explode(',', $shopCartIds);
        $shopCartInfos = ApiUtils::getMap(ShoppingCart::getDataByConditions(['id' => $shopCartIds]));
        $totalPrice = array_sum(ApiUtils::getCols($shopCartInfos, 'total_price'));
        $userAccount = UserAccount::getByUserId($userId);
        if ($userAccount['recharge'] + $userAccount['gift'] < $totalPrice/100) {
            throw new ApiBaseException(ApiErrorDescs::ERR_ACCOUNT_INSUFFICIENT_AMOUNT);
        }
        $orders = [];
        $transaction = \Yii::$app->getDb()->beginTransaction();
        try{
            foreach ($shopCartIds as $cartId) {
                $orderObj = new self;
                $orderObj->user_id = $userId;
                $orderObj->order_no = ApiUtils::generateOrderNo(ApiConfig::BUSINESS_TYPE_SHOPPING, $userObj['account']);
                $orderObj->category_id = $shopCartInfos[$cartId]['category_id'];
				$orderObj->good_id = $shopCartInfos[$cartId]['good_id'];
                $orderObj->indiana_good_id = $shopCartInfos[$cartId]['indiana_good_id'];
                $orderObj->good_issue = $shopCartInfos[$cartId]['good_issue'];
                $orderObj->cart_id = $cartId;
                $orderObj->num = $shopCartInfos[$cartId]['num'];
                $orderObj->price = $shopCartInfos[$cartId]['price'];
                $orderObj->total_price = $shopCartInfos[$cartId]['total_price'];
                $orderObj->color = $shopCartInfos[$cartId]['color'];
                $orderObj->style = $shopCartInfos[$cartId]['style'];
                $orderObj->status = self::ORDER_STATUS_CREATE;
                $orderObj->is_del = 0;
                $orderObj->ip = ApiUtils::getClientIp();
                $orderObj->create_time = time();
                if (!$orderObj->save()) {
                    throw new ApiBaseException(ApiErrorDescs::ERR_ORDER_CREATE_FAILED);
                }
                $orders[] = $orderObj->toArray();
            }
            $ret = ShoppingCart::updateAll(['status' => ShoppingCart::STATUS_BUY, 'update_time' => time()], ['id' => $shopCartIds, 'status' => ShoppingCart::STATUS_CREATE]);
            if (!$ret) {
                throw new ApiBaseException(ApiErrorDescs::ERR_SHOP_CART_STATUS_UPDATE_FAILED);
            }
            $transaction->commit();
        }catch (ApiBaseException $e) {
            $transaction->rollback();
            throw new ApiBaseException($e->getCode());
        }

        return self::orderPay($userId, $orders);
    }
    public static function orderPay($userId, $orders) {
        $transaction = \Yii::$app->getDb()->beginTransaction();
        try{
            if (!$orders) {
                throw new ApiBaseException(ApiErrorDescs::ERR_ORDER_PAY_FAILED);
            }
            foreach ($orders as $order) {
                IndianaGoods::updateInvolvedNum($order['indiana_good_id'], $order['num'], 3);
                $userJournal = UserAccount::lockAccount($userId, $order['total_price']/100, 0, $order['order_no']);
                $ret = self::updateAll(['status' => self::ORDER_STATUS_PAY, 'trade_no' => $userJournal['id'], 'update_time' => time()],
                    ['id' => $order['id'], 'status' => self::ORDER_STATUS_CREATE]);
                if (!$ret) {
                    throw new ApiBaseException(ApiErrorDescs::ERR_ORDER_PAY_FAILED);
                }
            }
            if (!OrderLuckNumber::create($orders)) {
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '插入幸运号失败');
            }
            $transaction->commit();
        }catch(ApiBaseException $e) {
            $transaction->rollback();
            throw $e;
        }
        return true;
    }

    /**
     * 更新订单
     * @param $userId
     * @param $id
     * @param $params
     * @return bool
     * @throws ApiBaseException
     * @throws \Exception
     */
    public static function updateOrder($userId, $id, $params) {
        if (!($order = self::find()->where(['id' => $id, 'user_id' => $userId, 'is_del' => 0])->one())) {
            throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '订单不存在');
        }
        $order->attributes = $params;
        $order->update_time = time();
        if (!$order->update()) {
            throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '状态更新失败');
        }
        return true;
    }

    /**
     * @param $indianaGoodId
     * @return array|null|\yii\db\ActiveRecord
     * 获取中奖订单
     */
    public static function getRewardOrder($indianaGoodId)
    {
        $info = self::find()->where(['indiana_good_id' => $indianaGoodId, 'status' => self::ORDER_STATUS_WINNING])->asArray()->one();
        if (empty($info)) return [];
//        $luckNumbs = OrderLuckNumber::getDataByConditions(['order_no' => $info['order_no'], 'user_id' => $info['user_id']]);
    }

    /**
     * @param $userId
     * @param $orderId
     * @return bool
     * @throws ApiBaseException
     */
    public static function cancal($userId, $orderId)
    {
        $transaction = \Yii::$app->getDb()->beginTransaction();
        try{
            $info = self::findOne($orderId);
            if (empty($info) || $info['status'] != self::ORDER_STATUS_CREATE) {
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '数据有误！');
            }
            if (!self::updateAll(['status' => self::ORDER_STATUS_CANCEL, 'update_time' => time()], ['id' => $orderId, 'status' => self::ORDER_STATUS_CREATE, 'user_id' => $userId])) {
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '订单取消失败！');
            }
            if (!UserAccount::unlockMoney($userId, $info['total_price']/100, UserAccount::ACCOUNT_OPERATOR_TYPE_RETURNS, $info['order_no'])) {
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '冻结金额释放失败！');
            }
            $transaction->commit();
        } catch (ApiBaseException $e) {
            $transaction->rollBack();
            throw $e;
        }
        return true;
    }
    
    public static function orderSend($orderId)
    {
        $info = self::findOne($orderId);
        if (empty($info) || $info['status'] != self::ORDER_STATUS_WINNING) {
            throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '数据有误！');
        }
        if (!self::updateAll(['status' => self::ORDER_STATUS_SHIP, 'update_time' => time()], ['id' => $orderId, 'status' => self::ORDER_STATUS_WINNING])) {
            throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '订单发送失败！');
        }
        return true;
    }

    public static function confirmReceive($userId, $orderId)
    {
        $transaction = \Yii::$app->getDb()->beginTransaction();
        try{
            $info = self::findOne($orderId);
            if (!self::updateAll(['status' => self::ORDER_STATUS_CONFIRM_RECEIVE, 'update_time' => time()], ['id' => $orderId, 'user_id' => $userId, 'status' => self::ORDER_STATUS_SHIP])) {
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '操作失败！');
            }
            if (!UserAccount::unlockMoney($userId, $info['total_price']/100, UserAccount::ACCOUNT_OPERATOR_TYPE_CONFIRM_RECEIPT, $info['order_no'])) {
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '冻结金额释放失败！');
            }
            $transaction->commit();
        } catch (ApiBaseException $e) {
            $transaction->rollBack();
            throw $e;
        }
        return true;
    }

    public static function formatStatRecords($list)
    {
        if (empty($list)) return [];
        $userIds = ApiUtils::getCols($list, 'user_id');
        $users = ApiUtils::getMap(UserInfo::gets(array_unique($userIds)), 'user_id'); 
        foreach ($list as &$row) {
            $row['part_date'] = date('Y-m-d', $row['create_time']);
            $row['part_time'] = date('H:i:s', $row['create_time']) . ':' . rand(100, 999);
            $row['part_time_display'] = str_replace(':', '', $row['part_time']);
            $row['user'] = $users[$row['user_id']];
        }
        return array_chunk($list, floor(count($list)/2));
    }
}

<?php

namespace front\models;

use common\models\ApiBaseException;
use common\models\ApiConfig;
use common\models\ApiErrorDescs;
use common\models\ApiUtils;
use common\models\RedisActiveRecord;
use Yii;

/**
 * This is the model class for table "shopping_cart".
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $good_issue
 * @property integer $good_id
 * @property integer $indiana_good_id
 * @property integer $user_id
 * @property integer $num
 * @property integer $price
 * @property integer $total_price
 * @property string $color
 * @property string $style
 * @property integer $status
 * @property integer $is_del
 * @property integer $ip
 * @property integer $create_time
 * @property integer $update_time
 */
class ShoppingCart extends RedisActiveRecord
{
    const STATUS_CREATE = 1;
    const STATUS_BUY = 2;
    const STATUS_DELETE = 3;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shopping_cart';
    }
    public static $tableName = 'shopping_cart';
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'good_id', 'indiana_good_id', 'user_id', 'num', 'status', 'price', 'total_price', 'is_del', 'ip', 'create_time', 'update_time'], 'integer'],
            [['create_time'], 'required'],
            [['good_issue'], 'string', 'max' => 32],
            [['color', 'style'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Category ID',
			'good_id'	=>	'Good ID',
            'good_issue' => 'Good Issue',
            'indiana_good_id' => 'Indiana Good ID',
            'user_id' => 'User ID',
            'num' => 'Num',
            'price' => 'Price',
            'total_price' => 'Total Price',
            'color' => 'Color',
            'style' => 'Style',
            'status' => 'Status',
            'is_del' => 'Is Del',
            'ip' => 'Ip',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    public static $statusMap = [
        self::STATUS_CREATE     =>  '创建',
        self::STATUS_BUY        =>  '已生成订单',
        self::STATUS_DELETE     =>  '已删除',
    ];

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

    /**
     * 购物车列表
     * @param $userId
     * @param int $status
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public static function getList($userId, $status = 1, $page = 1, $pageSize = 20)
    {
        $list = self::getDataByConditions(['user_id' => $userId, 'status' => $status, 'is_del' => ApiConfig::IS_DEL_NOT], 'id desc', $pageSize, $page);
        if (!$list) return [];
        foreach ($list as $record) {
            $categoryIds[] = $record['category_id'];
            $goodIds[] = $record['good_id'];
        }
        $categoryInfos = ApiUtils::getMap(Category::gets(array_unique($categoryIds)), 'id');
        $goodInfos = ApiUtils::getMap(Goods::gets(array_unique($goodIds)), 'id');
        $total = 0;
        foreach ($list as &$item) {
            $total += $item['total_price'];
            $item['category'] = !empty($categoryInfos[$item['category_id']]) ? $categoryInfos[$item['category_id']] : [];
            $item['good'] = !empty($goodInfos[$item['good_id']]) ? $goodInfos[$item['good_id']] : [];
        }
        return ['list' => $list, 'totalPrice' => $total];
    }

    /**
     * 购物车商品删除
     * @param $id
     * @throws ApiBaseException
     */
    public static function deleteGood($userId, $id)
    {
        if (!($obj = self::find()->where(['id' => $id, 'user_id' => $userId])->one())) {
            throw new ApiBaseException(ApiErrorDescs::ERR_SHOP_CART_ID_ERROR);
        }
        if ($obj->status != self::STATUS_CREATE) {
            throw new ApiBaseException(ApiErrorDescs::ERR_SHOP_CART_STATUS_ERROR);
        }
        $obj->status = self::STATUS_DELETE;
        $obj->update_time = time();
        if (!$obj->save()) {
            throw new ApiBaseException(ApiErrorDescs::ERR_SHOP_CART_DELETE_FAILED);
        }
    }
    
    /**
     * 加入购物车
     */
    public static function addGood($userId, $params)
    {
        if (empty($params['indiana_good_id'])) {
            throw new ApiBaseException(ApiErrorDescs::ERR_SHOP_CART_GOOD_NOT_EXISTS);
        }
        $indianaGood = IndianaGoods::get($params['indiana_good_id']);
        if (empty($indianaGood)) {
            throw new ApiBaseException(ApiErrorDescs::ERR_SHOP_CART_GOOD_NOT_EXISTS);
        }
		$goodInfo = Goods::get($indianaGood['good_id']);
        if ($indianaGood['involved_num'] >= $indianaGood['total_inputs'] || $goodInfo['is_lattest'] != 0) {
            throw new ApiBaseException(ApiErrorDescs::ERR_INDIANA_GOOD_SOLD_OUT);
        }
        if (!($obj = self::findOne(['indiana_good_id' => $params['indiana_good_id'], 'status' => self::STATUS_CREATE, 'user_id' => $userId]))) {
            $obj = new self;
            $obj->attributes = $params;
			$obj->good_id = $indianaGood['good_id'];
            $obj->category_id = $goodInfo['category_id'];
            $obj->good_issue = $indianaGood['issue'];
            $obj->color = $goodInfo['color'];
            $obj->style = !empty($goodInfo['style']) ? $goodInfo['style'] : '';
            $obj->price = intval($goodInfo['min_price']);
            $obj->total_price = intval($goodInfo['min_price']) * intval($params['num']);
            $obj->user_id = $userId;
            $obj->is_del = ApiConfig::IS_DEL_NOT;
            $obj->status = self::STATUS_CREATE;
            $obj->ip = ApiUtils::getClientIp();
            $obj->create_time = time();
        } else {
            if ($indianaGood['involved_num'] + $obj->num + intval($params['num']) > $indianaGood['total_inputs']) {
                throw new ApiBaseException(ApiErrorDescs::ERR_INDIANA_GOOD_SOLD_OUT);
            }
            $obj->num += intval($params['num']);
            $obj->total_price += intval($goodInfo['min_price']) * intval($params['num']);
            $obj->update_time = time();
        }
        if (!$obj->save()) {
            throw new ApiBaseException(ApiErrorDescs::ERR_SHOP_CART_GOOD_ADD_FAILED);
        }
        return true;
    }
    /**
     * 修改购物车商品数量
     */
    public static function updateNum($userId, $id, $num)
    {
        if (!($obj = self::findOne($id))) {
            throw new ApiBaseException(ApiErrorDescs::ERR_SHOP_CART_GOOD_NOT_EXISTS);
        }
        if ($obj->user_id != $userId || $obj->status != self::STATUS_CREATE) {
            throw new ApiBaseException(ApiErrorDescs::ERR_SHOP_CART_GOOD_NOT_EXISTS);
        }
        $indianaGood = IndianaGoods::get($obj->indiana_good_id);
		$goodInfo = Goods::get($obj->good_id);
        if (!$indianaGood || $indianaGood['involved_num'] + intval($num) + $obj->num > $indianaGood['total_inputs'] || $goodInfo['is_lattest'] != 0) {
            throw new ApiBaseException(ApiErrorDescs::ERR_INDIANA_GOOD_SOLD_OUT);
        }
        $newNum = $obj->num + $num;
        if ($newNum < 0) {
            throw new ApiBaseException(ApiErrorDescs::ERR_INDIANA_GOOD_SOLD_OUT);
        }
        $obj->num = $newNum;
        $obj->total_price += $num * $obj->price;
        $obj->update_time = time();
        if (!$obj->save()) {
            throw new ApiBaseException(ApiErrorDescs::ERR_SHOP_CART_NUM_UPDATE_FAILED);
        }
        return true;
    }

    /**
     * @param $id
     * @return bool
     * 支付后状态更新
     */
    public static function orderUpdateStatus($id) {
        if (!($obj = self::findOne($id))) {
            return false;
        }
        $obj->status = self::STATUS_BUY;
        $obj->update_time = time();
        return $obj->save();
    }
}

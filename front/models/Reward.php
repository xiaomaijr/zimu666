<?php
/**
 * Created by PhpStorm.
 * User: 58
 * Date: 2016/10/6
 * Time: 16:51
 */
namespace front\models;
use common\models\ApiBaseException;
use common\models\ApiConfig;
use common\models\ApiErrorDescs;
use common\models\ApiUtils;

class Reward
{
    /**
     * 参与人数及幸运号
     */
    public $userLuckNumber = [];
    /**
     * 揭晓商品
     */
    public $indianaedGoods = [];

    public function __construct()
    {
        $this->getIndianaedGood();
        $this->getIndianaedOrders();
    }

    /**
     * 获取即将揭晓的商品
     */
    public function getIndianaedGood()
    {
        $condition = [
            'is_lattest' => 1,
            'end_time <= ' . time(),
        ];
        $goods = Goods::getDataByConditions($condition);
        if (!$goods) {
            return false;
        }
        $goodIds = array_unique(ApiUtils::getCols($goods, 'id'));
        $indianaedGoods = IndianaGoods::getDataByConditions(['good_id' => $goodIds, 'status' => IndianaGoods::STATUS_SOLD_OUT]);
        $this->indianaedGoods = $indianaedGoods;
    }

    /**
     * 获取即将揭晓奖品夺宝用户订单
     */
    public function getIndianaedOrders()
    {
        if (!$this->indianaedGoods) {
            return false;
        }
        $indianaGoodIds = array_unique(ApiUtils::getCols($this->indianaedGoods, 'id'));
        $totalTime = 0;
        foreach($indianaGoodIds as $goodId) {
            $orders = Orders::getDataByConditions(['indiana_good_id' => $goodId, 'status' => Orders::ORDER_STATUS_PAY], 'id desc', 50);
            if (empty($orders)) continue;
            foreach($orders as $order) {
                $totalTime += intval(date('His', $order['update_time']) . rand(100, 999));
            }
            $luckNum = $this->buildLuckNumber($totalTime, $goodId);
            $rewardOrderId = OrderLuckNumber::getRewardOrder($goodId, $luckNum);
            $rewardOrder = Orders::get($rewardOrderId);
            $indianaedAttrs = [
                'user_id' => $rewardOrder['user_id'],
                'indiana_good_id' => $rewardOrder['indiana_good_id'],
                'luck_number' => strval($luckNum),
                'order_no' => $rewardOrder['order_no'],
                'order_id' => $rewardOrderId,
                'good_id'  =>   $rewardOrder['good_id'],
                'issue'     =>  $rewardOrder['good_issue'],
            ];
            if (empty($rewardOrder)) continue;
            $transaction = \Yii::$app->getDb()->beginTransaction();
            try{
                if (!Indianaed::add($indianaedAttrs)){
                    throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '中奖纪录插入失败');
                }
                if (!Orders::updateAll(['status' => Orders::ORDER_STATUS_NOT_WINNING, 'update_time' => time()], ['indiana_good_id' => $goodId, 'status' => Orders::ORDER_STATUS_PAY])) {
                    throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '未中奖订单更新失败');
                }
                if (!Orders::updateAll(['status' => Orders::ORDER_STATUS_WINNING, 'update_time' => time(), 'reward_luck_number' => $luckNum], ['id' => $rewardOrderId, 'status' => Orders::ORDER_STATUS_NOT_WINNING])) {
                    throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '中奖订单更新失败');
                }
                $params = [
                    'status' => IndianaGoods::STATUS_FINISH,
                    'update_time' => time(),
                    'reward_user_id' => $rewardOrder['user_id'],
                    'reward_order_no' => $rewardOrder['order_no'],
                    'luck_number' => $luckNum,
                    'reward_time' => time()];
                if (!IndianaGoods::updateAll($params, ['id' => $goodId, 'status' => IndianaGoods::STATUS_SOLD_OUT])) {
                    throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '揭晓商品状态更新失败');
                }
                if (!Goods::updateAll(['is_lattest' => 0, 'update_time' => time()], ['id' => $rewardOrder['good_id'], 'is_lattest' => 1])){
                    throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '商品已揭晓状态更新失败');
                }
                $transaction->commit();
            }catch (ApiBaseException $e){
                $transaction->rollBack();
                echo $e->getMessage();exit;
                continue;
            }
        }
    }

    /**
     * 生成幸运号
     */
    public function buildLuckNumber($totalTime, $goodId)
    {
        foreach($this->indianaedGoods as $good) {
            if ($good['id'] == $goodId) {
                $goodInfo = $good;
                break;
            }
        }
        if (empty($goodInfo)) return false;
        $luckNum = $totalTime % $goodInfo['total_inputs'] + 10000001;
        return $luckNum;
    }
}
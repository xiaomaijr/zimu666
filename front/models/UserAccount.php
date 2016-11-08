<?php

namespace front\models;

use common\models\ApiBaseException;
use common\models\ApiErrorDescs;
use common\models\ApiUtils;
use Yii;

/**
 * This is the model class for table "user_account".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $recharge
 * @property integer $locked
 * @property integer $gift
 * @property integer $cashback
 * @property integer $is_del
 * @property integer $version
 * @property integer $create_time
 * @property integer $update_time
 */
class UserAccount extends \yii\db\ActiveRecord
{
    const ACCOUNT_OPERATOR_TYPE_RECHARGE = 1;//用户充值
    const ACCOUNT_OPERATOR_TYPE_LOCK = 2;//支付订单账户金额锁定
    const ACCOUNT_OPERATOR_TYPE_CONFIRM_RECEIPT = 3;//确认收货
    const ACCOUNT_OPERATOR_TYPE_RETURNS = 4;//退货

    public static $operatorTypeMap = [
        self::ACCOUNT_OPERATOR_TYPE_RECHARGE => '用户充值',
        self::ACCOUNT_OPERATOR_TYPE_LOCK => '支付订单账户金额锁定',
        self::ACCOUNT_OPERATOR_TYPE_CONFIRM_RECEIPT => '确认收货',
        self::ACCOUNT_OPERATOR_TYPE_RETURNS => '退货',
    ];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_account';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'recharge', 'gift', 'locked', 'cashback', 'is_del', 'version', 'create_time', 'update_time'], 'integer'],
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
            'recharge' => 'Recharge',
            'gift' => 'Gift',
            'locked' => 'Locked',
            'cashback' => 'Cashback',
            'is_del' => 'Is Del',
            'version' => 'Version',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }


    public static $paramList = ['id', 'user_id', 'recharge', 'gift', 'locked', 'cashback', 'version', 'is_del', 'update_time', 'create_time'];

    public static function getApiArray($arr)
    {
        return [
            'id' => ApiUtils::getIntParam('id', $arr),
            'user_id' => ApiUtils::getIntParam('user_id', $arr),
            'recharge' => round(ApiUtils::getIntParam('recharge', $arr)/100, 2),
            'gift' => round(ApiUtils::getIntParam('gift', $arr)/100, 2),
            'locked' => round(ApiUtils::getIntParam('locked', $arr)/100, 2),
            'cashback' => round(ApiUtils::getIntParam('cashback', $arr)/100, 2) ,
            'version' => ApiUtils::getIntParam('version', $arr),
            'is_del' => ApiUtils::getIntParam('is_del', $arr),
            'update_time' => date('Y-m-d, H:i:s', ApiUtils::getIntParam('update_time', $arr)) . '',
            'create_time' => date('Y-m-d, H:i:s', ApiUtils::getIntParam('create_time', $arr)) . '',
        ];
    }

    /*
    * 根据用户id获取用户基本信息
    */
    public static function getByUserId($userId)
    {
        if (empty($userId)) return [];
        $userInfo = self::find()->select(self::$paramList)->where(['user_id' => $userId])->asArray()->one();
        if (empty($userInfo)) {
            self::createUserCount($userId);
            return self::getByUserId($userId);
        } else {
            return self::getApiArray($userInfo);
        }
    }

    /*
     * 创建账户
     */
    public  static function createUserCount($userId, $recharge = 0, $gift = 0, $locked = 0){
        if (empty($userId)) return false;
        $userAccount = new UserAccount();
        $userAccount->user_id = $userId;
        $userAccount->recharge = ($recharge == null) ? 0 : $recharge;
        $userAccount->gift =  ($gift == null) ? 0 : $gift;
        $userAccount->locked = ($locked == null) ? 0 : $locked;
        $userAccount->cashback = 0;
        $userAccount->version = 1;
        $userAccount->create_time = time();
        return $userAccount->save();
    }

    /*
     * 用户充值
     */
    public static function rechargeNotify($userId, $fee, $outTradeNo, $tradeNo = '', $loopCount = 0)
    {
        if (defined('yii_debug')) {
            $fee = $fee * 100000;
        }

        $userAccount = self::getByUserId($userId);

        if (empty($userAccount)) {
            self::createUserCount($userId);
            $userAccount = self::getByUserId($userId);
//            throw new ApiBaseException(ApiErrorDescs::ERR_USER_NOT_EXIST,"账号不存在");
        }
        $recharge = isset($userAccount['recharge']) ? $userAccount['recharge'] : 0;
        $recharge = $recharge + $fee;
        $version = isset($userAccount['version']) ? $userAccount['version'] : 0;
        $newVersion = $version + 1;

        $r = self::updateAll(['recharge'=>$recharge,'version'=>$newVersion,'update_time'=>time()],['user_id'=>$userId,'version'=>$version]);
        if (1 > $r) {
            if ($loopCount < 1) {
                self::rechargeNotify($userId, $fee, $outTradeNo, $loopCount + 1);
                return;
            }
//            ApiUtils::logOwn(Logger::LEVEL_TRACE, __CLASS__, __FUNCTION__, ['code' => ApiErrorDescs::ERR_DB_UPDATE_DATA_ERROR, 'userId' => $userId, 'fee' => $fee]);
            throw new ApiBaseException(ApiErrorDescs::ERR_RECHARGE_FAILED);
        }

        $userJournal = new UserJournal();
        $userJournal->user_id = $userId;
        $userJournal->trade_no = $tradeNo;
        $userJournal->type = self::ACCOUNT_OPERATOR_TYPE_RECHARGE;
        $userJournal->out_trade_no = $outTradeNo . '';;
        $userJournal->cashback = 0;
        $userJournal->update_time = time();
        $userJournal->create_time = time();
        $userJournal->save();
        return $userJournal->toArray();
    }

    /**
     * 支付扣款
     * @param $userId
     * @param $recharge
     * @param $gift
     * @param int $reTry
     * @return array
     * @throws ApiBaseException
     */
    public static function lockAccount($userId, $recharge, $gift, $orderNo, $reTry = 3) {
        if (!($account = self::getByUserId($userId))) {
            throw new ApiBaseException(ApiErrorDescs::ERR_ACCOUNT_NOT_EXISTS);
        }
        $oldRecharge = $account['recharge'];
        $oldGift = $account['gift'];
        $oldVersion = $account['version'];
        $oldLocked = $account['locked'];
        $diffGift = $oldGift - $gift;
        $newGift = $diffGift >= 0 ? $diffGift : 0;
        if ($diffGift < 0) {
            $newRecharge = $oldRecharge + $diffGift - $recharge;
            $locked = abs($diffGift) + $recharge;
        } else {
            $newRecharge = $oldRecharge - $recharge;
            $locked = $recharge;
        }
        if ($newRecharge < 0) {
            throw new ApiBaseException(ApiErrorDescs::ERR_ACCOUNT_INSUFFICIENT_AMOUNT);
        }
        $ret = self::updateAll(['recharge' => $newRecharge*100, 'gift' => $newGift*100, 'locked' => ($oldLocked + $locked)*100, 'update_time' => time(),
            'version' => $oldVersion + 1], ['user_id' => $userId, 'version' => $oldVersion]);
        if (!$ret) {
            if ($reTry > 0) {
                return self::lockAccount($userId, $recharge, $gift, $orderNo, $reTry--);
            }
            throw new ApiBaseException(ApiBaseException::ERR_ACCOUNT_INSUFFICIENT_AMOUNT);
        }
        $nue = self::findOne($userId);

        $userJournal = new UserJournal();
        $userJournal->user_id = $userId;
        $userJournal->type = self::ACCOUNT_OPERATOR_TYPE_LOCK;
        $userJournal->trade_no = $orderNo;
        $userJournal->recharge = $nue['recharge'];
        $userJournal->cashback = $nue['cashback'];
        $userJournal->gift = $nue['gift'];
        $userJournal->locked = $locked;
        $userJournal->comment = '订单支付账户金额锁定';
        $userJournal->update_time = time();
        $userJournal->create_time = time();
        $userJournal->save();
        return $userJournal->toArray();
    }

    /**
     * 确认收货或申请退货后解冻锁定金额
     * @param $userId
     * @param $locked
     * @param $type
     * @param $orderNo
     * @param int $reTry
     * @return array
     * @throws ApiBaseException
     *
     */
    public static function unlockMoney($userId, $locked, $type, $orderNo, $reTry = 3)
    {
        if (!($account = self::getByUserId($userId))) {
            throw new ApiBaseException(ApiErrorDescs::ERR_ACCOUNT_NOT_EXISTS);
        }
        $oldRecharge = $account['recharge'];
        $oldVersion = $account['version'];
        $oldLocked = $account['locked'];
        if ($locked > $oldLocked) {
            throw new ApiBaseException(ApiErrorDescs::ERR_ACCOUNT_LOCKED_NOT_ENOUGH);
        }
        $attrs = ['locked' => ($oldLocked - $locked)*100, 'version' => $oldVersion + 1, 'update_time' => time()];
        if ($type == self::ACCOUNT_OPERATOR_TYPE_CONFIRM_RECEIPT) {
            $ret = self::updateAll($attrs, ['user_id' => $userId, 'version' => $oldVersion]);
        } elseif ($type == self::ACCOUNT_OPERATOR_TYPE_RETURNS) {
            $attrs['recharge'] = ($oldRecharge + $locked)*100;
            $ret = self::updateAll($attrs, ['user_id' => $userId, 'version' => $oldVersion]);
        }
        if (!$ret) {
            if ($reTry > 0) {
                return self::unlockMoney($userId, $locked, $type, $orderNo, $reTry--);
            }
            throw new ApiBaseException(ApiBaseException::ERR_ACCOUNT_INSUFFICIENT_AMOUNT);
        }
        $nue = self::findOne($userId);

        $userJournal = new UserJournal();
        $userJournal->user_id = $userId;
        $userJournal->type = $type;
        $userJournal->trade_no = $orderNo;
        $userJournal->recharge = $nue['recharge'];
        $userJournal->cashback = $nue['cashback'];
        $userJournal->gift = $nue['gift'];
        $userJournal->locked = $locked;
        $userJournal->comment = self::$operatorTypeMap[$type];
        $userJournal->update_time = time();
        $userJournal->create_time = time();
        $userJournal->save();
        return $userJournal->toArray();
    }
}

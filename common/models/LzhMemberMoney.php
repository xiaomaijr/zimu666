<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lzh_member_money".
 *
 * @property integer $id
 * @property string $uid
 * @property integer $platform
 * @property string $total_money
 * @property string $freeze_money
 * @property string $collect_money
 * @property string $charge_money
 * @property string $invest_money
 * @property string $back_money
 * @property string $withdraw_money
 * @property string $withdraw_freeze
 * @property string $credit_limit
 * @property string $credit_cuse
 * @property string $borrow_vouch_limit
 * @property string $borrow_vouch_cuse
 * @property string $invest_vouch_limit
 * @property string $invest_vouch_cuse
 */
class LzhMemberMoney extends RedisActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lzh_member_money';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'withdraw_freeze', 'credit_limit', 'credit_cuse', 'borrow_vouch_limit', 'borrow_vouch_cuse', 'invest_vouch_limit', 'invest_vouch_cuse'], 'required'],
            [['uid', 'platform'], 'integer'],
            [['total_money', 'freeze_money', 'collect_money', 'charge_money', 'invest_money', 'back_money', 'withdraw_money', 'withdraw_freeze', 'credit_limit', 'credit_cuse', 'borrow_vouch_limit', 'borrow_vouch_cuse', 'invest_vouch_limit', 'invest_vouch_cuse'], 'number'],
            [['uid', 'platform'], 'unique', 'targetAttribute' => ['uid', 'platform'], 'message' => 'The combination of Uid and Platform has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'platform' => 'Platform',
            'total_money' => 'Total Money',
            'freeze_money' => 'Freeze Money',
            'collect_money' => 'Collect Money',
            'charge_money' => 'Charge Money',
            'invest_money' => 'Invest Money',
            'back_money' => 'Back Money',
            'withdraw_money' => 'Withdraw Money',
            'withdraw_freeze' => 'Withdraw Freeze',
            'credit_limit' => 'Credit Limit',
            'credit_cuse' => 'Credit Cuse',
            'borrow_vouch_limit' => 'Borrow Vouch Limit',
            'borrow_vouch_cuse' => 'Borrow Vouch Cuse',
            'invest_vouch_limit' => 'Invest Vouch Limit',
            'invest_vouch_cuse' => 'Invest Vouch Cuse',
        ];
    }

    public function insertEvent(){
        $cache = self::getCache();
        $cache->delete(self::$tableName . ':' . $this->uid);
    }

    public function updateEvent(){
        $cache = self::getCache();
        $cache->delete(self::$tableName . ':' . $this->uid);
    }

    public function deleteEvent(){
        $cache = self::getCache();
        $cache->delete(self::$tableName . ':' . $this->uid);
    }
    /*
     *获取用户账户总额
     */
    public static function getUserMoney($memberId){
        $preMoney = self::getDataByID($memberId, 'uid');
        $money = [
            'invest_money' => ApiUtils::getFloatParam('invest_money', $preMoney),
            'back_money' => ApiUtils::getFloatParam('back_money', $preMoney),
            'available_money' => ApiUtils::getFloatParam('total_money', $preMoney),
            'expected_assets' => ApiUtils::getFloatParam('total_money', $preMoney) + ApiUtils::getFloatParam('freeze_money', $preMoney) +
                ApiUtils::getFloatParam('collect_money', $preMoney),
        ];
        return $money;
    }

    public static function get($uid, $tableName = ''){
        $cache = self::getCache();
        $tableName = !empty($tableName)?$tableName:self::tableName();
        $key = $tableName . ":" . $uid;
        if($cache->exists($key)){
            return $cache->get($key);
        }
        $info = self::getDataByID($uid, 'uid');
        if(!empty($info)){
            $cache->set($key, $info);
        }
        return $info;

    }
}

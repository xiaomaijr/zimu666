<?php

namespace common\models;

use common\models\ApiUtils;
use common\models\BaseModel;
use Yii;
use yii\redis\Cache;

/**
 * This is the model class for table "lzh_borrow_invest".
 *
 * @property string $id
 * @property integer $status
 * @property string $borrow_id
 * @property string $investor_uid
 * @property integer $borrow_uid
 * @property string $investor_capital
 * @property string $investor_interest
 * @property string $receive_capital
 * @property string $receive_interest
 * @property string $substitute_money
 * @property string $expired_money
 * @property string $invest_fee
 * @property string $paid_fee
 * @property string $add_time
 * @property integer $audit_time
 * @property integer $audit_notify
 * @property integer $repayment_time
 * @property string $deadline_last
 * @property integer $integral_days
 * @property string $reward_money
 * @property integer $debt_status
 * @property integer $debt_uid
 * @property string $loanno
 * @property string $borrow_fee
 * @property string $hongbao_id
 * @property integer $is_statics
 * @property integer $recommend_id
 */
class LzhBorrowInvest extends RedisActiveRecord
{
    const BORROW_AND_INVEST_TOTAL = 'borrow_invest_total';//投资收益总额
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lzh_borrow_invest';
    }

    public static $tableName = 'lzh_borrow_invest';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'borrow_id', 'investor_uid', 'borrow_uid', 'add_time', 'audit_time', 'audit_notify', 'repayment_time', 'deadline_last', 'integral_days', 'debt_status', 'debt_uid', 'hongbao_id', 'is_statics', 'recommend_id'], 'integer'],
            [['borrow_id', 'investor_uid', 'borrow_uid', 'investor_capital', 'investor_interest', 'receive_capital', 'receive_interest', 'substitute_money', 'expired_money', 'invest_fee', 'paid_fee', 'add_time', 'reward_money', 'debt_uid', 'loanno'], 'required'],
            [['investor_capital', 'investor_interest', 'receive_capital', 'receive_interest', 'substitute_money', 'expired_money', 'invest_fee', 'paid_fee', 'reward_money', 'borrow_fee'], 'number'],
            [['loanno'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'borrow_id' => 'Borrow ID',
            'investor_uid' => 'Investor Uid',
            'borrow_uid' => 'Borrow Uid',
            'investor_capital' => 'Investor Capital',
            'investor_interest' => 'Investor Interest',
            'receive_capital' => 'Receive Capital',
            'receive_interest' => 'Receive Interest',
            'substitute_money' => 'Substitute Money',
            'expired_money' => 'Expired Money',
            'invest_fee' => 'Invest Fee',
            'paid_fee' => 'Paid Fee',
            'add_time' => 'Add Time',
            'audit_time' => 'Audit Time',
            'audit_notify' => 'Audit Notify',
            'repayment_time' => 'Repayment Time',
            'deadline_last' => 'Deadline Last',
            'integral_days' => 'Integral Days',
            'reward_money' => 'Reward Money',
            'debt_status' => 'Debt Status',
            'debt_uid' => 'Debt Uid',
            'loanno' => 'Loanno',
            'borrow_fee' => 'Borrow Fee',
            'hongbao_id' => 'Hongbao ID',
            'is_statics' => 'Is Statics',
            'recommend_id' => 'Recommend ID',
        ];
    }
    /*
     * 查询投资总额和收益总额
     */
    public static function getBorrowAndInvestTotal(){
        $key = CacheKey::getCacheKey('', self::BORROW_AND_INVEST_TOTAL);
        $cache = new Cache();
        if($cache->exists($key['key_name'])){
            $info = $cache->get($key['key_name']);
        }else{
            $condition = "loanno != '\'\''";
            $info = self::find()
                ->select(['sum(investor_capital) as borrow_money', 'sum(investor_interest) as borrow_interest '])
                ->where($condition)
                ->asArray()
                ->one();
            $cache->set($key['key_name'], $info, $key['expire']);
        }
        return $info;
    }

    public function insertEvent(){
        $cache = self::getCache();
        $cache->delete(self::$tableName . ':' . $this->id);
    }

    public function updateEvent(){
        $cache = self::getCache();
        $cache->delete(self::$tableName . ':' . $this->id);
    }

    public function deleteEvent(){
        $cache = self::getCache();
        $cache->delete(self::$tableName . ':' . $this->id);
    }
}

<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lzh_borrow_investor_0".
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
 * @property string $bonus_id
 */
class BorrowInvestor extends BorrowInvest
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return self::$tableName;
    }

    public static $tableName = 'lzh_borrow_investor_0';
    //设置分表tablename
    public function setTableName($tableName){
        self::$tableName = $tableName;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'borrow_id', 'investor_uid', 'borrow_uid', 'investor_capital', 'investor_interest', 'invest_fee'], 'required'],
            [['id', 'status', 'borrow_id', 'investor_uid', 'borrow_uid', 'add_time', 'audit_time', 'audit_notify', 'repayment_time', 'deadline_last', 'integral_days', 'debt_status', 'debt_uid', 'hongbao_id', 'is_statics'], 'integer'],
            [['investor_capital', 'investor_interest', 'receive_capital', 'receive_interest', 'substitute_money', 'expired_money', 'invest_fee', 'paid_fee', 'reward_money', 'borrow_fee'], 'number'],
            [['loanno'], 'string', 'max' => 100],
            [['bonus_id'], 'string', 'max' => 4000],
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
            'bonus_id' => 'Bonus ID',
        ];
    }

    public function insertEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'id:' . $this->id);
        $cache->hDel(self::$tableName, 'investor_uid:' . $this->investor_uid);
        $cache->hDel(self::$tableName, 'borrow_id:' . $this->borrow_id);
    }

    public function updateEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'id:' . $this->id);
//        $cache->hDel(self::$tableName, 'investor_uid:' . $this->investor_uid);
//        $cache->hDel(self::$tableName, 'borrow_id:' . $this->borrow_id);
    }

    public function deleteEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'id:' . $this->id);
        $cache->hDel(self::$tableName, 'investor_uid:' . $this->investor_uid);
        $cache->hDel(self::$tableName, 'borrow_id:' . $this->borrow_id);
    }

    /*
     * 获取某个标投标总人数及投资总额
     */
    public function getInvestPersonAndMoneyTotal($borrowId){
        $data = ['c' => 0, 's' => 0];

        $infos = $this->_getBorrowInvestRecord($borrowId);
        $intorTotal = 0;
        foreach($infos as $row){
            if(empty($row['loanno'])) continue;
            $intorTotal += $row['investor_capital'];
            $data['c'] ++;
        }
        $data['s'] = $intorTotal;
        return $data;
    }
    /*
     * 获取投标记录
     */
    public function getInvestRecordByBid($borrowId){
        $data = [];
        $infos = $this->_getBorrowInvestRecord($borrowId);
        $investorUids = array_unique(ApiUtils::getCols($infos, 'investor_uid'));
        $userInfos = ApiUtils::getMap(Members::gets($investorUids), 'id');
        foreach($infos as $info){
            if(empty($info['loanno'])) continue;
            $tmp = self::toApiArr($info);
            $tmp['user_name'] = ApiUtils::replaceByLength($userInfos[$info['investor_uid']]['user_name'],4, 4, -4);
            $data[] = $tmp;
        }
        return $data;
    }
    /*
     * 获取标原始投资记录
     */
    private function _getBorrowInvestRecord($borrowId){
        $infos = [];
        $field = 'borrow_id:' . $borrowId;
        $cache = self::getCache();
        if($cache->hExists(self::$tableName, $field)){
            $ids = $cache->hGet(self::$tableName, $field);
            $infos = self::gets($ids);
        }else{
            $infos = self::getDataByConditions(['borrow_id' => $borrowId], 'id desc');
            if(empty($infos)) return $infos;
            $ids = ApiUtils::getCols($infos, 'id');
            $cache->hSet(self::$tableName, $field, $ids);
        }
        return $infos;
    }
    /*
     * api返回结构过滤字段
     */
    private static function toApiArr($arr){
        return [
            'add_time' => ApiUtils::getDateByUnix($arr['add_time']),
            'money' => ApiUtils::getFloatParam('investor_capital', $arr),
            'invest_type' => '手动',
        ];

    }
}

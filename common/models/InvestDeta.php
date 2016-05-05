<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lzh_investor_detail_0".
 *
 * @property string $id
 * @property string $repayment_time
 * @property integer $callback_time
 * @property string $borrow_id
 * @property string $invest_id
 * @property string $investor_uid
 * @property string $borrow_uid
 * @property string $capital
 * @property string $interest
 * @property string $interest_fee
 * @property integer $status
 * @property string $receive_interest
 * @property string $receive_capital
 * @property integer $sort_order
 * @property integer $total
 * @property string $deadline
 * @property string $expired_money
 * @property integer $expired_days
 * @property string $call_fee
 * @property string $substitute_money
 * @property string $substitute_time
 * @property integer $pay_status
 * @property integer $repay_status
 * @property string $ts
 * @property integer $add_time
 */
class InvestDeta extends RedisActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return self::$tableName;
    }
    //设置tablename
    public function setTableName($tableName){
        self::$tableName = $tableName;
    }

    public static $tableName = 'lzh_investor_detail_0';
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['repayment_time', 'callback_time', 'borrow_id', 'invest_id', 'investor_uid', 'borrow_uid', 'status', 'sort_order', 'total', 'deadline', 'expired_days', 'substitute_time', 'pay_status', 'repay_status', 'add_time'], 'integer'],
            [['borrow_id', 'invest_id', 'investor_uid', 'borrow_uid', 'capital', 'interest', 'interest_fee', 'status', 'sort_order', 'total'], 'required'],
            [['capital', 'interest', 'interest_fee', 'receive_interest', 'receive_capital', 'expired_money', 'call_fee', 'substitute_money'], 'number'],
            [['ts'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'repayment_time' => 'Repayment Time',
            'callback_time' => 'Callback Time',
            'borrow_id' => 'Borrow ID',
            'invest_id' => 'Invest ID',
            'investor_uid' => 'Investor Uid',
            'borrow_uid' => 'Borrow Uid',
            'capital' => 'Capital',
            'interest' => 'Interest',
            'interest_fee' => 'Interest Fee',
            'status' => 'Status',
            'receive_interest' => 'Receive Interest',
            'receive_capital' => 'Receive Capital',
            'sort_order' => 'Sort Order',
            'total' => 'Total',
            'deadline' => 'Deadline',
            'expired_money' => 'Expired Money',
            'expired_days' => 'Expired Days',
            'call_fee' => 'Call Fee',
            'substitute_money' => 'Substitute Money',
            'substitute_time' => 'Substitute Time',
            'pay_status' => 'Pay Status',
            'repay_status' => 'Repay Status',
            'ts' => 'Ts',
            'add_time' => 'Add Time',
        ];
    }
    public function insertEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName,  'id:' . $this->id);
    }

    public function updateEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName,  'id:' . $this->id);
    }

    public function deleteEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName,  'id:' . $this->id);
    }
    /*
     * 添加新投资记录到分表
     */
    public function add($attrs = []){
        if(empty($attrs)){
            throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '投资还款信息不能为空');
        }
        $obj = clone $this;
        $obj->attributes = $attrs;
        $obj->add_time = time();
        $ret = $obj->save();
        if(!$ret){
            throw new ApiBaseException(ApiErrorDescs::ERR_INVEST_DETAIL_ADD_FAIL);
        }
        return $obj->id;
    }
    /*
     * 获取用户待收本金
     */
    public function getUserCollectMoney($uid){
        $collectMoney = 0;
        $uid = intval($uid);
        $data = $this->_getUserRepayRecords($uid);
        if(!$data) return $collectMoney;
        foreach($data as $row){
            if(($row['status'] == 0 || $row['status'] == 6 || $row['status'] == 7) && $row['pay_status'] == 1)
            $collectMoney += $row['interest'] + $row['capital'];
        }
        return $collectMoney;
    }
    /*
     * 获取用户还款原始记录
     */
    private function _getUserRepayRecords($uid){
        $uid = intval($uid);
        $cache = self::getCache();
        $filed = 'investor_uid:' . $uid;
        if(!$cache->hExists(self::$tableName, $filed)){
            $records = self::getDataByConditions(['investor_uid' => $uid], 'id desc', 0, 0);
            if(!$records) return $records;
            $ids = ApiUtils::getCols($records, 'id');
            $cache->hSet(self::$tableName, $filed, $ids);
        }else{
            $ids = $cache->hGet(self::$tableName, $filed);
            $records = self::gets($ids);
        }
        return $records;
    }
}

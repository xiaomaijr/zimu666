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
class LzhInvestDeta extends RedisActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lzh_investor_detail_0';
    }
    //设置分表tablename
    public function setSubTableName($tableName){
        $this->subTableName = $tableName;
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['repayment_time', 'callback_time', 'borrow_id', 'invest_id', 'investor_uid', 'borrow_uid', 'status', 'sort_order', 'total', 'deadline', 'expired_days', 'substitute_time', 'pay_status', 'repay_status', 'add_time'], 'integer'],
            [['borrow_id', 'invest_id', 'investor_uid', 'borrow_uid', 'capital', 'interest', 'interest_fee', 'status', 'receive_interest', 'receive_capital', 'sort_order', 'total', 'deadline', 'expired_money', 'substitute_money'], 'required'],
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
    /*
     * 添加新投资记录到总表
     */
//    public static function add($attrs = []){
//        if(empty($attrs)){
//            throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '投资还款信息不能为空');
//        }
//        $obj = new self;
//        $obj->attributes = $attrs;
//        $ret = $obj->save();
//        if(!$ret){
//            throw new ApiBaseException(ApiErrorDescs::ERR_INVEST_DETAIL_ADD_FAIL);
//        }
//        return $obj->id;
//    }
    /*
     * 添加新投资记录到分表
     */
    public function addSubTable($attrs = []){
        if(empty($attrs)){
            throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '投资还款信息不能为空');
        }
        $sql = "insert into " . $this->subTableName . " " . array_keys($attrs) . " valaues (" . array_values($attrs) . ")";
        $db = $this->getDb();
        $ret = $db->createCommand($sql)->execute();
        if(!$ret){
            throw new ApiBaseException(ApiErrorDescs::ERR_INVEST_DETAIL_ADD_FAIL);
        }
    }
}

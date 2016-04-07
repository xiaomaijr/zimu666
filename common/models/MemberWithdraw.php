<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lzh_member_withdraw".
 *
 * @property string $id
 * @property string $uid
 * @property integer $platform
 * @property string $withdraw_money
 * @property integer $withdraw_status
 * @property string $withdraw_fee
 * @property string $add_time
 * @property string $add_ip
 * @property string $deal_time
 * @property string $deal_user
 * @property string $deal_info
 * @property string $first_fee
 * @property string $second_fee
 * @property string $success_money
 * @property string $loanno
 * @property string $feeMode
 * @property string $withdrawType
 * @property string $bankCardNo
 * @property string $bank
 * @property integer $rearchFortyPercent
 * @property integer $notify_time
 * @property integer $is_rollback
 * @property integer $rollback_time
 * @property string $rollback_loanno
 */
class MemberWithdraw extends BaseModel
{
    const WITHDRAW_STATUS_FAILED  = 0; //提现失败
    const WITHDRAW_STATUS_SUCCESS = 1; //提现成功
    const WITHDRAW_STATUS_SUBMIT  = 2; //已提交
    const WITHDRAW_STATUS_RETURNED= 3; //提现金额被退回

    const WITHDRAW_DAY_MAX_TIMES  = 3;  //单日最大提现次数
    const WITHDRAW_DAY_MAX_MONNEY =  50000;//单日最大提现金额
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lzh_member_withdraw';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'withdraw_money', 'withdraw_status', 'withdraw_fee', 'add_time', 'add_ip', 'second_fee', 'success_money',], 'required'],
            [['uid', 'platform', 'withdraw_status', 'add_time', 'deal_time', 'rearchFortyPercent', 'notify_time', 'is_rollback', 'rollback_time'], 'integer'],
            [['withdraw_money', 'withdraw_fee', 'first_fee', 'second_fee', 'success_money'], 'number'],
            [['add_ip', 'feeMode', 'withdrawType', 'bank'], 'string', 'max' => 16],
            [['deal_user', 'loanno', 'rollback_loanno'], 'string', 'max' => 50],
            [['deal_info'], 'string', 'max' => 200],
            [['bankCardNo'], 'string', 'max' => 24],
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
            'withdraw_money' => 'Withdraw Money',
            'withdraw_status' => 'Withdraw Status',
            'withdraw_fee' => 'Withdraw Fee',
            'add_time' => 'Add Time',
            'add_ip' => 'Add Ip',
            'deal_time' => 'Deal Time',
            'deal_user' => 'Deal User',
            'deal_info' => 'Deal Info',
            'first_fee' => 'First Fee',
            'second_fee' => 'Second Fee',
            'success_money' => 'Success Money',
            'loanno' => 'Loanno',
            'feeMode' => 'Fee Mode',
            'withdrawType' => 'Withdraw Type',
            'bankCardNo' => 'Bank Card No',
            'bank' => 'Bank',
            'rearchFortyPercent' => 'Rearch Forty Percent',
            'notify_time' => 'Notify Time',
            'is_rollback' => 'Is Rollback',
            'rollback_time' => 'Rollback Time',
            'rollback_loanno' => 'Rollback Loanno',
        ];
    }

    public static function getUserDayWithDraw($uid,$time=0){
        if (empty($time)) {
            $time = time();
        }
        $count = $investChargeScale = 0;
        $date = date('Y-m-d', $time);
        $startTime = strtotime($date . ' 00:00:00');
        $endTime = strtotime($date . ' 23:59:59');
        $condition = [
            'add_time between ' . $startTime . ' and ' . $endTime,
            'loanno != ""',
            'uid' => $uid,
            'withdraw_status' => self::WITHDRAW_STATUS_SUCCESS
        ];
        $count = self::getCountByCondition($condition);
        $total = self::getDataByConditions($condition, 'id desc', 0, 0, 'sum(withdraw_money) as s');
        $total = empty($total)?0:$total[0]['s'];

        //累计投资金额
        $totalInvestAmount =  BorrowInvest::getInvestTotal($uid);
        //累计充值金额
        $totalChargeAmount = MemberPayonline::getUserTotal($uid);

        $investChargeScale = empty($totalChargeAmount)?0:round($totalInvestAmount / $totalChargeAmount, 1);
        return ['count' => $count, 'total' => $total, 'scale' => $investChargeScale];
    }

    /*
     * 添加提现记录
     */
    public function add($attrs, $param = []){
        $attributes = [
            'platform' => 0,
            'withdraw_status'=> MemberWithdraw::WITHDRAW_STATUS_SUBMIT,
            'add_time' => time(),
            'add_ip'   => ApiUtils::get_client_ip(),
            'second_fee' => 0,
            'success_money' => 0,
            'rearchFortyPercent' =>1,
        ];
        $this->attributes = array_merge($attributes, $attrs);
        if(!$this->save()){
            throw new ApiBaseException(ApiErrorDescs::ERR_WITHDRAW_ADD_FAIL);
        }
        return $this->id;
    }
}

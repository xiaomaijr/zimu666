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
class BorrowInvest extends RedisActiveRecord
{

    const BORROW_AND_INVEST_TOTAL = 'borrow_invest_total';//投资收益总额
    const CACHE_KEY_USER_TOTAL_INCODE = 'user_total_income'; //用户累计收益缓存key
    const CACHE_KEY_BORROW_INVESTOR_AND_MONEY_TOTAL = 'brw_intor_a_mny_ttl'; //
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lzh_borrow_invest';
    }

    public static $tableName = 'lzh_borrow_invest';

    public function setTableName($tableName){
        self::$tableName = $tableName;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'borrow_id', 'investor_uid', 'borrow_uid', 'add_time', 'audit_time', 'audit_notify', 'repayment_time', 'deadline_last', 'integral_days', 'debt_status', 'debt_uid', 'hongbao_id', 'is_statics', 'recommend_id'], 'integer'],
            [['borrow_id', 'investor_uid', 'borrow_uid', 'investor_capital', 'investor_interest', 'invest_fee', 'add_time'], 'required'],
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
        $cache->hDel(self::$tableName, 'id:' . $this->id);
        $cache->hDel(self::$tableName, 'investor_uid:' . $this->investor_uid);
    }

    public function updateEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'id:' . $this->id);
        $cache->hDel(self::$tableName, 'investor_uid:' . $this->investor_uid);
    }

    public function deleteEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'id:' . $this->id);
        $cache->hDel(self::$tableName, 'investor_uid:' . $this->investor_uid);
    }

    /*
     * 查询用户总收益
     */
    public static function getTotalIncomeByInvestId($investUid){
        $income = 0;
        $cache = self::getCache();
        if($cache->hExists(self::$tableName, 'investor_uid:' . $investUid)){
            $ids = $cache->hget(self::$tableName, 'investor_uid:' . $investUid);
            $incomeInfos = self::gets($ids);
        }else{
            $incomeInfos = self::getDataByConditions(['investor_uid' => intval($investUid), "loanno != ''"], null, 0, 0, ['id', 'borrow_id', 'investor_interest', 'add_time', 'integral_days']);
            if(empty($incomeInfos)) return $income;
            $ids = ApiUtils::getCols($incomeInfos, 'id');
            $cache->hset(self::$tableName, 'investor_uid:' . $investUid, $ids);
        }
        $tmp = [];
        foreach($incomeInfos as $info){
            $diffDay = ApiUtils::getDiffDay($info['add_time'], time());
            if($diffDay > $info['integral_days']){
                $tmp[] = $info['investor_interest'];
                continue;
            }
            $tmp[] = $info['investor_interest']/$info['integral_days'] * $diffDay;
        }
        $income = sprintf("%.02f", array_sum($tmp));
        return $income;
    }
    /*
     * 添加新投资记录
     */
    public function add($attrs = []){
        if(empty($attrs)){
            throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '投资信息不能为空');
        }
        $this->attributes = $attrs;
        $this->add_time = time();
        $ret = $this->save();
        if(!$ret){
            throw new ApiBaseException(ApiErrorDescs::ERR_INVEST_RECORD_ADD_FAIL);
        }
        return $this->id;
    }
}

<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lzh_member_payonline".
 *
 * @property string $id
 * @property string $uid
 * @property integer $platform
 * @property integer $source
 * @property string $money
 * @property string $fee
 * @property string $way
 * @property integer $status
 * @property string $add_time
 * @property string $add_ip
 * @property string $loan_no
 * @property string $order_no
 * @property integer $is_notify
 * @property string $deal_user
 * @property integer $deal_uid
 * @property string $payimg
 */
class MemberPayonline extends RedisActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lzh_member_payonline';
    }

    public static $tableName = 'lzh_member_payonline';
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'money', 'add_time', 'add_ip'], 'required'],
            [['uid', 'platform', 'source', 'status', 'add_time', 'is_notify', 'deal_uid'], 'integer'],
            [['money', 'fee'], 'number'],
            [['way'], 'string', 'max' => 20],
            [['add_ip'], 'string', 'max' => 16],
            [['loan_no', 'order_no'], 'string', 'max' => 50],
            [['deal_user'], 'string', 'max' => 40],
            [['payimg'], 'string', 'max' => 1000],
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
            'source' => 'Source',
            'money' => 'Money',
            'fee' => 'Fee',
            'way' => 'Way',
            'status' => 'Status',
            'add_time' => 'Add Time',
            'add_ip' => 'Add Ip',
            'loan_no' => 'Loan No',
            'order_no' => 'Order No',
            'is_notify' => 'Is Notify',
            'deal_user' => 'Deal User',
            'deal_uid' => 'Deal Uid',
            'payimg' => 'Payimg',
        ];
    }

    public function insertEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'id:' . $this->id);
        $cache->hDel(self::$tableName, 'uid:' . $this->uid);
    }

    public function updateEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'id:' . $this->id);
        $cache->hDel(self::$tableName, 'uid:' . $this->uid);
    }

    public function deleteEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'id:' . $this->id);
        $cache->hDel(self::$tableName, 'uid:' . $this->uid);
    }

    /*
     * 添加充值记录
     */
    public function addRecord($uid, $money, $platform = 0, $source = 1){
        $this->uid = $uid;
        $this->money = $money;
        $this->platform = $platform;
        $this->add_time = time();
        $this->add_ip = ApiUtils::get_client_ip();
        $this->source = $source;
        $ret = $this->save();
        if(!$ret){
            throw new ApiBaseException(ApiErrorDescs::ERR_RECHARGE_ADD_ORDER_FAIL);
        }
        return $this->id;
    }
    /*
     * 更新回调状态
     */
    public function updateNotifyStatus(){
        if($this->is_notify == 0){
            $this->is_notify = 1;
            return $this->update();
        }
    }
    /*
     * 获取用户提现记录
     * @param $uid int
     * return array
     */
    private static function _getUserInfos($uid){
        $cache = self::getCache();
        if($cache->hExists(self::$tableName, 'uid:' . $uid)){
            $ids = $cache->hget(self::$tableName, 'uid:' . $uid);
            $infos = self::gets($ids);
        }else{
            $infos = self::getDataByConditions(['uid' => intval($uid), 'status' => 1], null, 0, 0);
            if(empty($infos)) return $infos;
            $ids = ApiUtils::getCols($infos, 'id');
            $cache->hset(self::$tableName, 'uid:' . $uid, $ids);
        }
        return $infos;
    }
    /*
     * 获取用户提现总额
     */
    public static function getUserTotal($uid){
        $total = 0;
        $infos = self::_getUserInfos($uid);
        if(empty($infos)) return $total;
        foreach($infos as $info){
            $total =+ $info['money'];
        }
        return $total;
    }
}

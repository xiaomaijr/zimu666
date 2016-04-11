<?php

namespace common\models;

use Yii;
use yii\redis\Cache;

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
class MemberMoney extends RedisActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lzh_member_money';
    }

    public static $tableName = 'lzh_member_money';
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
     *获取用户账户总额
     */
    public static function getUserMoney($memberId, $platform = 'qdd'){
        $data = [
            'qdd' => [
                'invest_money' => 0.00,
                'back_money' => 0.00,
                'available_money' => 0.00,
                'expected_assets' => 0.00,
            ],
        ];
        $infos = self::_getUserMoney($memberId);
        foreach($infos as $info){
            if($info['platform'] == 0){
                $data['qdd'] = [
                    'invest_money' => ApiUtils::getFloatParam('invest_money', $info),
                    'back_money' => ApiUtils::getFloatParam('back_money', $info),
                    'available_money' => ApiUtils::getFloatParam('total_money', $info),
                    'expected_assets' => ApiUtils::getFloatParam('total_money', $info) + ApiUtils::getFloatParam('freeze_money', $info) +
                        ApiUtils::getFloatParam('collect_money', $info),
                ];
            }else{
                $data['yee'] = [];
            }
        }
        $money = $data[$platform];
        return $money;
    }
    /*
     * 获取用户相应平台资金池
     * @param $uid int
     * @param $platform int default 0 qdd, 1 yee
     * return array
     */
    public static function getUserPlatformMoney($uid, $platform = 0){
        $moneyAccount = self::_getUserMoney($uid);
        if(!$moneyAccount){
            return [];
        }
        foreach($moneyAccount as $record){
            if($record['platform'] == $platform){
                return $record;
            }
        }
        return [];
    }
    /*
     * 获取用户账户信息
     */
    private static function _getUserMoney($memberId){
        $cache = self::getCache();
        $field = 'uid:' . $memberId;
        if($cache->hExists(self::$tableName, $field)){
            $ids = $cache->hGet(self::$tableName, $field);
            $infos = self::gets($ids);
        }else{
            $infos = self::getDataByConditions(['uid' => $memberId]);
            if(empty($infos)) return $infos;
            $ids = ApiUtils::getCols($infos, 'id');
            $cache->hSet(self::$tableName, $field, $ids);
        }
        return $infos;
    }
    /*
     * 添加新纪录
     */
    public function add($attrs, $params = []){
        $attributes = [
            'uid' => ApiUtils::getIntParam('uid', $params),
            'platform' => ApiUtils::getIntParam('platform', $params),
            'withdraw_freeze' => ApiUtils::getFloatParam('withdraw_freeze', $params),
            'credit_limit' => ApiUtils::getFloatParam('credit_limit', $params),
            'credit_cuse' => ApiUtils::getFloatParam('credit_cuse', $params),
            'borrow_vouch_limit' => ApiUtils::getFloatParam('borrow_vouch_limit', $params),
            'borrow_vouch_cuse' => ApiUtils::getFloatParam('borrow_vouch_cuse', $params),
            'invest_vouch_limit' => ApiUtils::getFloatParam('invest_vouch_limit', $params),
            'invest_vouch_cuse' => ApiUtils::getFloatParam('invest_vouch_cuse', $params),
        ];
        $this->attributes = array_merge($attributes, $attrs);
        $ret = $this->save();
        if(!$ret){
            throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '资金账户初始化失败');
        }
        return $this->id;
    }
    /**
     * @param $uid
     * @param $type
     * @param $realMoney
     * @param $info
     *
     */
    public function setUserWithdrawMoneyInfo($uid, $realMoney,$info='用户提现'){
        if(empty($uid) || empty($realMoney)){
            return false;
        }
        $MM = self::getUserPlatformMoney($uid);
        if(empty($MM) || !is_array($MM)){
            $mmoneyId = $this->add(['uid'=>$uid,'platform'=>0]);
            $MM = self::getUserPlatformMoney($uid);
        }
        $tableEnd = intval($uid%10);
        $Moneylog = new MemberMoneylog(['tableName' => 'member_moneylog_'.$tableEnd]);
        $money = [
            'total_money'    => $MM['total_money']-$realMoney,
            'withdraw_money' => $MM['withdraw_money']+$realMoney,
        ];
        $moneylog = [
            'uid'      =>  $uid,
            'platform' =>  0,
            'type'     =>  MemberMoneylog::WITHDRAW_MONEY_SUCCESS,

            'affect_money'  => 0-$realMoney,
            'affect_type'   => MemberMoneylog::AFFECT_WITHDRAW_OK,
            'affect_before' => $MM['total_money'],

            'total_money'   => $MM['total_money']-$realMoney,
            'charge_money'  => $MM['charge_money'],
            'invest_money'  => $MM['invest_money'],
            'withdraw_money'=> $MM['withdraw_money']+$realMoney,
            'back_money'    => $MM['back_money'],
            'collect_money' => $MM['collect_money'],
            'freeze_money'  => $MM['freeze_money'],

            'info' => $info,
            'add_time' => time(),
            'add_ip' => ApiUtils::get_client_ip(),
            'target_uid' => 0,
            'target_uname' => '在线提现',
        ];
        MemberMoney::updateAll($money, ['id' => $mmoneyId]);
        $Moneylog->add($moneylog);
        return true;
    }


    /**
     * @param $uid
     * @param $type
     * @param $realMoney
     * @param $info
     *
     */
    public function setUserWithdrawRollMoneyInfo($uid, $realMoney,$info='用户提现退回'){
        if(empty($uid) || empty($realMoney)){
            return false;
        }
        $MM = self::getUserPlatformMoney($uid);
        if(empty($MM) || !is_array($MM)){
            $mmoneyId = $this->add(['uid'=>$uid,'platform'=>0]);
            $MM = self::getUserPlatformMoney($uid);
        }
        $tableEnd = intval($uid%10);
        $Moneylog = new MemberMoneylog(['tableName' => 'member_moneylog_'.$tableEnd]);
        $money = [
            'total_money'    => $MM['total_money']+$realMoney,
            'withdraw_money' => $MM['withdraw_money']-$realMoney,
        ];
        $moneylog = [
            'uid'      =>  $uid,
            'platform' =>  0,
            'type'     =>  MemberMoneylog::WITHDRAW_MONEY_ROLLBACK,

            'affect_money'  => $realMoney,
            'affect_type'   => 5,
            'affect_before' =>  MemberMoneylog::AFFECT_WITHDRAW_NO,

            'total_money'   => $MM['total_money']+$realMoney,
            'charge_money'  => $MM['charge_money'],
            'invest_money'  => $MM['invest_money'],
            'withdraw_money'=> $MM['withdraw_money']-$realMoney,
            'back_money'    => $MM['back_money'],
            'collect_money' => $MM['collect_money'],
            'freeze_money'  => $MM['freeze_money'],

            'info' => $info,
            'add_time' => time(),
            'add_ip' => ApiUtils::get_client_ip(),
            'target_uid' => 0,
            'target_uname' => '在线提现退回',
        ];
        MemberMoney::updateAll($money, ['id' => $mmoneyId]);
        $Moneylog->add($moneylog);
        return true;
    }
    /*
     * @param $uid
     * @param $type
     * @param $realMoney
     * @param $info
     */
    public function setUserChargeMoneyInfo($uid, $realMoney,$info='用户充值',$userType=0,$fee=0){
        if(empty($uid) || empty($realMoney)){
            return false;
        }
        $MM = self::getUserPlatformMoney($uid);
        if(empty($MM) || !is_array($MM)){
            $mmoneyId = $this->add(['uid'=>$uid,'platform'=>0]);
            $MM = self::getUserPlatformMoney($uid);
        }
        $tableEnd = intval($uid%10);
        $Moneylog = new MemberMoneylog(['tableName' => 'member_moneylog_'.$tableEnd]);
        $money = [
            'total_money'    => $MM['total_money']+$realMoney,
            'charge_money' => $MM['charge_money']+$realMoney,
        ];
        if($userType){
            $info = '您通过企业网银充值：￥'.($realMoney+$fee).'，其中扣除充值手续费:￥'.$fee.'，实际到账金额:￥'.$realMoney;
            $chargeType = MemberMoneylog::ENTERPRISE_MONEY_CHARGE;
        }else{
            $info = '个人网银充值-'.$realMoney;
            $chargeType = MemberMoneylog::PERSON_MONEY_CHARGE;
        }
        $moneylog = array(
            'uid'      =>  $uid,
            'platform' =>  0,
            'type'     =>  $chargeType,

            'affect_money'  => $realMoney,
            'affect_type'   => MemberMoneylog::AFFECT_CHARGE,
            'affect_before' => $MM['total_money'],

            'total_money'   => $MM['total_money']+$realMoney,
            'charge_money'  => $MM['charge_money']+$realMoney,
            'invest_money'  => $MM['invest_money'],
            'withdraw_money'=> $MM['withdraw_money'],
            'back_money'    => $MM['back_money'],
            'collect_money' => $MM['collect_money'],
            'freeze_money'  => $MM['freeze_money'],

            'info' => $info,
            'add_time' => time(),
            'add_ip' => ApiUtils::get_client_ip(),
            'target_uid' => 0,
            'target_uname' => '在线充值',
        );

        MemberMoney::updateAll($money, ['id' => $mmoneyId]);
        $Moneylog->add($moneylog);
        return true;
    }
}

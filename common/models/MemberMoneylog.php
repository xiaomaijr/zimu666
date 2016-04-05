<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lzh_member_moneylog_0".
 *
 * @property string $id
 * @property string $uid
 * @property integer $platform
 * @property integer $type
 * @property string $affect_money
 * @property integer $affect_type
 * @property string $affect_before
 * @property string $total_money
 * @property string $charge_money
 * @property string $invest_money
 * @property string $withdraw_money
 * @property string $back_money
 * @property string $collect_money
 * @property string $freeze_money
 * @property string $info
 * @property string $add_time
 * @property string $add_ip
 * @property integer $target_uid
 * @property string $target_uname
 * @property integer $invest_id
 * @property string $request_no
 */
class MemberMoneylog extends RedisActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return self::$tableName;
    }

    public static $tableName = 'lzh_member_moneylog_0';

    public function setTableName($tableName){
        self::$tableName = $tableName;
    }

    /* ********type*********** */
    //企业网银充值
    const ENTERPRISE_MONEY_CHARGE =2 ;
    //用户网银充值
    const PERSON_MONEY_CHARGE = 3;
    //提现申请
    const WITHDRAW_MONEY_APPLY = 59;
    //提现冻结
    const WITHDRAW_MONEY_FREEZE = 4;
    //提现退回
    const WITHDRAW_MONEY_ROLLBACK = 5;
    //提现失败
    const WITHDRAW_MONEY_FAILED = 12;
    //提现成功
    const WITHDRAW_MONEY_SUCCESS = 29;
    //提现手续费
    const WITHDRAW_MONEY_FEE = 49;
    //用户投资冻结
    const USER_INVEST_FREEZE = 6;
    //用户投资退回
    const USER_INVEST_ROLLBACK = 7;
    //流标 资金退回
    const FLOW_MONEY_ROLLBACK = 8;
    //收到融资着还款
    const ACCEPT_ENTERPRISE_REPAY = 9;
    //企业还款
    const ENTERPRISE_MONEY_REPAY = 11;
    //推广奖励
    const PROMOTION_REWARD = 13;
    //注册 绑定身份证 奖励
    const REGISTER_BIND_REWARD =  57;
    //邀请 好友 奖励
    const INVITE_FRIENDS_REWARD = 58;
    //复审通过用户资金扣减 变成待收金额 生成债券
    const AUDIT_USER_MONEY_FREEZE2COLLECT = 15;
    //复审不通过返还用户资金
    const AUDIT_USER_MONEY_ROLLBACK = 16;
    //复审通过资金入账
    const AUDIT_ENTER_MONEY_ACCOUNT = 17;
    //用户投资奖励
    const USER_INVEST_REWARD = 20;
    //企业支付投资奖励
    const ENTER_PAY_INVEST_REWARD = 21;
    //用户手动对账
    const USER_MANUAL_BALANCE = 51;
    //系统自动对账
    const SYS_AUTO_BALANCE = 52;
    //每日返还利息
    const DAY_RETURN_INTEREST = 54;

    /* ********affect_type*********** */
    //用户对账
    const AFFECT_BALACE = 1;
    //用户充值
    const AFFECT_CHARGE = 2;
    //投资失败
    const AFFECT_INVEST_FAIL = 3;
    //投标冻结
    const AFFECT_INVEST_FREEZE = 4;
    //投标退回
    const AFFECT_INVEST_ROLLBACK = 5;
    //投标流标
    const AFFECT_INVEST_FLOW = 6;
    //复审通过  投资者扣款
    const AFFECT_AUDIT_INVEST_OK = 7;
    //复审通过  融资者到账
    const AFFECT_AUDIT_BORROW_OK = 8;
    const AFFECT_AUDIT_NO = 9;
    const AFFECT_WITHDRAW_OK = 10;
    const AFFECT_WITHDRAW_NO = 11;
    //用户收到还款
    const AFFECT_REPAY_CAPITAL = 12;
    //用户收到还息
    const AFFECT_REPAY_INTEREST = 13;
    //企业还款
    const AFFECT_PAY_CAPITAL = 14;
    //企业还息
    const AFFECT_PAY_INTEREST = 15;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'type', 'affect_money', 'affect_before', 'total_money', 'charge_money', 'invest_money', 'withdraw_money', 'back_money', 'collect_money', 'freeze_money', 'info', 'add_time', 'add_ip', 'target_uname'], 'required'],
            [['uid', 'platform', 'type', 'affect_type', 'add_time', 'target_uid', 'invest_id'], 'integer'],
            [['affect_money', 'affect_before', 'total_money', 'charge_money', 'invest_money', 'withdraw_money', 'back_money', 'collect_money', 'freeze_money'], 'number'],
            [['info'], 'string', 'max' => 100],
            [['add_ip'], 'string', 'max' => 16],
            [['target_uname'], 'string', 'max' => 20],
            [['request_no'], 'string', 'max' => 50],
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
            'type' => 'Type',
            'affect_money' => 'Affect Money',
            'affect_type' => 'Affect Type',
            'affect_before' => 'Affect Before',
            'total_money' => 'Total Money',
            'charge_money' => 'Charge Money',
            'invest_money' => 'Invest Money',
            'withdraw_money' => 'Withdraw Money',
            'back_money' => 'Back Money',
            'collect_money' => 'Collect Money',
            'freeze_money' => 'Freeze Money',
            'info' => 'Info',
            'add_time' => 'Add Time',
            'add_ip' => 'Add Ip',
            'target_uid' => 'Target Uid',
            'target_uname' => 'Target Uname',
            'invest_id' => 'Invest ID',
            'request_no' => 'Request No',
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
     * 添加新记录
     */
    public function add($attrs = []){
        if(empty($attrs)){
            throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '流水内容不能为空');
        }
        $this->attributes = $attrs;
        $ret = $this->save();
        if(!$ret){
            throw new ApiBaseException(ApiErrorDescs::ERR_INVEST_RECORD_ADD_FAIL);
        }
        return $this->id;
    }
    /*
     * 获取某个用户流水
     */
    public function getFlowByUid($uid, $type = 0, $page = 1, $pageSize = 100){
        $data = [];
        if(empty($uid)) return $data;
        $field = 'uid:' . $uid;
        $cache = self::getCache();
        if(!$cache->hExists(self::$tableName, $field)){
            $condition = empty($type)?['uid' => $uid]:['uid' => $uid, 'add_time > ' . strtotime('+1 month') ];
            $userMsgs = self::getDataByConditions($condition, 'id desc', $pageSize, $page);
            if(empty($userMsgs)) return $data;
            $ids = ApiUtils::getCols($userMsgs, 'id');
            $cache->hSet(self::$tableName, $field, $ids);
        }else{
            $ids = $cache->hGet(self::$tableName, $field);
            $userMsgs = self::gets($ids);
        }
        foreach($userMsgs as $msg){
            $data[] = self::toApiArr($msg);
        }
        return $data;
    }
    //api过滤参数
    public static function toApiArr($arr){
        return [
            'avaiable_money' => ApiUtils::getFloatParam('total_money', $arr),
            'affect_money' => ApiUtils::getFloatParam('affect_money', $arr),
            'withdraw_money' => ApiUtils::getFloatParam('withdraw_money', $arr),
            'add_time' => date("Y-m-d H:i:s", ApiUtils::getIntParam('add_time', $arr)),
            'request_no' => ApiUtils::getStrParam('request_no', $arr),
            'type' => self::getTypeMap(ApiUtils::getIntParam('type', $arr)),
            'affect_type' => self::getTypeMap(ApiUtils::getIntParam('affect_type', $arr)),
        ];
    }
    //获取type映射字段
    private static function getTypeMap($type){
        if(!isset(ApiConfig::$moneyLogTypeMap[$type])){
            return '';
        }
        return ApiConfig::$moneyLogTypeMap[$type];
    }

    //获取affect_type映射字段
    private static function getAffectTypeMap($affectType){
        if(!isset(ApiConfig::$moneyLogAffectTypeMap[$affectType])){
            return '';
        }
        return ApiConfig::$moneyLogAffectTypeMap[$affectType];
    }

    /*
    * @param $uid
    * @param $type
    * @param $amoney
    * @param string $info
    * @param string $target_uid
    * @param string $target_uname
    * @param int $fee
    * @param int $invest_id
    * @return bool
    *记录资金流水
    */
    public function memberMoneyLog($uid,$type,$amoney,$info="",$targetUid="",$targetUname="",$fee=0,$invest_id=0){
        $amoney = floatval($amoney);
        if(empty($amoney)) return false;

        $memInfo = Members::get($uid);
        $rewardMoney = ApiUtils::getFloatParam('reward_money', $memInfo);

        $memberMoney = MemberMoney::getUserPlatformMoney($uid, 0);
        $totalMoney = ApiUtils::getFloatParam('total_money', $memberMoney);
        $collectMoney = ApiUtils::getFloatParam('collect_money', $memberMoney);
        $freezeMoney = ApiUtils::getFloatParam('freeze_money', $memberMoney);
        $backMoney = ApiUtils::getFloatParam('back_money', $memberMoney);

        $typeSave = in_array($type,["71","72","73"])?7:$type;
        if($targetUname=="" && $targetUid>0){
            $tUser = Members::get($targetUid);
            $tname = ApiUtils::getStrTime('user_name', $tUser);
        }else{
            $tname = $targetUname;
        }
        if($targetUid=="" && $targetUname==""){
            $targetUid=0;
            $tname = '@网站管理员@';
        }

        $data['uid'] = $uid;
        $data['type'] = $typeSave;
        $data['info'] = $info;
        $data['target_uid'] = $targetUid;
        $data['target_uname'] = $tname;
        $data['add_time'] = time();
        $data['add_ip'] = ApiUtils::get_client_ip();
        $data['invest_id'] = $invest_id;
        $data['platform'] = 0;
        switch($type){
            case 3: //充值
                $data['affect_money'] = $amoney;
                if(($totalMoney+$amoney)<0){
                    $data['account_money'] = 0;
                    $data['back_money'] = $totalMoney+$backMoney+$amoney;
                }else{
                    $data['account_money'] = $totalMoney+$amoney;
                    $data['back_money'] = $backMoney;
                }
                $data['collect_money'] = $collectMoney;
                $data['freeze_money'] = $freezeMoney;
                break;
        }

        $newid = $this->add($data);
        if(!$newid){
            throw new ApiBaseException(ApiErrorDescs::ERR_RECHARGE_MMLOG_ADD_FAIL);
        }
        //帐户更新
        $mmoney['money_freeze']=$data['freeze_money'];
        $mmoney['money_collect']=$data['collect_money'];
        $mmoney['account_money']=$data['account_money'];
        $mmoney['back_money']=$data['back_money'];
        //$mmoney['platform'] = 0;
        if(!MemberMoney::updateAll($mmoney, ['uid'=>$uid,'platform'=>0])){
            throw new ApiBaseException(ApiErrorDescs::ERR_RECHARGE_NOTIFY_FAIL);
        }
        //更新奖金
        if(!empty($dd)){
            Members::updateAll($dd, ['id' => $uid]);
        }
    }
}

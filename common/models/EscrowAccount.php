<?php

namespace common\models;

use Yii;
use yii\redis\Cache;

/**
 * This is the model class for table "lzh_escrow_account".
 *
 * @property integer $id
 * @property string $uid
 * @property string $account
 * @property string $mobile
 * @property string $email
 * @property string $real_name
 * @property string $id_card
 * @property integer $platform
 * @property string $platform_marked
 * @property string $qdd_marked
 * @property string $add_time
 * @property integer $type
 * @property string $auth_fee
 * @property integer $auth_state
 * @property integer $invest_auth
 * @property integer $repayment
 * @property integer $secondary_percent
 */
class EscrowAccount extends RedisActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lzh_escrow_account';
    }

    public static $tableName = 'lzh_escrow_account';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'account', 'mobile', 'email', 'real_name', 'id_card', 'platform_marked', 'qdd_marked', 'add_time', 'type', 'repayment', 'secondary_percent'], 'required'],
            [['uid', 'platform', 'add_time', 'type', 'auth_state', 'invest_auth', 'repayment', 'secondary_percent'], 'integer'],
            [['auth_fee'], 'number'],
            [['account', 'real_name'], 'string', 'max' => 30],
            [['mobile'], 'string', 'max' => 13],
            [['email', 'qdd_marked'], 'string', 'max' => 60],
            [['id_card'], 'string', 'max' => 20],
            [['platform_marked'], 'string', 'max' => 10],
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
            'account' => 'Account',
            'mobile' => 'Mobile',
            'email' => 'Email',
            'real_name' => 'Real Name',
            'id_card' => 'Id Card',
            'platform' => 'Platform',
            'platform_marked' => 'Platform Marked',
            'qdd_marked' => 'Qdd Marked',
            'add_time' => 'Add Time',
            'type' => 'Type',
            'auth_fee' => 'Auth Fee',
            'auth_state' => 'Auth State',
            'invest_auth' => 'Invest Auth',
            'repayment' => 'Repayment',
            'secondary_percent' => 'Secondary Percent',
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
     * 获取用户第三方支付绑定信息
     * @param $uid eq member id
     * unbind return ['yeeBind'=>0,'qddBind'=>0] else return ['yeeBind'=>1,'qddBind'=>1]
     */
    public static function getUserBindInfo($uid){
        $data = ['yeeBind'=>0,'qddBind'=>0];
        if(empty($uid)){
            return $data;
        }
        $cache = self::getCache();
        $field = 'uid:' . $uid;
        if($cache->hExists(self::$tableName, $field)){
            $ids = $cache->hGet(self::$tableName, $field);
            $accountInfos = self::gets($ids);
        }else{
            $accountInfos = self::getDataByConditions(['uid' => $uid]);
            if(empty($accountInfos)) return $data;
            $ids = ApiUtils::getCols($accountInfos, 'id');
            $cache->hSet(self::$tableName, $field, $ids);
        }
        foreach($accountInfos as $info){
            if(empty($info) || empty($info['qdd_marked'])) continue;
            if($info['platform'] == 0){
                $data['qddBind'] = 1;
            }elseif($info['platform'] == 1){
                $data['yeeBind'] = 1;
            }
        }
        return $data;
    }

    public function add($params){
        $this->attributes = $params;
        $ret = $this->save();
        if(!$ret){
            throw new ApiBaseException(ApiErrorDescs::ERR_QDD_REGISTER_FAIL);
        }
        return true;
    }
    /*
     * 获取用户第三方账户
     * @param $uid int
     * @param $platform int 0 qdd 1 yee
     * return $data array
     */
    public static function getUserThirdAccout($uid, $platform = 0){
        $data = [];
        if(empty($uid)){
            return $data;
        }
        $cache = self::getCache();
        $field = 'uid:' . $uid;
        if($cache->hExists(self::$tableName, $field)){
            $ids = $cache->hGet(self::$tableName, $field);
            $accountInfos = self::gets($ids);
        }else{
            $accountInfos = self::getDataByConditions(['uid' => $uid]);
            if(empty($accountInfos)) return $data;
            $ids = ApiUtils::getCols($accountInfos, 'id');
            $cache->hSet(self::$tableName, $field, $ids);
        }
        foreach($accountInfos as $row){
            if($row['platform'] == $platform){
                $data = $row;
            }
        }
        return $data;
    }
}

<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lzh_members".
 *
 * @property string $id
 * @property string $user_name
 * @property string $user_pass
 * @property integer $user_type
 * @property string $user_email
 * @property string $user_phone
 * @property string $reg_time
 * @property string $reg_ip
 * @property integer $user_leve
 * @property string $time_limit
 * @property string $recommend_id
 * @property string $enterprise_name
 * @property integer $is_ban
 * @property string $reward_money
 * @property string $invest_credits
 * @property integer $integral
 * @property integer $active_integral
 * @property integer $is_borrow
 * @property integer $is_transfer
 * @property string $last_log_ip
 * @property integer $last_log_time
 * @property integer $signin_day
 * @property integer $is_charge
 * @property integer $is_invest
 * @property string $open_id
 * @property integer $is_withdraw
 * @property integer $withdraw_limit
 * @property integer $reg_res
 * @property string $ip_province
 * @property string $ip_city
 * @property integer $withdraw_force
 * @property integer $channel_code
 * @property integer $reset_pass
 */
class Members extends RedisActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lzh_members';
    }

    public static $tableName = 'lzh_members';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_pass', 'user_phone', 'reg_time', 'reg_ip', 'last_log_ip'], 'required'],
            [['user_type', 'reg_time', 'user_leve', 'time_limit', 'recommend_id', 'is_ban', 'integral', 'active_integral', 'is_borrow', 'is_transfer', 'last_log_time', 'signin_day', 'is_charge', 'is_invest', 'is_withdraw', 'withdraw_limit', 'reg_res', 'withdraw_force', 'channel_code', 'reset_pass'], 'integer'],
            [['reward_money', 'invest_credits'], 'number'],
            [['user_name', 'user_email'], 'string', 'max' => 50],
            [['user_pass'], 'string', 'max' => 32],
            [['user_phone', 'reg_ip', 'last_log_ip'], 'string', 'max' => 15],
            [['enterprise_name'], 'string', 'max' => 255],
            [['open_id'], 'string', 'max' => 100],
            [['ip_province', 'ip_city'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_name' => 'User Name',
            'user_pass' => 'User Pass',
            'user_type' => 'User Type',
            'user_email' => 'User Email',
            'user_phone' => 'User Phone',
            'reg_time' => 'Reg Time',
            'reg_ip' => 'Reg Ip',
            'user_leve' => 'User Leve',
            'time_limit' => 'Time Limit',
            'recommend_id' => 'Recommend ID',
            'enterprise_name' => 'Enterprise Name',
            'is_ban' => 'Is Ban',
            'reward_money' => 'Reward Money',
            'invest_credits' => 'Invest Credits',
            'integral' => 'Integral',
            'active_integral' => 'Active Integral',
            'is_borrow' => 'Is Borrow',
            'is_transfer' => 'Is Transfer',
            'last_log_ip' => 'Last Log Ip',
            'last_log_time' => 'Last Log Time',
            'signin_day' => 'Signin Day',
            'is_charge' => 'Is Charge',
            'is_invest' => 'Is Invest',
            'open_id' => 'Open ID',
            'is_withdraw' => 'Is Withdraw',
            'withdraw_limit' => 'Withdraw Limit',
            'reg_res' => 'Reg Res',
            'ip_province' => 'Ip Province',
            'ip_city' => 'Ip City',
            'withdraw_force' => 'Withdraw Force',
            'channel_code' => 'Channel Code',
            'reset_pass' => 'Reset Pass',
        ];
    }

    public function insertEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'id:' . $this->id);
    }

    public function updateEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'id:' . $this->id);
    }

    public function deleteEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'id:' . $this->id);
    }

    /*
     * 用户注册并登录
     */
    public function register($params){
        $this->_checkPhoneRegistered($params['user_name']);
        $this->user_phone = $params['user_name'];
        $this->user_pass = md5('a2m' . $params['passwd'] . '1df');;
        $this->reg_time = time();
        $this->reg_ip = ApiUtils::getStrParam('REMOTE_ADDR', $_SERVER);
        $this->last_log_ip = ApiUtils::getStrParam('REMOTE_ADDR', $_SERVER);
        $this->user_name = $params['user_name'];
//        $this->time_limit = 0;
//        $this->enterprise_name = '';
//        $this->reward_money = 0.00;
//        $this->invest_credits = 0.00;
//        $this->integral = 0;
//        $this->active_integral = 0;
        $ret = $this->save();
        if(!$ret){
            throw new ApiBaseException(ApiErrorDescs::ERR_USER_REGISTER_FAIL);
        }
        $memAccToken = new MemberAccessToken();
        $accessToken = $memAccToken->login($this->id, $params['mobile_type']);
        return [
            'user_id' => $this->id,
            'access_token' => $accessToken
        ];
    }
    /*
     * 检查手机号是否已注册过
     */
    private function _checkPhoneRegistered($phone){
        if(self::checkExistByCondition(['user_name' => $phone])){
            throw new ApiBaseException(ApiErrorDescs::ERR_USER_REGISTER_PHONE_EXIST);
        }
    }

    /*
     * 用户登录
     */
    public static function login($userName, $passwd, $mobileType){
        $obj = self::findOne(['user_name' => $userName]);
        if(!$obj){
            throw new ApiBaseException(ApiErrorDescs::ERR_USER_NAME_NOT_REGISTER);
        }
        if(md5('a2m'.$passwd .'1df') != $obj['user_pass']){
            throw new ApiBaseException(ApiErrorDescs::ERR_USER_PASSWD_INPUT_WRONG);
        }
        $memAccToken = new MemberAccessToken();
        $accessToken = $memAccToken->login($obj->id, $mobileType);
        return [ 'access_token' => $accessToken, 'user_id' => $obj->id, 'mobile' => $obj['user_phone'] ];
    }
    /*
     * 重置登录密码
     */
    public static function resetUserPass($userName, $passwd){
        $obj = self::findOne(['user_name' => $userName]);
        if(!$obj){
            throw new ApiBaseException(ApiErrorDescs::ERR_USER_NAME_NOT_REGISTER);
        }
        $obj->user_pass =  md5('a2m'.$passwd .'1df');
        if(!$obj->update()){
            throw new ApiBaseException(ApiErrorDescs::ERR_RESET_PASSWD_FAIL);
        }
    }
    /*
     * 根据key验证短信发送用户是否已注册
     */
    public static function checkExistByMsgKey($userName, $key){
        if(in_array($key, MessageConfig::$checkNotExistMsgKeys) && self::checkExistByCondition(['user_name' => $userName])){
            throw new ApiBaseException(ApiErrorDescs::ERR_USER_REGISTER_PHONE_EXIST);
        } elseif(in_array($key, MessageConfig::$checkExistMsgKeys) && !self::checkExistByCondition(['user_name' => $userName])){
            throw new ApiBaseException(ApiErrorDescs::ERR_USER_NAME_NOT_REGISTER);
        }else{
            throw new ApiBaseException(ApiErrorDescs::ERR_ILL_REQUEST_MESSAGE);
        }
    }

}

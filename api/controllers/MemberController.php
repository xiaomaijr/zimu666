<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao-pc
 * Date: 2016/3/3
 * Time: 15:07
 */

namespace api\controllers;


use common\models\ApiBaseException;
use common\models\ApiConfig;
use common\models\ApiErrorDescs;
use common\models\ApiUtils;
use common\models\CacheKey;
use common\models\BorrowInvest;
use common\models\EscrowAccount;
use common\models\MemberAccessToken;
use common\models\MemberDeviceToken;
use common\models\MemberMoney;
use common\models\Members;
use common\models\MessageConfig;
use common\models\TimeUtils;
use common\models\Verify;
use yii\redis\Cache;

class MemberController extends ApiBaseController
{

    private $keyTypeMap = [
        'register'  =>   1,
        'forgetpass'  =>   2
    ];

    const CACHE_KEY_RESET_PASSWD = 'reset_passwd'; //密码重置有效期
    const CACHE_KEY_GET_MESSAGE_CODE = 'get_message_code';//短信验证码有效期
    const CACHE_KEY_GET_MESSAGE_LIMIT = 'get_message_limit';//短信验证码请求频率限制
    const CACHE_KEY_LOGIN_ERR_LIMIT = 'login_err_limit';//登录失败限制
    /*
     * 获取图形验证码
     */
    public function actionGetVerifyCode(){
        try{
            $config = [
                'length' => 4,
            ];
            $request = $_REQUEST;
            $timer = new TimeUtils();
            $timer->start('forget_passwd_verify_code');
            $objVerfiy = new Verify($config);
            $uniqStr = uniqid();
            $codeUrl = $objVerfiy->entry($request['key'] . '_' . $uniqStr);
            $timer->stop('forget_passwd_verify_code');

            $result = [
                'code' => ApiErrorDescs::SUCCESS,
                'message' => 'success',
                'result'  => [
                    'code_url' => $codeUrl,
                    'uniq_key' => $uniqStr,
                ]
            ];
        }catch(ApiBaseException $e){
            $result = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
        }
        header('Content-type: application/json');
        echo json_encode($result);

        $this->logApi(__CLASS__, __FUNCTION__, $result);
        \Yii::$app->end();
    }
    /*
     * 获取短信验证码
     */
    public function actionGetMessageCode(){
        try{
            $request = $_REQUEST;
            $userName =  ApiUtils::getStrParam('user_name', $request);
            $code = ApiUtils::getStrParam('verify_code', $request);
            $verifyId = ApiUtils::getStrParam('verify_id', $request);
            //手机号码格式验证
            ApiUtils::checkPhoneFormat($userName);

            $reqLimitCacheKey = CacheKey::getCacheKey($request['key'] . '_' . $userName, self::CACHE_KEY_GET_MESSAGE_LIMIT);
            $codeCacheKey = CacheKey::getCacheKey($request['key'] . '_' . $userName, self::CACHE_KEY_GET_MESSAGE_CODE);
            $timer = new TimeUtils();
            $cache = new Cache();
            //请求频率限制，1分钟间隔
            if($cache->exists($reqLimitCacheKey['key_name'])){
                throw new ApiBaseException(ApiErrorDescs::ERR_REQUEST_CODE_TOO_FREQUENT);
            }
            //校验图形验证码
            $timer->start('check_forget_pwd_code');
            $objVer = new Verify(['reset' => false]);
            $ret = $objVer->check($code, $request['key'] . '_' . $verifyId);
            $timer->stop('check_forget_pwd_code');
            if(!$ret){
                throw new ApiBaseException(ApiErrorDescs::ERR_VERIFY_CODE_WRONG);
            }
            //校验用户名是否存在
            $timer->start('check_user_name');
            Members::checkExistByMsgKey($userName, $request['key']);
            $timer->stop('check_user_name');
            //短信验证码发送
            $data['code'] = rand(100000, 999999);
            $timer->start('mobile_message_send');
            $type = $this->_getNoticeTypeByKey($request['key']);
            $ret = MessageConfig::Notice($type, $userName, 0, $data);
            $timer->stop('mobile_message_send');

            if(!$ret){
                throw new ApiBaseException(ApiErrorDescs::ERR_REGISTER_CODE_SEND_FAIL);
            }
            $cache->set($reqLimitCacheKey['key_name'], 1, $reqLimitCacheKey['expire']);//请求频率限制，有效期1分钟
            $cache->set($codeCacheKey['key_name'], $data['code'], $codeCacheKey['expire']);//注册验证码有效期2分钟

            $result = [
                'code' => ApiErrorDescs::SUCCESS,
                'message' => 'success',
                'result' => $data['code'],
            ];
        }catch(ApiBaseException $e){
            $result = [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        }
        header('Content-type: application/json');
        echo json_encode($result);

        $this->logApi(__CLASS__, __FUNCTION__, $result);
        \Yii::$app->end();
    }
    /*
     * 用户注册
     */
    public function actionRegister(){
        try{
            $request = $_REQUEST;
            ApiUtils::checkPhoneFormat($request['user_name']);

            $recomCode = ApiUtils::getStrParam('recommend_code', $request);
            $regAgrement = ApiUtils::getIntParam('agrement', $request);
            if(!$regAgrement){
                throw new ApiBaseException(ApiErrorDescs::ERR_REGISTER_AGREMENT_NOT_AGREE);
            }
            $timer = new TimeUtils();
            //校验图形验证码
            $timer->start('check_register_code');
            $objVer = new Verify();
            $ret = $objVer->check($request['verify_code'], $request['key'] . '_' . $request['verify_id']);
            $timer->stop('check_register_code');
            if(!$ret){
                throw new ApiBaseException(ApiErrorDescs::ERR_VERIFY_CODE_WRONG);
            }
            //短信验证码校验
            $timer->start('check_message_code');
            $codeCacheKey = CacheKey::getCacheKey($request['key'] . '_' .  $request['user_name'], self::CACHE_KEY_GET_MESSAGE_CODE);
            $cache = new Cache();
//            if(!$cache->exists($codeCacheKey['key_name']) || $cache->get($codeCacheKey['key_name']) != $request['phone_code']){
//                throw new ApiBaseException(ApiErrorDescs::ERR_REGISTER_MESSAGE_CODE_ERROR);
//            }
            $timer->stop('check_message_code');
            //校验用户密码
//            ApiUtils::checkPwd($request['passwd']);
            //用户注册
            $timer->start('register_member');
            $objMember = new Members();
            $ret = $objMember->register($request);
            $timer->stop('register_member');
            //device token绑定
            if(!empty($request['device_token'])){
                $timer->start('device_token_bind');
                MemberDeviceToken::bindToken($ret['user_id'], $request['device_token'], $request['mobile_type'], $request['user_name']);
                $timer->stop('device_token_bind');
            }
            $result = [
                'code' => ApiErrorDescs::SUCCESS,
                'message' => 'success',
                'result' => $ret,
            ];

        }catch(ApiBaseException $e){
            $result = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
        }
        header('Content-type: application/json');
        echo json_encode($result);

        $this->logApi(__CLASS__, __FUNCTION__, $result);
        \Yii::$app->end();
    }
    /*
     * 用户登录
     */
    public function actionLogin(){
        try{
            $request = $_REQUEST;
//            ApiUtils::checkPwd($request['passwd']);
            ApiUtils::checkPhoneFormat($request['user_name']);
            $key = CacheKey::getCacheKey($request['user_name'], self::CACHE_KEY_LOGIN_ERR_LIMIT);
            $timer = new TimeUtils();
//            $cache = new Cache();
            $redis = new \Redis();
            $redis->connect(\Yii::$app->redis->hostname);
            $exist = false;
            if($redis->exists($key['key_name'])) {
                $exist = true;
                $num = $redis->hGet($key['key_name'], 'num');
                $time = $redis->hGet($key['key_name'], 'time');
                $duration = time() - $time;
                if ($num > 4) {
                    throw new ApiBaseException(ApiErrorDescs::ERR_USER_LOGIN_ERR_FREQUENT);
                }
            }
            //用户登录
            $timer->start('password_check');
            $memberInfo = Members::login($request['user_name'], $request['passwd'], $request['mobile_type']);
            $timer->stop('password_check');
            //device token绑定
            if(!empty($request['device_token'])){
                $timer->start('device_token_bind');
                MemberDeviceToken::bindToken($memberInfo['user_id'], $request['device_token'], $request['mobile_type'], $memberInfo['mobile']);
                $timer->stop('device_token_bind');
            }
            $result = [
                'code' => ApiErrorDescs::SUCCESS,
                'message' => 'success',
                'result'  => [
                    'access_token' => $memberInfo['access_token'],
                    'user_id' => $memberInfo['user_id']
                ]
            ];
        }catch(ApiBaseException $e){
            if ($exist && $duration <= ApiConfig::USER_LOGIN_DURATION) {
                $num++;
                $redis->hset($key['key_name'], 'num', $num);
            } else {
                $time = time();
                $num = 1;
                $redis->hset($key['key_name'], 'time', $time);
                $redis->hset($key['key_name'], 'num', $num);
            }
            $redis->expire($key['key_name'], $key['expire']);
            $result = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
        }
        header('Content-type: application/json');
        echo json_encode($result);

        $this->logApi(__CLASS__, __FUNCTION__, $result);
        \Yii::$app->end();
    }
    /*
     * 退出登录
     */
    public function actionLogout(){
        try{
            $request = $_REQUEST;
            $timer = new TimeUtils();
            //验证用户access_token
            $this->checkAccessToken($request['access_token'], $request['user_id']);
            //删除access_token
            $timer->start('delete_access_token');
            MemberAccessToken::logOut($request['user_id']);
            $timer->stop('delete_access_token');
            //解绑device_token
            $timer->start('unbind_device_token');
            MemberDeviceToken::unbindToken($request['user_id']);
            $timer->stop('unbind_device_token');
            $result = [
                'code' => ApiErrorDescs::SUCCESS,
                'message' => 'success'
            ];
        }catch(ApiBaseException $e){
            $result = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
        }
        header('Content-type: application/json');
        echo json_encode($result);

        $this->logApi(__CLASS__, __FUNCTION__, $result);
        \Yii::$app->end();
    }

    /*
     * 忘记密码
     */
    public function actionForgetPasswd(){
        try{
            $request = $_REQUEST;
            $timer = new TimeUtils();
            //校验图形验证码
            $timer->start('check_forgetpass_code');
            $objVer = new Verify();
            $ret = $objVer->check($request['verify_code'], $request['key'] . '_' . $request['verify_id']);
            $timer->stop('check_forgetpass_code');
            if(!$ret){
                throw new ApiBaseException(ApiErrorDescs::ERR_VERIFY_CODE_WRONG);
            }
            //短信验证码校验
            $timer->start('check_message_code');
            $codeCacheKey = CacheKey::getCacheKey($request['key'] . '_' . $request['user_name'], self::CACHE_KEY_GET_MESSAGE_CODE);
            $cache = new Cache();
            if(!$cache->exists($codeCacheKey['key_name']) || $cache->get($codeCacheKey['key_name']) != $request['phone_code']){
                throw new ApiBaseException(ApiErrorDescs::ERR_REGISTER_MESSAGE_CODE_ERROR);
            }
            $timer->stop('check_message_code');
            //检查用户名是否存在并生成重置密码有效信息
            $timer->start('check_user_name');
            if(!Members::checkExistByCondition(['user_name' => $request['user_name']])){
                throw new ApiBaseException(ApiErrorDescs::ERR_USER_NAME_NOT_REGISTER);
            }
            $resetCacheKey = CacheKey::getCacheKey($request['user_name'], self::CACHE_KEY_RESET_PASSWD);
            $cache->set($resetCacheKey['key_name'], 1, $resetCacheKey['expire']);
            $timer->stop('check_user_name');

            $result = [
                'code' => ApiErrorDescs::SUCCESS,
                'message' => 'success'
            ];
        }catch(ApiBaseException $e){
            $result = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
        }
        header('Content-type: application/json');
        echo json_encode($result);

        $this->logApi(__CLASS__, __FUNCTION__, $result);
        \Yii::$app->end();
    }



    /*
     * 重置密码
     */
    public function actionResetPwd(){
        try{
            $request = $_REQUEST;
            $timer = new TimeUtils();
            $timer->start('param_check');
            ApiUtils::checkPhoneFormat($request['user_name']);
            ApiUtils::checkPwd($request['first_passwd']);
            ApiUtils::checkPwd($request['second_passwd']);
            if(strcmp($request['first_passwd'], $request['second_passwd']) != 0){
                throw new ApiBaseException(ApiErrorDescs::ERR_FORGET_PASS_DIFF);
            }
            $timer->stop('param_check');
            //检查重置密码的信息是否过期
            $timer->start('check_reset_token');
            $resetCacheKey = CacheKey::getCacheKey($request['user_name'], self::CACHE_KEY_RESET_PASSWD);
            $cache = new Cache();
            if(!$cache->exists($resetCacheKey['key_name'])){
                throw new ApiBaseException(ApiErrorDescs::ERR_RESET_USERPASS_OVERDUE);
            }
            $timer->stop('check_reset_token');
            //重置用户密码
            $timer->start('reset_user_pass');
            Members::resetUserPass($request['user_name'], $request['first_passwd']);
            $timer->stop('reset_user_pass');
            $result = [
                'code' => ApiErrorDescs::SUCCESS,
                'message' => 'success',
            ];
            MessageConfig::Notice(7, $request['user_name']);
        }catch(ApiBaseException $e){
            $result = [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        }
        header('Content-type: application/json');
        echo json_encode($result);

        $this->logApi(__CLASS__, __FUNCTION__, $result);
        \Yii::$app->end();
    }

    /*
     * 根据key获取notice type
     */
    private function _getNoticeTypeByKey($key){
        if(!isset($this->keyTypeMap[$key])){
            throw new ApiBaseException(ApiErrorDescs::ERR_NOTICE_KEY_NOT_EXIST);
        }
        return $this->keyTypeMap[$key];
    }

    /*
     * 个人账户
     */
    public function actionAccount(){
        try{
            $request = $_REQUEST;
            $timer = new TimeUtils();
            //检查用户登录信息
            $timer->start('check_access_token');
//            $this->checkAccessToken($request['access_token'], $request['user_id']);
            $timer->stop('check_access_token');
            //获取用户资金
            $timer->start('get_mm_money');
            $data = MemberMoney::getUserMoney($request['user_id']);
            $timer->stop('get_mm_money');
            //用户累计收益
            $timer->start('accumulated_income');
            $data['income'] = BorrowInvest::getTotalIncomeByInvestId($request['user_id']);
            $timer->stop('accumulated income');
            //检查用户是否在钱多多绑定账户
            $timer->start('escrow_account');
            $escrow = EscrowAccount::getUserBindInfo($request['user_id']);
            $data['escrow'] = $escrow['yeeBind'] | $escrow['qddBind'];
            $timer->stop('escrow_account');
            $result = [
                'code' => ApiErrorDescs::SUCCESS,
                'message' => 'success',
                'result'  => $data
            ];

        }catch(ApiBaseException $e){
            $result = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
        }
        header('Content-type: application/json');
        echo json_encode($result);

        $this->logApi(__CLASS__, __FUNCTION__, $result);
        \Yii::$app->end();
    }

}
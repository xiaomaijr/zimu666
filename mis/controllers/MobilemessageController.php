<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao
 * Date: 15/7/21
 * Time: 下午4:30
 */

namespace mis\controllers;


use common\models\ApiBaseException;
use common\models\ApiErrorDescs;
use common\models\ApiUtils;
use common\models\MobileMessage;
use mis\models\Util;
use yii\redis\Cache;

class MobilemessageController extends MisBaseController{

    const CACHE_KEY_PREFIX_USER_LOGIN_SMS_CAPTCHA = 'mall_user_mobile_captcha_';

    const CACHE_KEY_PREFIX_SELLER_LOGIN_SMS_CAPTCHA = 'jiadao_seller_mobile_captcha_user_';

    const CACHE_KEY_PREFIX_USER_LOGIN_CAPTCHA_SEND_FLAG = 'jiadao_user_login_mobile_captcha_send_flag_';

    const SMS_CAPTCHA_EFFECT_TIME = 120;

    const SMS_CAPTCHA_SEND_INTERVAL = 60; //每60s秒才能发一次



    public function actionSend(){
        $request = $_REQUEST;
        $query = isset($request['query'])?$request['query']:[];
        try{
            if(!empty($query)){
                $message = isset($query['message'])?trim($query['message']):'';
                $mobileStr = isset($query['mobile'])?trim($query['mobile']):'';
                if(!$message ||!$mobileStr){
                    throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN,'参数错误');
                }
                if(mb_strlen($message,'utf-8')>500){
                    throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN,'短信内容太长');
                }
                $mobile = explode(',',$mobileStr);
                $count = count($mobile);
                if($count == 1){
                    $ret = MobileMessage::sendMessage($message,$mobile);
                }else{
                    $ret = MobileMessage::sendMessage($message,$mobile,$flag=0);
                }
                if(!$ret){
                    throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN,'发送失败');
                }
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN,'发送成功');
            }
            return $this->render('send.tpl');

        }catch(ApiBaseException $e){
            $err = $e->getMessage();
            $str = !empty($err)?$err:'输入有误';
            Util::pageNotFund($str);
        }
    }
    public function actionSelectmsg()
    {
        return $this->render('selectmsg.tpl');
    }

    public function actionSearchCode(){
        $request = $_REQUEST;
        $mobile = ApiUtils::getStrParam('mobile', $request);
        $role = ApiUtils::getIntParam('role', $request);
        try{
            $cache = new Cache();

            $strSendFlayKey = self::CACHE_KEY_PREFIX_USER_LOGIN_CAPTCHA_SEND_FLAG . $mobile;
            $sendFlag = $cache->get($strSendFlayKey);
            if ($sendFlag) {
                throw new ApiBaseException(ApiErrorDescs::ERR_CAPTCHA_SEND_TOO_OFTEN);
            }
            if($role == 1){
                $strSmsKey = self::CACHE_KEY_PREFIX_USER_LOGIN_SMS_CAPTCHA . $mobile;
            }else{
                $strSmsKey = self::CACHE_KEY_PREFIX_SELLER_LOGIN_SMS_CAPTCHA . $mobile;
            }
            if(!$cache->exists($strSmsKey)){
                $strSmsCaptcha = rand(100000, 999999);
                $strSendFlayKey = $strSmsKey . $mobile;
                $cache->set($strSmsKey, $strSmsCaptcha, self::SMS_CAPTCHA_EFFECT_TIME); //设置缓存时间2分钟
                $cache->set($strSendFlayKey, 1, self::SMS_CAPTCHA_SEND_INTERVAL);
            }
            $captcha = $cache->get($strSmsKey);


            $result = [
                'code' => ApiErrorDescs::SUCCESS,
                'message' => '验证码已发送至手机，请查收！',
                'result' => $captcha,
            ];
        }catch (ApiBaseException $e){
            $result = [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        }
        echo json_encode($result);
    }
}
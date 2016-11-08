<?php
/**
 * Created by PhpStorm.
 * User: 58
 * Date: 2016/8/15
 * Time: 10:08
 */

namespace front\controllers;


use common\models\ApiBaseException;
use common\models\ApiErrorDescs;
use common\models\ApiUtils;
use common\models\MessageConfig;
use front\models\ChromePhp;
use front\models\UserPassport;
use yii\redis\Cache;

class UserPassportController extends BaseController
{
    /*
     * 获取短信验证码
     */
    public function actionGetCode(){
        try{
            $request = $_REQUEST;
            $account =  ApiUtils::getStrParam('account', $request);
            //手机号码格式验证
            ApiUtils::checkPhoneFormat($account);
            $reqLimitCacheKey = UserPassport::USER_PASSPORT_LIMIT . '_' . $account;
            $codeCacheKey = UserPassport::USER_PASSPORT_LOGIN . '_' . $account;
            $cache = new Cache();
            //请求频率限制，1分钟间隔
            if($cache->exists($reqLimitCacheKey)){
                throw new ApiBaseException(ApiErrorDescs::ERR_REQUEST_CODE_TOO_FREQUENT);
            }
            //短信验证码发送
            $data['code'] = rand(100000, 999999);
            $ret = MessageConfig::Notice(MessageConfig::MESSAGE_TYPE_USER_LOGIN, $account, 0, $data);
            if(!$ret){
                throw new ApiBaseException(ApiErrorDescs::ERR_REGISTER_CODE_SEND_FAIL);
            }
            $cache->set($reqLimitCacheKey, 1, UserPassport::USER_PASSPORT_LIMIT_EXPIRE);//请求频率限制，有效期1分钟
            $cache->set($codeCacheKey, $data['code'], UserPassport::USER_PASSPORT_LOGIN_EXPIRE);//注册验证码有效期2分钟

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

    /**
     * 用户注册
     */
    public function actionRegister()
    {
        try{
            $request = $this->request;
            ApiUtils::checkPhoneFormat($request['account']);
            $code = ApiUtils::getStrParam('auth_code', $request);
            $codeCacheKey = UserPassport::USER_PASSPORT_LOGIN . '_' . $request['account'];
            $cache = new Cache();
            if (!$cache->exists($codeCacheKey) || $cache->get($codeCacheKey) != $code) {
                throw new ApiBaseException(ApiErrorDescs::ERR_REGISTER_MESSAGE_CODE_ERROR);
            }
            if (empty($request['password']) || $request['password'] != $request['confirm_password']) {
                throw new ApiBaseException(ApiErrorDescs::ERR_USER_PASSWORD_FORMART_WORNG);
            }
            //用户注册
            UserPassport::register($request['account'], $request['password']);
            $result = [
                'code' => ApiErrorDescs::SUCCESS,
                'message' => 'success',
                'data'  =>  [
                    'backUrl' => !empty($request['back_url']) ? $request['back_url'] : '/',
                ],
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
    /**
     * 用户登录
     */
    public function actionLogin(){
        try{
            $request = $this->request;
            ApiUtils::checkPhoneFormat($request['account']);
            $code = ApiUtils::getStrParam('auth_code', $request);
            $codeCacheKey = UserPassport::USER_PASSPORT_LOGIN . '_' . $request['account'];
            $cache = new Cache();
            if (!$cache->exists($codeCacheKey) || $cache->get($codeCacheKey) != $code) {
                throw new ApiBaseException(ApiErrorDescs::ERR_REGISTER_MESSAGE_CODE_ERROR);
            }
            //用户登录
            UserPassport::login($request['account']);
            $result = [
                'code' => ApiErrorDescs::SUCCESS,
                'message' => 'success',
                'data'  =>  [
                    'backUrl'  =>  !empty($request['back_url']) ? $request['back_url'] : '/',
                ],
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

    public function actionRegisterView()
    {
        return $this->render('register.tpl');
    }

    public function actionLoginView()
    {
        $data['params'] = $this->request;
        return $this->render('login.tpl', $data);
    }

    public function actionLoginOut()
    {
        session_destroy();
        return $this->redirect('/');
    }
}
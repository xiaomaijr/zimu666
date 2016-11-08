<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao-pc
 * Date: 2016/2/25
 * Time: 15:09
 */

namespace common\models;


class ApiErrorDescs
{
        //1000以下系统错误，以上为业务错误
    const SUCCESS = 0; //返回正确
    const ERR_PARAM_INVALID = 1; //参数错误
    const ERR_SIGN_ERR = 2;//签名sign错误
    const ERR_REDIS_KEY_NOE_EXISTS = 3;//redis key 不存在
    const ERR_VERIFY_CODE_WRONG = 4;//图形验证码输入错误
    const ERR_REGISTER_CODE_SEND_FAIL = 5;//注册短信验证码发送失败
    const ERR_REQUEST_CODE_TOO_FREQUENT = 6;//请求过于频繁
    const ERR_REGISTER_AGREMENT_NOT_AGREE = 7;//注册协议未选择
    const ERR_REGISTER_MESSAGE_CODE_ERROR = 8;//短信验证码输入错误或已过期
    const ERR_PHONE_FORMAT_WRONG = 9;//手机号码格式有误
    const ERR_USER_PASSWORD_FORMART_WORNG = 10;//用户登录密码格式有误
    const ERR_USER_REGISTER_FAIL = 11;//用户注册失败
    const ERR_USER_REGISTER_PHONE_EXIST = 12;//用户名已注册
    const ERR_USER_LOGIN_FAIL = 13;//用户登录失败
    const ERR_USER_ACCESS_TOKEN_OVERDUE = 14;//用户登录信息已过期
    const ERR_USER_NAME_NOT_REGISTER = 15;//用户名未注册
    const ERR_USER_PASSWD_INPUT_WRONG = 16;//用户密码输入有误
    const ERR_USER_INFO_ERROR = 17;//用户信息有误
    const ERR_USER_NOT_EXISTS = 18;//用户不存在
    const ERR_FORGET_PASS_DIFF = 19;//两次输入密码不一致
    const ERR_RESET_PASSWD_FAIL = 20;//重置保存密码失败
    const ERR_NOTICE_KEY_NOT_EXIST = 21;//短信验证码key不存在
    const ERR_RESET_USERPASS_OVERDUE = 22;//重置密码信息已过期
    const ERR_USER_LOGIN_ERR_FREQUENT = 23;//用户登录失败次数过于频繁
    const ERR_ILL_REQUEST_MESSAGE = 24;//非法请求短信发送接口
    const ERR_UNKNOW_ERROR = 25;//异常错误
    const ERR_URL_CONFIG_KEY_ERR = 26;//URL CONFIG KEY NOT EXIST



    //1000-2000消息类错误
    const ERR_MESSAGE_INFO_EMPTY = 1000;//信息配置为空
    const ERR_MESSAGE_CODE_EMPTY = 1001;//注册短信验证码为空
    const ERR_MESSAGE_PHONE_EMPTY = 1002;//用户手机号不能为空
    const ERR_NOTICE_INFO_EMPTY = 1003;//通知信息为空
    const ERR_NOTICE_INVEST_ID_EMPTY = 1004;//通知内容标ID不能为空
    const ERR_NOTICE_INVEST_MONEY_EMPTY = 1005;//通知投标金额不能为空
    const ERR_NOTICE_RECHARGE_MONEY_EMPTY = 1006;//通知充值金额不能为空
    const ERR_NOTICE_RECHARGE_TRADE_NO_EMPTY = 1007;//通知充值手续费不能为空
    const ERR_NOTICE_WITHDRAW_MONEY_EMPTY = 1008;//通知提现金额不能为空

    //2000-3000购物订单错误信息
    const ERR_ORDER_CREATE_FAILED = 2000;//购物订单创建失败
    const ERR_ORDER_PAY_FAILED = 2001;//购物订单支付失败
    const ERR_ORDER_STOCK_NOT_ENOUGH = 2002;//支付时库存不足


    //3000-4000资金账户
    const ERR_ACCOUNT_NOT_EXISTS = 3000;//账户不存在
    const ERR_ACCOUNT_INSUFFICIENT_AMOUNT = 3001;//账户金额不足
    const ERR_ACCOUNT_LOCKED_NOT_ENOUGH = 3002;//冻结账户金额不足

    //4000-5000第三方错误
    const ERR_RECHARGE_FAILED = 4000;//充值失败


    //5000晒单错误
    const ERR_DISPLAY_ORDER_FAILED = 5000;//晒单失败

    //6000购物车错误
    const ERR_SHOP_CART_ID_ERROR = 6000;//购物车id不存在
    const ERR_SHOP_CART_STATUS_ERROR = 6001;//状态错误，无法删除
    const ERR_SHOP_CART_DELETE_FAILED = 6002;//删除失败
    const ERR_SHOP_CART_GOOD_NOT_EXISTS = 6003;//购物车添加商品id不错在
    const ERR_SHOP_CART_GOOD_ADD_FAILED = 6004;//购物车商品添加失败
    const ERR_SHOP_CART_NUM_UPDATE_FAILED = 6005;//购物车商品数量更新失败
    const ERR_SHOP_CART_STATUS_UPDATE_FAILED = 6006;//创建订单更新购物车状态失败

    //7000商品
    const ERR_INDIANA_GOOD_STOCK_NOT_ENOUTH = 7000;//商品库存不足
    const ERR_INDIANA_GOOD_STOCK_UPDATE_FAILED = 7001;//更新库存失败
    const ERR_INDIANA_GOOD_SOLD_OUT =7002;//该商品已售罄










    public static $arrApiErrDescs = [
        //1000以下系统错误，以上为业务错误
        self::SUCCESS   =>  'success',
        self::ERR_PARAM_INVALID  => '参数错误',
        self::ERR_SIGN_ERR  => '签名错误',
        self::ERR_REDIS_KEY_NOE_EXISTS => '缓存key不存在',
        self::ERR_VERIFY_CODE_WRONG => '图形验证码输入错误',
        self::ERR_REGISTER_CODE_SEND_FAIL => '注册短信验证码发送失败',
        self::ERR_REQUEST_CODE_TOO_FREQUENT => '请求过于频繁',
        self::ERR_REGISTER_AGREMENT_NOT_AGREE => '注册协议未选择',
        self::ERR_REGISTER_MESSAGE_CODE_ERROR => '短信验证码输入错误或已过期',
        self::ERR_PHONE_FORMAT_WRONG => '手机号码格式有误',
        self::ERR_USER_PASSWORD_FORMART_WORNG => '用户登录密码格式有误',
        self::ERR_USER_REGISTER_FAIL  => '用户注册失败',
        self::ERR_USER_REGISTER_PHONE_EXIST => '用户名已注册',
        self::ERR_USER_LOGIN_FAIL => '用户登录失败',
        self::ERR_USER_ACCESS_TOKEN_OVERDUE => '用户登录信息已过期',
        self::ERR_USER_NAME_NOT_REGISTER => '用户名未注册',
        self::ERR_USER_PASSWD_INPUT_WRONG  => '用户密码输入有误',
        self::ERR_USER_INFO_ERROR => '用户信息有误',
        self::ERR_FORGET_PASS_DIFF => '两次输入密码不一致',
        self::ERR_RESET_PASSWD_FAIL => '重置密码保存失败',
        self::ERR_NOTICE_KEY_NOT_EXIST => '短信验证码key不存在',
        self::ERR_RESET_USERPASS_OVERDUE => '重置密码信息已过期',
        self::ERR_USER_LOGIN_ERR_FREQUENT => '用户登录失败次数太过频繁，请5分钟后再进行登录',
        self::ERR_ILL_REQUEST_MESSAGE => '请求非法',
        self::ERR_UNKNOW_ERROR => '异常错误',
        self::ERR_URL_CONFIG_KEY_ERR => 'KEY NOT FUND IN URL CONFIGS ',


        //1000-2000消息类错误
        self::ERR_MESSAGE_INFO_EMPTY => '信息配置为空',
        self::ERR_MESSAGE_CODE_EMPTY => '注册短信验证码为空',
        self::ERR_MESSAGE_PHONE_EMPTY => '用户手机号不能为空',
        self::ERR_NOTICE_INFO_EMPTY => '通知信息为空',
        self::ERR_NOTICE_INVEST_ID_EMPTY => '通知内容标ID不能为空',
        self::ERR_NOTICE_INVEST_MONEY_EMPTY => '通知投标金额不能为空',
        self::ERR_NOTICE_RECHARGE_MONEY_EMPTY => '通知充值金额不能为空',
        self::ERR_NOTICE_RECHARGE_TRADE_NO_EMPTY => '通知充值手续费不能为空',
        self::ERR_NOTICE_WITHDRAW_MONEY_EMPTY => '通知提现金额不能为空',

        //2000-3000借款错误信息
        self::ERR_ORDER_CREATE_FAILED =>    '购物订单创建失败',
        self::ERR_ORDER_PAY_FAILED => '购物订单支付失败',
        self::ERR_ORDER_STOCK_NOT_ENOUGH => '支付时库存不足',
            //3000-4000资金账户及银行卡
        self::ERR_ACCOUNT_NOT_EXISTS => '账户不存在',
        self::ERR_ACCOUNT_INSUFFICIENT_AMOUNT => '账户余额不足',
        self::ERR_ACCOUNT_LOCKED_NOT_ENOUGH => '冻结账户金额不足',
        //4000第三方错误
        self::ERR_RECHARGE_FAILED => '用户充值失败',
        //5000晒单错误
        self::ERR_DISPLAY_ORDER_FAILED => '晒单失败',
        //6000购物车错误
        self::ERR_SHOP_CART_ID_ERROR => '购物车id不存在',
        self::ERR_SHOP_CART_STATUS_ERROR => '状态错误',
        self::ERR_SHOP_CART_DELETE_FAILED => '删除失败',
        self::ERR_SHOP_CART_GOOD_NOT_EXISTS => '购物车商品id不错在',
        self::ERR_SHOP_CART_GOOD_ADD_FAILED => '购物车商品添加失败',
        self::ERR_SHOP_CART_NUM_UPDATE_FAILED => '购物车商品数量更新失败',
        self::ERR_SHOP_CART_STATUS_UPDATE_FAILED => '创建订单时更新购物车状态失败',

        //7000 商品错误
        self::ERR_INDIANA_GOOD_STOCK_NOT_ENOUTH => '商品库存不足',
        self::ERR_INDIANA_GOOD_STOCK_UPDATE_FAILED => '更新库存失败',
        self::ERR_INDIANA_GOOD_SOLD_OUT =>  '该商品已售罄',
    ];







}
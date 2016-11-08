<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao-pc
 * Date: 2016/3/2
 * Time: 10:55
 */

namespace common\models;


use front\models\MobileMessage;
use front\models\UserInfo;

class MessageConfig
{
    /**
     * 获取短信类型key
     */
    //用户登录
    const MESSAGE_TYPE_USER_LOGIN = 'login';
    private static $messages = [
        'login' => '亲，您的验证码为#CODE#(2分钟内有效]，此验证码供您登录使用，请勿向任何人泄露！',
    ];
    /*
     * 消息发送
     * @param $type int different type show different business
     * @param $user int member_id
     * @param $data array params
     * return true or throw ApiBaseException
     */
    public static function Notice($type, $phone, $userId = 0, $data = []){
        $user = UserInfo::get($userId);
        switch($type){
            case 'login':         //注册短信验证码发送
                if(empty($data['code'])){
                    throw new ApiBaseException(ApiErrorDescs::ERR_MESSAGE_CODE_EMPTY);
                }
                if(empty($phone)){
                    throw new ApiBaseException(ApiErrorDescs::ERR_MESSAGE_PHONE_EMPTY);
                }
                $message = self::_getMessageByKey($type);
                $content = str_replace('#CODE#', $data['code'], $message);
                $objPhoneSend = new MobileMessage();
                $ret = $objPhoneSend->sendSms($phone, $content);
                $infos = [
                    'uid' => $userId,
                    'mobile' => $phone,
                    'contents' => $content,
                    'create_time' => time(),
                    'desc'   => '',
                    'types'  => $type,
                    'stauts' => 1
                ];
                $objPhoneSend->saveSendMessageLog($infos);
                return $ret;
//            case 2:         //忘记密码短信验证码发送
//                if(empty($data['code'])){
//                    throw new ApiBaseException(ApiErrorDescs::ERR_MESSAGE_CODE_EMPTY);
//                }
//                if(empty($phone)){
//                    throw new ApiBaseException(ApiErrorDescs::ERR_MESSAGE_PHONE_EMPTY);
//                }
//                $message = self::_getMessageByKey('forgetpass');
//                $content = str_replace('#CODE#', $data['code'], $message);
//                $objPhoneSend = new PhonesmsLog();
//                $ret = $objPhoneSend->sendSms($phone, $content);
//                $infos = [
//                    'uid' => $userId,
//                    'phone' => $phone,
//                    'contents' => $content,
//                    'sendtime' => time(),
//                    'desc'   => '',
//                    'types'  => $type,
//                    'stauts' => 1
//                ];
//                $objPhoneSend->saveSendMessageLog($infos);
//                return $ret;
//            case 3 :            //投资成功添加通知
//                if(empty($data['borrow_id'])){
//                    throw new ApiBaseException(ApiErrorDescs::ERR_NOTICE_INVEST_ID_EMPTY);
//                }
//                if(empty($data['invest_money'])){
//                    throw new ApiBaseException(ApiErrorDescs::ERR_NOTICE_INVEST_MONEY_EMPTY);
//                }
//                $notice = self::_getNoticeByKey('invest');
//                $contents = [
//                    'title' => str_replace(['#BORROW_ID#', '#INVEST_MONEY#'], [$data['borrow_id'], $data['invest_money']], $notice['title']),
//                    'msg' => str_replace(['#BORROW_ID#', '#INVEST_MONEY#'], [$data['borrow_id'], $data['invest_money']], $notice['msg']),
//                    'uid' => $userId
//                ];
//                $objInMsg = new InnerMsg();
//                $objInMsg->add($contents);//总表
//                $innerMsgTabName = 'lzh_inner_msg_' . intval($userId%5);
//                $objSubInMsg = new InnerMsg(['tableName' => $innerMsgTabName]);
//                return $objSubInMsg->add($contents);//分表
//            case 4 :   //充值成功添加站内信
//                if(!isset($data['fee'])){
//                    throw new ApiBaseException(ApiErrorDescs::ERR_NOTICE_RECHARGE_TRADE_NO_EMPTY);
//                }
//                if(!isset($data['real_money'])){
//                    throw new ApiBaseException(ApiErrorDescs::ERR_NOTICE_RECHARGE_MONEY_EMPTY);
//                }
//                $notice = self::_getNoticeByKey('recharge');
//                if(empty($data['fee'])){
//                    $msg = str_replace('#MONEY#', $data['real_money'], $notice['msg'][0]);
//                }else{
//                    $recharge = $data['real_money'] + $data['fee'];
//                    $msg = str_replace(['#MONEY#', '#FEE#', '#RECHARGE#'], [$data['real_money'], $data['fee'], $recharge], $notice['msg'][1]);
//                }
//                $contents = [
//                    'title' => $notice['title'],
//                    'msg' => $msg,
//                    'uid' => $userId
//                ];
//                $objInMsg = new InnerMsg();
//                $objInMsg->add($contents);//总表
//                $innerMsgTabName = 'lzh_inner_msg_' . intval($userId%5);
//                $objSubInMsg = new InnerMsg(['tableName' => $innerMsgTabName]);
//                return $objSubInMsg->add($contents);//分表
//            case 5 : //提现成功
//                if(!isset($data['withdraw_money']) || !isset($data['fee'])){
//                    throw new ApiBaseException(ApiErrorDescs::ERR_NOTICE_WITHDRAW_MONEY_EMPTY);
//                }
//                $notice = self::_getNoticeByKey('withdraw');
//                $contents = [
//                    'title' => $notice['title'],
//                    'msg' => str_replace(['#AMOUNT#', '#FEE#', '#INCOMEAMOUNT#'], [$data['withdraw_money'], $data['fee'], $data['withdraw_money'] - $data['fee']], $notice['msg']),
//                    'uid' => $userId
//                ];
//                $objInMsg = new InnerMsg();
//                $objInMsg->add($contents);//总表
//                $innerMsgTabName = 'lzh_inner_msg_' . intval($userId%5);
//                $objSubInMsg = new InnerMsg(['tableName' => $innerMsgTabName]);
//                return $objSubInMsg->add($contents);//分表
//            case 6://修改提现账户
//                $notice = self::_getNoticeByKey('changeAccount');
//                $contents = [
//                    'title' => $notice['title'],
//                    'msg' => $notice['msg'],
//                    'uid' => $userId
//                ];
//                $objInMsg = new InnerMsg();
//                $objInMsg->add($contents);//总表
//                $innerMsgTabName = 'lzh_inner_msg_' . intval($userId%5);
//                $objSubInMsg = new InnerMsg(['tableName' => $innerMsgTabName]);
//                return $objSubInMsg->add($contents);//分表
//            case 7://修改登录密码
//                $notice = self::_getNoticeByKey('modifyPwd');
//                $contents = [
//                    'title' => $notice['title'],
//                    'msg' => $notice['msg'],
//                    'uid' => $userId
//                ];
//                $objInMsg = new InnerMsg();
//                $objInMsg->add($contents);//总表
//                $innerMsgTabName = 'lzh_inner_msg_' . intval($userId%5);
//                $objSubInMsg = new InnerMsg(['tableName' => $innerMsgTabName]);
//                return $objSubInMsg->add($contents);//分表
//            case 8://提现申请短信验证码发送
//                if(empty($data['code'])){
//                    throw new ApiBaseException(ApiErrorDescs::ERR_MESSAGE_CODE_EMPTY);
//                }
//                if(empty($phone)){
//                    throw new ApiBaseException(ApiErrorDescs::ERR_MESSAGE_PHONE_EMPTY);
//                }
//                $message = self::_getMessageByKey('extcash');
//                $content = str_replace('#CODE#', $data['code'], $message);
//                $objPhoneSend = new PhonesmsLog();
//                $ret = $objPhoneSend->sendSms($phone, $content);
//                $infos = [
//                    'uid' => $userId,
//                    'phone' => $phone,
//                    'contents' => $content,
//                    'sendtime' => time(),
//                    'desc'   => '',
//                    'types'  => $type,
//                    'stauts' => 1
//                ];
//                $objPhoneSend->saveSendMessageLog($infos);
//                return $ret;
//            case 9 ://提现退回站内信
//                if(!isset($data['withdraw_money'])){
//                    throw new ApiBaseException(ApiErrorDescs::ERR_NOTICE_WITHDRAW_MONEY_EMPTY);
//                }
//                $notice = self::_getNoticeByKey('withdraw');
//                $contents = [
//                    'title' => $notice['title'],
//                    'msg' => str_replace('#AMOUNT#', $data['withdraw_money'], $notice['msg']),
//                    'uid' => $userId
//                ];
//                $objInMsg = new InnerMsg();
//                $objInMsg->add($contents);//总表
//                $innerMsgTabName = 'lzh_inner_msg_' . intval($userId%5);
//                $objSubInMsg = new InnerMsg(['tableName' => $innerMsgTabName]);
//                return $objSubInMsg->add($contents);//分表
//            case 10 ://注册成功站内信
//                $notice = self::_getNoticeByKey('thirdBind');
//                $contents = [
//                    'title' => $notice['title'],
//                    'msg' => $notice['msg'],
//                    'uid' => $userId
//                ];
//                $objInMsg = new InnerMsg();
//                $objInMsg->add($contents);//总表
//                $innerMsgTabName = 'lzh_inner_msg_' . intval($userId%5);
//                $objSubInMsg = new InnerMsg(['tableName' => $innerMsgTabName]);
//                return $objSubInMsg->add($contents);//分表
//
//            case 11://充值申请短信
//                $message = self::_getMessageByKey('payonline');
//                $content = $message['content'];
//                $content = str_replace( array( "#USERANEM#","#MONEY#" ), array( $user['user_name'],$data['real_money'] ), $content );
//                $phone = $user['user_phone'];
//                $objPhoneSend = new PhonesmsLog();
//                $ret = $objPhoneSend->sendSms($phone, $content);
//                $infos = [
//                    'uid' => $userId,
//                    'phone' => $phone,
//                    'contents' => $content,
//                    'sendtime' => time(),
//                    'desc'   => '',
//                    'types'  => $type,
//                    'stauts' => 1
//                ];
//                $objPhoneSend->saveSendMessageLog($infos);
//                return $ret;
        }
    }
    //获取短信信息
    private static function _getMessageByKey($key){
        if(empty(self::$messages[$key])){
            throw new ApiBaseException(ApiErrorDescs::ERR_MESSAGE_INFO_EMPTY);
        }
        return self::$messages[$key];
    }

}
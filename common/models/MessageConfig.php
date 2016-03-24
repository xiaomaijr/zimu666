<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao-pc
 * Date: 2016/3/2
 * Time: 10:55
 */

namespace common\models;


class MessageConfig
{
    private static $messages = [
        'payonline' => [
            'content' => '亲爱的芝麻粉#USERANEM#您好，您在线充值的#MONEY#元已成功到账，感谢您对芝麻金融的信任，欢迎来到有爱、有趣、有收益的芝麻圈~！【芝麻金融】',
            'enable' => '1'
        ],
        'payoffline' => [
            'content' => '#USERANEM#您好，您线下充值#MONEY#元已审核通过【芝麻金融】',
            'enable' => '1'
        ],
        'payback' => [
            'content' => '亲爱的芝麻粉#USERANEM#，您有#MONEY#元投资还款到账啦~！芝麻君提醒您,要及时到“我的账户”查询详情哦~ 登陆官网：www.zhimajinrong.com【芝麻金融】',
            'enable' => '1'
        ],
        'withdraw' => [
            'content' => '亲爱的芝麻粉，您已从芝麻金融第三方托管账户提现#MONEY#元，资金已转入您的银行账号，具体到账时间将以各银行入账时间为准。芝麻金融感谢您的陪伴，期待您再来~！客服电话：400-6688-122【芝麻金融】',
            'enable' => '1'
        ],
        'vip' => [
            'content' => '亲爱的芝麻粉#USERANEM#，恭喜您已经通过VIP认证，成为至尊芝麻粉！拥有更多服务和福利，参加各种活动沙龙，芝麻君都会先想着你哒~！【芝麻金融】',
            'enable' => '1'
        ],
        'firstV' => [
            'content' => '亲爱的芝麻粉，您于#ADDTIME#提交的#MONEY#元借款标已通过芝麻金融的风控审核，正式以“#BORROWNAME#”发标了~！募集期#COLLECTDAY#天，芝麻君预祝您在芝麻金融平台上顺利借到所需资金！【芝麻金融】',
            'enable' => '1'
        ],
        'refuse' => [
            'content' => '亲爱的芝麻粉，非常遗憾地通知您，您于#ADDTIME#所发布的#MONEY#元标现已流标，流标原因：不符合芝麻金融平台的借款要求，未能通过风控审核。非常感谢您对芝麻君的信任，详情可登陆我的账户查看，客服电话：400-6688-122【芝麻金融】',
            'enable' => '1'
        ],
        'approve' => [
            'content' => '亲爱的芝麻粉，恭喜您成功募集到#MONEY#元借款！还款日期#DEADTIME#，芝麻君提醒您别忘了及时还款给支持你的借款人哦~  芝麻金融感谢您的支持!【芝麻金融】',
            'enable' => '1'
        ],
        'dayinvest' => [
            'content' => '亲爱的芝麻粉#USERANEM#，您有#MONEY#元投资利息到账啦~！为避免打扰，将不再重复通知。芝麻君提醒您,要及时到“我的账户”查询详情哦~ 登陆官网：www.zhimajinrong.com【芝麻金融】',
            'enable' => '1'
        ],
        'day3invest' => [
            'content' => '亲爱的芝麻粉#USERANEM#，您参加的三月优享投资返3天收益#MONEY#元到账啦~！为避免打扰，将不再重复通知。芝麻君提醒您,要及时到“我的账户”查询详情哦~ 登陆官网：www.zhimajinrong.com【芝麻金融】',
            'enable' => '1'
        ],
        'verify_phone' => '亲爱的芝麻粉，您正在注册芝麻金融，本次的验证码是#CODE#。（芝麻君提醒您：注册过程中的短信验证码、登录密码、交易密码是重要个人信息，务必妥妥地保管，请勿透露给他人。芝麻金融及任何第三方不会向您索要这些信息。）官方微信：zhimajinrong 客服电话：400-6688-122【芝麻金融】',
        'safecode' => '亲爱的芝麻粉，您正在芝麻金融安全中心更改认证手机号码，本次的验证码是#CODE#。如非本人操作，请与客服联系：400-6688-122【芝麻金融】',
        'changephone' => '亲爱的芝麻粉，您正在安全中心更改认证手机号码，本次的验证码是#CODE#。如非本人操作，请与客服联系：400-6688-122【芝麻金融】',
        'newphone' => '亲爱的芝麻粉，您正在安全中心更改手机号码，本次的验证码是#CODE#。 如非本人操作，请与客服联系：400-6688-122【芝麻金融】',
        'newtip' => '',
        'extcash' => '亲爱的芝麻粉，您正在进行账户提现，本次的验证码是#CODE#。 如非本人操作，请与客服联系：400-6688-122',
        'forgetpass' => '亲爱的芝麻粉，您的验证码为#CODE#(3分钟内有效]，此验证码供您找回登录密码使用，请勿向任何人泄露！如有疑问，请联系客服：400-6688-122',
        'register' => '您正在进行注册，本次的验证码是#CODE#。 如非本人操作，请与客服联系：400-6688-122【小麦金融】',
    ];

    private static $notice = [
        'invest' => [
            'title' => '恭喜您投资#BORROW_ID#号项目#INVEST_MONEY#元成功！',
            'content' => '恭喜您投资#BORROW_ID#号项目#INVEST_MONEY#元成功！',
        ],
        'recharge' => [
            'title' => '您刚刚成功充值#RECHARGE#元',
            'content' => '您刚刚成功充值#RECHARGE#元，流水号#TRAND_NO#',
        ],
        'withdraw' => [
            'title' => '您刚刚成功提现#WITHDRAW#元',
            'content' => '您刚刚成功提现#WITHDRAW#元，如不是自己操作,请尽快联系客服',
        ],
        'changeAccount' => [
            'title' => '您刚刚修改了提现的银行帐户',
            'content' => '您刚刚修改了提现的银行帐户,如不是自己操作,请尽快联系客服',
        ],
        'modifyPwd' => [
            'title' => '您刚刚修改了登录密码',
            'content' => '您刚刚修改了登录密码，如不是自己操作,请尽快联系客服',
        ],
    ];

    //需要验证用户已注册的短信请求参数key
    public static $checkExistMsgKeys = [
        'forgetpass',
    ];
    //需要验证用户未注册的短信请求参数key
    public static $checkNotExistMsgKeys = [
        'register',
    ];
    /*
     * 消息发送
     * @param $type int different type show different business
     * @param $user int member_id
     * @param $data array params
     * return true or throw ApiBaseException
     */
    public static function Notice($type, $phone, $userId = 0, $data = []){
        $user = Members::get($userId , 'lzh_members');
        switch($type){
            case 1:         //注册短信验证码发送
                if(empty($data['code'])){
                    throw new ApiBaseException(ApiErrorDescs::ERR_MESSAGE_CODE_EMPTY);
                }
                if(empty($phone)){
                    throw new ApiBaseException(ApiErrorDescs::ERR_MESSAGE_PHONE_EMPTY);
                }
                $message = self::_getMessageByKey('register');
                $content = str_replace('#CODE#', $data['code'], $message);
                $objPhoneSend = new PhonesmsLog();
                $ret = $objPhoneSend->sendSms($phone, $content);
                $infos = [
                    'uid' => $userId,
                    'phone' => $phone,
                    'contents' => $content,
                    'sendtime' => time(),
                    'desc'   => '',
                    'types'  => $type,
                    'stauts' => 1
                ];
                $objPhoneSend->saveSendMessageLog($infos);
                return $ret;
            case 2:         //忘记密码短信验证码发送
                if(empty($data['code'])){
                    throw new ApiBaseException(ApiErrorDescs::ERR_MESSAGE_CODE_EMPTY);
                }
                if(empty($phone)){
                    throw new ApiBaseException(ApiErrorDescs::ERR_MESSAGE_PHONE_EMPTY);
                }
                $message = self::_getMessageByKey('forgetpass');
                $content = str_replace('#CODE#', $data['code'], $message);
                $objPhoneSend = new PhonesmsLog();
                $ret = $objPhoneSend->sendSms($phone, $content);
                $infos = [
                    'uid' => $userId,
                    'phone' => $phone,
                    'contents' => $content,
                    'sendtime' => time(),
                    'desc'   => '',
                    'types'  => $type,
                    'stauts' => 1
                ];
                $objPhoneSend->saveSendMessageLog($infos);
                return $ret;
            case 3 :            //投资成功添加通知
                if(empty($data['borrow_id'])){
                    throw new ApiBaseException(ApiErrorDescs::ERR_NOTICE_INVEST_ID_EMPTY);
                }
                if(empty($data['invest_money'])){
                    throw new ApiBaseException(ApiErrorDescs::ERR_NOTICE_INVEST_MONEY_EMPTY);
                }
                $notice = self::_getNoticeByKey('invest');
                $contents = [
                    'title' => str_replace(['#BORROW_ID#', '#INVEST_MONEY#'], [$data['borrow_id'], $data['invest_money']], $notice['title']),
                    'msg' => str_replace(['#BORROW_ID#', '#INVEST_MONEY#'], [$data['borrow_id'], $data['invest_money']], $notice['msg']),
                    'uid' => $userId
                ];
                $objInMsg = new InnerMsg();
                $objInMsg->add($contents);//总表
                $innerMsgTabName = 'lzh_inner_msg_' . intval($userId%5);
                $objSubInMsg = new InnerMsg(['tableName' => $innerMsgTabName]);
                return $objSubInMsg->add($contents);//分表
            case 4 :   //充值成功
                if(empty($data['trade_no'])){
                    throw new ApiBaseException(ApiErrorDescs::ERR_NOTICE_RECHARGE_TRADE_NO_EMPTY);
                }
                if(empty($data['recharge_money'])){
                    throw new ApiBaseException(ApiErrorDescs::ERR_NOTICE_RECHARGE_MONEY_EMPTY);
                }
                $notice = self::_getNoticeByKey('recharge');
                $contents = [
                    'title' => str_replace(['#RECHARGE#'], [$data['recharge_money']], $notice['title']),
                    'msg' => str_replace(['#RECHARGE#', '#TRAND_NO#'], [$data['recharge_money'], $data['trade_no']], $notice['msg']),
                    'uid' => $userId
                ];
                $objInMsg = new InnerMsg();
                $objInMsg->add($contents);//总表
                $innerMsgTabName = 'lzh_inner_msg_' . intval($userId%5);
                $objSubInMsg = new InnerMsg(['tableName' => $innerMsgTabName]);
                return $objSubInMsg->add($contents);//分表
            case 5 : //提现
                if(empty($data['withdraw'])){
                    throw new ApiBaseException(ApiErrorDescs::ERR_NOTICE_WITHDRAW_MONEY_EMPTY);
                }
                $notice = self::_getNoticeByKey('recharge');
                $contents = [
                    'title' => str_replace('#WITHDRAW#', $data['withdraw_money'], $notice['title']),
                    'msg' => str_replace('#WITHDRAW#', $data['withdraw_money'], $notice['msg']),
                    'uid' => $userId
                ];
                $objInMsg = new InnerMsg();
                $objInMsg->add($contents);//总表
                $innerMsgTabName = 'lzh_inner_msg_' . intval($userId%5);
                $objSubInMsg = new InnerMsg(['tableName' => $innerMsgTabName]);
                return $objSubInMsg->add($contents);//分表
            case 6://修改提现账户
                $notice = self::_getNoticeByKey('changeAccount');
                $contents = [
                    'title' => $notice['title'],
                    'msg' => $notice['msg'],
                    'uid' => $userId
                ];
                $objInMsg = new InnerMsg();
                $objInMsg->add($contents);//总表
                $innerMsgTabName = 'lzh_inner_msg_' . intval($userId%5);
                $objSubInMsg = new InnerMsg(['tableName' => $innerMsgTabName]);
                return $objSubInMsg->add($contents);//分表
            case 7://修改登录密码
                $notice = self::_getNoticeByKey('modifyPwd');
                $contents = [
                    'title' => $notice['title'],
                    'msg' => $notice['msg'],
                    'uid' => $userId
                ];
                $objInMsg = new InnerMsg();
                $objInMsg->add($contents);//总表
                $innerMsgTabName = 'lzh_inner_msg_' . intval($userId%5);
                $objSubInMsg = new InnerMsg(['tableName' => $innerMsgTabName]);
                return $objSubInMsg->add($contents);//分表
        }
    }
    //获取短信信息
    private static function _getMessageByKey($key){
        if(empty(self::$messages[$key])){
            throw new ApiBaseException(ApiErrorDescs::ERR_MESSAGE_INFO_EMPTY);
        }
        return self::$messages[$key];
    }
    //获取通知信息
    private static function _getNoticeByKey($key){
        if(empty(self::$notice[$key])){
            throw new ApiBaseException(ApiErrorDescs::ERR_NOTICE_INFO_EMPTY);
        }
        return self::$notice[$key];
    }

}
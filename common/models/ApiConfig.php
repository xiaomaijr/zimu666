<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao-pc
 * Date: 2016/2/25
 * Time: 15:32
 */

namespace common\models;


class ApiConfig
{
    public static $arrApiCheckParams = [
        'member' => [
            'get-verify-code' => ['key'],
            'get-message-code' => ['user_name', 'verify_code', 'verify_id', 'key'],
            'register' => ['user_name', 'agrement', 'verify_code', 'verify_id', 'phone_code', 'phone_code', 'key'],
            'login' => ['user_name', 'passwd'],
            'passwd' => ['user_name', 'verify_code', 'verify_id', 'key', 'phone_code'],
            'reset-pwd' => ['user_name', 'first_passwd', 'second_passwd'],
        ]
    ];

    public static $arrCommCheckParams = [
        'mobile_type', 'app_ver', 'api_ver', 'channel', 'app_name', 'sign'
    ];

    public static $arrNoNeedCheckApiSign = [

    ];

    const USER_LOGIN_TIMES_LIMIT = 5;//用户登录次数限制
    const USER_LOGIN_DURATION = 1*60;//用户登录次数限制期限
    //资金流水type值映射
    public static $moneyLogTypeMap = [
        2 => '企业网银充值',
        3 => '用户网银充值',
        4 => '提现冻结',
        5 => '提现退回',
        6 => '用户投资冻结',
        7 => '用户投资退回',
        8 => '流标 资金退回',
        9 => '收到融资着还款',

        11 => '企业还款',
        12 => '提现失败',
        13 => '推广奖励',

        15 => '生成债券',
        16 => '返还资金',
        17 => '资金入账',

        20 => '用户投资奖励',
        21 => '企业支付投资奖励',

        29 => '提现成功',

        49 => '提现手续费',

        51 => '用户手动对账',
        52 => '系统自动对账',

        54 => '每日返还利息',

        57 => '注册绑定身份证奖励',
        58 => '邀请好友奖励',
        59 => '提现申请',
    ];
    //资金流水affect_type值映射
    public static $moneyLogAffectTypeMap = [
        1 => '用户对账',
        2 => '用户充值',
        3 => '投资失败',
        4 => '投标冻结',
        5 => '投标退回',
        6 => '投标流标',
        7 => '复审通过 投资者扣款',
        8 => '复审通过 融资者到账',
        9 => '复审失败',
        10 => '提现成功',
        11 => '提现失败',
        12 => '用户收到还款',
        13 => '用户收到还息',
        14 => '企业还款',
        15 => '企业还息',
    ];
    //银行列表
    public static $bankList = [
        [
            'bank_code'=>1, 'bank_name'=>'中国银行',
        ],
        [
            'bank_code'=>2, 'bank_name'=>'中国工商银行',
        ],
        [
            'bank_code'=>3, 'bank_name'=>'中国农业银行',
        ],
        [
            'bank_code'=>4, 'bank_name'=>'交通银行',
        ],
        [
            'bank_code'=>5, 'bank_name'=>'广东发展银行',
        ],
        [
            'bank_code'=>7, 'bank_name'=>'中国建设银行',
        ],
        [
            'bank_code'=>8, 'bank_name'=>'浦发银行',
        ],
        [
            'bank_code'=>10, 'bank_name'=>'招商银行',
        ],
    ];
}
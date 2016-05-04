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
    /*
    * 首页轮播图id
    */
    const INDEX_CAROUSEL_IMG_ID = 4;

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

    //关于我们
    public static $aboutUs = [
        'company_profile' => [
            'title' => '公司简介',
            'content' => '  小麦金融网，一家集P2B、融资租赁、私募基金、资产管理、保理、保险经纪等业务为一体，以现代农业、海外资产配置、投资并购为投资方向的互联网金融综合性信息服务平台，隶属于小麦国际控股集团，成立于2014年，总部位于北京。
          集团注册资本金6亿元人民币，旗下拥有小麦财富、小麦金服等多个品牌，全国分公司已达80余家，管理资产近百亿人民币，拥有全球领先的管理理念和专业的金融投顾团队，为客户提供互联网金融、财富管理、投融资、商业保理、股权投资、企业上市及并购、投资银行等全方位、一站式的高、精、尖综合金融服务。
          小麦金融以资金安全为生命线，运用领先的风控体系，精心打造独具特色的现代农业产业链金融，为客户提供全方位的专属金融服务，并向最前沿的创新金融、科技金融迈进，构筑领先的金融生态圈，长期以来得到了各级政府领导与客户们的广泛认可。
          小麦金融核心团队全部来自国内外资深金融系统的专业人员，囊括金融投资、证券、保险、银行、期货等诸多行业精英，在投资、理财、基金管理、产品设计、风险控制、保值增值等领域拥有丰富一线经验。
          公司秉承“创新为源泉，品质为生命，服务为保障，诚信为基石”的企业理念，通过跨界合作与资源共享稳步做强，使得“让投资变得简单”的金融服务惠及每一位用户。',
        ],
        'wind_control_security' => [
            'title' => '风控保障',
            'content' => '  严密的风控体系 多重工序严控项目审核流程
          小麦金融致力于构建全风险管理体系。依托于小麦控股集团强大后盾，小麦金融创建了严格的风险管控体系。整体风控团队由来自具有多年银行、担保、信贷、小额贷款经验的精英组成。团队成员拥有债权融资、股权投资、信贷风险评审、收购兼并等金融服务行业的实战经验。每笔项目审核必须经过严格的线下审核，由经验丰富的审核人员验证资料真伪、实地考察后，撰写尽职调查报告和风控报告，专业的团队和严密的管理体系从制度、流程、系统等方面全面的保护了投资者利益。',
        ],
        'personal privacy' => [
            'title' => '个人隐私保障',
            'content' => '  小麦金融平台严格遵守国家相关的法律法规，对用户的隐私信息进行保护。未经用户的同意，小麦金融平台不会向任何第三方公司、组织和个人披露用户的个人信息、账户信息以及交易信息（法律法规另有规定的除外）。',
        ],
        'law_protect' => [
            'title' => '法律保障',
            'content' => '  依据《合同法》、《电子签名法》、《关于人民法院审理借贷案件的若干意见》，小麦金融平台提供居间撮合服务明确合法，小麦金融平台提供的电子合同合法并具合同效力，投资人通过小麦金融平台获得的出借理财收益为合法收益并受到法律保护。
          小麦金融同时还和辽宁瀛沈律师事务所签订了战略合作协议，在国家对于互联网金融监管政策和相关法规的指导下，结合行业的特色，为客户提供更好的金融法律服务，让客户享受更有法律保障的投资体验。',
        ],
        'technical_support' => [
            'title' => '',
            'content' => '  小麦金融采用国际领先的系统加密及保护技术，支持安全套接层协议和128位加密技术，数据的发送采用数字签名技术来保证信息以及来源的安全，平台采取双机热备方式对数据进行24小时不间断备份，确保网站在即使在受到黑客攻击后也能快速恢复平台程序和所有数据。',
        ],
    ];

    /*
     * 帮助中心
     */
    public static $helperCentor = [
        [
            'question' => '我忘记了用户名怎么办？',
            'answer' => '您可以致电小麦金融客服热线400-067-7895，我们的客服专员与您核实信息后可以帮您找回用户名。',
        ],
        [
            'question' => '如何更换注册的手机号码？',
            'answer' => '您可以直接在[我的账户]-[账户安全]中修改手机号码。',
        ],
        [
            'question' => '获取短信验证码失败怎么办？',
            'answer' => '请先检查您的手机中是否设置了屏蔽功能，如果确认没有被屏蔽的话，您可以致电小麦金融客服热线400-067-7895，由我们的客服专员帮您查询原因。',
        ],
    ];

}
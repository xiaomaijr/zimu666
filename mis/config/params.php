<?php

define('STATIC_VERSION', '201409191700');


//角色标识
define('SPUPER_MANAGER', 1); //超级管理员
define('GENERAL_MANAGER', 2); //普通管理员
define('PRODUCT_MANAGER', 3); //产品经理
define('OPERATION_MANAGER', 4); //运营经理
define('CUSTOMER_SERVICE_MANAGER', 5); //客服经理


//审核状态
define('RISK_CHECK_DEFAULT', 0); //待审核
define('RISK_CHECK_PASS', 1); //通过
define('RISK_CHECK_DENY', 2); //不通过
define('PRODUCT_UNDEPLOY', 3); //


//准入基本类型
define('PERMIT_TYPE_IS', 1);//是否类
define('PERMIT_TYPE_ENUMERATE', 2);//枚举类
define('PERMIT_TYPE_COMPARE', 3);//比较类
define('PERMIT_TYPE_RANGE', 4);//范围类
define('PERMIT_TYPE_TEXT', 5);//输入类
define('PERMIT_TYPE_IMAGE', 6);//图片类型
define('PERMIT_TYPE_FILE', 7);//非图片类文件
define('PERMIT_TYPE_DATE', 8);//日期
define('PERMIT_TYPE_NUM', 9);//数字
define('PERMIT_TYPE_TEXT_AREA', 10);//较长文本


//政策分类
define('SKU_TYPE_BASIC_PARAMS', 1); //申请信息
define('SKU_TYPE_BODY', 2); //材料文件
define('SKU_TYPE_ENGINE', 3); //初审项目
define('SKU_TYPE_ELECTROMOTOR', 4); //复审项目
define('SKU_TYPE_GEARBOX', 5); //其它补充信息
define('SKU_TYPE_SITE_TURN', 6); //合同文件
define('SKU_TYPE_WHEEL_BRAKE', 7); //
define('SKU_TYPE_SAFETY_EQUIPMENT', 8); //
define('SKU_TYPE_CONTROL_CONFIG', 9); //
define('SKU_TYPE_EXTERNAL_CONFIG', 10); //
define('SKU_TYPE_INNER_CONFIG', 11); //
define('SKU_TYPE_CHAIR_CONFIG', 12); //
define('SKU_TYPE_MULTIMEDIA_CONFIG', 13); //
define('SKU_TYPE_LIGHTS_CONFIG', 14); //
define('SKU_TYPE_GLASS', 15); //
define('SKU_TYPE_AIR_CONDITION', 16); //
define('SKU_TYPE_HIGH_TECH_CONFIG', 17); //
define('SKU_TYPE_OPTIONAL_PACKAGE', 18); //
define('SKU_TYPE_EXTERNAL_COLORS', 19); //
define('SKU_TYPE_INNER_COLORS', 20); //


//分页大小
define('PAGESIZE', 20);


class RiskConfig
{

    //功能菜单
    public static $menu = array(

        'inquiry/list' => [
            'title' => '管理员',
            'mlist' => [
                '/admin/list' => '管理员列表',
                '/admin/add' => '管理员添加',
            ]
        ],
        'category' => [
            'title' => '类别管理',
            'mlist' => [
                '/category/list' => '类别列表',
                '/category/add' => '类别添加',
            ],
        ],
        'product' => [
            'title' => '商品管理',
            'mlist' => [
                '/product/list' => '商品列表',
                '/product/add' => '商品添加',
            ],
        ],
        'order/list' => [
            'title' => '订单管理',
            'mlist' => [
                '/order/list' => '订单列表',
//                'sjt-activity/list' => '试驾团列表',
//                'sjt-order/list' => '报名信息列表',
//                'sjt-buy-order/list' => '订单列表',
//                'sjt-qrcode/list' => '投放二维码',
//                'sjt-qiye-activity/list' => '企业报名列表',
            ],
        ],

        'cms/list' => [
            'title' => 'CMS模块',
            'mlist' => [
                '/cms/list' => 'CMS列表',
                '/cms/add' => 'CMS添加',
            ],
        ],


        'tool' => [
            'title' => '小工具',
            'mlist' => [
                '/tool/recharge' => '用户充值',
            ],
        ],
//        'user/list' => [
//            'title' => '用户管理',
//            'mlist' => [
//            ],
//        ],
//
//        'groups/list' => [
//            'title' => '4S店管理',
//            'mlist' => [
//
//            ],
//        ],
//
//        'finance/list' => [
//            'title' => '支付管理',
//            'mlist' => [
//                'finance/list' => '流水管理',
//                'cash-order/list' => '提现管理',
//            ],
//        ],
//
//
//        'product/list' => [
//            'title' => '产品管理',
//            'mlist' => [
////                'vehicletype/list' => '车型管理',
////                'vehicleline/list' => '车系管理',
////                'brand/list'    => '品牌管理',
//            ],
//        ],
//        'power/list' => [
//            'title' => '系统管理',
//            'mlist' => [
//                'admin/list' => '管理员列表',
//                'role/list' => '角色管理',
//                'power/add' => '创建角色',
//            ],
//        ],
    );


    /**
     * 功能列表
     * @var array
     */
    public static $functionList = array(

        [
            'action' => 'vehicletype/vehline',
            'name' => '联动接口',
        ],
        [
            'action' => 'vehicleline/getseries',
            'name' => '车系联动接口',
        ],
        //登录相关
        [
            'action' => 'site/index',
            'name' => '欢迎页',
        ],


        //车辆相关
        [
            'action' => 'vehicle/list',
            'name' => '车辆列表',
        ],
        [
            'action' => 'vehicle/add',
            'name' => '车辆添加',
        ],
        [
            'action' => 'vehicle/delete',
            'name' => '车辆删除',
        ],
        [
            'action' => 'vehicle/edit',
            'name' => '车辆编辑',
        ],
        [
            'action' => 'vehicle/view',
            'name' => '车辆详情',
        ],
        //车型相关
        [
            'action' => 'vehicletype/list',
            'name' => '车型列表',
        ],
        [
            'action' => 'vehicletype/add',
            'name' => '车型增加',
        ],
        [
            'action' => 'vehicletype/delete',
            'name' => '车型删除',
        ],
        [
            'action' => 'vehicletype/edit',
            'name' => '车型编辑',
        ],
        [
            'action' => 'vehicletype/view',
            'name' => '车型详情',
        ],
        //车系相关
        [
            'action' => 'vehicleline/list',
            'name' => '车系列表',
        ],
        [
            'action' => 'vehicleline/add',
            'name' => '车系增加',
        ],
        [
            'action' => 'vehicleline/delete',
            'name' => '车系删除',
        ],
        [
            'action' => 'vehicleline/edit',
            'name' => '车系编辑',
        ],
        [
            'action' => 'vehicleline/view',
            'name' => '车系详情',
        ],
        //品牌管理
        [
            'action' => 'brand/list',
            'name' => '品牌列表',
        ],
        [
            'action' => 'brand/add',
            'name' => '品牌添加',
        ],
        [
            'action' => 'brand/delete',
            'name' => '品牌删除',
        ],
        [
            'action' => 'brand/edit',
            'name' => '品牌编辑',
        ],
        [
            'action' => 'brand/view',
            'name' => '品牌详情',
        ],

        [
            'action' => 'user/list',
            'name' => '用户管理',
        ],

        //订单相关
        [
            'action' => 'order/excel',
            'name' => '订单导出',
        ],
        [
            'action' => 'order/confirm',
            'name' => '客服确认',
        ],
        [
            'action' => 'order/list',
            'name' => '订单列表',
        ],

        //权限相关
        [
            'action' => 'admin/modify-pwd',
            'name' => '修改密码',
        ],
        [
            'action' => 'admin/add',
            'name' => '添加管理员',
        ],
        [
            'action' => 'admin/list',
            'name' => '管理员列表',
        ],
        [
            'action' => 'admin/view',
            'name' => '管理员详情',
        ],
        [
            'action' => 'admin/edit',
            'name' => '管理员编辑',
        ],
        [
            'action' => 'admin/delete',
            'name' => '管理员删除',
        ],
        //cms相关
        [
            'action' => 'cms/list',
            'name' => 'cms列表',
        ],
        [
            'action' => 'cms/add',
            'name' => 'cms添加',
        ],
        [
            'action' => 'cms/view',
            'name' => 'cms详情',
        ],
        [
            'action' => 'cms/edit',
            'name' => 'cms修改',
        ],
        [
            'action' => 'cms/delete',
            'name' => 'cms删除',
        ],
        //产品
        [
            'action' => 'product/list',
            'name' => '商品列表',
        ],
        [
            'action' => 'product/add',
            'name' => '商品添加',
        ],
        [
            'action' => 'product/edit',
            'name' => '商品修改',

        ],
        [
            'action' => 'product/delete',
            'name' => '商品删除',
        ],
        [
            'action' => 'product/view',
            'name' => '商品详情',
        ],
        [
            'action' => 'product/upload',
            'name' => '图片上传',
        ],
        [
            'action' => 'product/new-upload',
            'name' => '新图片上传',
        ],
        [
            'action' => 'mobilemessage/send',
            'name' => '短信发送',
        ],
        [
            'action' => 'role/list',
            'name' => '权限列表',
        ],
        [
            'action' => 'role/add',
            'name' => '权限修改',
        ],
        [
            'action' => 'power/list',
            'name' => '角色列表',
        ],
        [
            'action' => 'power/add',
            'name' => '角色添加',
        ],
        [
            'action' => 'power/edit',
            'name' => '角色修改',
        ],
        [
            'action' => 'power/delete',
            'name' => '角色删除',
        ],
        [
            'action' => 'power/view',
            'name' => '角色详情',
        ],
        [
            'action' => 'intention/list',
            'name' => '意向车型',
        ],

        [
            'action' => 'order/delete',
            'name' => '订单删除',
        ],

        [
            'action' => 'sku/list',
            'name' => '配置列表',
        ],

        [
            'action' => 'sku/edit',
            'name' => '配置编辑',
        ],

        [
            'action' => 'sku/view',
            'name' => '配置查看',
        ],

        [
            'action' => 'sku/del',
            'name' => '配置删除',
        ],

        [
            'action' => 'pan/edit',
            'name' => '产品编辑',
        ],

        [
            'action' => 'pan/view',
            'name' => '产品查看',
        ],

        [
            'action' => 'inquiry/list',
            'name' => '询价列表',
        ],

        [
            'action' => 'test-drive/list',
            'name' => '试驾列表',
        ],
        [
            'action' => 'sms-bulk/list',
            'name' => '短信群发列表',
        ],
        [
            'action' => 'sms-bulk/add',
            'name' => '配置添加',
        ],
        [
            'action' => 'sms-bulk/edit',
            'name' => '配置编辑',
        ],
        [
            'action' => 'sms-bulk/delete',
            'name' => '配置删除',
        ],

        [
            'action' => 'seller/list',
            'name' => '试驾列表',
        ],

        [
            'action' => 'seller/view',
            'name' => '销售顾问查看',
        ],

        [
            'action' => 'seller/edit',
            'name' => '销售顾问编辑',
        ],

        [
            'action' => 'shop/list',
            'name' => '4S店列表',
        ],

        [
            'action' => 'shop/view',
            'name' => '4S店查看',
        ],


        [
            'action' => 'shop/edit',
            'name' => '4S店编辑',
        ],
        [
            'action' => 'sms-bulk/view',
            'name' => '配置查看'
        ],

        [
            'action' => 'shop/add',
            'name' => '4S店添加'
        ],

        [
            'action' => 'seller/add',
            'name' => '销售顾问添加'
        ],
        [
            'action' => 'sms-bulk/view',
            'name' => '短信详情查看'
        ],

        [
            'action' => 'sms-bulk/send-message',
            'name' => '配置短信发送接口'
        ],

        [
            'action' => 'seller-offer/list',
            'name' => '顾问车型列表',
        ],

        [
            'action' => 'seller-offer/view',
            'name' => '顾问车型查看',
        ],

        [
            'action' => 'seller-offer/edit',
            'name' => '顾问车型编辑',
        ],

        [
            'action' => 'seller-offer/add',
            'name' => '顾问车型添加',
        ],


        [
            'action' => 'city/list',
            'name' => '城市列表',
        ],

        [
            'action' => 'city/view',
            'name' => '城市查看',
        ],

        [
            'action' => 'city/edit',
            'name' => '城市编辑',
        ],

        [
            'action' => 'city/add',
            'name' => '添加城市',
        ],

        [
            'action' => 'user-feedback/list',
            'name' => 'C2B用户反馈列表',
        ],
        [
            'action' => 'user-feedback/view',
            'name' => '用户反馈编辑',
        ],
        [
            'action' => 'user-feedback/post-feedback',
            'name' => '上传用户反馈',
        ],


        [
            'action' => 'inquiry-dispatch/list',
            'name' => '报价列表',
        ],

        [
            'action' => 'inquiry-dispatch/view',
            'name' => '报价查看',
        ],

        [
            'action' => 'inquiry-dispatch/edit',
            'name' => '报价编辑',
        ],

        [
            'action' => 'inquiry-dispatch/add',
            'name' => '报价添加',
        ],


        [
            'action' => 'sd-dispatch/list',
            'name' => '试驾接单列表',
        ],

        [
            'action' => 'sd-dispatch/view',
            'name' => '试驾接单查看',
        ],

        [
            'action' => 'sd-dispatch/edit',
            'name' => '试驾接单编辑',
        ],

        [
            'action' => 'sd-dispatch/add',
            'name' => '试驾接单添加',
        ],

        [
            'action' => 'seller/audit',
            'name' => '销售顾问审核',
        ],
        //试驾团从201开始

        [
            'action' => 'sjt-activity/list',
            'name' => '试驾团列表',
        ],

        [
            'action' => 'sjt-activity/view',
            'name' => '试驾团查看',
        ],

        [
            'action' => 'sjt-activity/edit',
            'name' => '试驾团编辑',
        ],

        [
            'action' => 'sjt-activity/add',
            'name' => '试驾团添加',
        ],

        [
            'action' => 'sjt-order/list',
            'name' => '试驾团报名列表',
        ],

        [
            'action' => 'sjt-order/view',
            'name' => '试驾团报名查看',
        ],

        [
            'action' => 'sjt-order/edit',
            'name' => '试驾团报名编辑',
        ],

        [
            'action' => 'sjt-order/add',
            'name' => '试驾团报名添加',
        ],
        [
            'action' => 'sjt-qrcode/list',
            'name' => '二维码投放管理',
        ],
        [
            'action' => 'sjt-qrcode/add',
            'name' => '二维码创建',
        ],
        [
            'action' => 'sjt-qrcode/edit',
            'name' => '二维码修改',
        ],
        [
            'action' => 'sjt-qrcode/del',
            'name' => '二维码删除',
        ],
        [
            'action' => 'sjt-qrcode/view',
            'name' => '二维码生成',
        ],
        [
            'action' => 'sjt-qiye-activity/list',
            'name' => '企业报名列表',
        ],
        [
            'action' => 'sjt-qiye-activity/excel',
            'name' => '企业报名列表导出',
        ],
        [
            'action' => 'sjt-order/excel',
            'name' => '试驾团报名表导出',
        ],
        [
            'action' => 'sms-bulk/upload',
            'name' => '配置短信号码导入'
        ],
        [
            'action' => 'sms-bulk/delete-message',
            'name' => '配置短信任务删除'
        ],


        [
            'action' => 'sjt-buy-order/list',
            'name' => '订单列表',
        ],

        [
            'action' => 'sjt-buy-order/view',
            'name' => '订单查看',
        ],

        [
            'action' => 'sjt-buy-order/edit',
            'name' => '订单编辑',
        ],

        [
            'action' => 'sjt-activity/del',
            'name' => '试驾团删除',
        ],

        [
            'action' => 'bf-old-order/list',
            'name' => '报废订单列表',
        ],
        [
            'action' => 'bf-old-order/edit',
            'name' => '处理报废订单',
        ],

        [
            'action' => 'bf-new-order/list',
            'name' => '新车换购订单列表',
        ],
        [
            'action' => 'bf-new-order/view',
            'name' => '新车换购订单查看',
        ],
        [
            'action' => 'bf-order/wait-list',
            'name' => '报废待处理列表',
        ],
        [
            'action' => 'bf-order/list',
            'name' => '报废进度查询列表',
        ],
        [
            'action' => 'bf-order/wait-edit',
            'name' => '初审处理',
        ],
        [
            'action' => 'bf-order/edit',
            'name' => '报废处理',
        ],
        [
            'action' => 'bf-order/search',
            'name' => '报废订单搜索',
        ],
        [
            'action' => 'bf-order/search-view',
            'name' => '报废订单搜索详情',
        ],
        [
            'action' => 'shop/qr-code',
            'name' => '查看4S店二维码',
        ],
        [
            'action' => 'finance/list',
            'name' => '支付流水',
        ],
        [
            'action' => 'seller-activity/list',
            'name' => '销售活动列表',
        ],
        [
            'action' => 'seller-activity/audit',
            'name' => '销售活动审核',
        ],
        [
            'action' => 'seller-activity/info',
            'name' => '销售活动详情',
        ],
        [
            'action' => 'seller-activity/off-line',
            'name' => '销售活动下线',
        ],
        [
            'action' => 'seller-act-order/list',
            'name' => '用户锁定活动列表',
        ],
        [
            'action' => 'seller-act-order/view',
            'name' => '报名活动详情',
        ],
        [
            'action' => 'inquiry-dispatch/audit',
            'name' => '发票审核',
        ],
        [
            'action' => 'cms/score-fix',
            'name' => 'C2B积分设置',
        ],
        [
            'action' => 'buy-order/list',
            'name' => '购车订单列表',
        ],
        [
            'action' => 'buy-order/view',
            'name' => '购车订单详情',
        ],
        [
            'action' => 'buy-order/audit',
            'name' => '购车订单审核',
        ],
        [
            'action' => 'buy-order/modify',
            'name' => '购车订单操作',
        ],
        [
            'action' => 'cms/history',
            'name' => 'c2b积分历史',
        ],
        [
            'action' => 'buy-order/excel',
            'name' => '购车订单导出',
        ],
        [
            'action' => 'seller/excel',
            'name' => '销售数据导出',
        ],
        [
            'action' => 'bf-kefu-order/list',
            'name' => '客服操作处理',
        ],
        [
            'action' => 'bf-kefu-order/edit',
            'name' => '客服操作处理',
        ],
        [
            'action' => 'bf-order/deal-list',
            'name' => '服务组待处理',
        ],
        [
            'action' => 'bf-order/deal-edit',
            'name' => '服务组待处理详情',
        ],
        [
            'action' => 'bf-kefu-order/search',
            'name' => '客服搜索处理',
        ],
        [
            'action' => 'bf-kefu-order/search-view',
            'name' => '客服搜索详情页面',
        ],
        [
            'action' => 'bf-order/dfp-list',
            'name' => '待分配解体厂',
        ],
        [
            'action' => 'bf-order/dsh-list',
            'name' => '资料待审核',
        ],
        [
            'action' => 'bf-order/ddd-list',
            'name' => '等待到达解体厂',
        ],
        [
            'action' => 'bf-order/ddw-list',
            'name' => '等待五联单返回',
        ],
        [
            'action' => 'bf-order/ddc-list',
            'name' => '等待残值返回',
        ],
        [
            'action' => 'bf-order/ywc-list',
            'name' => '已完成报废',
        ],
        [
            'action' => 'bf-order/wxy-list',
            'name' => '无效预约',
        ],
        [
            'action' => 'cash-order/list',
            'name' => '提现列表',
        ],
        [
            'action' => 'cash-order/view',
            'name' => '提现详情',
        ],
        [
            'action' => 'cash-order/deal',
            'name' => '提现处理',
        ],
        [
            'action' => 'bf-tx/list',
            'name' => '200返现提现申请',
        ],
        [
            'action' => 'bf-tx/pay-list',
            'name' => '200返现支付审核',
        ],
        [
            'action' => 'bf-tx/edit',
            'name' => '200返现提现操作',
        ],
        [
            'action' => 'bf-tx/pay-edit',
            'name' => '200返现转账操作',
        ],
        [
            'action' => 'bf-tx/excel',
            'name' => '导出200返现通过的列表',
        ],
        [
            'action' => 'bf-order/tongji',
            'name' => '报废付款统计',
        ],
        [
            'action' => 'bf-tx/cw-list',
            'name' => '付款订单列表',
        ],
        [
            'action' => 'bf-tx/cw-view',
            'name' => '付款订单详情',
        ],
        [
            'action' => 'bf-tx/cz-edit',
            'name' => '残值提现审核操作',
        ],
        [
            'action' => 'bf-tx/paycz-edit',
            'name' => '残值返现转账操作',
        ],
        [
            'action' => 'bf-tx/cz-excel',
            'name' => '导出残值返现通过的列表',
        ],

        [
            'action' => 'bf-tx/cz-excel-pay',
            'name' => '导出残值已付款的列表',
        ],
        [
            'action' => 'bf-tx/excel-pay',
            'name' => '导出返现已付款的列表',
        ],
        [
            'action' => 'bf-tx/all-excel-pay',
            'name' => '导出已付款的列表',
        ],
        [
            'action' => 'bf-order/stop-list',
            'name' => '终止订单',
        ],
        [
            'action' => 'cash-order/audit',
            'name' => '提现审核',
        ],
        [
            'action' => 'bf-order/typo',
            'name' => '手续打印',
        ],
        [
            'action' => 'bf-order/statis',
            'name' => '报废订单统计分析',
        ],
        [
            'action' => 'data/get-data',
            'name' => '数据统计',
        ],
        [
            'action' => 'cash-order/excel',
            'name' => '提现列表导出',
        ],
        [
            'action' => 'seller/view2',
            'name' => '销售车型信息',
        ],
        [
            'action' => 'seller/view3',
            'name' => '销售订单信息',
        ],
        [
            'action' => 'seller/quote',
            'name' => '销售报价列表'
        ],
        [
            'action' => 'seller/bill',
            'name' => '销售账户明细'
        ],
        [
            'action' => 'seller-offer/del',
            'name' => '顾问车型删除',
        ],
        [
            'action' => 'seller/gift',
            'name' => '销售礼金赠送',
        ],
        [
            'action' => 'mobilemessage/selectmsg',
            'name' => '验证码查询',
        ],
        [
            'action' => 'mobilemessage/search-code',
            'name' => '验证码查询接口',
        ],
        [
            'action' => 'bf-order/time-statistics',
            'name' => '报废时间统计'
        ],
        [
            'action' => 'bf-order/excel-time-statistics',
            'name' => '报废时间统计excel导出'
        ],
        [
            'action' => 'main-station-config/list',
            'name' => 'M站配置列表'
        ],
        [
            'action' => 'main-station-config/add',
            'name' => 'M站配置添加'
        ],
        [
            'action' => 'main-station-config/edit',
            'name' => 'M站配置编辑'
        ],
        [
            'action' => 'help/img-upload',
            'name' => '图片上传',
        ],
        [
            'action' => 'help/upload',
            'name' => '图片上传接口'
        ],
		[
            'action' => 'buy-order/user-unlock',
            'name' => '用户定金退还',
        ],
        [
            'action' => 'buy-order/seller-unlock',
            'name' => '销售定金退还',
        ],
    );

    /**
     * 角色
     * @var array
     */
    public static $moneyRoles = [
        SPUPER_MANAGER => '超级管理员',
        GENERAL_MANAGER => '普通管理员',
        PRODUCT_MANAGER => '产品经理',
        OPERATION_MANAGER => '运营经理',
        CUSTOMER_SERVICE_MANAGER => '客服经理',

    ];


    //配置SKU分类
    public static $policy_category = [
        SKU_TYPE_BASIC_PARAMS => '基本参数',
        SKU_TYPE_BODY => '车身',
        SKU_TYPE_ENGINE => '发动机',
        SKU_TYPE_ELECTROMOTOR => '发电机',
        SKU_TYPE_GEARBOX => '变速箱',
        SKU_TYPE_SITE_TURN => '底盘转向',
        SKU_TYPE_WHEEL_BRAKE => '车轮制动',
        SKU_TYPE_SAFETY_EQUIPMENT => '安全装备',
        SKU_TYPE_CONTROL_CONFIG => '操控配置',
        SKU_TYPE_EXTERNAL_CONFIG => '外部配置',
        SKU_TYPE_INNER_CONFIG => '内部配置',
        SKU_TYPE_CHAIR_CONFIG => '座椅配置',
        SKU_TYPE_MULTIMEDIA_CONFIG => '多媒体配置',
        SKU_TYPE_LIGHTS_CONFIG => '灯光配置',
        SKU_TYPE_GLASS => '玻璃/后视镜配置',
        SKU_TYPE_AIR_CONDITION => '空调/冰箱',
        SKU_TYPE_HIGH_TECH_CONFIG => '高科技配置',
        SKU_TYPE_OPTIONAL_PACKAGE => '选装包',
        SKU_TYPE_EXTERNAL_COLORS => '外观颜色',
        SKU_TYPE_INNER_COLORS => '内饰颜色',
    ];

    //配置SKU字段名
    public static $category_fields = [
        SKU_TYPE_BASIC_PARAMS => 'basic_params',
        SKU_TYPE_BODY => 'body',
        SKU_TYPE_ENGINE => 'engine',
        SKU_TYPE_ELECTROMOTOR => 'electromotor',
        SKU_TYPE_GEARBOX => 'gearbox',
        SKU_TYPE_SITE_TURN => 'site_turn',
        SKU_TYPE_WHEEL_BRAKE => 'wheel_brake',
        SKU_TYPE_SAFETY_EQUIPMENT => 'safety_equipment',
        SKU_TYPE_CONTROL_CONFIG => 'control_config',
        SKU_TYPE_EXTERNAL_CONFIG => 'external_config',
        SKU_TYPE_INNER_CONFIG => 'inner_config',
        SKU_TYPE_CHAIR_CONFIG => 'chair_config',
        SKU_TYPE_MULTIMEDIA_CONFIG => 'multimedia_config',
        SKU_TYPE_LIGHTS_CONFIG => 'lights_config',
        SKU_TYPE_GLASS => 'glass',
        SKU_TYPE_AIR_CONDITION => 'air_condition',
        SKU_TYPE_HIGH_TECH_CONFIG => 'high_tech_config',
        SKU_TYPE_OPTIONAL_PACKAGE => 'optional_package',
        SKU_TYPE_EXTERNAL_COLORS => 'external_colors',
        SKU_TYPE_INNER_COLORS => 'inner_colors',
    ];

    //SKU字段类型
    public static $policy_type = array(
        PERMIT_TYPE_IS => '是否',
        PERMIT_TYPE_ENUMERATE => '枚举',
        PERMIT_TYPE_COMPARE => '比较',
        PERMIT_TYPE_RANGE => '范围',
        PERMIT_TYPE_TEXT => '文本',
        PERMIT_TYPE_TEXT_AREA => '段落',
        PERMIT_TYPE_DATE => '日期',
        PERMIT_TYPE_NUM => '数字',
        PERMIT_TYPE_IMAGE => '图片文件',
        PERMIT_TYPE_FILE => '文档文件',
    );


}


return [
    'adminEmail' => 'admin@example.com',
];

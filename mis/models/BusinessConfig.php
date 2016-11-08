<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao
 * Date: 15/12/3
 * Time: 下午4:56
 */

namespace mis\models;


class BusinessConfig
{
    public static $businessCode = [
        Constant::BUY_CAR_ORDER_CC_CONFIRM_SUCCESS   =>  '购车订单客服确认成功',
        Constant::BUY_CAR_ORDER_CC_CONFIRM_FAIL   =>  '购车订单客服确认失败',
        Constant::BUY_CAR_ORDER_INVOICE_AUDIT_FAIL   =>  '购车订单发票审核失败',
        Constant::BUY_CAR_ORDER_INVOICE_AUDIT_SUCCESS   =>  '购车订单发票审核成功',
        Constant::FINANCE_CASH_SUCESS  => '财务提现成功',
        Constant::FINANCE_CASH_FAIL  => '财务提现驳回',
        Constant::BUY_CAR_ORDER_ADD_INVOICE_NUMBER  => '上传发票单号',
        Constant::BUY_CAR_ORDER_ADD_USER_TIP => '添加用户客服确认备注',
        Constant::BUY_CAR_ORDER_ADD_SELLER_TIP => '添加销售客服确认备注',
        Constant::BUY_CAR_ORDER_ADD_USER_INVOICE_TIP => '添加用户发票审核备注',
        Constant::BUY_CAR_ORDER_ADD_SELLER_INVOICE_TIP => '添加销售发票审核备注',
        Constant::BUY_CAR_ORDER_INVOICE_FEE => '添加购车发票金额',
        Constant::SELLER_ADD => '销售顾问添加',
        Constant::SELLER_EDIT => '销售顾问编辑',
        Constant::SELLER_AUDIT_SUCCESS => '销售顾问审核-审核通过',
        Constant::SELLER_AUDIT_FAIL => '销售顾问审核-审核失败',
        Constant::SELLER_DELETE => '销售顾问删除',
        Constant::SELLER_AUDITTING => '销售顾问审核-审核进行中',
        Constant::SELLER_REGISTER => '销售顾问审核-新销售注册',
        Constant::CASH_AUDIT_SUCCESS => '提现审核成功',
        Constant::CASH_AUDIT_FAIL => '提现审核失败',
        Constant::ADMIN_USER_CREATE => '管理员创建',
        Constant::ADMIN_USER_UPDATE => '管理员修改',
        Constant::ADMIN_USER_DELETE => '管理员删除',
        Constant::ADMIN_USER_RECOVER => '管理员解封',
        Constant::ADMIN_POWER_MODIFY => '权限修改',
        Constant::SELLER_ACTIVITY_AUDIT_SUCCESS => '销售活动审核通过',
        Constant::SELLER_ACTIVITY_AUDIT_FAIL => '销售活动审核失败',
        Constant::SELLER_ACTIVITY_OFFLINE => '销售活动下线',
        Constant::CMS_RECORD_ADD => '新配置数据添加',
        Constant::CMS_RECORD_MODIFY => '新配置数据修改',
        Constant::CMS_RECORD_DELETE => '新配置数据删除',
        Constant::CMS_RECORD_RECOVER => '新配置数据恢复',
        Constant::SELLER_OFFER_DEL => '顾问车型删除',
        Constant::BUY_ORDER_USER_UNLOCK => '用户定金退还',
        Constant::BUY_ORDER_SELLER_UNLOCK => '销售定金退还',
    ];
}
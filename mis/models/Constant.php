<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao
 * Date: 15/12/3
 * Time: 下午3:44
 */

namespace mis\models;


class Constant
{
    const BUY_CAR_ORDER_CC_CONFIRM_SUCCESS = 1;//购车订单客服确认成功
    const BUY_CAR_ORDER_CC_CONFIRM_FAIL = 2;//购车订单客服确认失败
    const BUY_CAR_ORDER_INVOICE_AUDIT_FAIL = 3;//购车订单发票审核失败
    const BUY_CAR_ORDER_INVOICE_AUDIT_SUCCESS = 4;//购车订单发票审核成功

    const FINANCE_CASH_SUCESS = 5;//提现成功
    const FINANCE_CASH_FAIL = 6;//提现失败

    const BUY_CAR_ORDER_ADD_INVOICE_NUMBER = 7;//填写发票单号
    const BUY_CAR_ORDER_ADD_USER_TIP = 8;//用户客服确认备注
    const BUY_CAR_ORDER_ADD_SELLER_TIP = 9;//销售客服确认备注
    const BUY_CAR_ORDER_ADD_USER_INVOICE_TIP = 10;//用户发票审核备注
    const BUY_CAR_ORDER_ADD_SELLER_INVOICE_TIP =11;//销售发票审核备注
    const BUY_CAR_ORDER_INVOICE_FEE =12;//销售发票金额
    const SELLER_EDIT = 13;//销售顾问编辑
    const SELLER_ADD = 14;//销售顾问添加
    const SELLER_AUDIT_SUCCESS = 15;//销售顾问审核通过
    const SELLER_AUDIT_FAIL = 16;//销售顾问审核失败
    const SELLER_DELETE = 17;//销售顾问删除
    const SELLER_AUDITTING = 18;//审核进行中
    const SELLER_REGISTER = 19;//新注册销售

    const CASH_AUDIT_SUCCESS = 20;//提现审核通过
    const CASH_AUDIT_FAIL = 21;//提现审核不通过

    const ADMIN_USER_CREATE = 22;//管理员创建
    const ADMIN_USER_UPDATE = 23;//管理员信息修改
    const ADMIN_USER_DELETE = 24;//管理员删除
    const ADMIN_USER_RECOVER = 25;//管理员解封

    const ADMIN_POWER_MODIFY = 26;//权限修改

    const SELLER_ACTIVITY_AUDIT_SUCCESS = 27;//销售活动审核通过
    const SELLER_ACTIVITY_AUDIT_FAIL = 28;//销售活动审核失败
    const SELLER_ACTIVITY_OFFLINE = 29;//销售活动下线

    const CMS_RECORD_ADD = 30;//新配置添加
    const CMS_RECORD_MODIFY = 31;//新配置修改
    const CMS_RECORD_DELETE = 32;//新配置删除
    const CMS_RECORD_RECOVER = 33;//新配置数据恢复

    const SELLER_OFFER_DEL = 34;//顾问车型删除
    const BUY_ORDER_USER_UNLOCK = 35;//顾问车型删除
    const BUY_ORDER_SELLER_UNLOCK = 36;//顾问车型删除

}
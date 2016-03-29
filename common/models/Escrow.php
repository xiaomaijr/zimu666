<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao-pc
 * Date: 2016/3/21
 * Time: 16:09
 */

namespace common\models;


use yii\base\Component;

class Escrow extends Component
{
    public $urlArr = []; //接口URL地址

    private $platFormMoneyMoremore = ''; // 平台乾多多标识

    private $antistate = 0;

    private $rsa = null;

    private $urlPrefix = '';

    public function __construct(){
        $apiConf = QddConfig::getConfig();
        $loan = $apiConf['payment'];
        $this->platFormMoneyMoremore = $loan['pfmmm'];
        $this->rsa = new RSA($loan['private_key'], $loan['public_key']);
        $this->urlArr = $apiConf['platform'];
        $this->urlPrefix = $apiConf['platform']['base_host'];
    }
    /**
     * 注册账户
     *
     * @param string $mobile  //手机号
     * @param string $email  //email
     * @param string $realname // 真实姓名
     * @param string  $identification_no // 身份证/营业执照号
     * @param string $loan_plat_form_account //用户在网贷平台账号
     * @param string  $plat_form_money_moremore //乾多多标识
     * @param integer $register_type  // 1:全自动， 2:半自动
     * @param integer $account_type  //账户类型  空：个人账户，1：企业账户
     * @param string $image1 // 身份证/营业执照正面
     * @param string $image2 // 身份证/营业执照背面
     * @param string $remark1 // 备注1
     * @param string $remark2 // 备注2
     * @param string $remark3 // 备注3
     *
     *
     */
    public function registerAccount($loanUsername,$remark='',$returnBackUrl = ''){
        $data['RegisterType'] = 2;
        $data['AccountType'] = '';
        $data['Mobile'] = '';
        $data['Email']  = '';
        $data['RealName'] = '';
        $data['IdentificationNo'] = '';
        $data['Image1'] = '';
        $data['Image2'] = '';
        $data['LoanPlatformAccount'] = $loanUsername;
        $data['PlatformMoneymoremore'] = $this->platFormMoneyMoremore;  // 平台乾多多标识
        $data['RandomTimeStamp'] = '';   // 随机时间戳，启用防抵赖时必填
        $data['Remark1'] = $remark;
        $data['Remark2'] = '';
        $data['Remark3'] = '';
        $backUrlTemp = !empty($returnBackUrl) ? $returnBackUrl : '/Notice/bindReturn';
        if(!empty($backUrl)) {
            $backUrlTemp=$backUrl;
        }
        $qddUrl = UrlConfig::getUrl('qdd_notify');
        $data['ReturnURL'] = $qddUrl . $backUrlTemp;       //返回地址
        $data['NotifyURL'] = $qddUrl . '/Notify/bind';  //后台通知地址
        $str = implode('',$data);
        if($this->antistate == 1){
            $str = strtoupper(md5($str));
        }
        $data['SignInfo']  = $this->rsa->sign($str);
        return $data;
    }
    /**
     * registerAccount回调的时候验证签名
     * @param $data
     * @return bool
     */
    public function registerVerify($data){
        $AccountType = $data['AccountType'];
        $AccountNumber = $data['AccountNumber'];
        $Mobile = $data['Mobile'];
        $Email =  $data['Email'];
        $RealName = $data['RealName'];
        $IdentificationNo = $data['IdentificationNo'];
        $LoanPlatformAccount = $data['LoanPlatformAccount'];
        $loanId = $data['MoneymoremoreId'];
        $PlatformId = $data['PlatformMoneymoremore'];
        $RandomTimeStamp = $data['RandomTimeStamp'];
        $AuthFee = $data['AuthFee'];
        $AuthState = $data['AuthState'];
        $Remark1 = $data['Remark1'];
        $Remark2 = $data['Remark2'];
        $Remark3 = $data['Remark3'];
        $ResultCode = $data['ResultCode'];
        $SignInfo   = $data['SignInfo'];
        $dataStr = $AccountType.$AccountNumber.$Mobile.$Email.$RealName.$IdentificationNo.$LoanPlatformAccount.$loanId.$PlatformId.$AuthFee.$AuthState.$RandomTimeStamp.$Remark1.$Remark2.$Remark3.$ResultCode;
        if($this->antistate == 1) {
            $dataStr = strtoupper(md5($dataStr));
        }
        return $this->rsa->verify($dataStr,$SignInfo);
    }
    /**
     * loanJsonList 方法
     *
     * @param string $LoanOutMoneymoremore  付款人乾多多标识 m或p开头
     * @param string $LoanInMoneymoremore   收款人乾多多标识 m或p开头
     * @param string $OrderNo        网贷平台订单号
     * @param string $BatchNo        网贷平台标号
     * @param double $Amount         金额
     * @param double $FullAmount     满标金额
     * @param string $TransferName   用途
     * @param string $Remark         备注
     * @param string $SecondaryJsonList   二次分配列表 转换成的json对象
     */
    public function loanJsonList($LoanOutMoneymoremore,$LoanInMoneymoremore, $OrderNo, $BatchNo, $Amount, $FullAmount='', $TransferName='', $Remark='', $SecondaryJsonList=''){
        $data = array();
        $data['LoanOutMoneymoremore'] = $LoanOutMoneymoremore;
        $data['LoanInMoneymoremore'] = $LoanInMoneymoremore;
        $data['OrderNo'] = $OrderNo;
        $data['BatchNo'] = $BatchNo;
        $data['Amount'] =  $Amount;
        $data['FullAmount'] = $FullAmount;
        $data['TransferName'] = $TransferName;
        $data['Remark']  = $Remark;
        $data['SecondaryJsonList'] = $SecondaryJsonList;
        return $data;
    }
    /**
     * 转账接口
     *
     * @param string $LoanJsonList    json 格式转账类型  参与签名是源字符串  提交用urlencode编码为utf8
     * @param string $ReturnURL       返回地址
     * @param string $NotifyURL       后台通知地址
     * @param int $TransferAction     转账类型 1：投标  2：还款
     * @param int $Action             操作类型 1：手动转账 2：自动转账
     * @param int $TransferType       转账方式 1：桥连 2:直连
     * @param int $NeedAudit          是否通过审核 空：需要审核 1：自动通过
     * @param string $Remark          备注
     */
    public function transfer($LoanJsonList, $ReturnURL, $NotifyURL, $TransferAction=1, $Action=1, $TransferType=2, $NeedAudit='', $Remark1='',$Remark2='',$Remark3=''){
        $data['LoanJsonList'] = $LoanJsonList;
        $data['PlatformMoneymoremore'] =  $this->platFormMoneyMoremore;
        $data['TransferAction'] = $TransferAction;
        $data['Action'] = $Action;
        $data['TransferType'] = $TransferType;
        $data['NeedAudit'] = $NeedAudit;
        $data['Remark1'] = $Remark1;
        $data['Remark2'] = $Remark2;
        $data['Remark3'] = $Remark3;
        $data['RandomTimeStamp'] = '';
        $data['ReturnURL'] = $ReturnURL ; //返回地址
        $data['NotifyURL'] = $NotifyURL ; //通知地址
        $dataStr = implode('',$data);
        if($this->antistate == 1) {
            $dataStr = strtoupper(md5($dataStr));
        }
        $data['SignInfo'] =  $this->rsa->sign($dataStr);
        $data['LoanJsonList'] =  urlencode($data['LoanJsonList']);
        return $data;
    }

    /**
     * 乾多多充值
     *
     * @param mixed $RechargeMoneymoremore    要充值的账号的钱多多标识 m1 p1以m或p开头
     * @param mixed $PlatformMoneymoremore    P1开通钱多多账号为平台账号时生成，以p开头
     * @param mixed $OrderNo                  平台钱多多单号
     * @param mixed $Amount                   金额 必须大于1
     * @param mixed $ReturnURL                页面返回地址
     * @param mixed $NotifyURL                后台通知地址
     * @param mixed $RechargeType             充值类型 空：网银充值 1：代扣充值
     * @param mixed $CardNo                   银行卡号 代扣时必填
     * @param mixed $RandomTimeStamp          随即时间戳 防抵御时必填
     * @param mixed $Remark1     备注1
     * @param mixed $Remark2     备注2
     * @param mixed $Remark3     备注3
     * @return string
     */
    public function expressCharge($qddMarked, $OrderNo, $Amount,$Remark=''){
        $data = array();
        $data['RechargeMoneymoremore'] = $qddMarked;
        $data['PlatformMoneymoremore'] = $this->platFormMoneyMoremore;
        $data['OrderNo'] = $OrderNo;
        $data['Amount'] = $Amount;
        $data['RechargeType'] =  2;
        $data['FeeType'] =  2;
        $data['CardNo'] = '';
        $data['RandomTimestamp'] = '';
        $data['Remark1'] = $Remark;
        $data['Remark2'] = '';
        $data['Remark3'] = '';
        $data['ReturnURL'] = UrlConfig::getUrl('qdd_notify') .'/member/Notice/chargeReturn';
        $data['NotifyURL'] = UrlConfig::getUrl('qdd_notify') . '/Notify/charge';
        $str = implode('',$data);
        if($this->antistate == 1){
            $str = strtoupper(md5($str));
        }
        if (!empty($data['CardNo'])) {
            //银行卡号加密
            $data['CardNo'] = $this->rsa->encrypt($data['CardNo']);
        }
        $data['SignInfo']  = $this->rsa->sign($str);
        return $data;
    }
}
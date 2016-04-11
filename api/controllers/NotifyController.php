<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao-pc
 * Date: 2016/3/21
 * Time: 17:45
 */

namespace api\controllers;


use common\models\ApiBaseException;
use common\models\ApiErrorDescs;
use common\models\ApiUtils;
use common\models\Escrow;
use common\models\EscrowAccount;
use common\models\InnerMsg;
use common\models\Logger;
use common\models\MemberInfo;
use common\models\MemberMoney;
use common\models\MemberMoneylog;
use common\models\MemberPayonline;
use common\models\Members;
use common\models\MembersStatus;
use common\models\MemberWithdraw;
use common\models\MessageConfig;
use common\models\NameApply;
use common\models\Notify;
use common\models\RedisUtil;
use yii\web\Controller;

class NotifyController extends Controller
{
    /**
     * 注册绑定回调接口
     */
    public function actionBind(){
        $request = ApiUtils::filter($_REQUEST);
        $resultCode = ApiUtils::getIntParam('ResultCode', $request);
        $loan = new Escrow();
        try{
            $verify =  $loan->registerVerify($request);
            if($resultCode == 88 && $verify){
                $userAccount = ApiUtils::getStrParam('LoanPlatformAccount', $request);
                $user = Members::findOne(['user_name' => $userAccount]);
                $data = [
                    'type' => 0,
                    'account'=>$request['AccountNumber'],
                    'mobile' => $request['Mobile'],
                    'email' => $request['Email'],
                    'real_name' => $request['RealName'],
                    'id_card'  => $request['IdentificationNo'],
                    'uid' => $user['id'],
                    'platform' => 0,
                    'platform_marked' => $request['PlatformMoneymoremore'],
                    'qdd_marked' => $request['MoneymoremoreId'],
                    'invest_auth' => 0,
                    'repayment' => 0,
                    'secondary_percent' => 0,
                    'add_time' => time(),
                ];
                $objEscAcc = new EscrowAccount();
                if($objEscAcc->add($data)){
                    $str = "SUCCESS";
                }else{
                    echo 'ERROR';
                    return;
                }
                MessageConfig::Notice(10, '', $user['id']);
                //更新用户信息和状态
                $userid = $user['id'];//用户id
                $member['user_phone'] = $request['Mobile'];
                $member['user_email'] = $request['Email'];
                Members::updateAll($member, ['id' => $userid]);

                //用户详情
                $member_info['idcard'] = $request['IdentificationNo'];
                $member_info['real_name'] = $request['RealName'];
                $member_info['cell_phone'] = $request['Mobile'];
                $member_info['up_time'] = time();
                $b = MemberInfo::getCountByCondition(['uid' => $userid]);
                if ($b == 1) {
                    MemberInfo::updateAll($member_info, ['uid' => $userid]);
                } else {
                    $member_info['uid'] = $userid;
                    $objMemInfo = new MemberInfo();
                    $objMemInfo-> add($member_info);
                }
                MembersStatus::add(['uid' => $userid, 'id_status' => 1, 'phone_status'=>1]);//会员认证状态更新
                MemberMoney::add(['uid' => $userid, 'platform' => 0]);
                $notifyData = [
                    'data_md5' => md5($request),
                    'notify_url' => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
                    'data' => json_encode($request),
                    'type' => '绑定账号' . $str,
                ];
                Notify::add($notifyData);
            }

        }catch(\Exception $e){

        }
    }

    /**
     * 充值回调地址
     */
    public function actionCharge(){
        try{
            $request = ApiUtils::filter($_REQUEST);
            $resultCode = ApiUtils::getIntParam('ResultCode', $request);
            $loan = new Escrow();
            $verify = $loan->chargeVerify($request);

            $orderNo = ApiUtils::getStrParam('OrderNo', $request);
            $id = intval(substr($orderNo,12));
            if($id<0){
                echo 'ERROR';
                return;
            }
            $pLine = MemberPayonline::findOne(['id' => $id]);
            $userType = 0;
            if($verify && $resultCode == 88 && $pLine){
                if($pLine['status'] == 0){
                    $fee = ApiUtils::getFloatParam('Fee', $request);
                    $amount = ApiUtils::getFloatParam('Amount', $request);
                    $realMoney = $amount - $fee;
                    $objMM = new MemberMoney();
                    $objMM->setUserChargeMoneyInfo($pLine['uid'], $realMoney,'用户在线充值',$userType,$fee);

                    $save = [
                        'status' => '1',
                        'loan_no' => ApiUtils::getStrParam('LoanNo', $request),
                    ];
                    MemberPayonline::updateAll($save, ['id' => $id]);
                    MessageConfig::Notice(4, '',$pLine['uid'], ['real_money' => $realMoney, 'fee' => $fee]);
                    $notifyData = [
                        'data_md5' => md5($_POST),
                        'notify_url' => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
                        'data' => json_encode($_POST),
                        'type' => '充值SUCCESS',
                    ];
                    Notify::add($notifyData);
                }
            }else{
                MemberPayonline::updateAll(['status' => 2], ['id' => $id]);
            }
            echo 'SUCCESS';
            return ;

        }catch(\Exception $e){
            $log = sprintf('tag : charge_callback_notify | verify : %s | result : %s | post : %s', var_export($verify, true), $str, json_encode($_POST));
            \Yii::$app->logging->debug($log);
            exit;
        }
    }
    /*
     * 提现回调
     */
    public function actionWithdraw(){
        try{
            $request = ApiUtils::filter($_REQUEST);
            $resultCode = ApiUtils::getIntParam('ResultCode', $request);
            $objEsc = new Escrow();
            $str = "SUCCESS";
            $verify = $objEsc->withdrawVerify($request);
            if($verify){
                $orderNo = ApiUtils::getStrParam('orderNo', $request);
                $ids = explode('-', $orderNo);
                $withdrawUid = intval($ids[1]);
                $withdrawNid = intval($ids[2]);
                $withdrawInfo = MemberWithdraw::getDataByID($withdrawNid);
                if($resultCode == 88 && $withdrawInfo['withdraw_status'] == MemberWithdraw::WITHDRAW_STATUS_SUBMIT){
                    $amount = ApiUtils::getFloatParam('Amount', $request);
                    $fee = ApiUtils::getFloatParam('FeeWithdraws', $request);
                    $updata = [];
                    $updata['withdraw_status'] = MemberWithdraw::WITHDRAW_STATUS_SUCCESS;
                    $updata['first_fee']       = ApiUtils::getFloatParam('fee', $request);
                    $updata['second_fee']      = $fee;
                    $updata['success_money']   = ($amount-$fee);
                    $updata['loanno']          = ApiUtils::getStrParam('LoanNo', $request);
                    $updata['notify_time']     = time();
                    $effectNums = MemberWithdraw::updateAll($updata, ['id' => $withdrawNid]);
                    if($effectNums){
                        $info   = sprintf("提现成功,扣除实际手续费%01.2f元，到账金额%01.2f元", $fee, ($amount-$fee));
                        //更新用户的money
                        $objMM = new MemberMoney();
                        $objMM->setUserWithdrawMoneyInfo($withdrawUid,$amount,$info);
                        //短信通知
                        //Notice(12,$withdrawUid,$amount);
                        //站内信通知
                        MessageConfig::Notice(5, '', $withdrawUid, ['withdraw_money' => $amount, 'fee' => $fee]);
                        $ntyData = [
                            'data_md5' => md5($_POST),
                            'notify_url' => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
                            'data' => json_encode($_POST),
                            'type' => '提现' . $str,
                        ];
                        Notify::add($ntyData);
                    }
                }elseif($resultCode == 89){ //退回资金
                    $updata = [];
                    $updata['withdraw_status'] = MemberWithdraw::WITHDRAW_STATUS_RETURNED;
                    $updata['is_rollback']     = 1;
                    $updata['rollback_time']   = time();
                    $updata['rollback_loanno'] = ApiUtils::getStrParam('LoanNo', $request);
                    $effectNums = MemberWithdraw::updateAll($updata, ['id' => $withdrawNid]);
                    if($effectNums){
                        $amount = ApiUtils::getFloatParam('Amount', $request);
                        $info = sprintf("提现退回资金%01.2f元", $amount);
                        //更新用户的money
                        $objMM = new MemberMoney();
                        $objMM->setUserWithdrawRollMoneyInfo($withdrawUid,$amount,$info);
                        //站内信通知
                        MessageConfig::Notice(9, '', $withdrawUid, ['withdraw_money' => $amount]);
                        $ntyData = [
                            'data_md5' => md5($_POST),
                            'notify_url' => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
                            'data' => json_encode($_POST),
                            'type' => '提现退回' . $str,
                        ];
                        Notify::add($ntyData);
                    }
                }else{
                    //返回的Code提示错误 但是我们也应该认为成功了
                    $ntyData = [
                        'data_md5' => md5($_POST),
                        'notify_url' => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
                        'data' => json_encode($_POST),
                        'type' => '提现ERR' . $resultCode,
                    ];
                    Notify::add($ntyData);
                }
                echo $str;
            }
        }catch(\Exception $e){
            $log = sprintf('tag : withdraw_callback_notify | verify : %s | result : %s | post : %s', var_export($verify, true), $str, json_encode($_POST));
            \Yii::$app->logging->debug($log);
            echo "ERROR";
        }
    }
}
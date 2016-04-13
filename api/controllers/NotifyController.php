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
use common\models\BorrowInfo;
use common\models\BorrowInvest;
use common\models\BorrowInvestor;
use common\models\Escrow;
use common\models\EscrowAccount;
use common\models\InnerMsg;
use common\models\InvestDeta;
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
    public $enableCsrfValidation=false;

    public function beforeAction($action)
    {
        if(parent::beforeAction($action)){
            $strControllerId = $action->controller->id;
            $strActionId = $action->id;
            \Yii::$app->logging->trace($strControllerId . '/' . $strActionId . json_encode($_REQUEST));
            return true;
        }
        return false;
    }
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
                $paramJsonStr = json_encode($request);
                $notifyData = [
                    'data_md5' => md5($paramJsonStr),
                    'notify_url' => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
                    'data' => $paramJsonStr,
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

        $request = ApiUtils::filter($_REQUEST);
        $resultCode = ApiUtils::getIntParam('ResultCode', $request);
        $loan = new Escrow();
        $verify = $loan->chargeVerify($request);

        $orderNo = ApiUtils::getStrParam('OrderNo', $request);
        $id = intval(substr($orderNo,19));
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
                $loanNo = ApiUtils::getStrParam('LoanNo', $request);
                $transaction = \Yii::$app->getDb()->beginTransaction();
                try{
                    $realMoney = $amount - $fee;
                    $objMM = new MemberMoney();
                    $objMM->setUserChargeMoneyInfo($pLine['uid'], $realMoney, $loanNo, '用户在线充值', $userType, $fee);

                    $save = [
                        'status' => '1',
                        'loan_no' => $loanNo,
                    ];
                    if(MemberPayonline::updateAll($save, ['id' => $id]) === false){
                        throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '充值记录更新失败');
                    }
                    MessageConfig::Notice(4, '',$pLine['uid'], ['real_money' => $realMoney, 'fee' => $fee]);
                    $transaction->commit();
                    $paramJsonStr = json_encode($request);
                    $notifyData = [
                        'data_md5' => md5($paramJsonStr),
                        'notify_url' => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
                        'data' => $paramJsonStr,
                        'type' => '充值SUCCESS',
                    ];
                    Notify::add($notifyData);
                }catch(ApiBaseException $e){
                    $transaction->rollBack();
                    $log = sprintf('tag : charge_callback_notify | verify : %s | result : %s | post : %s', $e->getCode(), $e->getMessage(), json_encode($request));
                    \Yii::$app->logging->debug($log);
                    echo 'ERROR';
                    exit;
                }
            }
        }else{
            MemberPayonline::updateAll(['status' => 2], ['id' => $id]);
        }
        echo 'SUCCESS';
        return ;
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
                        $paramJsonStr = json_encode($request);
                        $ntyData = [
                            'data_md5' => md5($paramJsonStr),
                            'notify_url' => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
                            'data' => $paramJsonStr,
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
                        $paramJsonStr = json_encode($request);
                        $ntyData = [
                            'data_md5' => md5($paramJsonStr),
                            'notify_url' => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
                            'data' => $paramJsonStr,
                            'type' => '提现退回' . $str,
                        ];
                        Notify::add($ntyData);
                    }
                }else{
                    //返回的Code提示错误 但是我们也应该认为成功了
                    $paramJsonStr = json_encode($request);
                    $ntyData = [
                        'data_md5' => md5($paramJsonStr),
                        'notify_url' => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
                        'data' => $paramJsonStr,
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
    /*
     * 投资回调
     */
    public function actionInvestNotify(){
        try{
            $request = ApiUtils::filter($_REQUEST);
            $objEsc = new Escrow();
            $verify = $objEsc->transferVerify($request);
            if($verify){
                $loanList = json_decode(urldecode($request['LoanJsonList']), true);
                $investInfo = isset($loanList[0])?$loanList[0]:$loanList;
                //红包返回数组为2的数据
                if(count($loanList)>1){
                    $investInfo['LoanNo']=$loanList[0]['LoanNo'].','.$loanList[1]['LoanNo'];
                }
                $orderString  =  $investInfo['OrderNo'];
                $orderArray  = explode('_',$orderString);
                $orders = $orderArray[0];
                $investId = substr($orders,12);
                $borrowId = intval($orderArray[1]);
                $userId = intval($orderArray[2]);
                $borrowInvestorTable = 'lzh_borrow_investor_'.intval($borrowId%3);
                $investorDetailTable = 'lzh_investor_detail_'.intval($userId%5);
                if(intval($request['ResultCode']) != 88){
//                    $this->investRollback($investId,$borrowId,$userId); //返回错误 删除投资信息  保留invest
                    $paramJsonStr = json_encode($request);
                    $ntyData = [
                        'data_md5' => md5($paramJsonStr),
                        'notify_url' => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
                        'data' => $paramJsonStr,
                        'type' => '投标失败',
                    ];
                    Notify::add($ntyData);
                }else{ //返回成功，更新投资信息并扣除资金
                    $objIntor = new BorrowInvestor(['tableName' => $borrowInvestorTable]);
                    $iinfo = $objIntor->get($investId);
                    $borrowInfo = BorrowInfo::get($borrowId);
                    $investUserMoney = MemberMoney::getUserPlatformMoney($userId);
                    $moneyLogTable = 'lzh_member_moneylog_'.intval($userId%10);
                    $objMMLog = new MemberMoneylog(['tableName' => $moneyLogTable]);
                    $objInvestDeta = new InvestDeta(['tableName' => $investorDetailTable]);
                    if(ApiUtils::getIntParam('Action', $request) == 2){ // 退回投标
                        //解除投资用户的冻结金额
                        $userTotalMoney =  $investUserMoney['total_money']  + $iinfo['investor_capital'];
                        $userFreezeMoney = $investUserMoney['freeze_money'] - $iinfo['investor_capital'];

                        //MemberMoney
                        $memberMoneyRecord = [
                            'total_money'    => $userTotalMoney,
                            'freeze_money'   => $userFreezeMoney
                        ];
                        //MemberMoneyLog
                        $moneylog = [
                            'uid'      => $userId,
                            'platform' => 0,
                            'type'     => MemberMoneylog::USER_INVEST_ROLLBACK,
                            'affect_money'  => $iinfo['investor_capital'],
                            'affect_type'   => MemberMoneylog::AFFECT_INVEST_ROLLBACK,
                            'affect_before' => $investUserMoney['total_money'],
                            'total_money'   => $userTotalMoney,
                            'charge_money'  => $investUserMoney['charge_money'],
                            'invest_money'  => $investUserMoney['invest_money'],
                            'withdraw_money'=> $investUserMoney['withdraw_money'],
                            'back_money'    => $investUserMoney['back_money'],
                            'collect_money' => $investUserMoney['collect_money'],
                            'freeze_money'  => $userFreezeMoney,

                            'info' => '投资资金退回',
                            'add_time' => time(),
                            'add_ip' => ApiUtils::get_client_ip(),
                            'target_uid' => $userId,
                            'target_uname' => 'invest_rollback',
                        ];

                        // 更新借款状态
                        $newBorrowInfo = [];
                        if($borrowInfo['borrow_status']==4){
                            $newBorrowInfo['borrow_status'] = 2;
                        }
                        $newBorrowInfo['borrow_times'] = $borrowInfo['borrow_times']-1;
                        $newBorrowInfo['has_borrow']   = $borrowInfo['has_borrow'] - $iinfo['investor_capital'];

                        $db = \Yii::$app->getDb();
                        $transaction = $db->beginTransaction();
                        $moneyMoneyLogId = $objMMLog->add($moneylog);
                        $moneyMoneyId = MemberMoney::updateAll($memberMoneyRecord, ['id' => $investUserMoney['id']]);
                        $borrowInfoId = BorrowInfo::updateAll($newBorrowInfo, ['id' => $investInfo['BatchNo']]);

                        $investorStatus = $objIntor->updateAll(['loanno' => '','status' => 1], ['id' => $investId]);
                        BorrowInvest::updateAll(['loanno'=> '','status'=>1], ['id' => $investId]);
                        $detailStatus = $objInvestDeta->updateAll(['pay_status' => 0], ['invest_id' => $investId]);
                        //站内信
                        MessageConfig::Notice(3, '', $userId, ['invest_money'=>$iinfo['investor_capital'], 'borrow_id'=>$borrowId]);

                        if($moneyMoneyLogId && $moneyMoneyId && $borrowInfoId && $investorStatus && $detailStatus){
                            $transaction->commit();
//                            $this->investRollback($investId,$borrowId,$userId);
                            echo 'SUCCESS';
                        }else{
                            $transaction->rollback();
                        }
                        exit;
                    }


                     // 支付成功之后 将序号更新到投资记录标，更新借款标信息
                    $borrowId = $investInfo['BatchNo'];
                    $money = $investInfo['Amount'];
                    $hongbaoMoney =  0;
                    if(!$iinfo['loanno']){
                        $db = \Yii::$app->getDb();
                        $transaction = $db->beginTransaction();
                        $investorStatus = $objIntor->updateAll(['loanno'=>$investInfo['LoanNo'],'status'=>1], ['id' => $investId]);
                        BorrowInvest::updateAll(['loanno'=>$investInfo['LoanNo'],'status'=>1], ['id' => $investId]);
                        $detailStatus = $objInvestDeta->updateAll(['pay_status' => 1], ['invest_id' => $investId]);
                        $upborrowarr = [];
                        $upborrowarr['has_borrow'] = $borrowInfo['has_borrow']+$money;
                        $upborrowarr['borrow_times'] = $borrowInfo['borrow_times']+1;
                        $borrowStatus = BorrowInfo::updateAll($upborrowarr, ['id' => $borrowId]);
                        $memberMoneyRecord = [
                            'total_money'    => $investUserMoney['total_money']  - $iinfo['investor_capital'],
                            'freeze_money'   => $investUserMoney['freeze_money'] + $iinfo['investor_capital'],
                        ];
                        $moneylog = [
                            'uid'      => $userId,
                            'platform' => 0,
                            'type'     => MemberMoneylog::USER_INVEST_FREEZE,
                            'affect_money'  => 0-$iinfo['investor_capital'],
                            'affect_type'   => MemberMoneylog::AFFECT_INVEST_FREEZE,
                            'affect_before' => $investUserMoney['total_money'],
                            'total_money'   => $memberMoneyRecord['total_money'],
                            'charge_money'  => $investUserMoney['charge_money'],
                            'invest_money'  => $investUserMoney['invest_money'],
                            'withdraw_money'=> $investUserMoney['withdraw_money'],
                            'back_money'    => $investUserMoney['back_money'],
                            'collect_money' => $investUserMoney['collect_money'],
                            'freeze_money'  => $memberMoneyRecord['freeze_money'],

                            'info' => '用户投资'.$borrowId.'号标'.$money,
                            'add_time' => time(),
                            'add_ip' => ApiUtils::get_client_ip(),
                            'target_uid' => $userId,
                            'target_uname' => 'invest_freeze',
                        ];
                        $moneyMoneyLogId = $objMMLog->add($moneylog);
                        $moneyMoneyId = MemberMoney::updateAll($memberMoneyRecord, ['id' => $investUserMoney['id']]);


                        //站内信
                        MessageConfig::Notice(3, '', $userId, ['invest_money'=>$iinfo['investor_capital'], 'borrow_id'=>$borrowId]);

                        if($investorStatus && $borrowStatus && $detailStatus && $moneyMoneyId && $moneyMoneyLogId ){
                            if( ($borrowInfo['has_borrow']+$money+$hongbaoMoney) == $borrowInfo['borrow_money']){
                                $saveborrow = [];
                                $saveborrow['borrow_status'] = 4;
                                $saveborrow['full_time'] = time();
                                BorrowInfo::updateAll($saveborrow, ['id' => $borrowId]);
                            }
                            $transaction->commit();
                            $str =  "SUCCESS";
                        }else{
                            $transaction->rollback();
                            $str =  'SUCCESS';
                        }
                    }else{
                        $str =  "SUCCESS";
                    }
                    $paramJsonStr = json_encode($request);
                    $ntyData = [
                        'data_md5' => md5($paramJsonStr),
                        'notify_url' => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
                        'data' => $paramJsonStr,
                        'type' => '普通投标' . $str,
                    ];
                    Notify::add($ntyData);
                    echo $str;
                }
            }
        }catch(ApiBaseException $e){
            $log = sprintf('tag : invest_callback_notify | verify : %s | result : %s | post : %s', var_export($verify, true), $str, json_encode($_POST));
            \Yii::$app->logging->debug($log);
            echo "ERROR";
        }
    }


}
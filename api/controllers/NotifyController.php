<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao-pc
 * Date: 2016/3/21
 * Time: 17:45
 */

namespace api\controllers;


use common\models\ApiUtils;
use common\models\Escrow;
use common\models\EscrowAccount;
use common\models\Logger;
use common\models\MemberInfo;
use common\models\MemberMoneylog;
use common\models\MemberPayonline;
use common\models\Members;
use common\models\MembersStatus;
use common\models\MessageConfig;
use common\models\NameApply;
use common\models\Notify;
use yii\web\Controller;

class NotifyController extends Controller
{
    /**
     * 注册绑定回调接口
     */
    public function actionBind(){
        if($_POST['ResultCode']=='88'){
            $loan = new Escrow();
            $verify =  $loan->registerVerify($_POST);
            if($verify){
                $str = '';
                $user = Members::find()->where("user_name='{$_POST['LoanPlatformAccount']}'")->one();
                $data = array(
                    'type' => 0,
                    'account'=>$_POST['AccountNumber'],
                    'mobile' => $_POST['Mobile'],
                    'email' => $_POST['Email'],
                    'real_name' => $_POST['RealName'],
                    'id_card'  => $_POST['IdentificationNo'],
                    'uid' => $user['id'],
                    'platform' => 0,
                    'platform_marked' => $_POST['PlatformMoneymoremore'],
                    'qdd_marked' => $_POST['MoneymoremoreId'],
                    'invest_auth' => 0,
                    'repayment' => 0,
                    'secondary_percent' => 0,
                    'add_time' => time(),
                );
                $objEscAcc = new EscrowAccount();
                if($objEscAcc->add($data)){
                    $str = "SUCCESS";
                }else{
                    echo 'ERROR';
                    return;
                }

                //更新用户信息和状态
                $userid = $user['id'];//用户id
                $member['user_phone'] = $_POST['Mobile'];
                $member['user_email'] = $_POST['Email'];
                Members::updateAll($member, ['id' => $userid]);

                //用户详情
                $member_info['idcard'] = $_POST['IdentificationNo'];
                $member_info['real_name'] = $_POST['RealName'];
                $member_info['cell_phone'] = $_POST['Mobile'];
                $member_info['up_time'] = time();
                $b = MemberInfo::getCountByCondition(['uid' => $userid]);
                if ($b == 1) {
                    MemberInfo::updateAll($member_info, ['uid' => $userid]);
                } else {
                    $member_info['uid'] = $userid;
                    $objMemInfo = new MemberInfo();
                    $objMemInfo-> add($member_info);
                }

                $data_apply['idcard'] = $_POST['IdentificationNo'];
                $data_apply['up_time'] = time();
                $data_apply['uid'] = $userid;
                $data_apply['status'] = 0;
                NameApply::add($data_apply);//实名认证更新
                MembersStatus::add(['uid' => $userid, 'id_status' => 1]);//会员认证状态更新
                $notifyData = [
                    'data_md5' => md5($_POST),
                    'notify_url' => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
                    'data' => json_encode($_POST),
                    'type' => '绑定账号' . $str,
                ];
                Notify::add($notifyData);
                //用户状态
                MembersStatus::setMemberStatus($userid, 'phone', 1, 10, '手机');
                MembersStatus::setMemberStatus($userid, 'email', 1, 9, '邮箱');
                echo $str;exit;
            }
        }
    }

    /**
     * 充值回调地址
     *
     */
    public function charge(){
        $loan = new Escrow();

        //验证签名
        $_POST = ApiUtils::filter($_POST);
        $verify = $loan->chargeVerify($_POST);

        //订单id
        $orders = $_POST['OrderNo'];
        $id = intval(substr($orders,12));
        $info = array();

        //更新回调状态
        if($id > 0){
            $info = $obj = MemberPayonline::findOne($id);
            $obj->updateNotifyStatus();
        }

        //更新订单和金额，并返回结果
        $str = 'ERROR';
        if($verify && $_POST['ResultCode']=='88' && $info){
            if($info['status']==1){
                $str = 'SUCCESS';
            }else{
                $updata = array(
                    'status'=>'1',
                    'loan_no'=> $_POST['LoanNo'],
                );
                if(intval($_POST['Fee'])>0) {
                    $realMoney = $_POST['Amount'] - $_POST['Fee'];
                } else {
                    $realMoney = $_POST['Amount'];
                }
                $moneyLogTabName = 'lzh_member_moneylog_' . intval($info['uid']%10);
                $objMonLog = new MemberMoneylog(['tableName' => $moneyLogTabName]);
                if($objMonLog->memberMoneyLog($info['uid'],3,$realMoney,"在线充值")) {
                    MemberPayonline::updateAllCounters($updata, ['id' => $id]);//核实成功，
                    MessageConfig::Notice(11, '',$info['uid'], ['real_money' => $realMoney]);
                    $str = "SUCCESS";
                }
            }
            $notifyData = [
                'data_md5' => md5($_POST),
                'notify_url' => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
                'data' => json_encode($_POST),
                'type' => '充值' . $str,
            ];
            Notify::add($notifyData);
        }

        //返回结果并记录日志
        echo $str;
        $log = sprintf('tag : charge_callback_notify | verify : %s | result : %s | post : %s', var_export($verify, true), $str, json_encode($_POST));
        \Yii::$app->logging->debug($log);
        exit;
    }
}
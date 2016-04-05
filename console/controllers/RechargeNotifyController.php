<?php

/**
 * Created by PhpStorm.
 * User: zhangxiao-pc
 * Date: 2016/4/5
 * Time: 17:27
 */
namespace console\controller;

use common\models\ApiBaseException;
use common\models\ApiErrorDescs;
use common\models\MemberMoneylog;
use common\models\MemberPayonline;
use common\models\MessageConfig;
use common\models\RedisUtil;
use yii\console\Controller;

class RechargeNotifyController extends Controller
{
    public function actionRecharge(){
        $redis = RedisUtil::getRedis();
        $rechargeListKey = 'rechargeNotifyList';
        if(!$redis->lLen($rechargeListKey)){
            return ;
        }
        $rechargeData = $redis->rPop($rechargeListKey);
        $data = json_decode($rechargeData, true);

        $orders = $data['OrderNo'];
        $id = intval(substr($orders,12));
        $info = $obj = MemberPayonline::findOne($id);
        if($info['status']==1){
            return;
        }
        $updata = [
            'status'=>'1',
            'loan_no'=> $data['LoanNo'],
        ];
        if(intval($data['Fee'])>0) {
            $realMoney = $data['Amount'] - $data['Fee'];
        } else {
            $realMoney = $data['Amount'];
        }
        $moneyLogTabName = 'lzh_member_moneylog_' . intval($info['uid']%10);
        $transaction = \Yii::$app->getDb()->beginTransaction();
        try{
            $objMonLog = new MemberMoneylog(['tableName' => $moneyLogTabName]);
            if($objMonLog->memberMoneyLog($info['uid'],3,$realMoney,"在线充值")) {
                if(!MemberPayonline::updateAll($updata, ['id' => $id])){
                    throw new ApiBaseException(ApiErrorDescs::ERR_RECHARGE_NOTIFY_PAYLINE_UPDATE_FAIL);
                }//核实成功，
                MessageConfig::Notice(11, '',$info['uid'], ['real_money' => $realMoney]);
                $str = "SUCCESS";
            }
        }catch(ApiBaseException $e){
            $transaction->rollback();
            RedisUtil::getRedis()->lPush('rechargeNotifyList', json_encode($data));
        }
    }
}
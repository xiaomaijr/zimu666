<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao-pc
 * Date: 2016/3/11
 * Time: 14:48
 */

namespace api\controllers;


use common\models\ApiBaseException;
use common\models\ApiConfig;
use common\models\ApiErrorDescs;
use common\models\ApiUtils;
use common\models\EscrowAccount;
use common\models\InnerMsg;
use common\models\MemberBanks;
use common\models\MemberInfo;
use common\models\MemberMoneylog;
use common\models\TimeUtils;

class UserController extends UserBaseController
{
    /*
     * 消息中心
     */
    public function actionMsgList(){
        try{
            $request = $_REQUEST;
            $userId = ApiUtils::getIntParam('user_id', $request);
            $page = ApiUtils::getIntParam('p', $request, 1);
            $pageSize = ApiUtils::getIntParam('page_size', $request, 100);
            $timer = new TimeUtils();
            $timer->start('get_msg_list');
            //暂时没用到分表
//            $tableName = 'lzh_inner_msg_' . $userId%5;
//            $objMsg = new InnerMsg(['tableName' => $tableName]);
            $objMsg = new InnerMsg();
            $list = $objMsg->getMsgByUid($userId, $page, $pageSize);
            $timer->stop('get_msg_list');

            $result = [
                'code' => ApiErrorDescs::SUCCESS,
                'message' => 'success',
                'result' => $list,
            ];
        }catch(ApiBaseException $e){
            $result = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
        }
        header('Content-type: application/json');
        echo json_encode($result);

        $this->logApi(__CLASS__, __FUNCTION__, $result);
        \Yii::$app->end();
    }
    /*
     * 资金流水
     */
    public function actionFundFlow(){
        try{
            $request = $_REQUEST;
            $userId = ApiUtils::getIntParam('user_id', $request);
            $page = ApiUtils::getIntParam('p', $request, 1);
            $pageSize = ApiUtils::getIntParam('page_size', $request, 100);
            $type = ApiUtils::getIntParam('type', $request);
            $timer = new TimeUtils();
            $timer->start('get_fund_flow');
            //获取资金流水
            $tableName = 'lzh_member_moneylog_' . $userId%10;
            $objMsg = new MemberMoneylog(['tableName' => $tableName]);
            $list = $objMsg->getFlowByUid($userId, $type, $page, $pageSize);
            $timer->stop('get_fund_flow');

            $result = [
                'code' => ApiErrorDescs::SUCCESS,
                'message' => 'success',
                'result' => $list,
            ];
        }catch(ApiBaseException $e){
            $result = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
        }
        header('Content-type: application/json');
        echo json_encode($result);

        $this->logApi(__CLASS__, __FUNCTION__, $result);
        \Yii::$app->end();
    }

    /*
     * 银行卡列表
     */
    public function actionBankList(){
        try{
            $request = $_REQUEST;
            $userId = ApiUtils::getIntParam('user_id', $request);
            $timer = new TimeUtils();

            $timer->start('get_banks');
            $list = MemberBanks::getListByUid($userId);
            $timer->stop('get_banks');

            $result = [
                'code' => ApiErrorDescs::SUCCESS,
                'message' => 'success',
                'result' => $list,
            ];
        }catch(ApiBaseException $e){
            $result = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
        }
        header('Content-type: application/json');
        echo json_encode($result);

        $this->logApi(__CLASS__, __FUNCTION__, $result);
        \Yii::$app->end();
    }
    /*
     * 绑定银行卡页面
     */
    public function actionBank(){
        try{
            $request = $_REQUEST;
            $userId = ApiUtils::getIntParam('user_id', $request);
            $timer = new TimeUtils();
            //验证用户银行卡是否被冻结
            $timer->start('freeze_bank');
            if(MemberBanks::checkExistByCondition(['uid' => $userId, 'status' => MemberBanks::BANK_STATUS_FREEZED])){
                throw new ApiBaseException(ApiErrorDescs::ERR_BANK_FREEZED);
            }
            $timer->stop('freeze_bank');
            //第三方支付绑定
            $timer->start('third_pay_bind');
            $thirdPayInfo = EscrowAccount::getUserBindInfo($userId);
            if(!$thirdPayInfo['qddBind'] && !$thirdPayInfo['yeeBind']){
                $this->redirect('/escrow/register-bind');
            }
            $timer->stop('third_pay_bind');
            //银行列表
            $timer->start('user_bank');
            $userBank = MemberBanks::getListByUid($userId);
            $timer->stop('user_bank');
            //用户信息
            $timer->start('user_info');
            $userInfo = MemberInfo::get($userId);
            $timer->stop('user_info');

            $result = [
                'code' => ApiErrorDescs::SUCCESS,
                'message' => 'success',
                'result' => [
                    'bank' => $userBank,
                    'user_info' => $userInfo
                ]
            ];
        }catch(ApiBaseException $e){
            $result = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
        }
        header('Content-type: application/json');
        echo json_encode($result);

        $this->logApi(__CLASS__, __FUNCTION__, $result);
        \Yii::$app->end();
    }
}
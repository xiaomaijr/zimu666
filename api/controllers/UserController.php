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
use common\models\BorrowInvest;
use common\models\EscrowAccount;
use common\models\InnerMsg;
use common\models\MemberBanks;
use common\models\MemberInfo;
use common\models\MemberMoney;
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
                throw new ApiBaseException(ApiErrorDescs::ERR_USER_UNBIND_THIRD_PAY);
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

    /*
     * 银行卡绑定接口
     */
    public function actionBindBank(){
        try{
            $request = $_REQUEST;
            $timer = new TimeUtils();

            $oldBkNuM = ApiUtils::getStrParam('old_account', $request);
            $newBkNum = ApiUtils::getStrParam('account', $request);
            $repeatBkNum = ApiUtils::getStrParam('repeat_account', $request);
            $bankName = ApiUtils::getStrParam('bank_name', $request);
            $privince = ApiUtils::getStrParam('privince', $request);
            $city = ApiUtils::getStrParam('city', $request);
            $bankAddr = ApiUtils::getStrParam('bank_addr', $request);

            if($newBkNum != $repeatBkNum){
                throw new ApiBaseException(ApiErrorDescs::ERR_BANK_NUM_INPUT_ERR);
            }
            $timer->start('check_old_bank_num');
            $userBank = MemberBanks::getListByUid($request['user_id'], 0);
            if($userBank){
                if($userBank[0]['bank_num'] != $oldBkNuM){
                    throw new ApiBaseException(ApiErrorDescs::ERR_OLD_BANK_NUM_INPUT_ERR);
                }
                if($userBank[0]['status'] == MemberBanks::BANK_STATUS_FREEZED){
                    throw new ApiBaseException(ApiErrorDescs::ERR_BANK_FREEZED);
                }
            }
            $timer->stop('check_old_bank_num');
            //验证该银行卡是否重复
            $timer->start('check_bank_num_repeat');
            $objMemBank = new MemberBanks();
            if($objMemBank->checkBankRepeat($newBkNum, MemberBanks::BANK_STATUS_UNBIND)){
                throw new ApiBaseException(ApiErrorDescs::ERR_BANK_NUM_NOT_REPEAT);
            }
            $timer->stop('check_bank_num_repeat');
            $userIp = ApiUtils::get_client_ip();

            $timer->start('add_bank_num');
            MemberBanks::add($request['user_id'], $newBkNum, $bankName, $privince, $city, $bankAddr, $userIp);
            $timer->stop('add_bank_num');
            $result = [
                'code' => ApiErrorDescs::SUCCESS,
                'message' => 'success'
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
    * 个人账户
    */
    public function actionAccount(){
        try{
            $request = $_REQUEST;
            $timer = new TimeUtils();
            //检查用户登录信息
            $timer->start('check_access_token');
//            $this->checkAccessToken($request['access_token'], $request['user_id']);
            $timer->stop('check_access_token');
            //获取用户资金
            $timer->start('get_mm_money');
            $data = MemberMoney::getUserMoney($request['user_id']);
            $timer->stop('get_mm_money');
            //用户累计收益
            $timer->start('accumulated_income');
            $data['income'] = BorrowInvest::getTotalIncomeByInvestId($request['user_id']);
            $timer->stop('accumulated income');
            //检查用户是否在钱多多绑定账户
            $timer->start('escrow_account');
            $escrow = EscrowAccount::getUserBindInfo($request['user_id']);
            $data['escrow'] = $escrow['yeeBind'] | $escrow['qddBind'];
            $timer->stop('escrow_account');
            $result = [
                'code' => ApiErrorDescs::SUCCESS,
                'message' => 'success',
                'result'  => $data
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
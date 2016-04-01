<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao-pc
 * Date: 2016/3/14
 * Time: 9:36
 */

namespace api\controllers;


use api\models\InvestInter;
use common\models\ApiBaseException;
use common\models\ApiErrorDescs;
use common\models\ApiUtils;
use common\models\BorrowInfo;
use common\models\BorrowInvest;
use common\models\BorrowInvestor;
use common\models\EscrowAccount;
use common\models\MemberMoney;
use common\models\TimeUtils;

class BorrowController extends ApiBaseController
{
    /*
     * 标详情页
     */
    public function actionDetail(){
        try{
            $request = $_REQUEST;
            $id = ApiUtils::getIntParam('id', $request);
            $timer = new TimeUtils();
            //获取借款详情
            $timer->start('get_borrow_info');
            $bowInfo = BorrowInfo::getInfo($id);
            $timer->stop('get_borrow_info');
            //获取用户资金账户
            $money = [];
            if(!empty($request['user_id'])){
                $timer->start('get_member_money');
                $this->checkAccessToken($request['access_token'], $request['user_id']);
                $money = MemberMoney::get($request['user_id']);
                $timer->stop('get_member_money');
            }
            //获取投标记录
            $timer->start('investor_record');
            $investorTabName = 'lzh_borrow_investor_' . intval($id%3);
            $objInvestor = new BorrowInvestor(['tableName' => $investorTabName]);
            $records = $objInvestor->getInvestRecordByBid($id);
            $timer->stop('investor_record');
            //风控及其它信息
            $result = [
                'code' => ApiErrorDescs::SUCCESS,
                'message' => 'success',
                'result'  => [
                    'borrow' => $bowInfo,
                    'money' => ApiUtils::getFloatParam('total_money', $money),
                    'borrow_info' => '融资方为国内一家知名制造业及贸易于一体的民营企业高层管理人员，其所在公司是一家管理规范、服务高效、具有资深运作经验的国际化、现代化综合型文化企业。该公司主要经营范围涵盖矿业公司制造设备配件生产，矿业设备维修，年营业额1亿元左右，上游企业为辽宁省，浙江省进货产品主要有配件、液压件、电器件、原材料钢板。下游企业为山西、陕西、内蒙、新疆、河南、河北，在全国各地均设有办事处。公司尊崇“踏实、拼搏、责任”的企业精神，并以诚信、共赢、创造良好的企业环境，以全新的管理模式，完善的技术，卓越的品质为生存根本，坚持用自己的服务去打动客户。',
                    'security_type' => '第1层保障措施：借款用途为优质企业提供流动资金，并以该企业的经营收入作为还款来源。企业高管及工作人员均具备多年业内的企业管理与经营经验，现金流稳定，实力雄厚。
第2层保障措施：借款方名下的厂房、流水线、办公设备、产品库存等作为该笔借款的反担保措施。',
                    'invert_record' => $records,
                ],
            ];
        }catch(ApiBaseException $e){
            $result = [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        }
        header('Content-type: application/json');
        echo json_encode($result);

        $this->logApi(__CLASS__, __FUNCTION__, $result);
        \Yii::$app->end();
    }
    /*
     * 投标信息验证
     */
    public function actionInvestCheck(){
        try{
            $request = $_REQUEST;
            $id = ApiUtils::getIntParam('id', $request);
            $money = ApiUtils::getFloatParam('money', $request);
            $userId = ApiUtils::getIntParam('user_id', $request);
            $accessToken = ApiUtils::getStrParam('access_token', $request);
            $timer = new TimeUtils();

            //验证标是否存在
            $timer->start('check_borrow_exist');
            if(!($borrow = BorrowInfo::get($id))){
                throw new ApiBaseException(ApiErrorDescs::ERR_BORROW_DATA_NOT_EXIST);
            }
            $timer->stop('check_borrow_exist');
            //验证 用户信息
            $timer->start('check_access_token');
            $this->checkAccessToken($accessToken, $userId);
            $timer->stop('check_access_token');
            //验证用户是否绑定第三方支付
            $timer->start('check_bind_qdd');
            $escrow = EscrowAccount::getUserBindInfo($userId);
            if($escrow['yeeBind'] | $escrow['qddBind']){
                throw new ApiBaseException(ApiErrorDescs::ERR_USER_UNBIND_THIRD_PAY);
            }
            $timer->stop('check_bind_qdd');
            //验证标状态等
            $timer->start('check_borrow_status');
            if($borrow['status'] != 2){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '只能投正在借款中的标');
            }
            if($borrow['borrow_uid'] == $userId){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '不能去投自己的标');
            }
            $timer->stop('check_borrow_status');
            //验证用户账户可用资金
            $timer->start('check_money');
            $userMny = MemberMoney::getUserMoney($userId);
            if($userMny['available_money'] < $money){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '账户余额不足，请先充值');
            }
            $timer->stop('check_money');
            $msg = "您的账户可用余额为{$userMny['available_money']}元，实际投标{$money}元，您确认投标吗？";
            $result = [
                'code' => ApiErrorDescs::SUCCESS,
                'message' => 'success',
                'result' => $msg
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
     * 投标
     */
    public function actionInvest(){
        try{
            $request = $_REQUEST;
            $uid = ApiUtils::getIntParam('user_id', $request);
            $borrowId = ApiUtils::getIntParam('id', $request);
            $money = ApiUtils::getFloatParam('money', $request);
            $timer = new TimeUtils();
            $timer->start('check_access_token');
//            $this->checkAccessToken($request['access_token'], $uid);
            $timer->stop('check_access_token');
            //投标过程
            $timer->start('invest');
            $investInfo = InvestInter::investMoneyThird($uid, $borrowId, $money);
            $timer->stop('invest');
            $result = [
                'code' => ApiErrorDescs::SUCCESS,
                'message' => 'success',
                'result' => $investInfo,
            ];
        }catch(ApiBaseException $e){
            $result = [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        }
        header('Content-type: application/json');
        echo json_encode($result);

        $this->logApi(__CLASS__, __FUNCTION__, $result);
        \Yii::$app->end();
    }
}
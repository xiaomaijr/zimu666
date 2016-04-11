<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao-pc
 * Date: 2016/3/21
 * Time: 17:44
 */

namespace api\controllers;


use common\models\ApiBaseException;
use common\models\ApiErrorDescs;
use common\models\ApiUtils;
use common\models\MemberPayonline;
use common\models\PlatformActivityManage;
use yii\web\Controller;
use yii\web\Response;

class NoticeController extends Controller
{
    public $layout = false;

    public $enableCsrfValidation=false;

    public function beforeAction($action)
    {
        if(parent::beforeAction($action)){
            $strControllerId = $action->controller->id;
            $strActionId = $action->id;
            \Yii::$app->logging->trace($strControllerId . '/' . $strActionId . json_encode($_REQUEST));
        }
    }


    /**
     * 注册绑定回跳接口
     */
    public function actionBindReturn(){
        $request = $_REQUEST;
        $resultCode = htmlspecialchars(ApiUtils::getIntParam('ResultCode', $request));
        if($resultCode == 88){
            return $this->render('bind_success.tpl');
        }
        $data = [
            'resultCode' => $resultCode,
        ];
        return $this->render('bind_fail.tpl', $data);


    }

    public function actionInvestReturn(){
        $request = $_REQUEST;
        $resultCode = htmlspecialchars(ApiUtils::getIntParam('ResultCode', $request));
        if($resultCode == 88){
            return $this->render('invest_success.tpl');
        }
        $data = [
            'resultCode' => $resultCode,
        ];
        return $this->render('invest_fail.tpl', $data);
    }

    public function actionRechargeReturn(){
        $request = $_REQUEST;
        $resultCode = htmlspecialchars(ApiUtils::getIntParam('ResultCode', $request));
        $orderNo = htmlspecialchars(ApiUtils::getStrParam('OrderNo', $request));
        $remark1 = htmlspecialchars(ApiUtils::getStrParam('Remark1', $request));
        $tmp = explode(':', $remark1);
        $uid = $tmp[1];
        $data = [
            'orderNo' => $orderNo,
            'resultCode' => $resultCode,
        ];
        if ($resultCode == 88) {
            //判断首次充值送红包活动
            $act_valid = PlatformActivityManage::getActivityValid(PlatformActivityManage::FIRST_CHARGE_BONUS);
            if($act_valid){
                //获取充值次数
                $condition = array(
                    'uid' => $uid,
                    'status' => 1,
                    "order_no != '" . $orderNo . "'",
                );
                //除此次充值之外，无其他成功充值记录，则本次为首次
                $charge_count = MemberPayonline::getCountByCondition($condition);
                if($charge_count == 0){
                    echo 'Charge:charge_success_red';
                    exit;
                }
            }
            return $this->render('recharge_success.tpl', $data);
        }else{
            return $this->render('recharge_fail.tpl', $data);
        }
    }

    public function actionTest(){
        $data = [
            'orderNo' => '123456asdf'
        ];
        return $this->render('recharge_success.tpl', $data);
    }
    /*
     * 提现返回页
     */
    public function actionWithdraw(){
        $request = $_REQUEST;
        $resultCode = htmlspecialchars(ApiUtils::getIntParam('ResultCode', $request));
        $orderNo = htmlspecialchars(ApiUtils::getStrParam('OrderNo', $request));

        $data = [
            'orderNo' => $orderNo,
            'resultCode' => $resultCode,
        ];
        if ($resultCode == 88) {
            return $this->render('withdraw_success.tpl', $data);
        }
        return $this->render('withdraw_fail.tpl', $data);


    }
}
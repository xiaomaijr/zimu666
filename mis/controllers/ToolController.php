<?php
/**
 * Created by PhpStorm.
 * User: 58
 * Date: 2016/10/22
 * Time: 23:43
 */
namespace mis\controllers;
use common\models\ApiBaseException;
use common\models\ApiConfig;
use common\models\ApiErrorDescs;
use common\models\ApiUtils;
use front\models\UserAccount;
use front\models\UserInfo;
use front\models\UserPassport;

class ToolController extends MisBaseController
{
    /**
     * 用户充值
     * @return string
     */
    public function actionRecharge()
    {
        $request = $_REQUEST;
        if (empty($request)) {
            return $this->render('recharge.tpl');
        }
        try{
            $mobile = ApiUtils::getStrParam('mobile', $request);
            $userInfo = UserPassport::find()->where(['account' => $mobile])->one();
            if (empty($userInfo)) {
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '用户不存在');
            }

            if (!($userAccount = UserAccount::find()->where(['user_id' => $userInfo['id']])->one())) {
                $ret = UserAccount::createUserCount($userInfo['id'], intval($request['money'])*100);
            } else {
                $userAccount->recharge = intval($request['money'])*100 + intval($userAccount->recharge);
                $userAccount->update_time = time();
                $ret = $userAccount->update();
            }
            if (!$ret) {
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '充值失败');
            }
            $result = [
                'code'  =>  ApiErrorDescs::SUCCESS,
                'message'   =>  'success',
            ];
        }catch (ApiBaseException $e) {
            $result = [
                'code'  =>  $e->getCode(),
                'message'   =>  $e->getMessage(),
            ];
        }
        echo json_encode($result);
    }
}
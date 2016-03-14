<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao-pc
 * Date: 2016/3/14
 * Time: 9:36
 */

namespace api\controllers;


use common\models\ApiBaseException;
use common\models\ApiErrorDescs;
use common\models\ApiUtils;
use common\models\LzhBorrowInfo;
use common\models\LzhMemberMoney;
use common\models\TimeUtils;

class BorrowController extends ApiBaseController
{
    public function actionDetail(){
        try{
            $request = $_REQUEST;
            $id = ApiUtils::getIntParam('id', $request);
            $timer = new TimeUtils();
            //获取借款详情
            $timer->start('get_borrow_info');
            $bowInfo = LzhBorrowInfo::getInfo($id);
            $timer->stop('get_borrow_info');
            //获取用户资金账户
            $money = [];
            if(!empty($request['user_id'])){
                $timer->start('get_member_money');
                $this->checkAccessToken($request['access_token'], $request['user_id']);
                $money = LzhMemberMoney::get($request['user_id']);
                $timer->stop('get_member_money');
            }
            //风控及其它信息
            $result = [
                'code' => ApiErrorDescs::SUCCESS,
                'message' => 'success',
                'result'  => [
                    'borrow' => $bowInfo,
                    'money' => ApiUtils::getFloatParam('total_money', $money),
                ],
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
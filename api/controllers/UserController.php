<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao-pc
 * Date: 2016/3/11
 * Time: 14:48
 */

namespace api\controllers;


use common\models\ApiBaseException;
use common\models\ApiErrorDescs;
use common\models\ApiUtils;
use common\models\InnerMsg;
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
            $type = ApiUtils::getIntParam('type', $request, 0);
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
}
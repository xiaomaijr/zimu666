<?php
/**
 * Created by PhpStorm.
 * User: 58
 * Date: 2016/10/1
 * Time: 9:13
 */

namespace front\controllers;


use api\controllers\ApiBaseController;
use common\models\ApiBaseException;
use common\models\ApiErrorDescs;
use common\models\ApiUtils;
use front\models\Orders;

class OrderController extends BaseController
{
    public function actionCreate()
    {
        try{
            $request = $this->request;
            $userId = $_SESSION['USER_ID'];
            $shopCartIds = ApiUtils::getStrParam('shop_cart_ids', $request);
            $trandNos = Orders::create($userId, $shopCartIds);
            $result = [
                'code'  =>  ApiErrorDescs::SUCCESS,
                'message'   =>  'success',
                'data'  =>  ['trandNos' => $trandNos],
            ];
        }catch (ApiBaseException $e) {
            $result = [
                'code'  =>  $e->getCode(),
                'message'   =>  $e->getMessage(),
            ];
        }
        echo json_encode($result);
    }

    /**
     * 订单支付
     * @return \yii\web\Response
     */
    public function actionPay()
    {
        try{
            $request = $this->request;
            $userId = $_SESSION['USER_ID'];
            $id = ApiUtils::getStrParam('id', $request);
            $orders = Orders::getDataByConditions(['id' => $id, 'status' => Orders::ORDER_STATUS_CREATE]);
            Orders::orderPay($userId, $orders);
            return $this->redirect('/user-center/order-list');
        }catch (ApiBaseException $e) {
            if ($e->getCode() == 3001) {
                return $this->redirect('/epay/recharge');
            }
            $this->redirect('/mall/error?errno=' . $e->getCode() . '&errmsg=' . $e->getMessage());
        }
    }

    /**
     * 订单取消
     * @return \yii\web\Response
     */
    public function actionCancal()
    {
        try{
            $request = $this->request;
            $userId = $_SESSION['USER_ID'];
            $id = ApiUtils::getStrParam('id', $request);
            Orders::cancal($userId, $id);
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

    public function actionConfirmReceive()
    {
        try{
            $request = $this->request;
            $userId = $_SESSION['USER_ID'];
            $orderId = ApiUtils::getStrParam('id', $request);
            Orders::confirmReceive($userId, $orderId);
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
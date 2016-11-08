<?php
/**
 * Created by PhpStorm.
 * User: 58
 * Date: 2016/10/5
 * Time: 14:41
 */
namespace front\controllers;
use common\models\ApiBaseException;
use common\models\ApiErrorDescs;
use front\models\ShoppingCart;
use front\models\UserAccount;

class ShopCartController extends UserBaseController
{
    /**
     * 加入购物车及立即一元购
     */
    public function actionAdd()
    {
        try{
            $request = $this->request;
            $userId = $this->userId;
            ShoppingCart::addGood($userId, $request);
            $result = [
                'code'   =>  ApiErrorDescs::SUCCESS,
                'message'   =>  'success',
            ];
        }catch (ApiBaseException $e) {
            $result = [
                'code'  =>  $e->getCode(),
                'message'   =>  $e->getMessage()
            ];
        }
        echo json_encode($result);
    }

    /**
     * 购物车页面
     */
    public function actionList()
    {
        $request = $this->request;
        $userId = $this->userId;
        $data = ShoppingCart::getList($userId);
        $data['user_account'] = UserAccount::getByUserId($userId);
        return $this->render('list.tpl', $data);
    }
    /**
     * 购物车商品删除
     */
    public function actionDeleteGood()
    {
        try{
            $request = $this->request;
            $userId = $this->userId;
            ShoppingCart::deleteGood($userId, $request['id']);
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
    /**
     * 更新购物车商品数量
     */
    public function actionUpdateNum()
    {
        try{
            $request = $this->request;
            $userId = $this->userId;
            if (empty($request['id']) || empty($request['num'])) {
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '参数错误');
            }
            ShoppingCart::updateNum($userId, $request['id'], $request['num']);
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
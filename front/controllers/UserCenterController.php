<?php
/**
 * Created by PhpStorm.
 * User: 58
 * Date: 2016/10/3
 * Time: 22:57
 */
namespace front\controllers;
use common\models\ApiBaseException;
use common\models\ApiErrorDescs;
use common\models\ApiUtils;
use front\models\DisplayOrder;
use front\models\Indianaed;
use front\models\Orders;
use front\models\Paging;
use front\models\UserAccount;
use front\models\UserAddress;
use front\models\UserInfo;

class UserCenterController extends UserBaseController{
    /**
     * 晒单列表
     */
    public function actionDisplayOrderList()
    {
        $request = $this->request;
        $userId = ApiUtils::getIntParam('user_id', $request, $this->userId);
        $request['condition'] = ['user_id' => $userId];
        $page = ApiUtils::getIntParam('p', $request, 1);
        $pageSize = ApiUtils::getIntParam('page_size', $request, 20);
        $total = DisplayOrder::getCountByCondition(['user_id' => $userId]);
        $url = !empty($request['user_id']) ? '/user-center/display-order-list?user_id=' . $userId . '&' : '/user-center/display-order-list?';
        $paging = new Paging($total, $pageSize, $url, $page);
        $template = !empty($request['user_id']) ? 'other_' : '';
        $data = [
            'userInfo' => UserInfo::get($userId),
            'userAccount'  =>  UserAccount::getByUserId($userId),
            'displayOrders' =>  DisplayOrder::getList($request),
            'paging'    =>  $paging->output(),
            'curUrl'    =>  '/user-center/display-order-list',
        ];
        return $this->render($template . 'display_order_list.tpl', $data);
    }

    /**
     * 地址列表
     * @return string
     */
    public function actionAddressList()
    {
        $userId = $this->userId;
        $data = [
            'userInfo' => UserInfo::get($userId),
            'userAccount'  =>  UserAccount::getByUserId($userId),
            'addressList' => UserAddress::getList($userId),
            'curUrl'    =>  '/user-center/address-list',
        ];
        return $this->render('address_list.tpl', $data);
    }

    /**
 * 中奖纪录
 * @return string
 */
    public function actionIndianaedList()
    {
        $request = $this->request;
        $userId = ApiUtils::getIntParam('user_id', $request, $this->userId);
        $page = ApiUtils::getIntParam('p', $request, 1);
        $pageSize = ApiUtils::getIntParam('page_size', $request, 20);
        $total = Indianaed::getCountByCondition(['user_id' => $userId]);
        $url = !empty($request['user_id']) ? '/user-center/indianaed-list?user_id=' . $userId . '&' : '/user-center/indianaed-list?';
        $paging = new Paging($total, $pageSize, $url, $page);
        $template = !empty($request['user_id']) ? 'other_' : '';
        $data = [
            'userInfo' => UserInfo::get($userId),
            'userAccount'  =>  UserAccount::getByUserId($userId),
            'indianaedList' => Indianaed::getList(['user_id' => $userId], $pageSize, $page),
            'curUrl'    =>  '/user-center/indianaed-list',
            'paging'    =>  $paging->output(),
            'orderStatusMap' => Orders::$statusMap,
        ];
        return $this->render($template . 'indianaed_list.tpl', $data);
    }
    /**
     * 购买纪录
     * @return string
     */
    public function actionOrderList()
    {
        $request = $this->request;
        $userId = ApiUtils::getIntParam('user_id', $request, $this->userId);
        $page = ApiUtils::getIntParam('p', $request, 1);
        $pageSize = ApiUtils::getIntParam('page_size', $request, 20);
        $total = Orders::getCountByCondition(['user_id' => $userId]);
        $url = !empty($request['user_id']) ? '/user-center/order-list?user_id=' . $userId . '&' : '/user-center/order-list?';
        $paging = new Paging($total, $pageSize, $url, $page);
        $orderList = Orders::getList(['user_id' => $userId], $pageSize, $page);
        $orderIds = ApiUtils::getCols($orderList, 'id');
        $template = !empty($request['user_id']) ? 'other_' : '';
        $data = [
            'userInfo' => UserInfo::get($userId),
            'userAccount'  =>  empty($template) ? UserAccount::getByUserId($userId) : [],
            'orderList' => $orderList,
            'indianaedOrder' =>   Indianaed::getIndianaedOrderData($userId, $orderIds),
            'curUrl'    =>  '/user-center/order-list',
            'paging'    =>  $paging->output(),
            'orderStatusMap' => Orders::$statusMap,
        ];
        return $this->render($template . 'order_list.tpl', $data);
    }

    /**
     * 修改昵称
     */
    public function actionModifyNick()
    {
        try{
            $request = $this->request;
            $userId = $_SESSION['USER_ID'];
            $nickName = ApiUtils::getStrParam('nick_name', $request);
            UserInfo::changeNickName($userId, $nickName);
            $result = [
                'code'   =>  ApiErrorDescs::SUCCESS,
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
     * 添加收货地址
     */
    public function actionAddAddress()
    {
        try{
            $request = $this->request;
            $userId = $_SESSION['USER_ID'];
            UserAddress::add($userId, $request);
            $result = [
                'code'   =>  ApiErrorDescs::SUCCESS,
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
     * 设计默认地址
     */
    public function actionSetDefaultAddress()
    {
        try{
            $request = $this->request;
            $userId = $_SESSION['USER_ID'];
            $id = ApiUtils::getIntParam('address_id', $request);
            UserAddress::setDefault($userId, $id);
            $result = [
                'code'   =>  ApiErrorDescs::SUCCESS,
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
 * 设计默认地址
 */
    public function actionModifyAddress()
    {
        try{
            $request = $this->request;
            $userId = $_SESSION['USER_ID'];
            $id = ApiUtils::getIntParam('address_id', $request);
            unset($request['address_id']);
            UserAddress::modifyAddress($userId, $id, $request);
            $result = [
                'code'   =>  ApiErrorDescs::SUCCESS,
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
     * 删除订单
     */
    public function actionOrderDelete()
    {
        try{
            $request = $this->request;
            $userId = $_SESSION['USER_ID'];
            $id = ApiUtils::getIntParam('id', $request);
            Orders::updateOrder($userId, $id, $request);
            $result = [
                'code'   =>  ApiErrorDescs::SUCCESS,
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
     * 删除中奖纪录
     */
    public function actionIndianaedDelete()
    {
        try{
            $request = $this->request;
            $userId = $_SESSION['USER_ID'];
            $id = ApiUtils::getIntParam('id', $request);
            Indianaed::updateRecord($userId, $id, $request);
            $result = [
                'code'   =>  ApiErrorDescs::SUCCESS,
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

    public function actionUpload(){
        $request = $_REQUEST;
        $files = $_FILES;
        try{
            if(!isset($request['type'])||empty($request['type'])){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR,'参数错误');
            }
            if(!isset($files['myfile'])||empty($files['myfile'])){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR,'参数错误');
            }
            $uploadDir = dirname(dirname(dirname(__FILE__))) . "/front/web/static/";
            switch(trim($request['type'])){
                case 'img':
                    $tmpDir = 'img/about/';
                    break;
                case 'banner':
                case 'product';
                case 'logo':
                case 'smalllogo':
                case 'code':
                case 'contract':
                    $tmpDir = trim($request['type']) . "/";
                    break;
                default :
                    $tmpDir = 'other/';
                    break;
            }
            $uploadDir .= $tmpDir;
            file_exists($uploadDir) || (mkdir($uploadDir,0775,true) && chmod($uploadDir,0775));
            if(!is_array($files['myfile']['name'])){
                $filename = time() . uniqid() . strstr($files['myfile']['name'],'.');
                move_uploaded_file($files['myfile']['tmp_name'], $uploadDir . $filename);
                $urlPrefix = '';
                $result = [
                    'code' => 0,
                    'src'  => rtrim($urlPrefix, '\/') . '/static/' . $tmpDir . $filename,
                ];
                echo json_encode($result);
                exit;
            }

        }catch(ApiBaseException $e){
            $result = [
                'code'=>$e->getCode(),
                'message'=>$e->getMessage()
            ];
            echo json_encode($result);
            exit;
        }

    }
}
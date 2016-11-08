<?php
/**
 * Created by PhpStorm.
 * User: panbook
 * Date: 6/23/15
 * Time: 4:01 PM
 */

namespace mis\controllers;


use common\models\ApiBaseException;
use common\models\ApiConfig;
use common\models\ApiErrorDescs;
use common\models\ApiUtils;
use common\models\Paging;
use front\models\Category;
use front\models\Goods;
use front\models\IndianaGoods;
use front\models\Orders;
use front\models\UserInfo;
use mis\models\Util;

class OrderController extends MisBaseController{

    public static $orderStatus = [
        1 => '待支付定金',
        2 => '待客服确认',
        3 => '待送车上门',
        4 => '待评价',
        5 => '交易完成',
        10 => '待退款',  //订单取消
        11 => '退款完成',
    ];

    /**
     * 获取订单列表
     * */
    public function actionList()
    {
        //过滤条件
        $request = $_REQUEST;
        $arrFilter = isset($request['query'])?$request['query']:[];
        foreach($arrFilter as $key=>$val){
            if(empty($val))
                unset($arrFilter[$key]);
        }
        $intPage = isset($request['p'])?intval($request['p']):1;
        $filter['orderby'] =  $orderBy = isset($request['order'])?trim($request['order']):'id';
        $filter['sortway'] = $sortWay = isset($request['sortway'])?trim($request['sortway']):'DESC';
        $filter['beginTime'] = $beginTime = ApiUtils::getStrParam('begin_time', $request);
        $filter['endTime'] = $endTime = ApiUtils::getStrParam('end_time', $request);
        $intPageSize = PAGESIZE;
        $arrList = [];
        $total = 0;
        $condition = [];
        if($beginTime){
            $condition[] = "create_time > " . strtotime($beginTime . "0:0:0");
        }
        if($endTime){
            $condition[] = "create_time < " . strtotime($endTime . "23:59:59");
        }
        if (!empty($arrFilter)) {
            $filter = array_merge($filter, $arrFilter);
            if (!empty($arrFilter['order_no'])) {
                $condition['order_no'] = $arrFilter['order_no'];
                $arrList = Orders::getDataByConditions($condition);
                $total = count($arrList);
            } elseif (!empty($arrFilter['mobile'])) {

                $userInfo = UserInfo::find()
                    ->select('user_id')
                    ->where(['mobile' => $arrFilter['mobile']])
                    ->asArray()->one();
                $condition['user_id'] = $userInfo ? $userInfo['user_id'] : 0;
            }
        }
        $arrList = Orders::getList($condition, $intPageSize, $intPage, $orderBy . ' ' . $sortWay);
        $condition['is_del'] = ApiConfig::IS_DEL_NOT;
        $total = Orders::getCountByCondition($condition);

        if (!empty($arrList)) {
            //用户信息
            $userIds = array_unique(ApiUtils::getCols($arrList,'user_id'));
            $userInfos = UserInfo::find()
                ->where(['user_id' => $userIds])
                ->asArray()
                ->all();
            foreach($userInfos as $user){
                $arrTplData['userInfo'][$user['user_id']] = $user;
            }
        }
        $url = \Yii::$app->request->baseUrl . '?' . ApiUtils::getMapping($request,'p') . '&';
        $objPage = new Paging($total, $intPageSize, $url , $intPage);
        $pageLink = $objPage->output();

        $arrTplData['list'] = $arrList;

        $arrTplData['arrPager'] = array(
            'count'     => $total,
            'pagesize'  => $intPageSize,
            'page'      => $intPage,
            'pagelink'  => $pageLink,
        );
        $arrTplData['filter'] = $filter;
//        echo json_encode($arrTplData);exit;
        return $this->render('order_list.tpl',$arrTplData);
    }

    public function actionSend(){
        $request = $_REQUEST;
        $orderId = isset($request['id'])?intval($request['id']):0;
        try{
            if(!$orderId){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '参数错误');
            }
            Orders::orderSend($orderId);
            $result = [
                'code'  =>  ApiErrorDescs::SUCCESS,
                'message'   =>  'success',
            ];
        }catch(ApiBaseException $e){
            $result = [
                'code'  =>  $e->getCode(),
                'message'   =>  $e->getMessage(),
            ];
        }
        echo json_encode($result);
    }

//    public function actionCancel(){
//        $request = $_REQUEST;
//        $orderId = isset($request['id'])?intval($request['id']):0;
//        $cancelReason = isset($request['reason'])?intval($request['reason']):0;
//        $refundCost = !empty($request['refund_cost'])?intval($request['refund_cost'])*100:0;
//        try{
//            if(!$orderId){
//                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN,'参数错误');
//            }
//            $ret = Order::orderCancel($orderId, true, $refundCost,$cancelReason);
//            $ret['code'] = ApiErrorDescs::SUCCESS;
//            echo json_encode($ret);
//            exit;
//        }catch(ApiBaseException $e){
//            $err = $e->getMessage();
//            $err .= ',取消订单失败';
//            $ret = [
//                'code' => $e->getCode(),
//                'message' => $err
//            ];
//            echo json_encode($ret);
//            exit;
//        }
//    }
//
//    public function actionRefund(){
//        $request = $_REQUEST;
//        $orderId = !empty($request['id'])?intval($request['id']):0;
//        try{
//            if(!$orderId){
//                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN, '参数错误');
//            }
//            if(!Order::find()->where(['id' => $orderId])->exists()){
//                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN, '数据不存在');
//            }
//            Order::orderRefund($orderId);
//            $result = [
//                'code' => ApiErrorDescs::SUCCESS,
//                'message' => '确认退款成功'
//            ];
//            echo json_encode($result);
//            exit;
//        }catch(ApiBaseException $e){
//            $err = $e->getMessage();
//            $err .= '订单退款失败';
//            $result = [
//                'code' => $e->getCode(),
//                'message' => $err
//            ];
//            echo json_encode($result);
//            exit;
//        }
//    }
//
    public function actionView(){
        try{
            $request = $_REQUEST;
            $id = isset($request['id'])?intval($request['id']):0;
            if(empty($id)){
                Util::pageNotFund();
            }
            $info = Orders::find()
                ->where(['id' => $id])
                ->asArray()
                ->one();

            $user = UserInfo::get($info['user_id']);
            $info['mobile'] = !empty($user['mobile'])?$user['mobile']:'';
            
            $data['status'] = Orders::$statusMap;
            $data['good'] = Goods::get($info['good_id']);
            $data['indiana_good'] = IndianaGoods::get($info['indiana_good_id']);
            $data['category'] = Category::get($info['category_id']);
            $data['user_info'] = UserInfo::get($info['user_id']);
            $data['info'] = $info;
//            echo json_encode($data);exit;
            return $this->render('view.tpl',$data);
        }catch(ApiBaseException $e){
            Util::pageNotFund();
        }
    }
    /*
     * 订单导出
     */
    public function actionExcel(){
        $request = $_REQUEST;
        $query = !empty($request['query'])?$request['query']:[];
        $flag = !empty($request['flag'])?$request['flag']:0;

        try{
            if(empty($flag)){
                return $this->render('excel.tpl');
            }
            $users = UserInfo::find()
                ->asArray()
                ->all();
            $users = ApiUtils::getMap($users, 'id');
            $where = "";
            if(!empty($query['start_time'])){
                $where .= " where create_time > " . strtotime($query['start_time']);
            }
            if(!empty($query['end_time'])){
                $where .= !empty($where)?" and create_time < " . strtotime($query['end_time']):" where create_time < " . strtotime($query['end_time']);
            }
            $sql = "select * from `orders` " . $where;

            $infos = Orders::findBySql($sql)->asArray()->all();

            $attrs = [
                '订单号',
                '用户名',
                '用户手机',
                '车型名称',
                '颜色',
                '价格方案',
                '城市',
                '定金费用',
                '订单状态',
                '退款金额',
                '退款状态',
                '订单生成时间',
                '最近更新时间',
                ];
            foreach($infos as $key=>$info){
                $lists[$key]['id'] = $info['id'];
                $lists[$key]['reservation_id'] = !empty($users[$info['user_id']]['name'])?$users[$info['user_id']]['name']:'';
                $lists[$key]['group'] = $info['mobile'];
                $lists[$key]['driver'] = $info['vehicle_type_name'];
                $lists[$key]['brand'] = $info['color'];
                $lists[$key]['line'] = $info['price_type'];
                $lists[$key]['vehicle'] = $info['city'];
                $lists[$key]['city'] = $info['order_fee'];
                $lists[$key]['status'] = self::$orderStatus[$info['status']];
                $lists[$key]['user'] = !empty($info['refund_cost'])?$info['refund_cost']:'';
                $lists[$key]['refund_status'] = !empty($info['refund_status'])?$info['refund_status']:'';
                $lists[$key]['createtime'] = date("Y-m-d H:i:s", $info['create_time']);
                $lists[$key]['updatetime'] = date("Y-m-d H:i:s", $info['update_time']);
            }
            Excel::reloadExcel($attrs, $lists);
        }catch(ApiBaseException $e){
            Util::pageNotFund($e->getMessage());
        }
    }

    public function actionConfirm(){
        $request = $_REQUEST;
        $orderId = !empty($request['id'])?intval($request['id']):0;
        try{
            if(!$orderId){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN, '参数错误');
            }
            if(!Orders::find()->where(['id' => $orderId])->exists()){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN, '数据不存在');
            }
            $opId = $_SESSION['money_user_id'];
            $ret = Orders::orderConfirm($orderId, $opId);
            if(!$ret){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN, '客服确认失败');
            }
            $orderInfo = Orders::find()->where(['id' => $orderId])->asArray()->one();
            $productInfo = Product::find()->where(['id' => $orderInfo['vehicle_type_id']])->asArray()->one();
            $userInfo = UserInfo::findOne(['user_id' => $orderInfo['user_id']]);

            $message = "您的订单已确认，购买车辆：{$productInfo['vehicle_type_name']}{$productInfo['brand_name']}{$productInfo['vehicle_line_name']}{$productInfo['vehicle_version']} {$orderInfo['color']}，送达地址：{$userInfo['address']}。您可以进入jiadao.cn，在个人中心查看订单信息，客服电话：400-010-6766。";
            $mobile = $ret;
            MobileMessage::sendMessage($message, $mobile);
            $result = [
                'code' => ApiErrorDescs::SUCCESS,
                'message' => '确认成功'
            ];
            echo json_encode($result);
            exit;
        }catch(ApiBaseException $e){
            $err = $e->getMessage();
            $err .= '确认失败';
            $result = [
                'code' => $e->getCode(),
                'message' => $err
            ];
            echo json_encode($result);
            exit;
        }
    }


    /**
     * 逻辑删除一个订单
     */
    public function actionDelete(){
        try{
            $id = $_REQUEST['id'];
            $isDel = isset($_REQUEST['is_del']) ? 1 : 0;
            if(!$id){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '参数有误！');
            }
            if(!$order = Orders::findOne(['id' => $id, 'status' => Orders::ORDER_STATUS_CREATE])){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '该订单状态无法操作！');
            }
            $order->is_del = $isDel;
            $order->update_time = time();
            if(!$order->update()){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '订单操作失败！');
            }
            $result = [
                'code' => ApiErrorDescs::SUCCESS,
                'message' => 'success',
            ];
        } catch (ApiBaseException $e) {
            $result = [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        }
        echo json_encode($result);
    }
}
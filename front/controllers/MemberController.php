<?php
/**
 * Created by PhpStorm.
 * User: 58
 * Date: 2016/8/17
 * Time: 17:58
 */

namespace front\controllers;


use api\controllers\ApiBaseController;
use common\models\ApiBaseException;
use common\models\ApiErrorDescs;
use common\models\ApiUtils;
use front\models\City;
use front\models\DisplayOrder;
use front\models\District;
use front\models\Paging;
use front\models\Province;
use front\models\ShoppingCart;

class MemberController extends BaseController
{
    /**
     * 晒单
     */
    public function actionDisplayOrder()
    {
        try{
            $request = array_merge($_GET, $_POST);
            $userId = $_SESSION['USER_ID'];
            $result = [
                'code' => ApiErrorDescs::SUCCESS,
                'message' => 'success',
                'data' => DisplayOrder::publish($userId, $request),
            ];
        }catch (ApiBaseException $e){
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
    /**
     * 晒单列表
     */
    public function actionDisplayOrderList()
    {
        $request = $this->request;
        $request['page_size'] = $pageSize = ApiUtils::getIntParam('page_size', $request, 20);
        $request['p'] = $page = ApiUtils::getIntParam('p', $request, 1);
        $total = DisplayOrder::getCountByCondition();
        $paging = new Paging($total, $pageSize, '/member/display-order-list?', $page);
        $list = DisplayOrder::getList($request);
        $data = [
            'list' =>   $list,
            'paging'    =>  $paging->output(),
        ];
        return $this->render('display_order_list.tpl', $data);
    }
    /**
     * 晒单详情页
     */
    public function actionDisplayOrderDetail(){
        $request = $this->request;
        $data = DisplayOrder::getDetail(intval($request['id']));
        return $this->render('display_order_detail.tpl', $data);
    }

    /**
     * 获取所有省份
     * @throws \yii\base\ExitException
     */
    public function actionProvince()
    {
        $request = $this->request;
        try{
            $result = [
                'code'  =>  ApiErrorDescs::SUCCESS,
                'message'   =>  'success',
                'data'  => [
                    'provinces' => Province::getAll(),
                ],
            ];
        } catch (ApiBaseException $e) {
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

    /**
     * 获取省份下对应城市列表
     * @throws \yii\base\ExitException
     */
    public function actionCity()
    {
        $request = $this->request;
        $provinceId = ApiUtils::getIntParam('province_id', $request);
        try{
            $result = [
                'code'  =>  ApiErrorDescs::SUCCESS,
                'message'   =>  'success',
                'data'  => [
                    'citys' => City::getDataByProvinceId($provinceId),
                ],
            ];
        } catch (ApiBaseException $e) {
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

    /**
     * 获取城市下对应区域列表
     * @throws \yii\base\ExitException
     */
    public function actionDistrict()
    {
        $request = $this->request;
        $cityId = ApiUtils::getIntParam('city_id', $request);
        try{
            $result = [
                'code'  =>  ApiErrorDescs::SUCCESS,
                'message'   =>  'success',
                'data'  => [
                    'citys' => District::getDataByCityId($cityId),
                ],
            ];
        } catch (ApiBaseException $e) {
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
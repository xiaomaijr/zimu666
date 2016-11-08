<?php
/**
 * Created by PhpStorm.
 * User: 58
 * Date: 2016/8/27
 * Time: 17:23
 */

namespace front\controllers;


use api\controllers\ApiBaseController;
use common\models\ApiBaseException;
use common\models\ApiConfig;
use common\models\ApiUtils;
use front\models\Category;
use front\models\ChromePhp;
use front\models\CmsData;
use front\models\DisplayOrder;
use front\models\Goods;
use front\models\Indianaed;
use front\models\IndianaGoods;
use front\models\OrderLuckNumber;
use front\models\Orders;
use front\models\Paging;
use front\models\Reward;
use front\models\UserInfo;
use yii\debug\components\search\matchers\Base;

class MallController extends BaseController
{
    /**
     * 首页
     */
    public function actionIndex()
    {
        $request = $this->request;
        $indianaHotGoods = [];
        $banners = CmsData::getByPSignAndSSign('index', 'banner');
        $hotGoods = Goods::getList(['is_hot' => 1, 'status' => 1, 'is_del' => ApiConfig::IS_DEL_NOT]);
        if ($hotGoods) {
            $hotGoodIds = ApiUtils::getCols($hotGoods, 'id');
            $indianaHotGoods = ApiUtils::getMap(IndianaGoods::getList(['good_id' => array_unique($hotGoodIds), 'status' => 0]), 'good_id');
        }
        $data = [
            'banners' => json_decode($banners['data'], true),
            'lattestGoods' => Goods::getList(['is_lattest' => 1, 'status' => 1, 'is_del' => ApiConfig::IS_DEL_NOT]),
            'hotGoods'     => Goods::getList(['is_hot' => 1, 'status' => 1, 'is_del' => ApiConfig::IS_DEL_NOT]),
            'indianaHotGoods' => $indianaHotGoods,
            'newGoods'     => Goods::getList(['is_index' => 1, 'status' => 1, 'is_del' => ApiConfig::IS_DEL_NOT], 4),
            'displayOrders'=> array_chunk(DisplayOrder::getList(['pageSize' => 5, 'page' => 1]), 2),
        ];
        return $this->render('index.tpl', $data);
    }

    /**
     * 产品页
     */
    public function actionProduct()
    {
        $request = $this->request;
        $pageType = ApiUtils::getStrParam('page_type', $request);
        $categoryId = ApiUtils::getIntParam('category_id', $request);
        $query = ApiUtils::getStrParam('query', $request);
        if ($categoryId > 0) {
            $condition['category_id'] = $categoryId;
        }
        if ($query) {
            $condition[] = 'name like %' . $query . '%';
        }
        $orderByMaps = [
            'end_time'     =>      '即将揭晓',
            'is_hot'       =>      '人气',
//            'involved_num' =>      '夺宝人次',
            'is_lattest'   =>      '最新',
            'price'        =>      '价值',
        ];
        $categoryMaps = [
            0   =>      ['name' => '全部分类'],
        ];
        $categorys = ApiUtils::getMap(Category::getAll(), 'id');

        $condition = ['status' => 1];
        if ($pageType == 'hot') {
            $condition['is_hot'] = 1;
        } elseif ($pageType == 'new') {
            $condition['is_index'] = 1;
        }
        $categoryId = ApiUtils::getIntParam('category_id', $request);
        if ($categoryId) $condition = ['category_id' => $categoryId];
        $orderBy = ApiUtils::getStrParam('orderBy', $request, 'end_time desc');
        $curPage = ApiUtils::getIntParam('p', $request, 1);
        $pageSize = ApiUtils::getIntParam('page_size', $request, 20);
        $total = Goods::getCountByCondition($condition);
        $pageObj = new Paging($total, $pageSize, '/mall/product?' . ApiUtils::getMapping($request, 'p'), $curPage);
        $products = Goods::getList($condition, $pageSize, $curPage, $orderBy);
        $indianaGoods = [];
        if ($products) {
            $goodIds = ApiUtils::getCols($products, 'id');
            $indianaGoods = ApiUtils::getMap(IndianaGoods::getList(['good_id' => array_unique($goodIds), 'status' => 0]), 'good_id');
        }
        $data = [
            'products' => $products,
            'indianaGoods' => $indianaGoods,
            'params' =>  [
                'categoryId' => $categoryId,
                'orderBy' => $orderBy,
                'curPage' => $curPage,
                'categoryUrl' => '/mall/product?' . ApiUtils::getMapping($request, 'category_id'),
                'orderUrl' => '/mall/product?' . ApiUtils::getMapping($request, 'orderBy'),
            ],
            'categoryMaps' => array_merge($categoryMaps, $categorys),
            'orderByMaps' => $orderByMaps,
            'pageing' => $pageObj->output(),
        ];
        return $this->render('product.tpl', $data);
    }
    /**
     * 最新揭晓页
     */
    public function actionDisclose()
    {
        $request = $this->request;
        $page = ApiUtils::getIntParam('p', $request, 1);
        $pageSize = ApiUtils::getIntParam('page_size', $request, 20);
        $total = Goods::getCountByCondition(['is_lattest' => 1]);
        $paging = new Paging($total, $pageSize, '/mall/disclose', $page);
        $data = [
            'lattestGoods' => Goods::getList(['is_lattest' => 1], $pageSize, $page),
            'paging'    =>  $paging->output(),
        ];
        return $this->render('disclose.tpl', $data);
    }
    /**
     * 详情页
     */
    public function actionDetail()
    {
        $request = $this->request;
        $id = ApiUtils::getIntParam('id', $request);
        $goodInfo = Goods::get($id);
        if (!$goodInfo) {
            $this->redirect('/mall/error');
        }
        $goodInfo['coverImages'] = explode(',', $goodInfo['cover_images']);
        $indianaGoodInfo = IndianaGoods::getInfoByCondition(['good_id' => $id, 'status' => 0]);
        $partOrders = Orders::getList(['indiana_good_id' => $indianaGoodInfo['id'], 'status' => Orders::ORDER_STATUS_PAY], 5, 1);
        $luckNumbers = $partRecords = [];
        if ($partOrders) {
            $partRecords = Orders::formatPartRecords($partOrders);
            $orderIds = ApiUtils::getCols($partOrders, 'id');
            $luckNumInfos = OrderLuckNumber::getDataByConditions(['order_id' => $orderIds]);
            foreach ($luckNumInfos as $info) {
                $luckNumbers[$info['order_id']][] = $info;
            }
        }
        $data = [
            'goodInfo' => $goodInfo,
            'indianaGoodInfo' => $indianaGoodInfo,
            'rewardInfos' => Indianaed::getList(['good_id' => $id], 5, 1),
            'partRecords' => $partRecords,
            'luckNumbers' => $luckNumbers,
            'displayOrders'=> DisplayOrder::getList(['pageSize' => 5, 'page' => 1, 'condition' => ['good_id' => $id]]),
        ];
//        echo json_encode($data);exit;
        return $this->render('detail.tpl', $data);
    }

    /**
     * 开奖页面
     * @return string
     */
    public function actionReward()
    {
        $request = $this->request;
        $id = ApiUtils::getIntParam('id', $request);
        $indianaGoodInfo = IndianaGoods::get($id);
        if ($indianaGoodInfo['status'] == IndianaGoods::STATUS_CREATE) {
            return $this->redirect('/mall/detail?id=' . $indianaGoodInfo['good_id']);
        }
        $goodInfo = Goods::get($indianaGoodInfo['good_id']);
        $partOrders = Orders::getList(['indiana_good_id' => $id, 'status > ' . Orders::ORDER_STATUS_CREATE], 5, 1);
        $luckNumbers = $partRecords = [];
        if ($partOrders) {
            if ($partOrders) {
                $partRecords = Orders::formatPartRecords($partOrders);
                $orderIds = ApiUtils::getCols($partOrders, 'id');
                $luckNumInfos = OrderLuckNumber::getDataByConditions(['order_id' => $orderIds]);
                foreach ($luckNumInfos as $info) {
                    $luckNumbers[$info['order_id']][] = $info;
                }
            }
        }
        $data = [
            'indianaGoodInfo' => $indianaGoodInfo,
            'goodInfo' =>  $goodInfo,
            'rewardUserInfo' => UserInfo::get($indianaGoodInfo['reward_user_id']),
            'luckNumbers' => OrderLuckNumber::getList(['user_id' => $indianaGoodInfo['reward_user_id'], 'indiana_good_id' => $id]),
            'nextGoodInfo' => IndianaGoods::getInfoByCondition(['good_id' => $goodInfo['id'], 'status' => IndianaGoods::STATUS_CREATE]),
            'partRecords' => $partRecords,
            'orderLuckNumbers' => $luckNumbers,
            'displayOrders'=> DisplayOrder::getList(['pageSize' => 5, 'page' => 1, 'condition' => ['issue' => $indianaGoodInfo['issue']]]),
            'statPartRecords' => Orders::formatStatRecords(Orders::getList(['indiana_good_id' => $id, 'status > ' . Orders::ORDER_STATUS_CREATE], 5, 1, 'id desc', false)),
        ];
//        echo json_encode($data);exit;
        return $this->render('reward.tpl', $data);
    }


    /**
     * 404页面
     */
    public function actionError()
    {
        $request = $this->request;
        return $this->render('error.tpl', $request);
    }

    public function actionRewardTest()
    {
        try{

            $obj = new Reward();
            echo 'success';
        }catch (ApiBaseException $e){
            echo $e->getMessage();
        }
        exit;
    }
}
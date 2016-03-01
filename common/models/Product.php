<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao-pc
 * Date: 2016/2/29
 * Time: 16:02
 */

namespace common\models;


use yii\redis\Cache;

class Product
{
    const PRODUCT_TYPE_P2B = 1;//网贷理财
    const PRODUCT_TYPE_YIZUDAI = 2;//租赁
    const PRODUCT_TYPE_JIJIN = 3;//基金
    const PRODUCT_TYPE_BAOLIDAI = 4;//保理

    private $type;

    public function __construct($type = 1)
    {
        $this->type = $type;
    }

    private $modelNameMap = [  //产品列表

        self::PRODUCT_TYPE_P2B => [ 'list' => 'common\models\LzhBorrowInfo', 'user' => 'common\models\LzhBorrowInvest'],
        self::PRODUCT_TYPE_YIZUDAI => '',
        self::PRODUCT_TYPE_JIJIN => '',
        self::PRODUCT_TYPE_BAOLIDAI => '',
    ];

    private $paramMap = [
        self::PRODUCT_TYPE_P2B => ['list' => 'id, borrow_name, borrow_interest_rate, borrow_duration, borrow_money, borrow_min, repayment_type, has_borrow',
            'user' => 'borrow_id'],
        self::PRODUCT_TYPE_YIZUDAI => '',
        self::PRODUCT_TYPE_JIJIN => '',
        self::PRODUCT_TYPE_BAOLIDAI => '',
    ];

    private $conditionMap = [
        self::PRODUCT_TYPE_P2B => [
            'list' => ['borrow_status'  =>  2],
            'user' => ['investor_uid' => '', 'borrow_id' => '']
        ],
        self::PRODUCT_TYPE_YIZUDAI => '',
        self::PRODUCT_TYPE_JIJIN => '',
        self::PRODUCT_TYPE_BAOLIDAI => '',
    ];

    private $cacheKeyMap = [
        self::PRODUCT_TYPE_P2B => 'product_borrowinfo_getlist',
        self::PRODUCT_TYPE_YIZUDAI => '',
        self::PRODUCT_TYPE_JIJIN => '',
        self::PRODUCT_TYPE_BAOLIDAI => '',
    ];

    private $listIndexMap = [
        self::PRODUCT_TYPE_P2B => [
            'list' => 'id',
            'user' => 'borrow_id'
        ],
        self::PRODUCT_TYPE_YIZUDAI => '',
        self::PRODUCT_TYPE_JIJIN => '',
        self::PRODUCT_TYPE_BAOLIDAI => '',
    ];

    public function getList($request, &$ids){

        $curPage = ApiUtils::getIntParam('p', $request, 1);
        $pageSize = ApiUtils::getIntParam('page_size', $request);
        $modelName = $this->getModeName('list');
        $params = $this->getParams('list');
        $condition = $this->getCondition('list');
        $index = $this->getListIndex('list');

        $preCacheKey = $this->getCacheKey();
        $cacheKey = CacheKey::getCacheKey(ApiUtils::getIntParam('p', $request), $preCacheKey);
        $cache = new Cache();
        $list = $ids = [];
        if($cache->exists($cacheKey['key_name'])){
            $list = $cache->get($cacheKey['key_name']);
        }else{
            $list = $modelName::getDataByConditions($condition, 'id desc', $pageSize, $curPage, $params);
            if($this->type == self::PRODUCT_TYPE_P2B){
                foreach($list as $row){
                    $process = $row['has_borrow']&&$row['borrow_money']?$row['has_borrow']/$row['borrow_money']:0;
    //                $tmp = self::toApiArr($row);
                    $row['process'] = $process;
                    $ret[$row[$index]] = $row;
                }
                $list = $ret;
            }
            $cache->set($cacheKey['key_name'], $list, $cacheKey['expire']);
        }
        $ids = array_keys($list);
        return $list;
    }

    public function getUserList($userId, $ids){
        $modelName = $this->getModeName('user');
        $params = $this->getParams('user');
        $condition = $this->getCondition('user');
        $index = $this->getListIndex('user');
        $i = 0;
        foreach($condition as $k=>$v){
            $condition[$k] = func_get_arg($i);
            $i++;
        }
        $list = $modelName::getDataByConditions($condition, 'id desc', 0, 0, $params);
        $investBorIds = ApiUtils::getCols($list, $index);
        return $investBorIds;
    }

    private function getModeName($key){
        return $this->modelNameMap[$this->type][$key];
    }

    private function getParams($key){
        return $this->paramMap[$this->type][$key];
    }

    private function getCondition($key){
        return $this->conditionMap[$this->type][$key];
    }

    private function getListIndex($key){
        return $this->listIndexMap[$this->type][$key];
    }

    private function getCacheKey(){
        return $this->cacheKeyMap[$this->type];
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao-pc
 * Date: 2016/3/7
 * Time: 10:19
 */

namespace common\models;


use yii\base\Component;
use yii\redis\Cache;

class Product extends Component
{
    const PRODUCT_TYPE_P2B = 1;//网贷理财
    const PRODUCT_TYPE_YIZUDAI = 2;//租赁
    const PRODUCT_TYPE_JIJIN = 3;//基金
    const PRODUCT_TYPE_BAOLIDAI = 4;//保理
    const REDIS_KEY_PRODUCT_LIST = 'product_list';

    public static function factory($configs){
        return \Yii::createObject($configs);
    }

    /*
     * 获取产品列表
     */
    public function getList($request, &$ids){

        $curPage = ApiUtils::getIntParam('p', $request, 1);
        $pageSize = ApiUtils::getIntParam('page_size', $request);

        $cacheKey = CacheKey::getCacheKey($this->type . '_' . ApiUtils::getIntParam('p', $request), self::REDIS_KEY_PRODUCT_LIST);
        $cache = new Cache();
        $list = [];
        $model =  $this->listModelName;
        if($cache->exists($cacheKey['key_name'])){
            $ids = $cache->get($cacheKey['key_name']);
            $list = $model::gets($ids);
        }else{
            $list = $model::getDataByConditions($this->listCondition, 'id desc', $pageSize, $curPage, $this->listParams);
            $ids = ApiUtils::getCols($list, $this->listIndex);
            $cache->set($cacheKey['key_name'], $ids, $cacheKey['expire']);
        }
        if($this->type == self::PRODUCT_TYPE_P2B){
            foreach($list as $row){
                $tmp = $model::toApiArr($row);
                $ret[] = $tmp;
            }
            $list = $ret;
        }
        return $list;
    }

    /*
     * 获取用户参与过的产品
     */
    public function getUserList($userId, $ids){
        $model = $this->userModelName;
        $i = 0;
        $condition = [];
        foreach($this->userCondition as $k=>$v){
            $condition[$k] = func_get_arg($i);
            $i++;
        }
        $list = $model::getDataByConditions($condition, 'id desc', 0, 0, $this->userParams);
        $investBorIds = ApiUtils::getCols($list, $this->userIndex);
        return $investBorIds;
    }

    public function setType($type){
        $this->type = $type;
    }

    public function setListModelName($modelName){
        $this->listModelName = $modelName;
    }

    public function setUserModelName($modelName){
        $this->userModelName = $modelName;
    }

    public function setListParams($params){
        $this->listParams = $params;
    }

    public function setUserParams($params){
        $this->userParams = $params;
    }

    public function setListCondition($condition){
        $this->listCondition = $condition;
    }

    public function setUserCondition($condition){
        $this->userCondition = $condition;
    }

    public function setListIndex($index){
        $this->listIndex = $index;
    }

    public function setUserIndex($index){
        $this->userIndex = $index;
    }

}
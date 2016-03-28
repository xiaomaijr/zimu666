<?php

namespace common\models;

use common\models\ApiUtils;
use common\models\BaseModel;
use common\models\CacheKey;
use Yii;
use yii\redis\Cache;

/**
 * This is the model class for table "lzh_borrow_info".
 *
 * @property string $id
 * @property string $borrow_name
 * @property integer $borrow_uid
 * @property integer $borrow_duration
 * @property string $borrow_money
 * @property string $borrow_interest
 * @property string $borrow_interest_rate
 * @property string $borrow_fee
 * @property string $has_borrow
 * @property string $borrow_times
 * @property string $repayment_money
 * @property string $repayment_interest
 * @property string $expired_money
 * @property integer $repayment_type
 * @property integer $borrow_type
 * @property integer $borrow_item_type
 * @property integer $borrow_status
 * @property integer $add_time
 * @property integer $collect_day
 * @property string $collect_time
 * @property string $full_time
 * @property integer $second_verify_time
 * @property string $deadline
 * @property string $add_ip
 * @property string $borrow_info
 * @property integer $repament_total_times
 * @property integer $has_repment_times
 * @property integer $has_pay
 * @property string $borrow_min
 * @property string $borrow_max
 * @property integer $is_tuijian
 * @property integer $use_hongbao
 * @property string $item_use_desc
 * @property string $item_position
 * @property string $borrow_username
 * @property integer $queue_id
 * @property string $deal_info
 * @property integer $is_ready
 */
class BorrowInfo extends RedisActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lzh_borrow_info';
    }

    public static $tableName = 'lzh_borrow_info';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['borrow_name', 'borrow_uid', 'borrow_duration', 'borrow_money', 'borrow_interest', 'borrow_interest_rate', 'expired_money', 'repayment_type', 'borrow_type', 'borrow_status', 'add_time', 'collect_day', 'collect_time', 'deadline', 'add_ip', 'borrow_info', 'repament_total_times', 'has_pay', 'borrow_min', 'borrow_max', 'deal_info'], 'required'],
            [['borrow_uid', 'borrow_duration', 'borrow_times', 'repayment_type', 'borrow_type', 'borrow_item_type', 'borrow_status', 'add_time', 'collect_day', 'collect_time', 'full_time', 'second_verify_time', 'deadline', 'repament_total_times', 'has_repment_times', 'has_pay', 'borrow_min', 'borrow_max', 'is_tuijian', 'use_hongbao', 'queue_id', 'is_ready'], 'integer'],
            [['borrow_money', 'borrow_interest', 'borrow_interest_rate', 'borrow_fee', 'has_borrow', 'repayment_money', 'repayment_interest', 'expired_money'], 'number'],
            [['borrow_name', 'borrow_username'], 'string', 'max' => 50],
            [['add_ip'], 'string', 'max' => 16],
            [['borrow_info'], 'string', 'max' => 1000],
            [['item_use_desc'], 'string', 'max' => 200],
            [['item_position'], 'string', 'max' => 100],
            [['deal_info'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'borrow_name' => 'Borrow Name',
            'borrow_uid' => 'Borrow Uid',
            'borrow_duration' => 'Borrow Duration',
            'borrow_money' => 'Borrow Money',
            'borrow_interest' => 'Borrow Interest',
            'borrow_interest_rate' => 'Borrow Interest Rate',
            'borrow_fee' => 'Borrow Fee',
            'has_borrow' => 'Has Borrow',
            'borrow_times' => 'Borrow Times',
            'repayment_money' => 'Repayment Money',
            'repayment_interest' => 'Repayment Interest',
            'expired_money' => 'Expired Money',
            'repayment_type' => 'Repayment Type',
            'borrow_type' => 'Borrow Type',
            'borrow_item_type' => 'Borrow Item Type',
            'borrow_status' => 'Borrow Status',
            'add_time' => 'Add Time',
            'collect_day' => 'Collect Day',
            'collect_time' => 'Collect Time',
            'full_time' => 'Full Time',
            'second_verify_time' => 'Second Verify Time',
            'deadline' => 'Deadline',
            'add_ip' => 'Add Ip',
            'borrow_info' => 'Borrow Info',
            'repament_total_times' => 'Repament Total Times',
            'has_repment_times' => 'Has Repment Times',
            'has_pay' => 'Has Pay',
            'borrow_min' => 'Borrow Min',
            'borrow_max' => 'Borrow Max',
            'is_tuijian' => 'Is Tuijian',
            'use_hongbao' => 'Use Hongbao',
            'item_use_desc' => 'Item Use Desc',
            'item_position' => 'Item Position',
            'borrow_username' => 'Borrow Username',
            'queue_id' => 'Queue ID',
            'deal_info' => 'Deal Info',
            'is_ready' => 'Is Ready',
        ];
    }

    //还款方式映射
    public static $repayMap = [
        1 => '按天到期还款',
        2 => '按月分期还款',
        3 => '按季分期还款',
        4 => '每月还息到期还本',

    ];
    //借款类型映射
    public static $borrowTypeMap = [
        1 => '普通标',
        2 => '担保标',
        3 => '秒还标',
        4 => '净值标',
        5 => '抵押标',
    ];
    //借款状态映射
    public static $borrowStatusMap = [
        0 => '初审待审核',
        1 => '初审未通过',
        2 => '借款中',
        3 => '流标',
        4 => '复审中',
        5 => '复审未通过',
        6 => '还款中',
        7 => '正常完成',
        8 => '已逾期',
        9 => '网站代还完成',
        10 => '逾期还款',
    ];
    /*
     * api返回字段
     */
    public static $apiParams = ['id', 'borrow_name', 'borrow_duration',  'borrow_type', 'borrow_money', 'borrow_interest',  'borrow_interest_rate', 'has_borrow', 'borrow_min',
    'borrow_max', 'repayment_type', 'is_tuijian', 'use_hongbao', 'borrow_status'];
    /*
     * 过滤列表api需要字段
     */
    public static function toApiArr($arr){
        return [
            'id' => ApiUtils::getIntParam('id', $arr),
            'borrow_name' => ApiUtils::getStrParam('borrow_name', $arr),
            'borrow_duration' => ApiUtils::getIntParam('borrow_duration', $arr),
//            'borrow_type' => self::$borrowTypeMap[ApiUtils::getIntParam('borrow_type', $arr)],
            'borrow_money' => ApiUtils::getFloatParam('borrow_money', $arr),
            'borrow_interest' => ApiUtils::getFloatParam('borrow_interest', $arr),
            'borrow_interest_rate' => ApiUtils::getFloatParam('borrow_interest_rate', $arr),
            'has_borrow' => ApiUtils::getFloatParam('has_borrow', $arr),
            'borrow_min' => ApiUtils::getIntParam('borrow_min', $arr),
            'borrow_max' => ApiUtils::getIntParam('borrow_max', $arr),
            'repayment_type' => self::$repayMap[ApiUtils::getIntParam('repayment_type', $arr)],
            'is_tuijian' => ApiUtils::getIntParam('is_tuijian', $arr),
            'use_hongbao' => ApiUtils::getIntParam('use_hongbao', $arr),
            'borrow_status' => self::$borrowStatusMap[ApiUtils::getIntParam('borrow_status', $arr)],
            'remain' => ApiUtils::getFloatParam('borrow_money', $arr) - ApiUtils::getFloatParam('has_borrow', $arr),
            'process' => ApiUtils::getFloatParam('borrow_money', $arr)?ApiUtils::getFloatParam('has_borrow', $arr)/ApiUtils::getFloatParam('borrow_money', $arr):0,
        ];
    }
    /*
     * 获取推荐融资项目列表
     */
    public static function getList($request, $condition, $params = ''){
        $curPage = ApiUtils::getIntParam('p', $request, 1);
        $pageSize = ApiUtils::getIntParam('page_size', $request);
        $cacheKey = CacheKey::getCacheKey($curPage);
        $ret = $ids = [];
        $params = !empty($params)?$params:self::$apiParams;
        $cache = new Cache();
        if($cache->exists($cacheKey['key_name'])){
            $ids = $cache->get($cacheKey['key_name']);
            $list = self::gets($ids);
        }else{
            $list = self::getDataByConditions($condition, 'id desc', $pageSize, $curPage, $params);
            $ids = ApiUtils::getCols($list, 'id');
            $cache->set($cacheKey['key_name'], $ids, $cacheKey['expire']);
        }
        foreach($list as $row){
            $tmp = self::toApiArr($row);
            $ret[] = $tmp;
            $ids[] = $row['id'];
        }

        return $ret;
    }
    public function insertEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'id:' . $this->id);
    }

    public function updateEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'id:' . $this->id);
    }

    public function deleteEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'id:' . $this->id);
    }
    //获取投标总人数及投资总额
    public static function getInfo($id){
        $info = self::get($id);
        if(empty($info)){
            throw new ApiBaseException(ApiErrorDescs::ERR_BORROW_DATA_NOT_EXIST);
        }
        $objInvest = new BorrowInvest(['tableName' => 'lzh_borrow_investor_' . $id%3]);
        $investInfo = $objInvest->getInvestPersonAndMoneyTotal($id);
        $data = self::toApiArr($info);
        $data['person_count'] = ApiUtils::getIntParam('c', $investInfo);
        $data['money_total'] = ApiUtils::getFloatParam('s', $investInfo);
        return $data;
    }
    /*
     * 更新has_borrow并验证其是否小于borrow_money
     */
    public static function updateHasBorrow($id ,$money){
        $sql = "update " . self::$tableName . " set has_borrow = has_borrow + " . $money . " where id = " . $id . " and has_borrow + " . $money . " < borrow_money";
        $db = self::getDb();
        return $db->createCommand($sql)->execute();
    }
}

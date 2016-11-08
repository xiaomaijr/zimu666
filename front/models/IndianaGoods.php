<?php

namespace front\models;

use common\models\ApiBaseException;
use common\models\ApiConfig;
use common\models\ApiErrorDescs;
use common\models\ApiUtils;
use common\models\RedisActiveRecord;
use Yii;

/**
 * This is the model class for table "indiana_goods".
 *
 * @property integer $id
 * @property string $issue
 * @property integer $good_id
 * @property integer $end_time
 * @property integer $total_inputs
 * @property integer $involved_num
 * @property integer $status
 * @property integer $is_del
 * @property integer $reward_user_id
 * @property string $reward_order_no
 * @property string $luck_number
 * @property integer $reward_time
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $version
 */
class IndianaGoods extends RedisActiveRecord
{
    /**
     * 待揭晓时长
     */
    const TO_BE_ANNOUNCED_DURATION = 30*60;
    const STATUS_CREATE = 0;
    const STATUS_SOLD_OUT = 1;
    const STATUS_FINISH = 2;
    /**
     * 表名
     */
    public static $tableName = 'Indiana_goods';
    /**
     * @inheritdoc
     */
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'indiana_goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['good_id', 'end_time', 'total_inputs', 'involved_num', 'status', 'is_del', 'reward_user_id', 'reward_time', 'create_time', 'update_time', 'version'], 'integer'],
            [['create_time'], 'required'],
            [['issue', 'reward_order_no', 'luck_number'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'issue' => 'Issue',
            'good_id' => 'Good ID',
            'end_time' => 'End Time',
            'total_inputs' => 'Total Inputs',
            'involved_num' => 'Involved Num',
            'status' => 'Status',
            'is_del' => 'Is Del',
            'reward_user_id' => 'Reward User ID',
            'reward_order_no' => 'Reward Order No',
            'luck_number' => 'Luck Number',
            'reward_time' => 'Reward Time',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'version' => 'Version',
        ];
    }
    public static $statusMap = [
        0   =>  '创建',
        1   =>  '已购完',
        2   =>  '已结束',
    ];
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

    /**
     * 最新即将揭晓列表
     */
    public static function getList($condition, $limit = 5, $offset = 1, $order = 'id desc', $needFormat = true)
    {
        $list = self::getDataByConditions($condition, $order, $limit, $offset);
        if (empty($list)) return $list;
        if (!$needFormat) {
            return $list;
        }
//        $categoryIds = ApiUtils::getCols($list, 'category_id');
//        $categorys = ApiUtils::getMap(Category::gets($categoryIds), 'id');
        foreach ($list as &$record) {
            $record = self::format($record);
        }
        return $list;
    }
    /**
     * 格式化记录
     */
    protected static function format($record)
    {
        return [
            'id' => ApiUtils::getIntParam('id', $record),
            'issue' => ApiUtils::getStrParam('issue', $record),
            'name' => ApiUtils::getStrParam('name', $record),
            'good_id' => ApiUtils::getIntParam('good_id', $record),
            'price' => round(ApiUtils::getIntParam('price', $record)/100, 2),
            'min_price' => round(ApiUtils::getIntParam('min_price', $record)/100, 2),
            'color' => ApiUtils::getStrParam('color', $record),
            'image' => ApiUtils::getStrParam('image', $record),
            'end_time' => ApiUtils::getStrTime($record['end_time']),
            'countdown' => date('H:i:s', ApiUtils::getStrTime($record['end_time']) - time()),
            'total_inputs' => ApiUtils::getIntParam('total_inputs', $record),
            'involved_num' => ApiUtils::getIntParam('involved_num', $record),
            'status' => self::$statusMap[$record['status']],
            'reward_user_id' => ApiUtils::getIntParam('reward_user_id', $record),
            'reward_order_no' => ApiUtils::getStrParam('reward_order_no', $record),
            'luck_number'   =>  ApiUtils::getStrParam('luck_number', $record),
            'reward_time'   =>  !empty($record['reward_time']) ? ApiUtils::getStrTime($record['reward_time']) : '',
            'create_time' => ApiUtils::getStrTime($record['create_time']),
        ];
    }

    public static function create($params)
    {
        $obj = new self();
        $obj->attributes = $params;
        $obj->good_id = $params['id'];
        $obj->create_time = time();
        $obj->status = self::STATUS_CREATE;
        $obj->issue = self::buildIssue($params['id']);
        $obj->version = 0;
        if (!$obj->save()) {
            throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '创建夺宝商品失败!');
        }
        return $obj;
    }

    /**
     * 生成期号
     * @param $goodId
     * @return string
     */
    public static function buildIssue($goodId)
    {
        $info = self::find()->where(['good_id' => $goodId, 'is_del' => ApiConfig::IS_DEL_NOT])
        ->orderBy('id desc')->one();
        if (empty($info)) {
            return 100 . sprintf('%03d', $goodId) . 1;
        }
        return strval(intval($info['issue']) + 1);
    }

    /**获取单条数据
     * @param $condition
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getInfoByCondition($condition)
    {
        return self::find()
            ->where($condition)->orderBy('id desc')
            ->asArray()->one();
    }
    
    
    public static function updateInvolvedNum($id, $num, $retry = 3)
    {
        $indianaGood = self::get($id);
        if ($indianaGood['involved_num'] + $num > $indianaGood['total_inputs']) {
            throw new ApiBaseException(ApiErrorDescs::ERR_ORDER_STOCK_NOT_ENOUGH);
        }
        $oldVersion = $indianaGood['version'];
        $attrs = ['involved_num' => $indianaGood['involved_num'] + $num, 'version' => $oldVersion + 1, 'update_time' => time()];

        if ($indianaGood['involved_num'] + $num == $indianaGood['total_inputs']) {
            $attrs['end_time'] = time() + 300;
            $attrs['status'] = self::STATUS_SOLD_OUT;
        }
        if (!IndianaGoods::updateAll($attrs, ['id' => $id, 'version' => $oldVersion, 'status' => self::STATUS_CREATE])) {
            if (--$retry > 0) {
                return self::updateInvolvedNum($id, $num, $retry);
            }
            throw new ApiBaseException(ApiErrorDescs::ERR_ORDER_STOCK_NOT_ENOUGH);
        }
        if ($indianaGood['involved_num'] + $num == $indianaGood['total_inputs']) {
            if (!Goods::updateAll(['is_lattest' => 1, 'update_time' => time(), 'end_time' => $attrs['end_time'] + 300,
            'indiana_good_id' => $id, 'issue' => $indianaGood['issue']], ['id' => $indianaGood['good_id']])) {
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '生成即将揭晓商品失败');
            }
            if (!self::create(['id' => $indianaGood['good_id'], 'total_inputs' => $indianaGood['total_inputs']])) {
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '生成下期商品失败');
            }
        }
        return true;
    }
}

<?php

namespace front\models;

use Yii;

/**
 * This is the model class for table "goods".
 *
 * @property integer $id
 * @property string $issue
 * @property integer $category_id
 * @property string $name
 * @property integer $indiana_good_id
 * @property integer $price
 * @property integer $min_price
 * @property string $color
 * @property string $style
 * @property string $image
 * @property string $cover_images
 * @property integer $total_inputs
 * @property integer $status
 * @property integer $is_lattest
 * @property integer $is_hot
 * @property integer $is_index
 * @property integer $weight
 * @property integer $is_del
 * @property integer $end_time
 * @property integer $create_time
 * @property integer $update_time
 */
namespace front\models;
use common\models\ApiUtils;
use common\models\RedisActiveRecord;

class Goods extends RedisActiveRecord
{
     /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'indiana_good_id', 'price', 'min_price', 'total_inputs', 'status', 'is_lattest', 'is_hot', 'is_index', 'weight', 'is_del', 'end_time', 'create_time', 'update_time'], 'integer'],
            [['create_time'], 'required'],
            [['issue'], 'string', 'max' => 32],
            [['name'], 'string', 'max' => 256],
            [['color', 'style'], 'string', 'max' => 64],
            [['image'], 'string', 'max' => 255],
            [['cover_images'], 'string', 'max' => 500],
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
            'category_id' => 'Category ID',
            'name' => 'Name',
            'indiana_good_id' => 'Indiana Good ID',
            'price' => 'Price',
            'min_price' => 'Min Price',
            'color' => 'Color',
            'style' => 'Style',
            'image' => 'Image',
            'cover_images' => 'Cover Images',
            'total_inputs' => 'Total Inputs',
            'status' => 'Status',
            'is_lattest' => 'Is Lattest',
            'is_hot' => 'Is Hot',
            'is_index' => 'Is Index',
            'weight' => 'Weight',
            'is_del' => 'Is Del',
            'end_time'  =>  'End Time',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
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
        $categoryIds = ApiUtils::getCols($list, 'category_id');
        $categorys = ApiUtils::getMap(Category::gets($categoryIds), 'id');
        foreach ($list as &$record) {
            $record = self::format($record, $categorys);
        }
        return $list;
    }

    /**
     * 格式化记录
     */
    protected static function format($record, $categorys = [])
    {
        return [
            'id' => ApiUtils::getIntParam('id', $record),
            'issue' => ApiUtils::getStrParam('issue', $record),
            'category' => $categorys[$record['category_id']],
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
            'reward_user_id' => ApiUtils::getIntParam('reward_user_id', $record),
            'reward_order_no' => ApiUtils::getStrParam('reward_order_no', $record),
            'luck_number'   =>  ApiUtils::getStrParam('luck_number', $record),
            'reward_time'   =>  !empty($record['reward_time']) ? ApiUtils::getStrTime($record['reward_time']) : '',
            'create_time' => ApiUtils::getStrTime($record['create_time']),
        ];
    }
}

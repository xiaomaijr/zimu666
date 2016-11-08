<?php

namespace front\models;

use common\models\ApiBaseException;
use common\models\ApiErrorDescs;
use common\models\ApiUtils;
use common\models\RedisActiveRecord;
use Yii;

/**
 * This is the model class for table "display_order".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $order_no
 * @property integer $good_id
 * @property string $issue
 * @property string $luck_number
 * @property string $title
 * @property string $comment
 * @property string $image
 * @property string $ext_images
 * @property integer $create_time
 * @property integer $update_time
 */
class DisplayOrder extends RedisActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'display_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'good_id', 'create_time', 'update_time'], 'integer'],
            [['comment'], 'required'],
            [['comment', 'ext_images'], 'string'],
            [['luck_number'], 'string', 'max' => 32],
            [['order_no', 'issue'], 'string', 'max' => 64],
            [['title'], 'string', 'max' => 255],
            [['image'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'order_no' => 'Order No',
            'good_id' => 'Good Id',
			'issue'	=>	'Issue',
            'luck_number' => 'Luck Number',
            'title' => 'Title',
            'comment' => 'Comment',
            'image' => 'Image',
			'ext_images'	=>	'Ext Images',
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
     * @param $params
     * @return bool
     * @throws ApiBaseException
     * 晒单
     */
    public static function publish($userId, $params)
    {
        $orderInfo = Orders::get($params['order_id']);
        $imageUrls = explode(',', $params['image_urls']);
        $image = array_shift($imageUrls);
        $attrs = [
            'title' => $params['title'],
            'comment' => $params['content'],
            'image' =>  $image,
            'ext_images' => !empty($imageUrls) ? implode(',', $imageUrls) : '',
            'user_id' => $userId,
            'order_no' => $orderInfo['order_no'],
            'good_id' => $orderInfo['good_id'],
            'issue'	=>	$orderInfo['good_issue'],
            'luck_number' => $orderInfo['reward_luck_number'],
        ];
        $obj = new self;
        $obj->attributes = $attrs;
        $obj->create_time = time();
        $transaction = \Yii::$app->getDb()->beginTransaction();
        try{
            if (!$obj->save()) {
                throw new ApiBaseException(ApiErrorDescs::ERR_DISPLAY_ORDER_FAILED);
            }
            if (!Orders::updateAll(['status' => Orders::ORDER_STATUS_DISPLAY, 'update_time' => time()], ['id' => $params['order_id'], 'status' => Orders::ORDER_STATUS_CONFIRM_RECEIVE])) {
                throw new ApiBaseException(ApiErrorDescs::ERR_DISPLAY_ORDER_FAILED, '更新订单状态失败');
            }
            $transaction->commit();
        }catch (ApiBaseException $e) {
            $transaction->rollBack();
            throw $e;
        }
        return true;
    }

    /**
     * 晒单列表
     * @param $params array
     * page
     * pageSize
     */
    public static function getList($params)
    {
        $curPage = ApiUtils::getIntParam('p', $params);
        $pageSize = ApiUtils::getIntParam('page_size', $params);
        $condition = isset($params['condition']) ? $params['condition'] : [];
        $list = self::getDataByConditions($condition, 'id desc', $pageSize, $curPage, '');
        if (!$list) return $list;
        $userIds = ApiUtils::getCols($list, 'user_id');
        $user = ApiUtils::getMap(UserInfo::gets($userIds), 'user_id');
        $goodIds = ApiUtils::getCols($list, 'good_id');
        $goods = ApiUtils::getMap(Goods::gets(array_unique($goodIds)));
        foreach($list as $row){
            $tmp = self::toApiArr($row, $user, $goods);
            $ret[] = $tmp;
        }
        return $ret;
    }

    /**
     * 格式化数据
     */
    public static function toApiArr($row, $user, $goods)
    {
        return [
            'id' =>  ApiUtils::getIntParam('id', $row),
            'user_name' => $user[$row['user_id']]['account'],
            'user_avatar' => $user[$row['user_id']]['avatar'],
            'order_no' => ApiUtils::getStrParam('order_no', $row),
            'luck_number' =>  ApiUtils::getStrParam('luck_number', $row),
            'good' => $goods[$row['good_id']],
			'issue'	=>	ApiUtils::getStrParam('issue', $row),
            'title' => ApiUtils::getStrParam('title', $row),
            'comment' => ApiUtils::getStrParam('comment', $row),
            'image' => ApiUtils::getStrParam('image', $row),
			'ext_images' =>	!empty($row['ext_images']) ? explode(',', $row['ext_images']) : '',
            'create_time' => date('Y-m-d H:i:s', $row['create_time']),
        ];
    }
    
    public static function getDetail($id)
    {
        if (!$id) return [];
        $info = self::get($id);
        if (!$info) return [];
        $user = UserInfo::get($info['user_id']);
        $good = Goods::get($info['good_id']);
        $order = Orders::getDataByID($info['order_no'], 'order_no');
        $info = self::toApiArr($info, [$user['user_id'] => $user], [$good['id'] => $good]);
        return ['info' => $info, 'order' => $order];
    }
}

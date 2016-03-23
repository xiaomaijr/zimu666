<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lzh_inner_msg".
 *
 * @property integer $id
 * @property string $uid
 * @property string $title
 * @property string $msg
 * @property string $send_time
 * @property integer $status
 */
class InnerMsg extends RedisActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return self::$tableName;
    }

    public static $tableName = 'lzh_inner_msg';

    //设置分表tablename
    public function setTableName($tableName){
        self::$tableName = $tableName;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'title', 'msg', 'send_time'], 'required'],
            [['uid', 'send_time', 'status'], 'integer'],
            [['msg'], 'string'],
            [['title'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'title' => 'Title',
            'msg' => 'Msg',
            'send_time' => 'Send Time',
            'status' => 'Status',
        ];
    }

    public function insertEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'id:' . $this->id);
        $cache->hDel(self::$tableName, 'uid:' . $this->uid);
    }

    public function updateEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'id:' . $this->id);
        $cache->hDel(self::$tableName, 'uid:' . $this->uid);
    }

    public function deleteEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'id:' . $this->id);
        $cache->hDel(self::$tableName, 'uid:' . $this->uid);
    }

    /*
     * 添加新通知
     */
    public function add($attrs = []){
        if(empty($attrs)){
            throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '通知内容不能为空');
        }
        $this->attributes = $attrs;
        $this->send_time = time();
        $this->status = 0;
        $ret = $this->save();
        if(!$ret){
            throw new ApiBaseException(ApiErrorDescs::ERR_INVEST_RECORD_ADD_FAIL);
        }
        return $this->id;
    }
    /*
     * 获取某个用户通知
     */
    public function getMsgByUid($uid, $page = 1, $pageSize = 100){
        $data = [];
        if(empty($uid)) return $data;
        $field = 'uid:' . $uid;
        $cache = self::getCache();
        if(!$cache->hExists(self::$tableName, $field)){
            $userMsgs = self::getDataByConditions(['uid' => $uid], 'id desc', $pageSize, $page);
            if(empty($userMsgs)) return $data;
            $ids = ApiUtils::getCols($userMsgs, 'id');
            $cache->hSet(self::$tableName, $field, $ids);
        }else{
            $ids = $cache->hGet(self::$tableName, $field);
            $userMsgs = self::gets($ids);
        }
        foreach($userMsgs as $msg){
            $data[] = self::toApiArr($msg);
        }
        return $data;
    }
    //api过滤参数
    public static function toApiArr($arr){
        return [
            'title' => ApiUtils::getStrParam('title', $arr),
            'msg' => ApiUtils::getStrParam('msg', $arr),
            'send_time' => date('Ymd H:i:s', $arr['send_time']),
            'status' => ApiUtils::getIntParam('status', $arr),
        ];
    }

}

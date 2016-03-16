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
class LzhInnerMsg extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lzh_inner_msg';
    }

    //设置分表tablename
    public function setSubTableName($tableName){
        $this->subTableName = $tableName;
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

    /*
     * 添加新通知到总表
     */
    public static function add($attrs = []){
        if(empty($attrs)){
            throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '通知内容不能为空');
        }
        $obj = new self;
        $obj->attributes = $attrs;
        $ret = $obj->save();
        if(!$ret){
            throw new ApiBaseException(ApiErrorDescs::ERR_INVEST_RECORD_ADD_FAIL);
        }
        return $obj->id;
    }
    /*
     * 添加新通知到分表
     */
    public function addSubTable($attrs = []){
        if(empty($attrs)){
            throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '通知内容不能为空');
        }
        $sql = "insert into " . $this->subTableName . " " . array_keys($attrs) . " valaues (" . array_values($attrs) . ")";
        $db = $this->getDb();
        $ret = $db->createCommand($sql)->execute();
        if(!$ret){
            throw new ApiBaseException(ApiErrorDescs::ERR_INVEST_RECORD_ADD_FAIL);
        }
    }
}

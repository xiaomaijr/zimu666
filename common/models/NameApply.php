<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lzh_name_apply".
 *
 * @property integer $id
 * @property string $uid
 * @property integer $up_time
 * @property integer $status
 * @property string $idcard
 * @property string $deal_info
 */
class NameApply extends RedisActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lzh_name_apply';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'up_time', 'status', 'idcard', 'deal_info'], 'required'],
            [['uid', 'up_time', 'status'], 'integer'],
            [['idcard'], 'string', 'max' => 20],
            [['deal_info'], 'string', 'max' => 80],
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
            'up_time' => 'Up Time',
            'status' => 'Status',
            'idcard' => 'Idcard',
            'deal_info' => 'Deal Info',
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
    //添加新纪录
    public static function add($attrs){
        if(!isset($attrs['uid'])){
            return false;
        }
        if(!($obj = self::find()->where(['uid' => $attrs['uid']])->one())){
            $obj = new self;
        }
        $obj->attributes = $attrs;
        return $obj->save();
    }
}

<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lzh_borrow_risk_controls".
 *
 * @property string $id
 * @property integer $borrow_id
 * @property integer $vouch_id
 * @property string $vouch_explain
 * @property string $risk_desc
 * @property string $counter_guarant
 */
class BorrowRiskControls extends RedisActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lzh_borrow_risk_controls';
    }

    public static $tableName = 'lzh_borrow_risk_controls';
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['borrow_id'], 'required'],
            [['borrow_id', 'vouch_id'], 'integer'],
            [['counter_guarant'], 'string'],
            [['vouch_explain', 'risk_desc'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'borrow_id' => 'Borrow ID',
            'vouch_id' => 'Vouch ID',
            'vouch_explain' => 'Vouch Explain',
            'risk_desc' => 'Risk Desc',
            'counter_guarant' => 'Counter Guarant',
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

    /*
     * 获取公司风控信息
     */
    public static function getInfoByBId($bid){
        $info = self::_getInfoByBorrowId($bid);
        if(!$info) return $info;
        return $data = [
            'risk_desc' => nl2br(strip_tags(htmlspecialchars_decode(ApiUtils::getStrParam('risk_desc', $info)))),
            'counter_guarant' => nl2br(strip_tags(htmlspecialchars_decode(ApiUtils::getStrParam('counter_guarant', $info)))),
        ];
    }
    /*
     * 根据借款id获取风控信息
     */
    private static function _getInfoByBorrowId($bid){
        $cache = self::getCache();
        $filed = 'borrow_id:' . $bid;
        if(!$cache->hExists(self::$tableName, $filed)){
            $info = self::getDataByID($bid, 'borrow_id');
            if(!$info) return $info;
            $cache->hset(self::$tableName, $filed, $info['id']);
        }else{
            $id = $cache->hGet(self::$tableName, $filed);
            $info = self::get($id);
        }
        return $info;
    }
}

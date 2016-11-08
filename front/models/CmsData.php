<?php

namespace front\models;

use common\models\ApiConfig;
use common\models\RedisActiveRecord;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "cms_data".
 *
 * @property integer $id
 * @property string $p_sign
 * @property string $s_sign
 * @property string $data
 * @property string $description
 * @property integer $is_del
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $operator_id
 */
class CmsData extends RedisActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cms_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['p_sign', 'data'], 'required'],
            [['data', 'description'], 'string'],
            [['is_del', 'create_time', 'update_time', 'operator_id'], 'integer'],
            [['p_sign', 's_sign'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'p_sign' => 'P Sign',
            's_sign' => 'S Sign',
            'data' => 'Data',
            'description' => 'Description',
            'is_del' => 'Is Del',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'operator_id' => 'Operator ID',
        ];
    }

    public static $tableName = "cms_data";

    public static function getByPSignAndSSign($pSign, $sSign = "")
    {
        $key = 'p_sign:' . $pSign . ':s_sign:' . $sSign;
        $cache = self::getCache();
        if (!$cache->hExists(self::$tableName, $key)) {
            $module = self::find()->where(['p_sign' => $pSign, 's_sign' => $sSign, 'is_del' => 0])->asArray()->one();
            if(!empty($module)){
                $cache->hSet(self::$tableName, $key, $module['id']);
                $cache->hSet(self::$tableName, 'id:' . $module['id'], $module);
            }
        } else {
            $id = $cache->hGet(self::$tableName, $key);
            $module = self::get($id, self::$tableName);
        }

        return $module;
    }
    public function insertEvent(){
        $cache = self::getCache();
        $cache->hDel(static::$tableName, 'id:' . $this->id);
    }

    public function updateEvent(){
        $cache = self::getCache();
        $cache->hDel(static::$tableName, 'id:' . $this->id);
    }

    public function deleteEvent(){
        $cache = self::getCache();
        $cache->hDel(static::$tableName, 'id:' . $this->id);
    }
}

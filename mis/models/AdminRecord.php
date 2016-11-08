<?php

namespace mis\models;

use Yii;

/**
 * This is the model class for table "admin_record".
 *
 * @property integer $id
 * @property integer $business_code
 * @property string $business_id
 * @property string $source
 * @property string $extends
 * @property integer $operator_id
 * @property string $record_time
 */
class AdminRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_record';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['business_code', 'operator_id'], 'integer'],
            [['business_id', 'record_time'], 'required'],
            [['extends'], 'string'],
            [['record_time'], 'safe'],
            [['business_id'], 'string', 'max' => 32],
            [['source'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'business_code' => 'Business Code',
            'business_id' => 'Business ID',
            'source' => 'Source',
            'extends' => 'Extends',
            'operator_id' => 'Operator ID',
            'record_time' => 'Record Time',
        ];
    }

    public function addRecord($params = []){
        $attrs = $this->attributeLabels();
        $interAttrs = array_intersect_key($params, $attrs);
        if(count($interAttrs) != count($attrs) - 1){
            return false;
        }

        $this->attributes = $interAttrs;
        return $this->save();
    }
}

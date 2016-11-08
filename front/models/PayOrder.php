<?php

namespace front\models;

use Yii;

/**
 * This is the model class for table "pay_order".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $trade_no
 * @property integer $price
 * @property integer $type
 * @property integer $status
 * @property string $out_trade_no
 * @property integer $create_time
 * @property integer $update_time
 */
class PayOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pay_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'price', 'type', 'status', 'create_time', 'update_time'], 'integer'],
            [['trade_no'], 'string', 'max' => 32],
            [['out_trade_no'], 'string', 'max' => 64],
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
            'trade_no' => 'Trade No',
            'price' => 'Price',
            'type' => 'Type',
            'status' => 'Status',
            'out_trade_no' => 'Out Trade No',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}

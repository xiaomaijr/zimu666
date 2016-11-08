<?php

namespace front\models;

use Yii;

/**
 * This is the model class for table "user_journal".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $type
 * @property integer $recharge
 * @property integer $gift
 * @property integer $locked
 * @property integer $cashback
 * @property string $trade_no
 * @property string $out_trade_no
 * @property string $attach
 * @property string $comment
 * @property integer $is_del
 * @property integer $create_time
 * @property integer $update_time
 */
class UserJournal extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_journal';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'type', 'recharge', 'gift', 'locked', 'cashback', 'is_del', 'create_time', 'update_time'], 'integer'],
            [['trade_no'], 'string', 'max' => 32],
            [['out_trade_no'], 'string', 'max' => 64],
            [['attach', 'comment'], 'string', 'max' => 1024],
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
            'type' => 'Type',
            'recharge' => 'Recharge',
            'gift' => 'Gift',
            'locked' => 'Locked',
            'cashback' => 'Cashback',
            'trade_no' => 'Trade No',
            'out_trade_no' => 'Out Trade No',
            'attach' => 'Attach',
            'comment' => 'Comment',
            'is_del' => 'Is Del',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}

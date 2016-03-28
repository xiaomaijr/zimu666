<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lzh_member_creditslog".
 *
 * @property string $id
 * @property string $uid
 * @property integer $type
 * @property integer $affect_credits
 * @property integer $account_credits
 * @property string $info
 * @property string $add_time
 * @property string $add_ip
 */
class MemberCreditslog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lzh_member_creditslog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'type', 'affect_credits', 'account_credits', 'info', 'add_time', 'add_ip'], 'required'],
            [['uid', 'type', 'affect_credits', 'account_credits', 'add_time'], 'integer'],
            [['info'], 'string', 'max' => 50],
            [['add_ip'], 'string', 'max' => 16],
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
            'type' => 'Type',
            'affect_credits' => 'Affect Credits',
            'account_credits' => 'Account Credits',
            'info' => 'Info',
            'add_time' => 'Add Time',
            'add_ip' => 'Add Ip',
        ];
    }

    public static function memberCreditsLog($userId, $logType, $credits, $logInfo = '无'){
        if(!$credits){
            return false;
        }
        $attrs = [
            'uid' => $userId,
            'type' => $logType,
            'affect_credits' => $credits,
            'account_credits' => $credits, //  总积分需要从member中获取
            'info' => $logInfo,
            'add_time' => time(),
            'add_ip' => $_SERVER['REMOTE_ADDR']
        ];
        $obj = new self;
        $obj->attributes = $attrs;
        return $obj->save();
    }
}

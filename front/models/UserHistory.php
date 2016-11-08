<?php

namespace front\models;

use Yii;

/**
 * This is the model class for table "user_history".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $ip
 * @property integer $create_time
 * @property integer $update_time
 */
class UserHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'ip', 'create_time', 'update_time'], 'integer'],
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
            'ip' => 'Ip',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
    /**
     * 更新用户登录历史记录
     */
    public static function updateUser($userId)
    {
        $obj = new self;
        $obj->user_id = $userId;
        $obj->ip = ip2long(\Yii::$app->request->getUserIP());
        $obj->create_time = $obj->update_time = time();
        return $obj->save();
    }
}

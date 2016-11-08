<?php

namespace mis\models;

use Yii;

/**
 * This is the model class for table "sms_bulk".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $content
 * @property string $mobiles
 * @property string $preview_mobiles
 * @property string $mobile_source
 * @property integer $content_cnt
 * @property integer $send_num
 * @property string $push_time
 * @property string $status
 * @property string $update_time
 * @property string $create_time
 */
class SmsBulk extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sms_bulk';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'content_cnt', 'send_num'], 'integer'],
            [['content',  'preview_mobiles', 'push_time',  'update_time', ], 'required'],
           // [['content', 'mobiles', 'preview_mobiles'], 'string'],
            //[['push_time', 'status', 'update_time', 'create_time'], 'safe'],
           // [['mobile_source'], 'string', 'max' => 256]
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
            'content' => 'Content',
            'mobiles' => 'Mobiles',
            'preview_mobiles' => 'Preview Mobiles',
            'mobile_source' => 'Mobile Source',
            'content_cnt' => 'Content Cnt',
            'send_num' => 'Send Num',
            'push_time' => 'Push Time',
            'status' => 'Status',
            'update_time' => 'Update Time',
            'create_time' => 'Create Time',
        ];
    }
}

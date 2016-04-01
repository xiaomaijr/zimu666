<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lzh_feedback".
 *
 * @property integer $id
 * @property string $mobile
 * @property string $msg
 * @property string $ip
 * @property string $add_time
 * @property integer $status
 */
class Feedback extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lzh_feedback';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mobile', 'msg', 'ip', 'add_time'], 'required'],
            [['add_time', 'status'], 'integer'],
            [['mobile'], 'string', 'max' => 30],
            [['msg'], 'string', 'max' => 500],
            [['ip'], 'string', 'max' => 16],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mobile' => 'Mobile',
            'msg' => 'Msg',
            'ip' => 'Ip',
            'add_time' => 'Add Time',
            'status' => 'Status',
        ];
    }

    /*
     * 添加用户反馈
     */
    public function add($attrs, $params = []){
        $attributes = [
            'mobile' => ApiUtils::getStrParam('mobile', $params),
            'msg' => ApiUtils::getStrParam('msg', $params),
            'ip' => ApiUtils::get_client_ip(),
            'add_time' => time(),
            'status' => 0,
        ];
        $this->attributes = array_merge($attributes, $attrs);
        if(!$this->save()){
            throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '用户反馈添加失败');
        }
    }
}

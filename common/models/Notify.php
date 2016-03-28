<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lzh_notify".
 *
 * @property string $id
 * @property string $data_md5
 * @property string $notify_url
 * @property string $data
 * @property string $addtime
 * @property integer $num
 * @property string $type
 */
class Notify extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lzh_notify';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['addtime', 'num'], 'integer'],
            [['data_md5', 'type'], 'string', 'max' => 100],
            [['notify_url'], 'string', 'max' => 1000],
            [['data'], 'string', 'max' => 3000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'data_md5' => 'Data Md5',
            'notify_url' => 'Notify Url',
            'data' => 'Data',
            'addtime' => 'Addtime',
            'num' => 'Num',
            'type' => 'Type',
        ];
    }
}

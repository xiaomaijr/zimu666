<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sku_template".
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $rely
 * @property string $title
 * @property string $var_name
 * @property integer $type
 * @property string $data
 * @property string $unit
 * @property string $error_info
 * @property integer $is_required
 * @property integer $is_phone_check
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $is_del
 */
class SkuTemplate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sku_template';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'type', 'is_required', 'is_phone_check', 'create_time', 'update_time', 'is_del'], 'integer'],
            [['rely'], 'string', 'max' => 1024],
            [['title', 'error_info'], 'string', 'max' => 255],
            [['var_name', 'unit'], 'string', 'max' => 64],
            [['data'], 'string', 'max' => 4096]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Category ID',
            'rely' => 'Rely',
            'title' => 'Title',
            'var_name' => 'Var Name',
            'type' => 'Type',
            'data' => 'Data',
            'unit' => 'Unit',
            'error_info' => 'Error Info',
            'is_required' => 'Is Required',
            'is_phone_check' => 'Is Phone Check',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'is_del' => 'Is Del',
        ];
    }
}

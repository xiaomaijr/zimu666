<?php

namespace front\models;

use Yii;

/**
 * This is the model class for table "street".
 *
 * @property integer $street_id
 * @property string $street_name
 * @property string $pinyin
 * @property string $url
 * @property string $location
 * @property integer $district_id
 * @property string $category_url
 * @property integer $script_index
 * @property integer $display_order
 * @property integer $created_by
 * @property string $created_by_name
 * @property string $created_time
 * @property integer $modified_by
 * @property string $modified_by_name
 * @property string $modified_time
 */
class Street extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'street';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['street_name', 'pinyin', 'url', 'district_id', 'script_index', 'display_order', 'created_by_name', 'created_time'], 'required'],
            [['district_id', 'script_index', 'display_order', 'created_by', 'created_time', 'modified_by', 'modified_time'], 'integer'],
            [['street_name', 'created_by_name', 'modified_by_name'], 'string', 'max' => 20],
            [['pinyin', 'url', 'location', 'category_url'], 'string', 'max' => 50],
            [['district_id', 'url', 'category_url'], 'unique', 'targetAttribute' => ['district_id', 'url', 'category_url'], 'message' => 'The combination of Url, District ID and Category Url has already been taken.'],
            [['district_id', 'street_name', 'category_url'], 'unique', 'targetAttribute' => ['district_id', 'street_name', 'category_url'], 'message' => 'The combination of Street Name, District ID and Category Url has already been taken.'],
            [['district_id', 'pinyin', 'category_url'], 'unique', 'targetAttribute' => ['district_id', 'pinyin', 'category_url'], 'message' => 'The combination of Pinyin, District ID and Category Url has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'street_id' => 'Street ID',
            'street_name' => 'Street Name',
            'pinyin' => 'Pinyin',
            'url' => 'Url',
            'location' => 'Location',
            'district_id' => 'District ID',
            'category_url' => 'Category Url',
            'script_index' => 'Script Index',
            'display_order' => 'Display Order',
            'created_by' => 'Created By',
            'created_by_name' => 'Created By Name',
            'created_time' => 'Created Time',
            'modified_by' => 'Modified By',
            'modified_by_name' => 'Modified By Name',
            'modified_time' => 'Modified Time',
        ];
    }
}

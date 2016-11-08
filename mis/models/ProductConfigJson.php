<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product_config_json".
 *
 * @property integer $id
 * @property string $brand_id
 * @property string $brand_name
 * @property string $subbrand_id
 * @property string $subbrand_name
 * @property string $vehicle_line_id
 * @property string $vehicle_line_name
 * @property string $vehicle_type_id
 * @property string $vehicle_type_name
 * @property string $year_style
 * @property string $maker
 * @property string $guide_price
 * @property string $basic_params
 * @property string $body
 * @property string $engine
 * @property string $electromotor
 * @property string $gearbox
 * @property string $site_turn
 * @property string $wheel_brake
 * @property string $safety_equipment
 * @property string $control_config
 * @property string $external_config
 * @property string $inner_config
 * @property string $chair_config
 * @property string $multimedia_config
 * @property string $lights_config
 * @property string $glass
 * @property string $air_condition
 * @property string $high_tech_config
 * @property string $optional_package
 * @property string $external_colors
 * @property string $inner_colors
 * @property string $create_time
 * @property string $update_time
 */
class ProductConfigJson extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_config_json';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['basic_params', 'body', 'engine', 'electromotor', 'gearbox', 'site_turn', 'wheel_brake', 'safety_equipment', 'control_config', 'external_config', 'inner_config', 'chair_config', 'multimedia_config', 'lights_config', 'glass', 'air_condition', 'high_tech_config', 'optional_package', 'external_colors', 'inner_colors'], 'string'],
            [['create_time', 'update_time'], 'safe'],
            [['brand_id', 'subbrand_id', 'vehicle_line_id', 'vehicle_line_name', 'vehicle_type_id', 'year_style', 'maker', 'guide_price'], 'string', 'max' => 16],
            [['brand_name', 'subbrand_name', 'vehicle_type_name'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'brand_id' => 'Brand ID',
            'brand_name' => 'Brand Name',
            'subbrand_id' => 'Subbrand ID',
            'subbrand_name' => 'Subbrand Name',
            'vehicle_line_id' => 'Vehicle Line ID',
            'vehicle_line_name' => 'Vehicle Line Name',
            'vehicle_type_id' => 'Vehicle Type ID',
            'vehicle_type_name' => 'Vehicle Type Name',
            'year_style' => 'Year Style',
            'maker' => 'Maker',
            'guide_price' => 'Guide Price',
            'basic_params' => 'Basic Params',
            'body' => 'Body',
            'engine' => 'Engine',
            'electromotor' => 'Electromotor',
            'gearbox' => 'Gearbox',
            'site_turn' => 'Site Turn',
            'wheel_brake' => 'Wheel Brake',
            'safety_equipment' => 'Safety Equipment',
            'control_config' => 'Control Config',
            'external_config' => 'External Config',
            'inner_config' => 'Inner Config',
            'chair_config' => 'Chair Config',
            'multimedia_config' => 'Multimedia Config',
            'lights_config' => 'Lights Config',
            'glass' => 'Glass',
            'air_condition' => 'Air Condition',
            'high_tech_config' => 'High Tech Config',
            'optional_package' => 'Optional Package',
            'external_colors' => 'External Colors',
            'inner_colors' => 'Inner Colors',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}

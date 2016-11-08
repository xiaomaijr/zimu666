<?php

namespace mis\models;

use Yii;

/**
 * This is the model class for table "role_power".
 *
 * @property integer $id
 * @property integer $role_id
 * @property string $powers
 * @property integer $operator_id
 * @property integer $is_del
 * @property string $create_time
 * @property string $update_time
 */
class Rolepower extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'role_power';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_id', 'powers', 'operator_id'], 'required'],
            [['role_id', 'operator_id', 'is_del'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['powers'], 'string', 'max' => 1024]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role_id' => 'Role ID',
            'powers' => 'Powers',
            'operator_id' => 'Operator ID',
            'is_del' => 'Is Del',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    public static function getPowersByRoleId($id){
        $powers = self::find()
            ->where(['role_id' => $id, 'is_del' => 0])
            ->asArray()
            ->one();
        return $powers?explode(',' , $powers['powers']):[];
    }
}

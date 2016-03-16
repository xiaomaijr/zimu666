<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao-pc
 * Date: 2016/3/16
 * Time: 19:27
 */

namespace common\models;


use yii\db\ActiveRecord;

class SubTabActiveRecord extends ActiveRecord
{
    public static $tableName = '';
    /**
     * Inserts an ActiveRecord into DB without considering transaction.
     * @param array $attributes list of attributes that need to be saved. Defaults to null,
     * meaning all attributes that are loaded from DB will be saved.
     * @return boolean whether the record is inserted successfully.
     */
    protected function insertInternal($attributes = null)
    {
        if (!$this->beforeSave(true)) {
            return false;
        }
        $values = $this->getDirtyAttributes($attributes);
        if (($primaryKeys = static::getDb()->schema->insert(static::$tableName, $values)) === false) {
            return false;
        }
        foreach ($primaryKeys as $name => $value) {
            $id = static::getTableSchema()->columns[$name]->phpTypecast($value);
            $this->setAttribute($name, $id);
            $values[$name] = $id;
        }

        $changedAttributes = array_fill_keys(array_keys($values), null);
        $this->setOldAttributes($values);
        $this->afterSave(true, $changedAttributes);

        return true;
    }
}
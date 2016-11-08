<?php

namespace front\models;

use Yii;

/**
 * This is the model class for table "apps".
 *
 * @property integer $id
 * @property string $name
 * @property string $url
 * @property string $logo
 * @property integer $is_del
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $category_id
 */
class Apps extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'apps';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_del', 'category_id ', 'create_time', 'update_time'], 'integer'],
            [['name', 'logo'], 'string', 'max' => 255],
            [['url'], 'string', 'max' => 64],
            [['name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'url' => 'Url',
            'logo' => 'Logo',
            'is_del' => 'Is Del',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'category_id' => 'Category Id',
        ];
    }

    public static function getAll($categoryId = 2, $pageSize = 100)
    {
        $appData = self::find()
            ->where(['category_id' => $categoryId])
            ->limit($pageSize)
            ->asArray()
            ->all();
		return self::formatData($appData);
    }

	public static function formatData($data)
	{
		if (empty($data)) return [];
		foreach ($data as &$row) {
			if (strpos($row['url'], 'http://') !== 0) {
				$row['url'] = 'http://' . $row['url'];
			}
		}
		return $data;
	}
}

<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lzh_member_info".
 *
 * @property string $uid
 * @property string $sex
 * @property string $zy
 * @property string $cell_phone
 * @property string $info
 * @property string $marry
 * @property string $education
 * @property string $income
 * @property integer $age
 * @property string $idcard
 * @property string $card_img
 * @property string $real_name
 * @property string $address
 * @property integer $province
 * @property integer $province_now
 * @property integer $city
 * @property integer $city_now
 * @property integer $area
 * @property integer $area_now
 * @property string $up_time
 * @property string $card_back_img
 */
class MemberInfo extends RedisActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lzh_member_info';
    }

    public static $tableName = 'lzh_member_info';
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'sex', 'zy', 'cell_phone', 'info', 'marry', 'education', 'income', 'age', 'idcard', 'card_img', 'real_name', 'address', 'province', 'province_now', 'city', 'city_now', 'area', 'area_now', 'up_time', 'card_back_img'], 'required'],
            [['uid', 'age', 'province', 'province_now', 'city', 'city_now', 'area', 'area_now', 'up_time'], 'integer'],
            [['sex', 'marry', 'income', 'idcard'], 'string', 'max' => 20],
            [['zy'], 'string', 'max' => 40],
            [['cell_phone'], 'string', 'max' => 11],
            [['info'], 'string', 'max' => 500],
            [['education', 'real_name'], 'string', 'max' => 50],
            [['card_img', 'card_back_img'], 'string', 'max' => 200],
            [['address'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid' => 'Uid',
            'sex' => 'Sex',
            'zy' => 'Zy',
            'cell_phone' => 'Cell Phone',
            'info' => 'Info',
            'marry' => 'Marry',
            'education' => 'Education',
            'income' => 'Income',
            'age' => 'Age',
            'idcard' => 'Idcard',
            'card_img' => 'Card Img',
            'real_name' => 'Real Name',
            'address' => 'Address',
            'province' => 'Province',
            'province_now' => 'Province Now',
            'city' => 'City',
            'city_now' => 'City Now',
            'area' => 'Area',
            'area_now' => 'Area Now',
            'up_time' => 'Up Time',
            'card_back_img' => 'Card Back Img',
        ];
    }
    public function insertEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'uid:' . $this->uid);
    }

    public function updateEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'uid:' . $this->uid);
    }

    public function deleteEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'uid:' . $this->uid);
    }

    /*
     * 获取用户详情
     */
    public static function get($uid, $tableName = ''){
        $cache = self::getCache();
        $tableName = $tableName?$tableName:self::tableName();

        $field = 'uid:' . $uid;
        if (!$cache->hExists($tableName, $field)) {
            $module = self::find()->where(['uid' => $uid])->asArray()->one();
            $cache->hSet($tableName, $field, $module);
        } else {
            $module = $cache->hGet($tableName, $field);
        }

        return self::toApiArr($module);
    }
    //api过滤参数
     private static function toApiArr($arr){
         $idcard = ApiUtils::getStrParam('idcard', $arr);
        return [
            'mobile' => ApiUtils::getStrParam('cell_phone', $arr),
            'idcard' => $idcard?ApiUtils::replaceByLength($idcard, strlen($idcard)-8, 4, -4):'',
            'real_name' => ApiUtils::getStrParam('real_name', $arr),
            'up_time' => ApiUtils::getStrTimeByUnix(ApiUtils::getIntParam('up_time', $arr)),
        ];
    }
}

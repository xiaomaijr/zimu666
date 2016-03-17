<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lzh_member_banks".
 *
 * @property integer $id
 * @property string $uid
 * @property string $bank_num
 * @property string $bank_province
 * @property string $bank_city
 * @property string $bank_address
 * @property string $bank_name
 * @property string $add_time
 * @property string $add_ip
 * @property integer $status
 * @property integer $platform
 * @property string $factor
 */
class MemberBanks extends RedisActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lzh_member_banks';
    }

    const BANK_KEY_DEFAULT = 'xiaomaijr2016';//银行卡秘钥

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'bank_num', 'bank_province', 'bank_city', 'bank_address', 'bank_name', 'add_time', 'add_ip'], 'required'],
            [['uid', 'add_time', 'status', 'platform'], 'integer'],
            [['bank_num', 'bank_name'], 'string', 'max' => 50],
            [['bank_province', 'bank_city', 'factor'], 'string', 'max' => 20],
            [['bank_address'], 'string', 'max' => 100],
            [['add_ip'], 'string', 'max' => 16],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'bank_num' => 'Bank Num',
            'bank_province' => 'Bank Province',
            'bank_city' => 'Bank City',
            'bank_address' => 'Bank Address',
            'bank_name' => 'Bank Name',
            'add_time' => 'Add Time',
            'add_ip' => 'Add Ip',
            'status' => 'Status',
            'platform' => 'Platform',
            'factor' => 'Factor',
        ];
    }

    public function insertEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'id:' . $this->id);
        $cache->hDel(self::$tableName, 'uid:' . $this->uid);
    }

    public function updateEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'id:' . $this->id);
        $cache->hDel(self::$tableName, 'uid:' . $this->uid);
    }

    public function deleteEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'id:' . $this->id);
        $cache->hDel(self::$tableName, 'uid:' . $this->uid);
    }

    /**
     * 对称加密算法之加密
     * @param String $string 需要加密的字串
     * @param String $skey 加密KEY
     * @return String
     */
    public  function encode($string = '', $skey = self::BANK_KEY_DEFAULT) {
        $strArr = str_split(base64_encode($string));
        $strCount = count($strArr);
        foreach (str_split($skey) as $key => $value)
            $key < $strCount && $strArr[$key].=$value;
        return join('', $strArr);
    }


    /**
     * 对称加密算法之解密
     * @param String $string 需要解密的字串
     * @param String $skey 解密KEY
     * @return String
     */
    public function decode($string = '', $skey = self::BANK_KEY_DEFAULT) {
        $strArr = str_split($string, 2);
        $strCount = count($strArr);
        foreach (str_split($skey) as $key => $value)
            $key <= $strCount  && isset($strArr[$key]) && $strArr[$key][1] === $value && $strArr[$key] = $strArr[$key][0];
        return base64_decode(join('', $strArr));
    }

    /**
     * 查询银行卡名称(提现银行卡)
     * @param string $field 查询字段
     * @param number $platfrom 所属平台
     * @param number $status 支持状态（1为支持，0为不支持）
     * @return unknown
     */
    public function selectBanksName($field = '*',$platform = 0,$status = 1){
        $list = array(
            array(
                'bank_code'=>1, 'bank_name'=>'中国银行',
            ),
            array(
                'bank_code'=>2, 'bank_name'=>'中国工商银行',
            ),
            array(
                'bank_code'=>3, 'bank_name'=>'中国农业银行',
            ),
            array(
                'bank_code'=>4, 'bank_name'=>'交通银行',
            ),
            array(
                'bank_code'=>5, 'bank_name'=>'广东发展银行',
            ),
            array(
                'bank_code'=>7, 'bank_name'=>'中国建设银行',
            ),
            array(
                'bank_code'=>8, 'bank_name'=>'浦发银行',
            ),
            array(
                'bank_code'=>10, 'bank_name'=>'招商银行',
            ),
        );
        return $list;
    }
}

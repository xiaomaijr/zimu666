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

    const BANK_STATUS_BIND_SUCCESS = 1;//银行卡绑定成功

    const BANK_STATUS_BINDING = -2;//银行卡绑定中

    const BANK_STATUS_FREEZED = -3;//银行卡被冻结

    const BANK_STATUS_UNBIND = -4;//银行卡已解绑
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lzh_member_banks';
    }

    public static $tableName = 'lzh_member_banks';

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
    public function getBankName($bankCode){
        $bankList = ApiConfig::$bankList;
        foreach($bankList as $row){
            if($row['bank_code'] == $bankCode){
                return $row['bank_name'];
            }
        }
    }

    /*
     * 获取某个用户绑定银行卡列表
     * @param $uid int
     * @param $replace int replace by '****' when $replace eq 1
     * return $data array
     */
    public static function getListByUid($uid, $replace = 1){
        $data = [];
        if(empty($uid)) return $data;
        $field = 'uid:' . $uid;
        $cache = self::getCache();
        if(!$cache->hExists(self::$tableName, $field)){
            $infos = self::getDataByConditions(['uid' => $uid, 'status' => [1, -2, -3]]);
            if(empty($infos)) return $data;
            $ids = ApiUtils::getCols($infos, 'id');
            $cache->hSet(self::$tableName, $field, $ids);
        }else{
            $ids = $cache->hGet(self::$tableName, $field);
            $infos = self::gets($ids);
        }
        foreach($infos as $info){
            $data[] = self::toApiArr($info, $replace);
        }
        return $data;
    }
    //api过滤参数
    public static function toApiArr($arr, $replace = 1){
        $factor = ApiUtils::getStrParam('factor', $arr, self::BANK_KEY_DEFAULT);
        $bankNum = ApiUtils::getStrParam('bank_num', $arr);
        return [
            'bank_name' => self::getBankName(ApiUtils::getIntParam('bank_name', $arr)),
            'bank_num' => $replace?self::getBankNum($bankNum, $factor):self::decode($bankNum, $factor),
            'bank_address' => ApiUtils::getStrParam('bank_address', $arr),
            'add_time' => date("Y-m-d H:i:s", ApiUtils::getIntParam('add_time', $arr)),
            'status' => ApiUtils::getIntParam('status', $arr),
        ];
    }

    //银行卡号处理
    private static function getBankNum($bankNum, $factor){
        $decodeBankNum = self::decode($bankNum, $factor);
        $len = strlen($decodeBankNum) - 8;
        return substr_replace($decodeBankNum, str_repeat('*', $len), 4, -4);
    }
    /*
     * 添加新银行卡
     */
    public static function add($uid, $newBkNum, $bankName, $privince, $city, $bankAddr, $ip){
        $new = false;
        if(!($obj = self::find()->where(['uid' => $uid])->andWhere('status > -4')->one())){
            $obj = new self;
            $obj->uid = $uid;
            $new = true;
        }
        $factor = $obj->_generRandom($newBkNum, 5);
        $obj->bank_num = self::encode($newBkNum, $factor);
        $obj->bank_name = $bankName;
        $obj->bank_province = $privince;
        $obj->bank_city = $city;
        $obj->bank_address = $bankAddr;
        $obj->add_ip = $ip;
        $obj->add_time = time();
        $obj->status = self::BANK_STATUS_BIND_SUCCESS;
        $obj->platform = 0;
        $obj->factor = $factor;
        $ret = $new?$obj->save():$obj->update();
        if(!$ret){
            throw new ApiBaseException(ApiErrorDescs::ERR_BANK_BIND_FAIL);
        }
        return $obj->id;
    }

    //生成银行卡加密秘钥
    private function _generRandom($bankNum, $length = 5){
        if($length < 5 || $length > 10){
            $length = 5;
        }
        $key = substr($bankNum, -$length);
        $keyArr = str_split($key, 1);
        foreach($keyArr as $k => $row){
            if($row > 5){
                $keyArr[$k] = chr($row + 92);
            }
        }
        rsort($keyArr);
        return implode('', $keyArr);
    }

    public function checkBankRepeat($bankNum, $status){
        $factor = $this->_generRandom($bankNum);
        $encodeBankNum = $this->encode($bankNum, $factor);
        return self::checkExistByCondition(['bank_num' => $encodeBankNum, 'status > ' . $status]);
    }

}

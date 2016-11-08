<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lzh_phonesms_log".
 *
 * @property string $id
 * @property integer $uid
 * @property string $phone
 * @property string $contents
 * @property string $sendtime
 * @property string $desc
 * @property integer $types
 * @property integer $status
 */
class PhonesmsLog extends RedisActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lzh_phonesms_log';
    }

    public static $tableName = 'lzh_phonesms_log';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'sendtime', 'types', 'status'], 'integer'],
            [['phone'], 'string', 'max' => 20],
            [['contents'], 'string', 'max' => 200],
            [['desc'], 'string', 'max' => 100],
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
            'phone' => 'Phone',
            'contents' => 'Contents',
            'sendtime' => 'Sendtime',
            'desc' => 'Desc',
            'types' => 'Types',
            'status' => 'Status',
        ];
    }

    const MarketingKey = 777335;

    const P2Pkey = 727727;

    const VerifyKey = 676767;

    /**
     * @var array
     *
     */
    private $account = array(
        'username'  => 'xiaomaijr',
        'password'  => 'XIAOmai123',
    );

    /**
     * @var string
     */
    private $baseUrl = 'www.ztsms.cn:8800';


    /**
     * @var string
     */
    private $signInfo = '【小麦金融】';


    /**
     * @param $phoneNum
     * @param $Content
     */
    public function sendSms($phone,$content,$sendTime='',$productid=self::VerifyKey){
        $content = $this->auto_charset($content,'utf-8','utf-8');
        $content = $content . $this->signInfo;
        if(!in_array($productid,array(self::VerifyKey,self::MarketingKey,self::P2Pkey))){
            return false;
        }
        $bindParam = array(
            'productid' => $productid,
            'xh'        => '',
            'mobile'    => $phone,
            'content'   => $content,
            'dstime'    => $sendTime,
        );
        $params = array_merge($bindParam,$this->account);
        $ret = defined('yii_debug')?true:ApiUtils::curlByPost($this->baseUrl.'/sendSms.do',$params);
        return $ret;
    }


    /**
     *
     *
     * @param int $productid
     *
     *
     */
    public function getRestSms($productid = self::VerifyKey){
        if(!in_array($productid,array(self::VerifyKey,self::MarketingKey,self::P2Pkey))){
            return false;
        }
        $params = array_merge(array('productid'=>$productid),$this->account);
        $ret = $this->_curlByPost($this->baseUrl.'/balance.do',$params);
        return $ret;
    }

    // 自动转换字符集 支持数组转换
    private function auto_charset($fContents, $from='gbk', $to='utf-8') {
        $from = strtoupper($from) == 'UTF8' ? 'utf-8' : $from;
        $to = strtoupper($to) == 'UTF8' ? 'utf-8' : $to;
        if (strtoupper($from) === strtoupper($to) || empty($fContents) || (is_scalar($fContents) && !is_string($fContents))) {
            //如果编码相同或者非字符串标量则不转换
            return $fContents;
        }
        if (is_string($fContents)) {
            if (function_exists('mb_convert_encoding')) {
                return mb_convert_encoding($fContents, $to, $from);
            } elseif (function_exists('iconv')) {
                return iconv($from, $to, $fContents);
            } else {
                return $fContents;
            }
        } elseif (is_array($fContents)) {
            foreach ($fContents as $key => $val) {
                $_key = auto_charset($key, $from, $to);
                $fContents[$_key] = auto_charset($val, $from, $to);
                if ($key != $_key)
                    unset($fContents[$key]);
            }
            return $fContents;
        }
        else {
            return $fContents;
        }
    }
    /*
     * 保存短信发送记录
     */
    public function saveSendMessageLog($data){
        $this->attributes = $data;
        $this->save();
    }

    public function insertEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'id:' . $this->id);
    }

    public function updateEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'id:' . $this->id);
    }

    public function deleteEvent(){
        $cache = self::getCache();
        $cache->hDel(self::$tableName, 'id:' . $this->id);
    }
}

<?php

namespace front\models;

use common\models\RedisActiveRecord;
use Yii;

/**
 * This is the model class for table "mobile_message".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $mobile
 * @property string $contents
 * @property integer $create_2016/8/15time
 * @property string $desc
 * @property string $type
 * @property integer $status
 * @property integer $create_time
 * @property integer $update_time
 */
class MobileMessage extends RedisActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mobile_message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'create_2016/8/15time', 'status', 'create_time', 'update_time'], 'integer'],
            [['mobile'], 'string', 'max' => 32],
            [['contents'], 'string', 'max' => 255],
            [['desc', 'type'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'mobile' => 'Mobile',
            'contents' => 'Contents',
            'create_2016/8/15time' => 'Create 2016/8/15time',
            'desc' => 'Desc',
            'type' => 'Type',
            'status' => 'Status',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
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
    private $signInfo = '一元宝';


    /**
     * @param $phoneNum
     * @param $Content
     */
    public function sendSms($phone,$content,$sendTime=''){
        $content = $this->auto_charset($content,'utf-8','utf-8');
        $content = $content . $this->signInfo;
        $bindParam = array(
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

    // �Զ�ת���ַ��� ֧������ת��
    private function auto_charset($fContents, $from='gbk', $to='utf-8') {
        $from = strtoupper($from) == 'UTF8' ? 'utf-8' : $from;
        $to = strtoupper($to) == 'UTF8' ? 'utf-8' : $to;
        if (strtoupper($from) === strtoupper($to) || empty($fContents) || (is_scalar($fContents) && !is_string($fContents))) {
            //���������ͬ���߷��ַ���������ת��
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
     * ������ŷ��ͼ�¼
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

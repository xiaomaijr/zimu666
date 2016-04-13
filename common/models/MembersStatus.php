<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lzh_members_status".
 *
 * @property string $uid
 * @property integer $phone_status
 * @property string $phone_credits
 * @property integer $id_status
 * @property string $id_credits
 * @property integer $face_status
 * @property string $face_credits
 * @property integer $email_status
 * @property string $email_credits
 * @property integer $account_status
 * @property string $account_credits
 * @property integer $credit_status
 * @property string $credit_credits
 * @property integer $safequestion_status
 * @property string $safequestion_credits
 * @property integer $video_status
 * @property string $video_credits
 * @property integer $vip_status
 * @property string $vip_credits
 */
class MembersStatus extends RedisActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lzh_members_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid'], 'required'],
            [['uid', 'phone_status', 'phone_credits', 'id_status', 'id_credits', 'face_status', 'face_credits', 'email_status', 'email_credits', 'account_status', 'account_credits', 'credit_status', 'credit_credits', 'safequestion_status', 'safequestion_credits', 'video_status', 'video_credits', 'vip_status', 'vip_credits'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid' => 'Uid',
            'phone_status' => 'Phone Status',
            'phone_credits' => 'Phone Credits',
            'id_status' => 'Id Status',
            'id_credits' => 'Id Credits',
            'face_status' => 'Face Status',
            'face_credits' => 'Face Credits',
            'email_status' => 'Email Status',
            'email_credits' => 'Email Credits',
            'account_status' => 'Account Status',
            'account_credits' => 'Account Credits',
            'credit_status' => 'Credit Status',
            'credit_credits' => 'Credit Credits',
            'safequestion_status' => 'Safequestion Status',
            'safequestion_credits' => 'Safequestion Credits',
            'video_status' => 'Video Status',
            'video_credits' => 'Video Credits',
            'vip_status' => 'Vip Status',
            'vip_credits' => 'Vip Credits',
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

    //添加新纪录
    public static function add($attrs){
        if(!isset($attrs['uid'])){
            return false;
        }
        if(!($obj = self::find()->where(['uid' => $attrs['uid']])->one())){
            $obj = new self;
        }
        $obj->attributes = $attrs;
        return $obj->save();
    }

    public static $credConf = array ( 'safequestion' => array ( 'fraction' => '10', 'description' => '安全问题', ), 'vip' => array ( 'fraction' => '10', 'description' => 'VIP认证', ), 'email' => array ( 'fraction' => '10', 'description' => '邮箱认证', ), 'phone' => array ( 'fraction' => '10', 'description' => '手机认证', ), 'id' => array ( 'fraction' => '10', 'description' => '实名认证', ), 'face' => array ( 'fraction' => '10', 'description' => '现场认证', ), 'video' => array ( 'fraction' => '10', 'description' => '视频认证', ), 1 => array ( 'fraction' => '10', 'description' => '居住证(暂住证)', ), 10 => array ( 'fraction' => '10', 'description' => '住房公积金', ), 2 => array ( 'fraction' => '10', 'description' => '社保', ), 3 => array ( 'fraction' => '10', 'description' => '行驶证', ), 4 => array ( 'fraction' => '10', 'description' => '驾驶证', ), 5 => array ( 'fraction' => '10', 'description' => '地税证', ), 6 => array ( 'fraction' => '10', 'description' => '国税证', ), 7 => array ( 'fraction' => '10', 'description' => '生活照', ), 8 => array ( 'fraction' => '10', 'description' => '房产证', ), 9 => array ( 'fraction' => '10', 'description' => '居住地租赁合同', ), 11 => array ( 'fraction' => '10', 'description' => '水费发票或电费发票或煤气发票（最近2个月）', ), 12 => array ( 'fraction' => '10', 'description' => '营业执照副本（需要彩色）', ), 13 => array ( 'fraction' => '10', 'description' => '机构代码证', ), 14 => array ( 'fraction' => '10', 'description' => '公司银行流水（近三个月）', ), 15 => array ( 'fraction' => '10', 'description' => '劳动合同或单位证明或工作证', ), 16 => array ( 'fraction' => '10', 'description' => '近3个月银行代发工资记录或个人转账存款记录', ), 17 => array ( 'fraction' => '10', 'description' => '学位证书或毕业证书', ), 18 => array ( 'fraction' => '10', 'description' => '户口本', ), 19 => array ( 'fraction' => '10', 'description' => '结婚证', ), 20 => array ( 'fraction' => '10', 'description' => '家人身份证正面', ), 21 => array ( 'fraction' => '10', 'description' => '家人身份证背面', ), 22 => array ( 'fraction' => '10', 'description' => '固定电话通话记录清单（最近2个月）', ), 23 => array ( 'fraction' => '10', 'description' => '手机通话记录清单（最近2个月）', ), 24 => array ( 'fraction' => '10', 'description' => '借款承诺书', ), 25 => array ( 'fraction' => '10', 'description' => '信用报告', ), 26 => array ( 'fraction' => '10', 'description' => '其他借款说明', ), 28 => array ( 'fraction' => '10', 'description' => '视频认证类资料', ), 30 => array ( 'fraction' => '10', 'description' => '现场认证类资料', ), 31 => array ( 'fraction' => '10', 'description' => '本人身份证正面', ), 32 => array ( 'fraction' => '10', 'description' => '信用卡对账单', ), 33 => array ( 'fraction' => '10', 'description' => '其他借款说明', ), 27 => array ( 'fraction' => '10', 'description' => '其他能说明您收入、资产、职务或素质的有效资料（凡不属于以上内容的都放在此）', ), );
    /*
     * 根据type更新用户认证情况
     */
    public static function setMemberStatus($userId, $type, $status, $logType, $logInfo){
        $infos = self::get($userId);
        $typeField = $type . '_status';
        $typeCredits = $type . '_credits';
        $credits = !empty(self::$credConf[$type]['fraction'])?self::$credConf[$type]['fraction']:0;
        if($infos){
            if($infos[$typeCredits] || $infos[$typeField] === 1 || $status == 2){
                $attrs = [
                    'uid' => $userId,
                    $typeField => $status,
                ];
            }else{
                $attrs = [
                    'uid' => $userId,
                    $typeField  => $status,
                    $typeCredits => $status === 1? $credits:0,
                ];
            }
        }else{
            $attrs = [
                'uid' => $userId,
                $typeField  => $status,
                $typeCredits => $status === 1? $credits:0,
            ];
        }
        $ret = self::add($attrs);
        if($status === 1 && $ret){
            MemberCreditslog::memberCreditsLog($userId, $logType, $credits, $logInfo."认证通过,奖励积分{$credits}");
        }
        return $ret;
    }

    public static function get($uid, $tableName = ''){
        $cache = self::getCache();
        if($cache->hExists(self::$tableName, 'uid:' . $uid)){
            $info = $cache->hGet(self::$tableName, 'uid:' . $uid);
        }else{
            $info = self::getDataByID($uid, 'uid');
            if(!$info) return $info;
            $cache->hSet(self::$tableName, 'uid:' . $uid, $info);
        }
        return $info;
    }
}

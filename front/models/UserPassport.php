<?php

namespace front\models;

use common\models\ApiBaseException;
use common\models\ApiErrorDescs;
use common\models\ApiUtils;
use common\models\RedisUtil;
use Yii;

/**
 * This is the model class for table "user_passport".
 *
 * @property integer $id
 * @property string $account
 * @property string $passwd
 * @property integer $create_time
 * @property integer $update_time
 */
class UserPassport extends \yii\db\ActiveRecord
{
    /**
     *秘钥
     */
    const PASSWORD_SALT = 'yiyuanbao';
    /**
     * 短信验证码redis key prefix
     */
    const USER_PASSPORT_LOGIN = 'user_passport_login';
    const USER_PASSPORT_LOGIN_EXPIRE = 120;
    /**
     * 短信验证码发送次数限制
     */
    const USER_PASSPORT_LIMIT = 'user_passport_limit';
    const USER_PASSPORT_LIMIT_EXPIRE = 60;
    /**
     *手机正则
     */
    const MOBILE_REGEXP = '/^1[3578]\d{9}$/';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_passport';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_time', 'update_time'], 'integer'],
            [['account', 'passwd'], 'string', 'max' => 64],
        ];
    }

    /**   n h
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'account' => 'Account',
            'passwd' => 'Passwd',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    /**
     * 用户注册
     */
    public static function register($mobile, $password)
    {
        if (!preg_match(self::MOBILE_REGEXP, $mobile)) {
            throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '请输入正确手机号');
        }
        if (self::find()->where(['account' => $mobile])->exists()) {
            throw new ApiBaseException(ApiErrorDescs::ERR_USER_REGISTER_PHONE_EXIST);
        }
        $obj = new self;
        $obj->account = $mobile;
        $obj->passwd = md5(self::PASSWORD_SALT . $password);
        $obj->create_time = time();
        if (!$obj->save()) {
            throw new ApiBaseException(ApiErrorDescs::ERR_USER_REGISTER_FAIL);
        }
        $objUserInfo = new UserInfo();
        $objUserInfo->register(['account' => $mobile, 'mobile' => $mobile, 'user_id' => $obj->id]);
        return true;
    }
    /**
     * 用户登录
     */
    public static function login($mobile)
    {
        if (!preg_match(self::MOBILE_REGEXP, $mobile)) {
            throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '请输入正确手机号');
        }
        if (!($user = self::findOne(['account' => $mobile]))) {
            $user = self::buildUser($mobile);
        }
        UserHistory::updateUser($user['id']);
        $_SESSION['MOBILE'] = $mobile;
        $_SESSION['USER_ID'] = $user['id'];
        return true;
    }
    /**
     * 生成新用户
     */
    protected static function buildUser($mobile)
    {
        $obj = new self;
        $obj->account = $mobile;
        $obj->passwd = md5(self::PASSWORD_SALT . rand(100000, 999999));
        $obj->create_time = time();
        if (!$obj->save()) {
            throw new ApiBaseException(ApiErrorDescs::ERR_USER_REGISTER_FAIL);
        }
        $objUserInfo = new UserInfo();
        $objUserInfo->register(['account' => $mobile, 'mobile' => $mobile, 'user_id' => $obj->id]);
        return $obj;
    }
}

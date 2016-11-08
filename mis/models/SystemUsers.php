<?php

namespace mis\models;

use Yii;
use yii\db\Connection;

/**
 * This is the model class for table "system_users".
 *
 * @property integer $id
 * @property integer $mis_user_id
 * @property integer $role_id
 * @property string $name
 * @property string $password
 * @property string $mobile
 * @property string $mail
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $is_del
 * @property integer $login_time
 */
class SystemUsers extends \yii\db\ActiveRecord
{


    private $password_hash = 'zimu';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'system_users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_id', 'create_time', 'update_time', 'is_del', 'login_time'], 'integer'],
            [['name', 'password'], 'string', 'max' => 32],
            [['mobile'], 'string', 'max' => 11],
            [['mail'], 'string', 'max' => 64],
            [['mobile'], 'unique']
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
            'name' => 'Name',
            'password' => 'Password',
            'mobile' => 'Mobile',
            'mail' => 'Mail',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'is_del' => 'Is Del',
            'login_time' => 'Login Time',
        ];
    }


    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByMobile($mobile)
    {
        return static::findOne(['mobile' => $mobile, 'is_del' => 0]);
    }


    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
//        return true;
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }


    /**
     * 查询系统用户列表
     * */
    public function getSystemUserList($arrFilter = [], $intPage = 1, $intPageSize = 10)
    {
        $strWhere = ' is_del=0 ';
        if(!empty($arrFilter['id'])){
            $strWhere.= ' AND id = ' . $arrFilter['id'];
        }
        if (!empty($arrFilter['name'])){
            $strWhere.= ' AND name LIKE "%' . $arrFilter['name'] . '%"';
        }
        if (!empty($arrFilter['bank_id'])){
            $strWhere.= ' AND bank_id in ('.$arrFilter['bank_id'].') ';
        }
        //角色
        if (!empty($arrFilter['role_id']))
        {
            $strWhere.= ' AND role_id = "' . intval($arrFilter['role_id']) . '"';

        }
        //手机号
        if (!empty($arrFilter['mobile'])){
            $strWhere.= ' AND mobile = "' . $arrFilter['mobile'] . '"';
        }
        $orderByColumn ='id';
        if(!empty($arrFilter['orderbycolumn'])){
            $orderByColumn=$arrFilter['orderbycolumn'];
        }
        $sortWay = 'ASC';
        if(!empty($arrFilter['sortway'])){
            $sortWay=$arrFilter['sortway'];
        }
        $strSql = sprintf('SELECT SQL_CALC_FOUND_ROWS id,name,mobile,mail,create_time' .
            ' FROM `system_users` ' .
            ' WHERE %s ' .
            ' ORDER BY %s %s ' .
            ' LIMIT %d, %d', $strWhere,$orderByColumn,$sortWay,($intPage - 1) * $intPageSize, $intPageSize);

        $db = Yii::$app->db;
        $arrResult = $db->createCommand($strSql)->queryAll();
        $arrCount = $db->createCommand('SELECT FOUND_ROWS() AS total')->execute();

        return array(
            'count'     => $arrCount[0]['total'],
            'result'    => (array)$arrResult
        );
    }

    public static function checkUserPassport($mobile,$password){
        if(!$mobile || !$password){
            return false;
        }
        $ret = self::find()
            ->where(['mobile' => trim($mobile)])
            ->asArray()
            ->one();
        return !$ret?false:(trim($ret['password']) === trim($password)?true:false);
    }

    //生成密码
    public static function encryPassword($password){
        $encryStr = 'zimu';
        if(!$password){
            return false;
        }
        $encryPwd = substr(md5($password),0,16);
        $encryPwd .= substr(md5($encryStr),16,16);
        return $encryPwd;

    }


    /**
     * 获取内部管理员ID和姓名的映射
     * @return array
     */
    public static function getUserNameMap()
    {
        $map = self::find()->where(['is_del' => 0])->asArray()->all();
        $newMap = [];
        foreach($map as $v) {
            $newMap[$v['id']] = $v['name'];
        }
        return $newMap;
    }

}

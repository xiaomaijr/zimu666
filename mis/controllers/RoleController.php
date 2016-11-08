<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao
 * Date: 15/9/10
 * Time: 下午3:59
 */

namespace mis\controllers;



use common\models\ApiUtils;
use mis\models\Constant;
use mis\models\Rolepower;
use mis\models\Roles;

class RoleController extends MisBaseController{

    private $source = '/role/list';

    /**
     * 角色列表
     * @return string
     */
    public function actionList(){
        $data = [];
        $request = $_REQUEST;
        $roles = Roles::find()
            ->where(['is_del' => 0])
            ->asArray()
            ->all();
        $roleId = !empty($request['role_id'])?intval($request['role_id']):($roles?$roles[0]['id']:0);
        $powers = Rolepower::getPowersByRoleId($roleId);
        $urls = \RiskConfig::$functionList;
        foreach($urls as $k=>$v){
            $tmp = explode('/', $v['action']);
            $lists[$tmp[0]][$k] = $v;
        }
        ksort($lists);
        $data = [
            'roles' => $roles,
            'powers' => $powers,
            'urls' => $lists,
            'curId' => $roleId
        ];
        $data['records'] = $this->getRecordList($roleId);
        return $this->render('list.tpl', $data);
    }


    /**
     * 添加角色
     */
    public function actionAdd(){
        $request = $_REQUEST;
        $roleId = !empty($request['role_id'])?intval($request['role_id']):0;
        if(!$roleId){
            ApiUtils::pageRedirect('参数错误','/role/list');
        }
        $rightIds = $powers = !empty($request['power'])?$request['power']:[];
        $powerStr = implode(',' , $powers);
        if($objPower = Rolepower::findOne(['role_id' => $roleId, 'is_del' => 0])){
            $objPower->update_time = date("Y-m-d");
        }else{
            $objPower = new Rolepower();
            $objPower->role_id = $roleId;
            $objPower->create_time = date("Y-m-d");
        }
        $objPower->powers = $powerStr;
        $objPower->operator_id = $_SESSION['money_user_id'];
        $ret = $objPower->save();
        if(!$ret){
            ApiUtils::pageRedirect('修改失败','/role/list');
        }
        $params = [
            'business_id' => $roleId,
            'business_code' => Constant::ADMIN_POWER_MODIFY,
            'source' => $this->source,
        ];
        $this->setRecord($params);
        ApiUtils::pageRedirect('操作成功',"/role/list?role_id=" . $roleId);

    }

    /**
     * 发送邮件
     */
    public function actionEmail(){
        $request = $_REQUEST;
        $to = !empty($request['to'])?$request['to']:['zhangxiao@jiadao.cn','yumiao@jiadao.cn','shupan@jiadao.cn'];
        $mail= \Yii::$app->mailer->compose();
        $mail->setTo($to);
        $mail->setSubject("邮件测试");
//$mail->setTextBody('zheshisha ');   //发布纯文字文本
        $mail->setHtmlBody("<br>问我我我我我");    //发布可以带html标签的文本
        if($mail->send())
            echo "success";
        else
            echo "failse";
        die();
    }
}
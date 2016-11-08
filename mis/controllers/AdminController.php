<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 15-6-22
 * Time: 下午21:16
 * To change this template use File | Settings | File Templates.
 */

namespace mis\controllers;

use common\models\ApiBaseException;
use common\models\ApiErrorDescs;
use common\models\ApiUtils;
use common\models\MobileMessage;
use common\models\Paging;
use mis\models\Constant;
use mis\models\Roles;
use mis\models\SystemUsers;
use mis\models\Util;

class AdminController extends MisBaseController{

    private $source = '/admin/view';

    /**
     * 获取账户列表
     * */
    public function actionList()
    {
        //过滤条件
        $request = $_REQUEST;
        $query = isset($request['query'])?$request['query']:[];
        foreach($query as $k=>$v){
            if(empty($v))
                unset($query[$k]);
        }
        $filter['orderby'] =  $orderBy = isset($request['order'])?trim($request['order']):'id';
        $filter['sortway'] = $sortWay = isset($request['sortway'])?trim($request['sortway']):'DESC';
        $filter = array_merge($filter, $query);
        $intPage = isset($request['p'])&&$request['p']?intval($request['p']):1;
        $intPageSize = PAGESIZE;


        if(!empty($query)){
            $count = SystemUsers::find()
                ->where($query)
                ->count();
            $list = SystemUsers::find()
                ->where($query)
                ->orderBy($orderBy . ' ' . $sortWay)
                ->limit($intPageSize)
                ->offset(($intPage - 1)*$intPageSize)
                ->asArray()
                ->all();
        }else{
            $count = SystemUsers::find()
                ->count();
            $list = SystemUsers::find()
                ->orderBy($orderBy . ' ' . $sortWay)
                ->limit($intPageSize)
                ->offset(($intPage - 1)*$intPageSize)
                ->asArray()
                ->all();
        }
        $total = $count?$count:0;

        $queryStr = ApiUtils::getMapping($request,'p');
        $url = \Yii::$app->request->baseUrl . '?' . $queryStr . "&";
        $objPage = new Paging($total, $intPageSize, $url , $intPage);
        $pageLink = $objPage->output();

        $data['list'] = $list;
        $data['arrPager'] = array(
            'count'     => $total,
            'pagesize'  => $intPageSize,
            'page'      => $intPage,
            'pagelink'  => $pageLink,
        );
        $data['filter'] = $filter;
//        echo json_encode($data);exit;
        return $this->render('alist.tpl',$data);
    }


    /**
     * 查看账户信息
     */
    public function actionView(){
        $request = $_REQUEST;
        $id = !empty($request['id'])?intval($request['id']):0;
        try{
            if(!$id){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN, '参数错误');
            }
            $info = SystemUsers::find()
                ->where(['id' => $id])
                ->asArray()
                ->one();
            if(!$info){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN, '数据不存在');
            }
            $data['admin'] = $info;
            $data['role_list'] = ApiUtils::getMap(Roles::find()->asArray()->all(), 'id');
            $data['records'] = $this->getRecordList($id);
            return $this->render('admin_add.tpl',$data);
        }catch(ApiBaseException $e){
            Util::pageNotFund($e->getMessage());
        }

    }


    /**
     * 添加一个账户
     */
    public function actionAdd()
    {
        if(\Yii::$app->request->getIsPost()){
            $systemUser = new SystemUsers();
            $admin = (array)\Yii::$app->request->post('admin');
            $prePassword = !empty($admin['password'])?trim($admin['password']):$this->getPassword();
            $password = SystemUsers::encryPassword($prePassword);
            $systemUser->name = $admin['name'];
            $systemUser->mobile = $admin['mobile'];
            $systemUser->mail = $admin['mail'];
            $systemUser->password = $password;
            $systemUser->role_id = 0;
            $systemUser->create_time = time();
            $systemUser->update_time = time();

            $userId = $systemUser->save();
            if($userId){
                $this->sendMail($admin['mail'], $admin['mobile'],$prePassword);
            }
            $this->redirect('/admin/list');
        }
        else{
            $id = \Yii::$app->request->getQueryParam('id');
            $systemUser = SystemUsers::findOne(['id' => $id]);
            if ($systemUser) {
                $arrTplData['admin'] = $systemUser->getAttributes();
            }
            $arrTplData['action'] = 2;
            return $this->render('admin_add.tpl',$arrTplData);
        }
    }


    /**
     * 编辑账户
     */
    public function actionEdit()
    {
        $request = $_REQUEST;
        $admin = isset($request['admin'])?$request['admin']:[];
        if ($admin) {
            $systemUser = SystemUsers::findOne(['id' => intval($admin['id'])]);
            $resetPwd = !empty($admin['is_reset_pwd'])?1:0;
            $adminId = ApiUtils::getIntParam('id', $admin);
            unset($admin['id']);
            unset($admin['is_reset_pwd']);
            $systemUser->attributes = $admin;
            $systemUser->update_time = time();
//            是否重置密码
            if ($resetPwd){
                $password = $this->getPassword();
                $this->sendMail($admin['mail'], $admin['mobile'],$password);
                $systemUser->password = SystemUsers::encryPassword($password);
            }
            $ret = $systemUser->save();
            if($ret){
                $params = [
                    'business_id' => $adminId,
                    'business_code' => Constant::ADMIN_USER_UPDATE,
                    'source' => $this->source,
                ];
                $this->setRecord($params);
            }
            $this->redirect('/admin/list');

        } elseif (\Yii::$app->request->getQueryParam('id')){

            $id = intval(\Yii::$app->request->getQueryParam('id'));
            $arrTplData['admin'] = SystemUsers::findOne(['id' => $id])->getAttributes();
            $arrTplData['action'] = 1;
            $arrTplData['role_list'] = ApiUtils::getMap(Roles::find()->asArray()->all(), 'id');
            return $this->render('admin_add.tpl',$arrTplData);
        }
    }


    /**
     * @return 生成原始密码
     */
    public function getPassword()
    {
        $rawPassword =  rand(100000,999999);
        return $rawPassword;
    }


    /**
     * 逻辑删除用户名
     */
    public function actionDelete(){
        $request = $_REQUEST;
        $id = isset($request['id'])?intval($request['id']):0;
        $isDel = isset($request['is_del'])?0:1;
        try{
            if(!$id){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR,'参数错误');
            }
            $objSysUser = SystemUsers::findOne(['id' => $id]);
            $objSysUser->is_del = $isDel;
            $objSysUser->update_time = time();
            $ret = $objSysUser->save();
            if(!$ret){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR,'更新失败');
            }
            $result = [
                'code' => ApiErrorDescs::SUCCESS,
                'message' => '更新成功'
            ];

        }catch(ApiBaseException $e){
            $result = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
        }
        echo json_encode($result);
    }

    /**
     * 修改密码
     */
    public function actionEditpwd()
    {
        if (\Yii::$app->request->getIsPostRequest())
        {
            $arrUserInfo = array();
            $userId = \Yii::$app->session['money_user_id'];
            $userModel = new MoneySystemUser(DBModel::MODE_RW);
            $arrUserInfo = $userModel->getUserInfoById($userId);
            if (\Yii::$app->request->getPost('oldpass') && \Yii::$app->request->getPost('newpass') && \Yii::$app->request->getPost('re-newpass'))
            {
                $arrUserInfo["old_password"]=\Yii::$app->request->getPost('oldpass');
                $arrUserInfo["new_password"]=\Yii::$app->request->getPost('newpass');
                $arrUserInfo["renew_password"]=\Yii::$app->request->getPost('re-newpass');
                if ($arrUserInfo["new_password"] !== $arrUserInfo["renew_password"])
                {
                    \Yii::$app->user->setFlash('saveinfo', '保存失败，两次输入的密码不一致');
                }
                elseif (!GeneralUtil :: checkPassword($arrUserInfo["new_password"]))
                {
                    \Yii::$app->user->setFlash('saveinfo', '您设置的密码强度不够，请重新设置，密码至少6位，必须包含大小写字母、数字和特殊符号');
                }
                else
                {
                    $loginUserId = \Yii::$app->session['money_user_id'];
                    $loginUserName = \Yii::$app->session['money_user_name'];
                    $ret = $userModel->checkPasswd($arrUserInfo);
                    if(!$ret)
                    {
                        Yii::log("old passwd is  worning!!","warning");
                        \Yii::$app->user->setFlash('saveinfo', '旧密码错误，请重新输入!!');
                    }else
                    {
                        $userModel->updatePasswd($arrUserInfo,$loginUserId,$loginUserName);
                        \Yii::$app->user->setFlash('saveinfo', '保存成功！');
                    }
                }
            }
            else{
                \Yii::$app->user->setFlash('saveinfo', '保存失败，所有项都为必填项');
            }
            \Yii::$app->request->redirect(\Yii::$app->createURL('admin/editpwd'));
        }
        else
        {
            $this->render('admin_editpwd.tpl');
        }
    }

    /**发送邮件
     * @param $address
     * @param $passwd
     */
    public function  sendMail($address,$username,$passwd)
    {
        $subject = '[好车驾到]Mis后台账号添加';
        $body = "您好，已为您添加[好车驾到]Mis后台账号,用户名为{$username},密码为{$passwd},点击 <a href='http://erp.jiadao.cn'>Mis后台</a>跳转";
//        mail($address, $subject,$body);
        ApiUtils::email($address,$subject,$body, 1);
    }

    public function actionModifyPwd(){
        $reqeust = $_REQUEST;
        $userId = isset($reqeust['id'])?intval($reqeust['id']):0;
        $oldPwd = isset($reqeust['old_pwd'])?trim($reqeust['old_pwd']):'';
        $newPwd = isset($reqeust['new_pwd'])?trim($reqeust['new_pwd']):'';
        $reNewPwd = isset($reqeust['renew_pwd'])?trim($reqeust['renew_pwd']):'';
        $edit = isset($reqeust['edit'])?trim($reqeust['edit']):'';
        try{
            if(!$userId){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN,'参数有误');
            }
            $user = SystemUsers::findOne(['id' => $userId]);
            if(!$user){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN,'用户不存在');
            }
            if(!$edit){
                $data['info'] = $user;
                return $this->render('admin_editpwd.tpl',$data);
            }
            if(!$oldPwd){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN,'旧密码不能为空');
            }
            $encryOldPwd = SystemUsers::encryPassword($oldPwd);
            $curPwd = trim($user->password);
            if($curPwd != $encryOldPwd){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN,'旧密码输入错误');
            }
            if(!$newPwd){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN,'新密码不能为空');
            }
            if($newPwd !== $reNewPwd){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN,'新密码两次输入不一致');
            }
            $encryNewPwd = SystemUsers::encryPassword($newPwd);
            $user->password = $encryNewPwd;
            $ret = $user->save();
            if(!$ret){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN,'保存密码失败');
            }
            $result = [
                'code' => ApiErrorDescs::SUCCESS,
                'message' => '修改成功'
            ];
            echo json_encode($result);
            exit;
        }catch(ApiBaseException $e){
            $result = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
            echo json_encode($result);
            exit;
        }
    }

    /*
     * 忘记密码
     */
    public function actionForgetPwd(){
        $request = $_REQUEST;
        $mobile = !empty($request['mobile'])?trim($request['mobile']):'';
        try{
            if(!$mobile){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN, '请输入手机号码');
            }
            if(!($systemUser = SystemUsers::find()->where(['mobile' => $mobile])->one())){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN, '手机号码输入有误');
            }
            $password = $this->getPassword();
            $systemUser->password = SystemUsers::encryPassword($password);
            $systemUser->update_time = time();
            $ret = $systemUser->save();
            if(!$ret){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN, '重置密码失败');
            }
            $message = "密码重置为" . $password . ",登录后可进行修改";
            $ret = MobileMessage::sendMessage($message, $mobile);
            if(!$ret){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN, '手机号码有误');
            }
            $result = [
                'code' => 0,
                'message' => '请查看短信'
            ];
            echo json_encode($result);
            exit;
        }catch(ApiBaseException $e){
            $result = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
            echo json_encode($result);
            exit;
        }
    }
}

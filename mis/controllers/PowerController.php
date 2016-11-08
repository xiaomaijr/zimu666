<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao
 * Date: 15/9/10
 * Time: 下午3:59
 */

namespace mis\controllers;



use common\models\ApiBaseException;
use common\models\ApiErrorDescs;
use common\models\ApiUtils;
use mis\models\Rolepower;
use mis\models\Roles;
use yii\rbac\Role;

class PowerController extends MisBaseController{

    public function actionList(){
        $data = [];
        $request = $_REQUEST;
        $roles = Roles::find()
            ->where(['is_del' => 0])
            ->asArray()
            ->all();
        $roleId = !empty($request['role_id'])?intval($request['role_id']):($roles?$roles[0]['id']:0);
        $powers = Rolepower::getPowersByRoleId($roleId);
        $data = [
            'roles' => $roles,
            'powers' => $powers,
            'urls' => \RiskConfig::$functionList
        ];

        return $this->render('list.tpl', $data);
    }


    public function actionAdd(){
        $request = $_REQUEST;
        $query = !empty($request['query'])?$request['query']:[];
        if(!$query){
            $data['action'] = 1;
            return $this->render("info.tpl", $data);
        }
        if(empty($query['name'])){
            ApiUtils::pageRedirect('角色名不能为空');
        }
        $name = trim($query['name']);
        $objRole = new Roles();
        $objRole->name = $name;
        $objRole->operator_id = $_SESSION['money_user_id'];
        $objRole->create_time = date("Y-m-d H:i:s");
        $ret = $objRole->save();
        if(!$ret){
            ApiUtils::pageRedirect('添加失败');
        }
        ApiUtils::pageRedirect('操作成功',"/power/list");
    }

    public function actionDelete(){
        $request = $_REQUEST;
        $id = !empty($request['id'])?intval($request['id']):0;
        $isDel = !empty($request['is_del'])?intval($request['is_del']):1;
        try{
            if(!($objRole = Roles::findOne(['id' => $id, 'is_del' => 0]))){
                throw new ApiBaseException(ApiErrorDescs::ERR_404_ERROR, '数据不存在');
            }
            $objRole->is_del = $isDel;
            $objRole->update_time = date("Y-m-d H:i:s");
            $ret = $objRole->save();
            if(!$ret){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN, '修改失败');
            }
            $result = [
                'code' => ApiErrorDescs::SUCCESS,
                'message' => '操作成功'
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

    public function actionView(){
        $request = $_REQUEST;
        $id = !empty($request['id'])?intval($request['id']):0;
        try{
            if(!($objRole = Roles::findOne(['id' => $id, 'is_del' => 0]))){
                throw new ApiBaseException(ApiErrorDescs::ERR_404_ERROR, '数据不存在');
            }
            $data = [
                'info' => $objRole
            ];
            return $this->render('info.tpl', $data);
        }catch(ApiBaseException $e){
            ApiUtils::pageRedirect();
        }
    }

    public function actionEdit(){
        $request = $_REQUEST;
        $id = !empty($request['id'])?intval($request['id']):0;
        $query = !empty($request['query'])?$request['query']:0;
        if(!($objRole = Roles::findOne(['id' => $id, 'is_del' => 0]))){
            ApiUtils::pageRedirect('数据不存在');
        }
        if(!$query){
            $data['info'] = $objRole;
            $data['action'] = 2;
            return $this->render("info.tpl", $data);
        }
        if(empty($query['name'])){
            ApiUtils::pageRedirect('角色名不能为空');
        }
        $name = trim($query['name']);
        $objRole->name = $name;
        $objRole->operator_id = $_SESSION['money_user_id'];
        $objRole->update_time = date("Y-m-d H:i:s");
        $ret = $objRole->save();
        if(!$ret){
            ApiUtils::pageRedirect('修改失败');
        }
        ApiUtils::pageRedirect('操作成功',"/power/list");

    }
}
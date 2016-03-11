<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao-pc
 * Date: 2016/3/11
 * Time: 14:51
 */

namespace api\controllers;


class UserBaseController extends ApiBaseController
{
    public function beforeAction($action)
    {
        if(parent::beforeAction($action)){
            $this->checkAccessToken($_REQUEST['access_token'], $_REQUEST['user_id']);
            return true;
        }
        return false;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: 58
 * Date: 2016/10/3
 * Time: 22:58
 */
namespace front\controllers;

class UserBaseController extends BaseController
{
    public $userId;

    public function beforeAction($action){

        if (parent::beforeAction($action)) {
            $this->userId = \Yii::$app->session['USER_ID'];
            return true;
        }
        return false;
    }
}
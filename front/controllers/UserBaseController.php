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
            if (!empty(\Yii::$app->session['USER_ID'])) {
                $this->userId = \Yii::$app->session['USER_ID'];
            } else {
                if (\Yii::$app->request->getIsAjax()) {

                } else {
                    $this->redirect('/user-passport/login-view?back_url=' . $_SERVER['REQUEST_URI']);
                }
            }
            return true;
        }
        return false;
    }
}
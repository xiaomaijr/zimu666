<?php
/**
 * Created by PhpStorm.
 * User: panbook
 * Date: 6/21/15
 * Time: 3:09 PM
 */

namespace mis\controllers;

use common\models\ApiUtils;
use yii\web\Controller;

class MisBaseController extends Controller{

    public $layout = false;


    public function beforeAction($action) {
        //禁止浏览器缓存
        @header("Cache-Control: no-cache, must-revalidate");
        @header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
        date_default_timezone_set("PRC");
//        set_error_handler(array(&$this,"myErrorHandler"));

//        RongSessionHandler::apply(); //使用基于redis缓存的session存储。
        session_start();

        $controllerName = strtolower($this->id);
        $actionName = strtolower($action->id);
        $strCA = $controllerName . '/' . $actionName;
        \Yii::$app->session['current_url'] = $strCA;
        if (in_array($actionName, array('login','logout', 'forget-pwd', 'error', 'captcha', 'uploadimage','forgetpassword','getfindpasswordcaptcha','get-brand','img-upload','get-uniq-name')))
        {
            return true;
        }
    elseif (!\Yii::$app->session['money_user_id']) //未登录
        {
            echo "<script>window.parent.location='/login?backdir={$_SERVER['REQUEST_URI']}';</script>";
            \Yii::$app->end();
        }
        else
        {
            return true;
        }

    }

    protected function getUrl($request){
        return \Yii::$app->request->baseUrl . '?' . ApiUtils::getMapping($request,'p') . '&';
    }
}
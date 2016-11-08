<?php
/**
 * Created by PhpStorm.
 * User: panbook
 * Date: 6/21/15
 * Time: 9:05 PM
 */

namespace mis\controllers;

use common\models\ApiBaseException;
use common\models\ApiErrorDescs;
use common\models\ApiUtils;
use mis\models\Rolepower;
use mis\models\SystemUsers;
use mis\models\LoginForm;
use mis\models\Util;
use yii\base\Exception;
use yii\redis\Cache;
use yii\web\User;

class SiteController extends MisBaseController{

    public function actions()
    {

            $testCode='2910';
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha'=>array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,  //背景颜色
                'minLength' => 4,  //最短为4位
                'maxLength' => 4,   //是长为4位
                'transparent' =>true,  //显示为透明，当关闭该选项，才显示背景颜色
                'height' => 40,
                'width'  => 80,
                'testLimit' => 1,
//		    'testCode' => $testCode,
            ),
        );
    }

    public function actionIndex()
    {
        return $this->render('welcome.tpl', array());
    }


    /**
     * @brief   登录后首页
     */
    public function actionWelcome(){
        $this -> renderFile('welcome.tpl', array());
    }


    /**
     * @brief   用户登录逻辑
     */
    public function actionLogin()
    {
        if (\Yii::$app->request->getIsPost())
        {
            $form = (array)\Yii::$app->request->post('LoginForm');

            $cache = new Cache();


            $strUserIP = $_SERVER['REMOTE_ADDR'];
            $strMemcacheKey = 'money_login_' . $strUserIP . '_' . $form['mobile'];
            $strMemcacheVal = intval($cache->get($strMemcacheKey));
            $backDir = ApiUtils::getStrParam('backdir', $_REQUEST);
            if ($strMemcacheVal >= 10)
            {

                $data['error']['message'] = '您的输入次数已超过上限';
                return $this->render('login.tpl',$data);
            }


            $user = SystemUsers::findByMobile($form['mobile']);

            if ($user)
            {
                $passwd = SystemUsers::encryPassword($form['password']);
                if (SystemUsers::checkUserPassport(trim($form['mobile']),$passwd))
                {
                    \Yii::$app->session['money_user_id'] = $user->id;
                    \Yii::$app->session['money_user_name'] = $user->name;
                    \Yii::$app->session['role_id'] = $user->role_id;
                    $backDir = !empty($backDir)?$backDir:'/site/index';
                    $this->redirect($backDir);
                    \Yii::$app->end();
                }
                else
                {
//                    Yii::app()->user->setFlash('error','账号或密码错误，登录失败');
                    $data['error']['message'] = '账号或密码错误，登录失败';
                    $cache->set($strMemcacheKey, $strMemcacheVal+1, 300);
                    return $this->render('login.tpl',$data);
                }
            }
            else
            {
//                Yii::app()->user->setFlash('error','账号或密码错误，登录失败');
                $data['error']['message'] = '账号或密码错误，登录失败';
                $cache->set($strMemcacheKey, $strMemcacheVal+1, 300);
                return $this->render('login.tpl',$data);
            }
        }
        else
        {
            return $this->render('login.tpl');
        }
    }

    /**
     * 退出
     */
    public function actionLogout()
    {
        \Yii::$app->session['money_user_id'] = '';
        \Yii::$app->session['money_user_name'] = '';
        \Yii::$app->session['money_user_bank_id'] = '';
        \Yii::$app->session['money_user_roles']='';
        \Yii::$app->session['money_user_rights'] = '';
        \Yii::$app->session['money_system_all_rights']='';
        \Yii::$app->session['arr_page_right'] = '';
        \Yii::$app->session['img_access'] = '';
//        echo "<script>window.parent.location='/login';</script>";
        return $this->redirect('/site/login');
        Yii :: app()->end();
    }

//    public function actionError(){
//        Util::pageNotFund('site/error:数据有误');
//    }
}
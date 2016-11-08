<?php
/**
 * Created by PhpStorm.
 * User: 58
 * Date: 2016/10/17
 * Time: 14:05
 */
namespace front\controllers;
class EpayController extends UserBaseController{
    
    
    public function actionRecharge()
    {
        return $this->render('recharge.tpl');
    }
} 
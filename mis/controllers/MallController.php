<?php
/**
 * Created by PhpStorm.
 * User: 58
 * Date: 2016/10/22
 * Time: 13:10
 */
namespace mis\controllers;
class MallController extends MisBaseController
{
    public function actionIndex()
    {
        $this->redirect('/site/login');
    }
}
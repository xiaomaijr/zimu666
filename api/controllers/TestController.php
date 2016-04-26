<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao-pc
 * Date: 2016/4/19
 * Time: 16:47
 */

namespace api\controllers;

use yii\web\Controller;

class TestController extends Controller
{

    public function actionContain(){
        \Yii::$container->set('common\models\TestModel', ['id' => 1, 'name' => 'zhangxiao']);
        $obj = \Yii::createObject('common\models\TestModel');
        echo $obj->id . "\t" . $obj->name;exit;
    }
}
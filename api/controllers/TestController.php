<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao-pc
 * Date: 2016/4/19
 * Time: 16:47
 */

namespace api\controllers;

use common\models\Escrow;
use common\models\EscrowAccount;
use yii\web\Controller;

class TestController extends Controller
{

    public function actionContain(){
        \Yii::$container->set('common\models\TestModel', ['id' => 1, 'name' => 'zhangxiao']);
        $obj = \Yii::createObject('common\models\TestModel');
        echo $obj->id . "\t" . $obj->name;exit;
    }

    public function actionTest(){
        $userId = 236748;
        $userEscInfo = EscrowAccount::getUserThirdAccout($userId);
        $data['PlatformId'] = $userEscInfo['qdd_marked'];
        $objEsc = new Escrow();
        $infos['params'] = $objEsc->searchAccount($data);
        $infos['url'] = $objEsc->urlArr['balance'];
        echo $infos['url'] . '?' . http_build_query($infos['params']);exit;
    }
}
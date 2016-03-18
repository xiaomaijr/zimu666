<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao-pc
 * Date: 2016/3/18
 * Time: 9:59
 */

namespace api\controllers;


use common\models\ApiBaseException;
use common\models\ApiConfig;
use common\models\ApiErrorDescs;

class CommonController extends ApiBaseController
{
    public function actionBankList(){
        try{
            $result = [
                'code' => ApiErrorDescs::SUCCESS,
                'message' => 'success',
                'result' => ApiConfig::$bankList,
            ];
        }catch(ApiBaseException $e){
            $result = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
        }
        header('Content-type: application/json');
        echo json_encode($result);

        $this->logApi(__CLASS__, __FUNCTION__, $result);
        \Yii::$app->end();
    }
}
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
use common\models\ApiUtils;
use common\models\Area;
use common\models\TimeUtils;

class CommonController extends ApiBaseController
{
    /*
     * 获取银行列表
     */
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
    /*
     * 获取城市
     */
    public function actionGetArea(){
        try{
            $request = $_REQUEST;
            $reid = ApiUtils::getIntParam('reid', $request);
            $timer = new TimeUtils();

            $timer->start('get_area');
            $areas = Area::getAreaByReid($reid);
            $timer->stop('get_area');
            $result = [
                'code' => ApiErrorDescs::SUCCESS,
                'message' => 'success',
                'result' => $areas
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
    /*
     *关于我们
     */
    public function actionAboutUs(){
        try{
            $result = [
                'code' => ApiErrorDescs::SUCCESS,
                'message' => 'success',
                'result' => ApiConfig::$aboutUs,
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
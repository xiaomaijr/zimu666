<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao-pc
 * Date: 2016/2/25
 * Time: 14:52
 */

namespace front\controllers;


use common\models\ApiBaseException;
use common\models\ApiConfig;
use common\models\ApiErrorDescs;
use common\models\ApiUtils;
use common\models\MemberAccessToken;
use front\models\Category;

class BaseController extends \yii\web\Controller
{

    public $timeStart = 0;

    public $enableCsrfValidation=false;

    public $request = [];

    public $layout = false;
    /*
     * 每个action之前对参数及签名校验
     *
     */
    public function beforeAction($action)
    {
        try{
            ini_set('display_errors', 1);
            ini_set('error_reporting', E_ALL ^ E_NOTICE);
            session_start();
            $this->timeStart = microtime(true) * 1000;
            $strControllerId = $action->controller->id;
            $strActionId = $action->id;
            \Yii::$app->logging->trace($strControllerId . '/' . $strActionId . json_encode($_REQUEST));
            $checkNeed = isset(ApiConfig::$arrNoNeedCheckApiSign[$strControllerId][$strActionId]);
            if(parent::beforeAction($action)){
                //做参数校验
                $this->request = $arrParams = $_REQUEST;
                $arrParamsNeeded = isset(ApiConfig::$arrApiCheckParams[$strControllerId][$strActionId]) ?
                    ApiConfig::$arrApiCheckParams[$strControllerId][$strActionId] : [];
                $arrParamsNeeded = array_merge($arrParamsNeeded, ApiConfig::$arrCommCheckParams);
//                if (!(defined('yii_debug') && !empty($_REQUEST['xiaomai'])  && (strcmp($_REQUEST['xiaomai'], 'heiheihei') == 0)) && false === ApiUtils::checkParams($arrParamsNeeded, $arrParams)) {
//                    throw new ApiBaseException(ApiErrorDescs::ERR_PARAM_INVALID);
//                }
//                //做api sign校验
//                if (!(defined('yii_debug') && !empty($_REQUEST['xiaomai'])  && (strcmp($_REQUEST['xiaomai'], 'heiheihei') == 0)) && !$checkNeed && false === ApiUtils::checkSign($arrParams, XIAOMAI_API_SIGN_SECRET)) {
//                    throw new ApiBaseException(ApiErrorDescs::ERR_SIGN_ERR);
//                }
                $urlParams = parse_url($_SERVER['REQUEST_URI']);
                $_SESSION['CURURL'] = $urlParams['path'];
                $_SESSION['CATEGORYS'] = Category::getAll();
                return true;
            }
            return false;
        }catch(ApiBaseException $e){
            $result = [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        }
        header('Content-type: application/json');
        echo json_encode($result);
        \Yii::$app->logging->error(__CLASS__ . '_' . __FUNCTION__ . json_encode($_REQUEST));
        $this->logApi(__CLASS__, __FUNCTION__, $result);
        \Yii::$app->end();
    }


    /**
     * 打印日志，每个api结尾必须调用此方法，并且只能调用一次
     * @param $controller
     * @param $action
     * @param $arrResult
     * @param array $arrExtend
     */
    public function logApi($controller, $action, $arrResult, $arrExtend = [])
    {
        $driverId = isset($_REQUEST['seller_id']) ? trim($_REQUEST['seller_id']) : '';
        $timeUsed = intval(microtime(true) * 1000 - $this->timeStart);
        $strLog = "[seller][$controller][$action][$driverId][" . $arrResult['code'] . "][" . $arrResult['message'] . "] time_used[$timeUsed]";

        if (!empty($arrExtend)) {
            foreach ($arrExtend as $key => $value) {
                $strLog .= " $key" . "[$value]";
            }
        }

        //code为0，则打印在info日志中，否则，打印到error日志中
        if (ApiErrorDescs::SUCCESS == $arrResult['code']) {
            \Yii::info($strLog);
        } else {
            \Yii::error($strLog);
        }
    }
}
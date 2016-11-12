<?php
/**
 * Created by PhpStorm.
 * User: 58
 * Date: 2016/10/3
 * Time: 22:58
 */
namespace front\controllers;

use common\models\ApiBaseException;
use common\models\ApiErrorDescs;

class UserBaseController extends BaseController
{
    public $userId;

    public function beforeAction($action){
        try{
            if (parent::beforeAction($action)) {
                if (!empty(\Yii::$app->session['USER_ID'])) {
                    $this->userId = \Yii::$app->session['USER_ID'];
                } else {
                    if (\Yii::$app->request->getIsAjax()) {
                        throw new ApiBaseException(ApiErrorDescs::ERR_USER_ACCESS_TOKEN_OVERDUE);
                    } else {
                        $this->redirect('/user-passport/login-view?back_url=' . $_SERVER['REQUEST_URI']);
                    }
                }
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
        \Yii::$app->end();
    }
}
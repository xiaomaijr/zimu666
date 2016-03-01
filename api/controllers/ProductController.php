<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao-pc
 * Date: 2016/2/29
 * Time: 15:28
 */

namespace api\controllers;


use common\models\ApiBaseException;;
use common\models\ApiErrorDescs;
use common\models\ApiUtils;
use common\models\Product;
use common\models\TimeUtils;

class ProductController extends ApiBaseController
{
    /*
     *获取产品列表
     */
    public function actionList(){
        try{
            $request = $_REQUEST;
            if(!empty($request['access_token'])&&!empty($request['user_id'])){
              $user = $this->checkAccessToken($request['access_token'], $request['user_id']);
            }
            $type = ApiUtils::getIntParam('type', $request, 1);
            $timer =  new TimeUtils();
            //获取产品列表
            $timer->start('product_list');
            $objPro = new Product($type);
            $productList = $objPro->getList($request, $ids);
            $timer->stop('product_list');
            //获取用户已投资产品
            if(!empty($user['id'])){
                $timer->start('user_invest_product');
                $userProIds = $objPro->getUserList($user['id'], $ids);
                $timer->stop('user_invest_product');
            }
            foreach($productList as $key => $val){
                $productList[$key]['flag'] = empty($userProIds) || !in_array($key, $userProIds)?0:1;
            }

            $ret = [
                'code' => ApiErrorDescs::SUCCESS,
                'message' => 'success',
                'result' => [
                    'type' => $type,
                    'list' =>  $productList
                ]
            ];
        }catch(ApiBaseException $e){
            $ret = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
        }
        header('Content-type: application/json');
        echo json_encode($ret);

        $this->logApi(__CLASS__, __FUNCTION__, $ret);
        \Yii::$app->end();
    }
}
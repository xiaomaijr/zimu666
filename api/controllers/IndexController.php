<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao-pc
 * Date: 2016/2/25
 * Time: 15:50
 */

namespace api\controllers;



use common\models\LzhAd;
use common\models\LzhBorrowInfo;
use common\models\LzhBorrowInvest;
use common\models\ApiBaseException;
use common\models\ApiErrorDescs;
use common\models\TimeUtils;


class IndexController extends ApiBaseController

{

    /*
     * 首页
     */
    public function actionIndex()
    {
        try{
            $request = $_REQUEST;
            $objTimer = new TimeUtils();
            //推荐产品列表
            $objTimer->start('borrowInfo');
            $condition = [
                'borrow_status'  =>  2,
                'is_tuijian' => 0 ,
            ];
            $borrows = LzhBorrowInfo::getList($request, $condition);
            $objTimer->stop('borrowInfo');
            //投资及收益累计总额
            $objTimer->start('borrowAndInvestTotal');
            $brwAndInvInfo = LzhBorrowInvest::getBorrowAndInvestTotal();
            $objTimer->stop('borrowAndInvestTotal');
            //banner信息
            $objTimer->start('ad_banner');
            $banners = LzhAd::getAppBanners();
            $objTimer->stop('ad_banner');

            $ret = [
                'borrows' => $borrows,
                'brw_and_inv' => $brwAndInvInfo,
                'banners' => $banners,
            ];

            $result = [
                'code' => ApiErrorDescs::SUCCESS,
                'message' => 'success',
                'result'  => $ret
            ];
        }catch(ApiBaseException $e){
            $result = [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        }
        header('Content-type: application/json');
        echo json_encode($result);

        $this->logApi(__CLASS__, __FUNCTION__, $result);
        \Yii::$app->end();
    }
}
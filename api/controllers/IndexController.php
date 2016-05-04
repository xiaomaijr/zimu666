<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao-pc
 * Date: 2016/2/25
 * Time: 15:50
 */

namespace api\controllers;



use api\models\GetParam;
use api\models\jiemen;
use api\models\RequestFilter;
use api\models\Xiaohei;
use api\models\XiaoHong;
use common\models\Ad;
use common\models\ApiConfig;
use common\models\BorrowInfo;
use common\models\BorrowInvest;
use common\models\ApiBaseException;
use common\models\ApiErrorDescs;
use common\models\MemberBanks;
use common\models\MemberPayonline;
use common\models\TimeUtils;
use common\models\UrlConfig;
use yii\redis\Cache;


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
            $borrows = BorrowInfo::getList($request, $condition);
            $objTimer->stop('borrowInfo');
            //投资及收益累计总额
            $objTimer->start('borrowAndInvestTotal');
            $brwAndInvInfo = BorrowInvest::getBorrowAndInvestTotal();
            $objTimer->stop('borrowAndInvestTotal');
            //banner信息
            $objTimer->start('ad_banner');
            $banners = Ad::getAppBanners(ApiConfig::INDEX_CAROUSEL_IMG_ID);
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

    public function actionTest()
    {
//        $obj = MemberPayonline::findOne(5358);
//        $obj->updateNotifyStatus();
        echo md5('zx123456');
//        \Yii::$app->logging->debug(__CLASS__ . '_' . __FUNCTION__ . json_encode($_REQUEST));
        exit;
    }
}